<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Services\ClientService\BrandServices;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BrandController extends ApiController
{
    private BrandServices $brandServices;

    public function __construct(BrandServices $brandServices)
    {
        $this->brandServices = $brandServices;
    }

    public function index(): JsonResponse
    {
        try {
            return $this->respond(['brands' => new BrandResource( $this->brandServices->list())]);
        }
        catch (Exception $e) {
            return $this->respondExceptionError($e);
        }
    }
}
