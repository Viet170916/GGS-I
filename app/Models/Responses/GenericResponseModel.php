<?php
namespace App\Models\Responses;

class GenericResponseModel {
    public static function Error( string $message, array $detail = null, mixed $data = null ): array {
        return [ 'error' => $message, 'detail' => $detail, 'data' => $data ];
    }
    public static function Success( string $message, array $detail = null, mixed $data = null ): array {
        return [ 'message' => $message, 'detail' => $detail, 'data' => $data ];
    }
}
