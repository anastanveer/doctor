<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class LogController extends Controller
{
    public function index(): View
    {
        $path = storage_path('logs/laravel.log');
        $lines = [];

        if (File::exists($path) && File::isReadable($path)) {
            $content = File::get($path);
            $lines = preg_split('/\r\n|\r|\n/', $content) ?: [];
            $lines = array_slice($lines, -200);
        }

        $errorCount = collect($lines)->filter(function ($line) {
            return str_contains($line, 'ERROR');
        })->count();

        return view('admin.logs', [
            'lines' => $lines,
            'errorCount' => $errorCount,
            'logPath' => $path,
        ]);
    }
}
