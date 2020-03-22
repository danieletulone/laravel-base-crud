<?php

namespace DanieleTulone\BaseCrud\Traits;

use Illuminate\Support\Str;

trait HasCrudQueries
{
    final private function getModelName()
    {
        return strtolower(class_basename($this->model));
    }

    /**
     * Query used for delete method.
     * 
     * @param mixed $params 
     * @return mixed
     */
    public function deleteQuery(&$params)
    {
        $modelName = $this->getModelName();

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
        $modelsName = Str::plural($this->getModelName());

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
        $modelName = $this->getModelName();

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
        $modelName = $this->getModelName();

        if (isset($params["data"])) {
            $data = $params["data"];
        } else {
            $data = $params;
        }

        $params["updated"] = $this->model::findOrFail($params[$modelName])->update($data);
    }
}