<?php

namespace DanieleTulone\BaseCrud\Traits;

use DanieleTulone\BaseCrud\Helpers\ClassHelper;
use Illuminate\Support\Str;

trait HasCrudQueries
{
    /**
     * Query used for delete method.
     * 
     * @param mixed $params 
     * @return mixed
     */
    public function deleteQuery(&$params)
    {
        $modelName = strtolower(ClassHelper::getName($this->models));

        $params["deleted"] = $this->model::findOrFail($params[$modelName])->delete();
    }

    /**
     * Query used for index method.
     * 
     * @param mixed $params 
     * @return mixed
     */
    public function indexQuery(&$params)
    {
        $modelsName = Str::plural(strtolower(ClassHelper::getName($this->models)));

        $params[$modelsName] = $this->model::paginate();
    }

    /**
     * Query used for show method
     * 
     * @param mixed $params 
     * @return mixed 
     */
    public function showQuery(&$params)
    {
        $modelName = strtolower(ClassHelper::getName($this->models));

        return $this->model::findOrFail($params[$modelName]);
    }

    /**
     * Query used for store method.
     * 
     * @param mixed $data 
     * @return mixed 
     */
    public function storeQuery(&$params)
    {
        if (isset($params["data"])) {
            $data = $params["data"];
        } else {
            $data = $params;
        }

        return $this->model::create($data);
    }

    /**
     * Query used for update method.
     * 
     * @param mixed $params 
     * @param mixed $data 
     * @return mixed 
     */
    public function updateQuery(&$params)
    {
        $modelName = strtolower(ClassHelper::getName($this->models));

        if (isset($params["data"])) {
            $data = $params["data"];
        } else {
            $data = $params;
        }

        $params["updated"] = $this->model::findOrFail($params[$modelName])->update($data);
    }
}