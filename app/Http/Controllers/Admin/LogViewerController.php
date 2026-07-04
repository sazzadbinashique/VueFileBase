<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogViewerController extends Controller
{
    public function index()
    {
        $path = storage_path('logs/laravel.log');
        if (!file_exists($path)) {
            return response()->json(['lines' => []]);
        }

        $lines = $this->tail($path, 500);
        return response()->json(['lines' => $lines]);
    }

    public function clear()
    {
        $path = storage_path('logs/laravel.log');
        file_put_contents($path, '');
        return response()->json(['message' => 'Logs cleared.']);
    }

    private function tail($path, $lines)
    {
        $file = new \SplFileObject($path, 'r');
        $file->seek(PHP_INT_MAX);
        $total = $file->key();

        $start = max(0, $total - $lines);
        $file->seek($start);

        $result = [];
        while ($file->valid()) {
            $line = $file->current();
            if ($line !== false && trim($line) !== '') {
                $result[] = rtrim($line);
            }
            $file->next();
        }

        return $result;
    }
}
