<?php

namespace DanieleTulone\BaseCrud\Traits;

use DanieleTulone\BaseCrud\Helpers\ViewHelper;

trait Viewable
{
    /**
     * Page to redirect after store.
     * 
     * @var string
     */
    protected $storeRedirect = 'index';

     /**
     * Page to redirect after update.
     * 
     * @var string
     */
    protected $updateRedirect = 'index';

     /**
     * Page to redirect after delete.
     * 
     * @var string
     */
    protected $deleteRedirect = 'index';

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
            $method = redirect()->route(ViewHelper::getView(
                $this->model, 
                $this->{$method . "Redirect"},
                $params
            ));
        }

        return view(ViewHelper::getView($this->model,  $method, false), $params);
    }
}