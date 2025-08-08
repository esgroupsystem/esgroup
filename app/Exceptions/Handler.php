<?php

use Illuminate\Http\Exceptions\PostTooLargeException;

class Handler extends \ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof PostTooLargeException) {
            flash()->error('Upload failed: File too large. Max 2GB allowed.');
            return redirect()->back();
        }

        return parent::render($request, $exception);
    }
}