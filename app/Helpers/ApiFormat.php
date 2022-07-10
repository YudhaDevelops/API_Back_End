<?php 

namespace App\Helpers;

class ApiFormat
{
    protected static $response = [
        'code'      => null,
        'message'   => null,
        'data'      => null,
    ];

    public static function kirimResponse($code = null, $message = null, $data = null){
        self::$response['code'] = $code;
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, self::$response['code']);
    }

    public static function responPerSatuObject($code = null, $message = null, $data = null){
        self::$response['code'] = $code;
        self::$response['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, self::$response['code']);
    }


    //punya orang
    public function sendError($error, $errorMessages = [], $code = null)
    {
        $code = 404;
        $response = [
            "success" => false,
            'code'    => $code,
            "message" => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    // buat sendiri
    public  static function responseError($code = null,$error, $errorMessages = []){
        $response = [
            "success"   => false,
            "code"      => $code,
            "message"   => $error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }

        return response()->json($response);
    }
}

?>