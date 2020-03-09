<?php


namespace DanieleTulone\BaseCrud\Controllers;

use DanieleTulone\BaseCrud\Traits\HasCrudQueries;
use DanieleTulone\BaseCrud\Traits\Sideable;
use DanieleTulone\BaseCrud\Traits\Validable;
use DanieleTulone\BaseCrud\Helpers\ViewHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;

/**
 * Base controller used for basic crud.
 * 
 * @author Daniele Tulone <danieletulone.work@gmail.com>
 * 
 * @package App\Http\Controllers
 */
class CrudController extends BaseController
{
    use AuthorizesRequests, 
        DispatchesJobs, 
        HasCrudQueries, 
        Sideable, 
        Validable;

    /**
     * FormRequest used for validate data.
     * 
     * @var mixed
     */
    protected $formRequest;

    /**
     * Model to use with this controller.
     * 
     * @var mixed
     */
    protected $model;

    /**
     * Prefix of name of view to be used after store, 
     * update and delete action.
     * 
     * @var mixed
     */
    protected $prefixNotGet = "index";

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
     * Redirect to a view. 
     * Automatically redirect to admin or web routes.
     * 
     * @param mixed $flag 
     * @param mixed $method 
     * @return mixed 
     */
    protected function response($params, $method)
    {
        if (in_array($method, ["store", "update", "delete"])) {
            $method = $this->prefixNotGet;
        }

        return view(ViewHelper::getView($this->model,  $method, false), $params);
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