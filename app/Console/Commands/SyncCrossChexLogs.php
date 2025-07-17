<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\BiometricLog;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;

class SyncCrossChexLogs extends Command
{
    protected $signature = 'crosschex:sync-logs';
    protected $description = 'Sync biometric logs from CrossChex Cloud (past 1 month only)';

    public function handle()
    {
        $apiUrl = 'https://api.us.crosschexcloud.com';
        $apiKey = '46332840a78485e57cbee93deae7a80d';
        $apiSecret = '573778df0160dac942d1f83e2f3a88b6';

        $this->info('🔐 Authenticating with CrossChex Cloud...');

        try {
            $authResponse = Http::post($apiUrl, [
                'header' => [
                    'nameSpace' => 'authorize.token',
                    'nameAction' => 'token',
                    'version' => '1.0',
                    'requestId' => Str::uuid(),
                    'timestamp' => now()->toIso8601String(),
                ],
                'payload' => [
                    'api_key' => $apiKey,
                    'api_secret' => $apiSecret,
                ],
            ]);

            if (!$authResponse->successful()) {
                $this->error('❌ Authentication failed: ' . $authResponse->body());
                return;
            }

            $accessToken = $authResponse->json('payload.token');

            if (!$accessToken) {
                $this->error('❌ Token missing in response.');
                return;
            }
        } catch (Exception $e) {
            $this->error('❌ Error during authentication: ' . $e->getMessage());
            return;
        }

        $startTime = Carbon::now()->subDays(15)->startOfDay();
        $endTime = Carbon::now()->endOfDay();

        $this->info("✅ Authenticated. Fetching logs from $startTime to $endTime...");

        $page = 1;
        $perPage = 100;
        $totalSynced = 0;

        $existingLogs = BiometricLog::select('employee_id', 'log_time')->get()
            ->mapWithKeys(fn ($log) => [$log['employee_id'] . '|' . $log['log_time'] => true])
            ->all();

        do {
            try {
                $this->info("📄 Fetching page $page...");

                $logResponse = Http::post($apiUrl, [
                    'header' => [
                        'nameSpace' => 'attendance.record',
                        'nameAction' => 'getrecord',
                        'version' => '1.0',
                        'requestId' => Str::uuid(),
                        'timestamp' => now()->toIso8601String(),
                    ],
                    'authorize' => [
                        'type' => 'token',
                        'token' => $accessToken,
                    ],
                    'payload' => [
                        'begin_time' => $startTime,
                        'end_time' => $endTime,
                        'order' => 'desc',
                        'page' => $page,
                        'per_page' => $perPage,
                    ],
                ]);

                if (!$logResponse->successful()) {
                    $this->error("❌ API request failed on page $page: " . $logResponse->body());
                    break;
                }

                $logs = $logResponse->json('payload.list') ?? [];

                if (empty($logs)) {
                    break;
                }

                foreach ($logs as $log) {
                    $employee = $log['employee'] ?? [];
                    $employeeId = $employee['workno'] ?? 'unknown';
                    $employeeName = trim(($employee['first_name'] ?? '') . ' ' . ($employee['last_name'] ?? ''));
                    $logTime = Carbon::parse($log['checktime'])->setTimezone('Asia/Manila')->format('Y-m-d H:i:s');

                    $key = $employeeId . '|' . $logTime;
                    if (isset($existingLogs[$key])) {
                        continue;
                    }

                    $status = match ($log['checktype'] ?? null) {
                        0 => 'IN',
                        1 => 'OUT',
                        default => 'UNKNOWN',
                    };

                    BiometricLog::create([
                        'employee_id' => $employeeId,
                        'employee_name' => $employeeName,
                        'log_time' => $logTime,
                        'status' => $status,
                    ]);

                    $totalSynced++;
                }

                $page++;
                $this->info("⏳ Waiting 30 seconds before next page...");
                sleep(30);
            } catch (Exception $e) {
                $this->error("❌ Unexpected error on page $page: " . $e->getMessage());
                break;
            }

        } while (true);
        
        $this->info("✅ Done. Synced $totalSynced new log(s) from the past 1 month.");
    }
}
