<?php

namespace DanieleTulone\BaseCrud\Traits;

use Validator;

trait Validable
{

    /**
     * Validate the request.
     * 
     * @return void 
     */
    public function callValidator(&$params)
    {
        if (!empty($this->formRequest)) {
            $params["data"] = Validator::make(
                $params, 
                $this->formRequest::getRules($params)
            )->validate();
        }
    }
}