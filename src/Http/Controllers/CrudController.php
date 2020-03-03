<?php

namespace DanieleTulone\BaseCrud\Controllers;

use DanieleTulone\BaseCrud\ClassHelper;
use DanieleTulone\BaseCrud\ViewHelper;

use Validator;

/**
 * Base controller used for basic crud.
 * 
 * @author Daniele Tulone <danieletulone.work@gmail.com>
 * 
 * @package App\Http\Controllers
 */
class BaseCrudController extends Controller
{
    /**
     * FormRequest used for validate data.
     * 
     * @var mixed
     */
    public $formRequest;

    /**
     * Defaul paginate value.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @var int
     */
    public $itemsForPage = 12;

    /**
     * Messages used to give feedback to user.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @var string[][]
     */
    public $messages = [
        "destroy" => [
            "success" => "Record rimosso correttamente.",
            "error" => "Non è stato possibile rimuovere il record."
        ],
        "store" => [
            "success" => "Record creato correttamente.",
            "error" => "Non è stato possibile creare il record."
        ],
        "update" => [
            "success" => "Record aggiornato correttamente.",
            "error" => "Non è stato possibile aggiornare il record."
        ]
    ];

    /**
     * Model to use with this controller.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @var mixed
     */
    public $model;

    /**
     * Set if this controller return view() as response or work only as api
     * 
     * @var mixed
     */
    public $useView; 

    /**
     * Make operations after action.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * @trait App\Traits\HasAfterActions
     * 
     * @param mixed $method 
     * @param mixed $result 
     * @return void 
     */
    private function callAfterAction($method, $result)
    {
        if (ClassHelper::hasTrait(get_called_class(), 'App\Traits\HasAfterActions')) {
            $method = ucfirst($method);
            $this->{"after{$method}"}($result);
        }
    }

    private function callBeforeAction($method, &$params)
    {        
        if (ClassHelper::hasTrait(get_called_class(), 'App\Traits\HasBeforeActions')) {
            $method = ucfirst($method);
            $this->{"before{$method}"}($params);
        }
    }

    /**
     * Get all params from route url and from GET, POST, JSON.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
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
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @param mixed $id 
     * @return mixed 
     */
    public function destroy()
    {
        $params = $this->getParams();
        
        $this->callBeforeAction(__FUNCTION__ , $params);

        $deleted = $this->model::findOrFail($params["model"])->delete();
        
        $this->callAfterAction(__FUNCTION__ ,$deleted);

        return $this->response($deleted, __FUNCTION__);
    }

    /**
     * Default index action.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @return mixed 
     */
    public function index()
    {
        $params = $this->getParams();

        $this->callBeforeAction(__FUNCTION__ , $params);

        $params["models"] = $this->indexQuery($params);

        $this->callAfterAction(__FUNCTION__ , $params);

        return view(ViewHelper::getView($this->model, "index"), $params);
    }

    /**
     * Query used for index action.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @return mixed
     */
    public function indexQuery(&$params)
    {
        return $this->model::paginate($this->itemsForPage);
    }

    /**
     * Redirect to a view. 
     * Automatically redirect to admin or web routes.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @param mixed $flag 
     * @param mixed $method 
     * @return mixed 
     */
    private function response($flag, $method)
    {
        if ($flag) {
            return redirect()
                ->route(ViewHelper::getView($this->model, "index", false))
                ->with(
                    'success', 
                    $this->messages[$method]['success']
                );
        } else {
            return redirect()
                ->back()
                ->with(
                    'error', 
                    $this->messages[$method]['error']
                );
        }
    }

    /**
     * 
     * 
     * @return Illuminate\View\View|Illuminate\Contracts\View\Factory 
     */
    public function show()
    {   
        $params = $this->getParams();

        $model = $this->showQuery($params);

        $params["model"] = $model;

        return view(ViewHelper::getView($this->model, "show"), $params);
    }

    public function showQuery(&$params)
    {
        return $this->model::findOrFail($params['model']);
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

        $rules = $this->formRequest::getRules($params);
        
        $data = Validator::make($params, $rules)->validate();

        $params["model"] = $this->model::create($data);

        $this->callAfterAction(__FUNCTION__ , $params);

        return $this->response($params["model"], __FUNCTION__);
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

        $rules = $this->formRequest::getRules($params);
        
        $data = Validator::make($params, $rules)->validate();

        $params["updated"] = $this->model::findOrFail($params["model"])->update($data);

        $this->callAfterAction(__FUNCTION__ , $params);

        return $this->response($params["updated"], __FUNCTION__);
    }
}