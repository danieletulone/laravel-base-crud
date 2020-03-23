<?php

namespace DanieleTulone\BaseCrud\Http\Controllers;

use DanieleTulone\BaseCrud\Traits\HasCrudQueries;
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
        Sideable;
        
    /**
     * Model to use with this controller.
     * 
     * @var mixed
     */
    protected $model;

    /**
     * Get all params from route url and from request.
     *  
     * @return array 
     */
    public function getParams()
    {
        return array_merge(
            request()->all(),
            request()->route()->parameters
        );
    }

    /**
     * Default destroy action.
     * 
     * @param mixed $id 
     * @return mixed 
     */
    public function destroy()
    {
        return $this->dispacthAction(__FUNCTION__);
    }

    /**
     * Default index action.
     * 
     * @return mixed 
     */
    public function index()
    {
        return $this->dispacthAction(__FUNCTION__);
    }

    /**
     * Show the resource.
     * 
     * @return Illuminate\View\View|Illuminate\Contracts\View\Factory 
     */
    public function show()
    {   
        return $this->dispacthAction(__FUNCTION__);
    }

    /**
     * Default store action.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @param Illuminate\Http\Request $request 
     * @return mixed 
     */
    public function store()
    {
        return $this->dispacthAction(__FUNCTION__);
    }

    /**
     * Default update action.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @param mixed $id 
     * @param mixed $data 
     * @return mixed 
     */
    public function update()
    {   
        return $this->dispacthAction(__FUNCTION__);
    }

    public function dispacthAction($action)
    {
        $params = $this->getParams();

        if (method_exists($this, "callBeforeAction")) {
            $this->callBeforeAction($action, $params);
        }
        
        if (method_exists($this, "callValidator") && in_array($action, ["store", "update"])) {
            $params["data"] = $this->callValidator($params);
        }

        if (method_exists($this, $action . "Query")) {
            $this->{$action . "Query"}($params);
        }

        if (method_exists($this, "callAfterAction")) {
            $this->callAfterAction($action, $params);
        }

        return $this->response($params, $action);
    }
}