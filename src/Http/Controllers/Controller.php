<?php

namespace DanieleTulone\BaseCrud\Http\Controllers;

use DanieleTulone\BaseCrud\Traits\HasCrudQueries;
use DanieleTulone\BaseCrud\Traits\ResourcesDeductible;
use DanieleTulone\BaseCrud\Traits\Sideable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;

/**
 * Base controller used for basic crud.
 *
 * @author Daniele Tulone <danieletulone.work@gmail.com>
 *
 * @package DanieleTulone\BaseCrud\Http\Controllers
 */
class Controller extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        HasCrudQueries,
        ResourcesDeductible,
        Sideable;

    /**
     * List of default actions.
     *
     * @var array
     */
    public $actions = [];

    /**
     * List of default actions.
     *
     * @var array
     */
    protected $defaultActions = [];

    /**
     * Params.
     *
     * @var array
     */
    public $params = [];

    /**
     * The model to use.
     *
     * @var [type]
     */
    protected $model;

    /**
     * If setted, the form request to use.
     *      *
     * @var FormRequest|null
     */
    protected $formRequest;

    /**
     * Get property or get automatically model or formRequest, otherwise null.
     *
     * @param [type] $method
     * @param [type] $parameters
     * @return void
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, $this->actions)) {
            return $this->dispacthAction($method);
        }

        if (in_array($method, $this->actions) || method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $parameters);
        } else {
            return parent::__call($method, $parameters);
        }
    }

    public function __construct()
    {
        if (request()->method != null) {
            $this->params = $this->injectParams();

            if (!isset($this->model)) {
                $this->model = $this->deduceModel();
            }

            if (!isset($this->formRequest)) {
                $this->formRequest = $this->deduceFormRequest();
            }
        }
    }

    /**
     * Get controller
     *
     * @return void
     */
    public function getControllerName()
    {
        $controller = app('request')->route()->getAction()['controller'];

        return explode("@", class_basename($controller))[0];
    }

    public function getActions()
    {
        return array_merge($this->defaultActions, $this->actions);
    }

    /**
     * Get all params from route url and from request.
     *
     * @return array
     */
    public function injectParams()
    {
        return array_merge(
            request()->all(),
            request()->route()->parameters
        );
    }

    public function registerAction($name, $validable = false)
    {
        $this->actions[$name] = [];
    }

    /**
     * Get all params from route url and from request.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    public function dispacthAction($action)
    {
        if (method_exists($this, "callBeforeAction")) {
            $this->callBeforeAction($action);
        }

        if (in_array($action, ["store", "update"])) {
            if ($this->formRequest != null) {
                resolve($this->formRequest);
            }
        }

        if (method_exists($this, $action . "Query")) {
            $this->{$action . "Query"}();
        }

        if (method_exists($this, "callAfterAction")) {
            $this->callAfterAction($action);
        }

        return $this->response($this->params, $action);
    }

    /**
     * Get model name by class namespace.
     *
     * @return string
     */
    final protected function getModelName()
    {
        return strtolower(class_basename($this->model));
    }
}
