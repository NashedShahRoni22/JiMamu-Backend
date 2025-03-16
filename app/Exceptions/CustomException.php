<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    public $message;
    public $statusCode;

    public function __construct($message = "Custom API Error", $statusCode = 500)
    {
        parent::__construct($message);
        $this->message = $message;
        $this->statusCode = $statusCode;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        // Return a JSON response for the API
        return response()->json([
            'success' => 'false',
            'message' => $this->message,
            'data' => null,
            'errors' => null,
        ], $this->statusCode);
    }
}
