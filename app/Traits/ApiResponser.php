<?php

namespace App\Traits;

trait ApiResponser
{

    /**
     * It takes in a result and a message, and returns a JSON response with the result and message
     * 
     * @param Array result The data that you want to send back to the client.
     * @param String message The message you want to send to the user.
     * 
     * @return \Illuminate\Http\Response JSON response with a 200 status code.
     */
    public function success($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * It takes an error message, an array of error messages, and a status code, and returns a JSON
     * response with the error message and error messages
     * 
     * @param String error The error message
     * @param Array errorMessages This is an array of error messages that you want to send back to the
     * client.
     * @param Int HTTP status code
     * 
     * @return \Illuminate\Http\Response JSON response with a success key set to false, a message key set to the error message,
     * and a data key set to the error messages.
     */
    public function error($error, $errorMessages = [], $code = 400)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}