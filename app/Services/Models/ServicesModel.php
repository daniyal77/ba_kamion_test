<?php

namespace App\Services\Models;

use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\QueryBuilderRequest;

abstract class ServicesModel
{

    public $model;

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    abstract function modelClass();

    /**
     * @param array $columns
     *
     * @return mixed
     */
    public function all($columns = array('*'))
    {
        return $this->modelClass()->get($columns);
    }

    /**
     * @param int   $perPage
     * @param array $columns
     *
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*'))
    {
        return $this->modelClass()->paginate($perPage, $columns);
    }

    /**
     * @param int   $perPage
     * @param array $columns
     *
     * @return mixed
     */
    public function paginateWithRealation($with, $perPage = 15, $columns = array('*'))
    {
        return $this->modelClass()->with($with)->paginate($perPage, $columns);
    }

    /**
     * @param int   $perPage
     * @param array $columns
     *
     * @return mixed
     */
    public function trash($perPage = 15, $columns = array('*'))
    {
        return $this->modelClass()->onlyTrashed()->paginate($perPage, $columns);
    }

    /**
     * @param array $data
     * @param       $setModel
     */
    public function create(array $data, $setModel = false)
    {
        $model = $this->modelClass()->create($data);
        if ($setModel) {
            $this->model = $model;
            return $this;
        }

        return $model;
    }

    /**
     * @param array  $data
     * @param        $id
     * @param string $attribute
     *
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        return $this->modelClass()->where($attribute, '=', $id)->update($data);
    }

    public function updateWithModel(array $data)
    {
        return $this->model->update($data);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function delete($id, $attribute = "id")
    {
        return $this->modelClass()->where($attribute, $id)->delete();
    }

    public function forceDelete($id, $attribute = "id")
    {
        return $this->modelClass()->where($attribute, $id)->forceDelete();
    }

    public function deleteWithModel()
    {
        return $this->model->delete();
    }

    public function findWithRelation($id, $relations, $setModel = false, $columns = array('*'))
    {
        $model = $this->modelClass()->with($relations)->findOrFail($id, $columns);
        if ($setModel) {
            $this->model = $model;
            return $this;
        }
        return $model;
    }

    /**
     * @param $id
     * @param $setModel
     * @param $columns
     *
     * @return $this
     */
    public function find($id, $setModel = false, $columns = array('*'))
    {
        $model = $this->modelClass()->findOrFail($id, $columns);
        if ($setModel) {
            $this->model = $model;
            return $this;
        }
        return $model;
    }

    /**
     * @param       $attribute
     * @param       $value
     * @param array $columns
     *
     */
    public function findBy($attribute, $value, $setModel = false, $with = [], $columns = array('*'))
    {
        $model = $this->modelClass()->with($with)->where($attribute, '=', $value)->first($columns);

        if ($setModel) {
            $this->model = $model;
            return $this;
        }
        return $model;
    }


    /**
     * @param $attribute
     * @param $value
     * @param $relations
     * @param $setModel
     * @param $columns
     *
     * @return $this
     */
    public function findByWithRelation($attribute, $value, $relations = [], $setModel = false, $columns = array('*'))
    {
        $model = $this->modelClass()->with($relations)->where($attribute, '=', $value)->first($columns);

        if ($setModel) {
            $this->model = $model;
            return $this;
        }
        return $model;
    }

    /**
     * @param $where
     * @param $data
     *
     * @return mixed
     */
    public function updateOrCreate($where = [], $data = [],$setModel = false)
    {
        $model= $this->modelClass()->updateOrCreate(
            $where, $data
        );
        if ($setModel) {
            $this->model = $model;
            return $this;
        }
        return $model;
    }

    /**
     * @param $condition
     *
     * @return boolean
     */
    public function exist($key, $value)
    {
        $object = $this->findBy($key, $value);
        if (!$object) return false;
        return true;
    }

    /**
     * @param $action
     * @param $selection
     * @param $field
     *
     * @return void
     */
    public function runAction($action, $selection = [], $field = 'id')
    {

        if ($action == '' || $selection == null)
            return;

        switch ($action) {
            case "delete":
                $this->modelClass()->whereIn($field, $selection)->forceDelete();
                break;
            case "restore":
                $this->modelClass()->whereIn($field, $selection)->restore();
                break;
            case "destroy":
                $this->modelClass()->whereIn($field, $selection)->delete();
                break;
        }
    }

    public function findAndSetModel($value, $attribute = 'id', $cols = '*')
    {
        $this->model = $this->findBy($attribute, $value, $cols);
        return $this;
    }

    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function log($message, $change = false)
    {
        $this->model->log($message, $change);
    }

    public function logWithType($message, $type = '')
    {
        $this->model->log($message, ['type' => $type]);
    }


    public function chunk($callback, $where = [], $with = [], $count = 20)
    {
        $model = $this->modelClass();

        if ($where)
            $model->where($where);

        if ($with)
            $model->with($with);

        $model->chunk($count, $callback);

        return $model;
    }

    public function filter(
        $parameters = '', array $whiteList = [],
        array $allowedIncluded = [], array $where = [], array $column = ['*'], int $paginate = 15,
        bool $trashed = false, $allowSort = ''
    )
    {

        $sharedKeys = [];

        $filter = $parameters ? array_keys($parameters) : [];

        if ($filter)
            $sharedKeys = array_values(array_intersect($filter, $whiteList));

        QueryBuilderRequest::setArrayValueDelimiter(',');

        $query = QueryBuilder::for($this->modelClass());

        if (count($allowedIncluded) > 0)
            $query->with($allowedIncluded);

        if ($where) $query = $query->where($where);

        if ($allowedIncluded) $query = $query->allowedIncludes($allowedIncluded);

        $allowedFilters = [];

        if ($trashed)
            $allowedFilters[] = AllowedFilter::trashed();

        foreach ($sharedKeys as $key)
            $allowedFilters[] = $key;

        $query = $query
            ->allowedFilters($allowedFilters);

        if ($allowSort)
            $query = $query->allowedSorts($allowSort);

        return $query->paginate($paginate, $column)
            ->appends(request()->query());
    }

    public function filterWithQueryBuilder(
        $query, $parameters = '', array $whiteList = [], array $allowedIncluded = [], array $where = [],
        array $column = ['*'], int $paginate = 15, bool $trashed = false, $allowSort = ''
    )
    {

        $sharedKeys = [];

        $filter = $parameters ? array_keys($parameters) : [];

        if ($filter)
            $sharedKeys = array_values(array_intersect($filter, $whiteList));

        QueryBuilderRequest::setArrayValueDelimiter(',');

        if ($where) $query = $query->where($where);

        if ($allowedIncluded) $query = $query->allowedIncludes($allowedIncluded);

        $allowedFilters = [];

        if ($trashed)
            $allowedFilters[] = AllowedFilter::trashed();

        foreach ($sharedKeys as $key)
            $allowedFilters[] = $key;

        $query = $query
            ->allowedFilters($allowedFilters);

        if ($allowSort)
            $query = $query->allowedSorts($allowSort);

        return $query;
    }
}
