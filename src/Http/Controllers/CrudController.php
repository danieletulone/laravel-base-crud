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
class CrudController extends Controller
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
     * 
     * @param mixed $method 
     * @param mixed $params
     * @return void 
     */
    private function callAfterAction($method, &$params)
    {
        if (ClassHelper::hasTrait(get_called_class(), 'DanieleTulone\BaseCrud\Traits\HasAfterActions')) {
            $method = ucfirst($method);
            $this->{"after{$method}"}($params);
        }
    }

    /**
     * Make operations before action.
     * 
     * @param mixed $method 
     * @param mixed $params 
     * @return void 
     */
    private function callBeforeAction($method, &$params)
    {        
        if (ClassHelper::hasTrait(get_called_class(), 'DanieleTulone\BaseCrud\Traits\HasBeforeActions')) {
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
        
        $params["deleted"] = $deleted;

        $this->callAfterAction(__FUNCTION__ , $params);

        return $this->response($params, __FUNCTION__);
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

        return $this->response($params, __FUNCTION__);
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
    private function response($params, $method)
    {
        return view(ViewHelper::getView($this->model,  $method, false), $params);
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

        return $this->response($params, __FUNCTION__);
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

        $rules = $this->formRequest::getRules($params);
        
        $data = Validator::make($params, $rules)->validate();

        $params["updated"] = $this->model::findOrFail($params["model"])->update($data);

        $this->callAfterAction(__FUNCTION__ , $params);

        return $this->response($params, __FUNCTION__);
    }
}