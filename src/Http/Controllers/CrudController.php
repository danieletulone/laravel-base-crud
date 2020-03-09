<?php


namespace DanieleTulone\BaseCrud\Controllers;

use DanieleTulone\BaseCrud\Traits\HasCrudQueries;
use DanieleTulone\BaseCrud\Traits\Sideable;
use DanieleTulone\BaseCrud\Traits\Validable;
use DanieleTulone\BaseCrud\Helpers\ViewHelper;

/**
 * Base controller used for basic crud.
 * 
 * @author Daniele Tulone <danieletulone.work@gmail.com>
 * 
 * @package App\Http\Controllers
 */
class CrudController extends Controller
{
    use HasCrudQueries, Sideable ,Validable;

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
        $params = $this->getParams();
        
        $this->callBeforeAction(__FUNCTION__ , $params);

        $deleted = $this->model::findOrFail($params["model"])->delete();
        
        $params["deleted"] = $deleted;

        $this->callAfterAction(__FUNCTION__ , $params);

        return $this->response($params, __FUNCTION__);
    }

    /**
     * Default index action.
     * 
     * @return mixed 
     */
    public function index()
    {
        $params = $this->getParams();

        $this->callBeforeAction(__FUNCTION__ , $params);

        $params["models"] = $this->indexQuery($params);

        $this->callAfterAction(__FUNCTION__ , $params);

        return $this->response($params, __FUNCTION__);
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
        return view(ViewHelper::getView($this->model,  $method, false), $params);
    }

    /**
     * Show the resource.
     * 
     * @return Illuminate\View\View|Illuminate\Contracts\View\Factory 
     */
    public function show()
    {   
        $params = $this->getParams();

        $model = $this->showQuery($params);

        $params["model"] = $model;

        return $this->response($params, __FUNCTION__);
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
        $params = $this->getParams();

        $this->callBeforeAction(__FUNCTION__ , $params);

        $data = $this->validate($params);

        $params["model"] = $this->storeQuery($data);

        $this->callAfterAction(__FUNCTION__ , $params);

        return $this->response($params, __FUNCTION__);
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
        $params = $this->getParams();

        $this->callBeforeAction(__FUNCTION__ , $params);
        
        $data = $this->validate($params);

        $params["updated"] = $this->updateQuery($params, $data);

        $this->callAfterAction(__FUNCTION__ , $params);

        return $this->response($params, __FUNCTION__);
    }
}