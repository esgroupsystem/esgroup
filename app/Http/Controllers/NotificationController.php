<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\GlobalUserNotification;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use App\Models\Joborder;
use App\Models\User;
use App\Models\JobFiles;
use Carbon\Carbon;
use DB;
use Auth;

class NotificationController extends Controller
{
    public function redirectToDetail($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);

        // Mark as read
        if ($notification->unread()) {
            $notification->markAsRead();
        }

        // Redirect to the link in the notification data
        return redirect($notification->data['url'] ?? '/');
    }

    public function clearAll()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
