<?php
use Illuminate\Http\Response;
// api json response
if (!function_exists('sendResponse')) {
    function sendResponse($success, $message = null, $data = null, $status = 200, $error = null) {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'error' => $error,
        ], $status);
    }
}
if (!function_exists('responseMessage')) {
    function responseMessage($type)
    {
        if ($type == 'store') {
            return "Data stored successfully";
        }elseif ($type == 'update') {
            return "Data updated successfully";
        }elseif ($type == 'delete') {
            return "Data deleted successfully";
        }elseif ($type == 'get') {
            return "Data retrieved successfully";
        }elseif ($type == 'error') {
            return "Something went wrong";
        }
    }
}
if (!function_exists('sliceFilePath')) {
    function sliceFilePath($path){
        $slicePath = explode('/', $path);
        return end($slicePath);
    }
}
