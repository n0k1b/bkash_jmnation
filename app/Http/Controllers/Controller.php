<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function successJsonResponse(string $message, $data = null, $total = null): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data ?: [],
            'total' => $total ?: ((is_null($data) ? 0 : (is_array($data) ? count($data) : 1))),
        ]);
    }

    /**
     * Error JSON Response Method.
     *
     * @param string $message
     * @param array $data
     * @return JsonResponse
     */
    public function errorJsonResponse(string $message, array $data = []): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message ?: 'Something went wrong! Please try again later.',
            'data' => $data,
            'total' => 0,
        ]);
    }

    /**
     * Exception JSON Response.
     *
     * @param $exception
     * @param string|null $message
     * @param string $channel
     * @return JsonResponse
     */
    public function exceptionJsonResponse($exception, string $message = null, string $channel = 'default'): JsonResponse
    {
        if ($channel === 'default') {
            //Slack notification
            //User::first()->notify(new DefaultNotification($exception));
        }

        // Response
        return response()->json([
            'status' => false,
            'message' => $message ?? 'Something went wrong! Please try again later.',
            'data' => [
                'exceptions' => $exception->getMessage(),
            ],
            'total' => 0,
        ]);
    }
}
