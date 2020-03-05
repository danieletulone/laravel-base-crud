<?php

namespace DanieleTulone\BaseCrud\Controllers;

use DanieleTulone\BaseCrud\Controllers\CrudController;

/**
 * Crud controller for rest application.
 * 
 * @package DanieleTulone\BaseCrud\Controllers
 */
class RestCrudController extends CrudController
{
    /**
     * Return $params. Laravel convert it into json response.
     * 
     * @param mixed $params 
     * @return mixed 
     */
    public function response($params)
    {
        return $params;
    }
}