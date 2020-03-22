<?php

namespace DanieleTulone\BaseCrud\Traits;

use DanieleTulone\BaseCrud\Helpers\ViewHelper;

/**
 * This trait add create and edit methods to controller.
 * 
 * @package DanieleTulone\BaseCrud\Traits
 */
trait HasFrontForms
{
    /**
     * Show a form to store a new model into db.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @return mixed 
     */
    public function create()
    {
        $params = request()->route()->parameters;

        return view(
            ViewHelper::getView($this->model, "create"), 
            $params
        );
    }

    /**
     * Show a form to edit a model into db.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @return mixed 
     */
    public function edit()
    {
        $params = request()->route()->parameters;
        
        $model = $this->showQuery($params);
        
        $modelName = strtolower(class_basename($this->models));
        
        $params[$modelName] = $model;

        return view(
            ViewHelper::getView($this->model, "edit"), 
            $params
        );
    }
}