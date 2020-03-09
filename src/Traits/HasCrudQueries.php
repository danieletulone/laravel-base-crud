<?php

namespace DanieleTulone\BaseCrud\Traits;

trait HasCrudQueries
{
    /**
     * Query used for index method.
     * 
     * @param mixed $params 
     * @return mixed
     */
    public function indexQuery(&$params)
    {
        return $this->model::paginate();
    }

    /**
     * Query used for show method
     * 
     * @param mixed $params 
     * @return mixed 
     */
    public function showQuery(&$params)
    {
        return $this->model::findOrFail($params['model']);
    }

    /**
     * Query used for store method.
     * 
     * @param mixed $data 
     * @return mixed 
     */
    public function storeQuery($data)
    {
        return $this->model::create($data);
    }

    /**
     * Query used for update method.
     * 
     * @param mixed $params 
     * @param mixed $data 
     * @return mixed 
     */
    public function updateQuery($params, $data)
    {
        return $this->model::findOrFail($params["model"])->update($data);
    }
}