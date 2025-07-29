<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Job Order Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }
        .header {
            background-color: #004085;
            color: #fff;
            padding: 15px;
            text-align: center;
        }
        .content {
            margin: 30px auto;
            width: 90%;
            max-width: 600px;
            border: 1px solid #ddd;
            padding: 25px;
            border-radius: 6px;
        }
        .job-info {
            margin-bottom: 15px;
        }
        .job-info strong {
            width: 140px;
            display: inline-block;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>🚨 Job Order Notification</h2>
    </div>

    <div class="content">
        <p>Hello,</p>
        <p>A new job order has been submitted. Here are the details:</p>

        <div class="job-info">
            <p><strong>Job Name:</strong> {{ $jobOrder->job_name }}</p>
            <p><strong>Type:</strong> {{ $jobOrder->job_type }}</p>
            <p><strong>Date Start:</strong> {{ \Carbon\Carbon::parse($jobOrder->job_datestart)->format('F j, Y') }}</p>
            <p><strong>Start Time:</strong> {{ \Carbon\Carbon::parse($jobOrder->job_time_start)->format('h:i A') }}</p>
            <p><strong>End Time:</strong> {{ \Carbon\Carbon::parse($jobOrder->job_time_end)->format('h:i A') }}</p>
            <p><strong>Seat Numbers:</strong> {{ $jobOrder->job_sitNumber }}</p>
            <p><strong>Remarks:</strong> {{ $jobOrder->job_remarks ?? 'N/A' }}</p>
            <p><strong>Created By:</strong> {{ $jobOrder->job_creator }}</p>
        </div>

        <p>
            <a href="{{ route('view/details', ['id' => $jobOrder->id]) }}" style="display:inline-block;padding:10px 20px;background:#004085;color:#fff;text-decoration:none;border-radius:5px;">View Job Order</a>
        </p>
    </div>

    <div class="footer">
        &copy; {{ now()->year }} Your Company Name. This is an automated email.
    </div>

</body>
</html>
