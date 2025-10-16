<?php

namespace App\Http\Middleware;

use Closure;
use App\HRISUser;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class HRISPortalLoggerMiddleware
{
    public function handle($request, Closure $next)
    {
        $client = new Client([
            'verify' => false,
            'timeout' => env('PORTAL_USER_LOGGER_TIMEOUT', 5),
        ]);

        $os = strtoupper(substr(PHP_OS, 0, 3));
        $host = env('PORTAL_USER_LOGGER_HOST_URL');

        // Ping check (Windows vs Linux)
        if ($os === 'WIN') {
            exec("ping -n 1 -w 3000 " . escapeshellarg($host), $output, $status);
        } else {
            exec("ping -c 1 -W 3 " . escapeshellarg($host), $output, $status);
        }

        // If ping failed, skip logging but continue request
        if ($status !== 0) {
            Log::warning('⚠️ Portal logger server unreachable.');
            return $next($request);
        }

        try {
            //  Safely get HRIS user ID (null-safe)
            $hrisUserId = null;
            if (Auth::check()) {
                $user = auth()->user();
                $hrisUser = HRISUser::where('email', $user ? $user->email : null)->first();
                $hrisUserId = $hrisUser ? $hrisUser->id : null;
            }

            $currentRoute = Route::current();
            $action = $currentRoute ? $currentRoute->getAction() : [];

            $response = $client->post(env('PORTAL_USER_LOGGER_URL'), [
                'form_params' => [
                    'useragent'       => $request->userAgent(),
                    'ipaddress'       => $request->ip(),
                    'user_id'         => $hrisUserId,
                    'portal_id'       => env('PORTAL_USER_LOGGER_PORTAL_ID'),
                    'portal'          => Route::currentRouteName(),
                    'url'             => $request->fullUrl(),
                    'url_name'        => Route::currentRouteName(),
                    'url_description' => isset($action['description']) ? $action['description'] : 'No description provided',
                    'url_purpose'     => isset($action['purpose']) ? $action['purpose'] : 'No purpose provided',
                    'url_method'      => $request->method(),
                    'is_authenticated'=> Auth::check() ? 1 : 0,
                ]
            ]);

            if ($response->getStatusCode() >= 400) {
                Log::error('❌ Failed to log portal activity: ' . $response->getBody()->getContents());
            }

        } catch (RequestException $e) {
            Log::error('Failed to log portal activity: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Unexpected error in HRISPortalLoggerMiddleware: ' . $e->getMessage());
        }

        return $next($request);
    }
}
