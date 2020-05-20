<?php

namespace DanieleTulone\BaseCrud\Traits;

use Illuminate\Support\Str;

trait ResourcesDeductible
{
    /**
     * Get Models namespace.
     *
     * @return void
     */
    final protected function getModelNamespace()
    {
        return config('basecrud.namespaces.models') ??
               $this->getAppNamespace();
    }

    /**
     * Get Requests namespace
     *
     * @return void
     */
    final protected function getFormRequestNamespace()
    {
        return config('basecrud.namespaces.formRequests') ??
               $this->getAppNamespace() . 'Http\\Requests\\';
    }

    /**
     * Get app namespace.
     *
     * @return void
     */
    final protected function getAppNamespace()
    {
        return app()->getNamespace();
    }

    /**
     * Get model name from controller.
     * If controller is PostController, model will be Post
     *
     * @return void
     */
    final protected function deduceFormRequest()
    {
        if (!isset($this->model)) {
            $this->model = $this->deduceModel();
        }

        $model = class_basename($this->model);

        $formRequest = $this->getFormRequestNamespace() . $model . 'Request';

        if (class_exists($formRequest)) {
            return $formRequest;
        } else {
            return null;
        }
    }

    /**
     * Get model name from controller.
     * If controller is PostController, model will be Post
     *
     * @return void
     */
    final protected function deduceModel()
    {
        $model = Str::replaceFirst('Controller', '', $this->getControllerName());

        if (class_exists($this->getModelNamespace() . ucfirst($model))) {
            return $this->getModelNamespace() . ucfirst($model);
        } else {
            return null;
        }
    }
}
