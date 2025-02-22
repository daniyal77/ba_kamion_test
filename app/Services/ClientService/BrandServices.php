<?php

namespace App\Services\ClientService;

use App\Models\Brand;
use App\Services\Models\ServiceModel;

class BrandServices extends ServiceModel
{

    function modelClass(): Brand
    {
        return new Brand();
    }

    public function list()
    {
        return $this->all();
    }

}
