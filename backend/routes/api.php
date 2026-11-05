<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    $debug = (bool) config('app.debug');

    $databaseReachable = false;
    $databaseMessage = null;

    try {
        DB::connection()->getPdo();
        DB::select('SELECT 1');
        $databaseReachable = true;
    } catch (Throwable $exception) {
        $databaseMessage = $debug ? $exception->getMessage() : 'unreachable';
    }

    $reverbHost = (string) config('health.reverb.host', 'reverb');
    $reverbPort = (int) config('health.reverb.port', 8080);
    $reverbReachable = false;
    $reverbMessage = null;

    $socket = @stream_socket_client(
        sprintf('tcp://%s:%d', $reverbHost, $reverbPort),
        $errorNumber,
        $errorMessage,
        1
    );

    if ($socket !== false) {
        $reverbReachable = true;
        fclose($socket);
    } else {
        $reverbMessage = $debug
            ? ($errorMessage ?: ('Socket error ' . $errorNumber))
            : 'unreachable';
    }

    return response()->json([
        'app' => [
            'name' => config('app.name'),
            'version' => config('app.version'),
            'laravel' => app()->version(),
            'environment' => app()->environment(),
        ],
        'database' => [
            'connection' => config('database.default'),
            'reachable' => $databaseReachable,
            'message' => $databaseMessage,
        ],
        'reverb' => [
            'host' => $reverbHost,
            'port' => $reverbPort,
            'reachable' => $reverbReachable,
            'message' => $reverbMessage,
        ],
    ]);
});
