<?php

namespace DanieleTulone\BaseCrud\Traits;

use Illuminate\Support\Str;

trait HasCrudQueries
{
    /**
     * Get model name by class namespace.
     * 
     * @return string 
     */
    final private function getModelName()
    {
        return strtolower(class_basename($this->model));
    }

    /**
     * Get data or params. When there are validated 
     * data return data, otherwise params.
     * 
     * @param mixed $params 
     * @return mixed 
     */
    final private function getDataOrParams($params)
    {
        if (isset($params["data"])) {
            return $params["data"];
        }
        
        return $params;
    }

    /**
     * Query used for delete method.
     * 
     * @param mixed $params 
     * @return mixed
     */
    public function destroyQuery(&$params)
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

        $params[$modelName] = $this->model::findOrFail($params[$modelName]);
    }

    /**
     * Query used for store method.
     * 
     * @param mixed $data 
     * @return mixed 
     */
    public function storeQuery(&$params)
    {
        $data = $this->getDataOrParams($params);
        $modelName =$this->getModelName();

        $params[$modelName] = $this->model::create($data);
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

        $data = $this->getDataOrParams($params);

        $params["updated"] = $this->model::findOrFail($params[$modelName])->update($data);
    }
}