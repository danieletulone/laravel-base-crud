<?php

namespace DanieleTulone\BaseCrud\Http\Controllers;

use DanieleTulone\BaseCrud\Exceptions\ModelNotSpecifiedException;
use DanieleTulone\BaseCrud\Traits\HasCrudQueries;
use DanieleTulone\BaseCrud\Traits\Sideable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;

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
        Sideable;

    /**
     * List of default actions.
     *
     * @var array
     */
    protected $actions = [
        'create',
        'destroy',
        'edit',
        'index',
        'show',
        'store',
        'update',
    ];

    /**
     * Params.
     *
     * @var array
     */
    public $params = [];

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
            return call_user_func_array( array( $this, $method), $parameters);
        } else {
            return parent::__call($method, $parameters);
        }
    }

    public function __construct()
    {
        $this->params = $this->injectParams();
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        } else {
            if ($property == "model") {
                return $this->getModel();
            }

            if ($property == "formRequest") {
                return $this->getFormRequest();
            }

            return null;
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

    /**
     * Get model name from controller.
     * If controller is PostController, model will be Post
     *
     * @return void
     */
    public function getModel()
    {
        $model = Str::replaceFirst('Controller', '', $this->getControllerName());

        if (class_exists('App\\' . ucfirst($model))) {
            return 'App\\' . ucfirst($model);
        } else {
            throw new ModelNotSpecifiedException($this->getControllerName());
        }
    }

    /**
     * Get model name from controller.
     * If controller is PostController, model will be Post
     *
     * @return void
     */
    public function getFormRequest()
    {
        $model = Str::replaceFirst('App\\', '', $this->getModel());
        $formRequest = 'App\Http\Requests\\' . $model . 'Request';

        if (class_exists($formRequest)) {
            return $formRequest;
        } else {
            return null;
        }
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

        if (method_exists($this, "callValidator") && in_array($action, ["store", "update"])) {
            $params["data"] = $this->callValidator();
        }

        if (method_exists($this, $action . "Query")) {
            $this->{$action . "Query"}();
        }

        if (method_exists($this, "callAfterAction")) {
            $this->callAfterAction($action);
        }

        return $this->response($this->params, $action);
    }
}
