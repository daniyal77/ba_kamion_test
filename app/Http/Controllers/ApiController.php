<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    public function successMessage(string $message): JsonResponse
    {
        return response()->json(['message' => $message]);
    }

    public function errorMessage(string $message, string $code = '422'): JsonResponse
    {
        return response()->json(['message' => $message], $code);
    }

    public function errorMessageException(\Exception $e): JsonResponse
    {
        return response()->json(['message' => $e->getMessage(),'status'=>$e->getCode()],422);
    }

    public function respond(array $dataMessage, string $code = '200'): JsonResponse
    {
        return response()->json($dataMessage, $code);
    }

    public function notFoundMessage(): JsonResponse
    {
        return response()->json(['message' => "صفحه مورد نظر یافت نشد"], 404);
    }

    public function notSupportMethodRoute(): JsonResponse
    {
        return response()->json(['message' => "صفحه مورد نظر یافت نشد"], 404);
    }

    public function forbiddenMessage(): JsonResponse
    {
        return response()->json(['message' => "شما مجوز دسترسی ندارید"], 403);
    }

    public function unauthorizedMessage(): JsonResponse
    {
        return response()->json(['message' => "لطفا مجددا وارد سیستم شوید"], 401);
    }

    public function manyRequestMessage(): JsonResponse
    {
        return response()->json(['message' => "درخواست شما بیش از حد مجاز است"], 429);
    }

    public function errorServerMessage(): JsonResponse
    {
        return response()->json(['message' => "لطفا با پشتیبانی تماس بگیرید"], 422);
    }

    public function respondExceptionError(\Exception $e): JsonResponse
    {
        if ($e instanceof ModelNotFoundException)
            return $this->notFoundMessage();

        Log::error($e->getMessage() . '::' . $e->getTraceAsString());
        return $this->errorServerMessage();
    }

}
