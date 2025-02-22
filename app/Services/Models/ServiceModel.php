<?php

namespace App\Services\Models;

use Carbon\Carbon;

abstract class ServiceModel extends ServicesModel
{
    public function getId()
    {
        return @$this->model->id;
    }

    public function getCreatedAt($isJalali = false, $onlyTime = false)
    {
        return  $this->model->created_at;
    }

    public function getUpdatedAt($isJalali = false, $onlyTime = false)
    {
        return $this->model->updated_at;
    }
}

