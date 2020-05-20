<?php

namespace DanieleTulone\BaseCrud\Traits;

use Illuminate\Support\Str;

trait HasCrudQueries
{

    /**
     * Get data or params. When there are validated
     * data return data, otherwise params.
     *
     * @param mixed $params
     * @return mixed
     */
    final private function getDataOrParams()
    {
        $params = $this->getParams();

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
    public function destroyQuery()
    {
        $modelName = $this->getModelName();

        $this->params["deleted"] = $this->model::findOrFail($this->params[$modelName])->delete();
    }

    /**
     * Query used for index method.
     *
     * @param mixed $params
     * @return mixed
     */
    public function indexQuery()
    {
        if ($this->formRequest != null) {
            resolve($this->formRequest);
        }

        $modelsName = Str::plural($this->getModelName());

        $this->params[$modelsName] = $this->model::paginate();
    }

    /**
     * Query used for show method
     *
     * @param mixed $params
     * @return mixed
     */
    public function showQuery()
    {
        $modelName = $this->getModelName();

        $this->params = $this->model::findOrFail($this->params[$modelName]);
    }

    /**
     * Query used for store method.
     *
     * @param mixed $data
     * @return mixed
     */
    public function storeQuery()
    {
        if ($this->formRequest != null) {
            resolve($this->formRequest);
        }

        $params = $this->getParams();
        $data = $this->getDataOrParams($params);
        $modelName =$this->getModelName();

        $this->params[$modelName] = $this->model::create($data);
    }

    /**
     * Query used for update method.
     *
     * @param mixed $params
     * @param mixed $data
     * @return mixed
     */
    public function updateQuery()
    {
        if ($this->formRequest != null) {
            resolve($this->formRequest);
        }

        $params = $this->getParams();

        $modelName = $this->getModelName();

        $data = $this->getDataOrParams($params);

        $this->params["updated"] = $this->model::findOrFail($params[$modelName])->update($data);
    }
}
