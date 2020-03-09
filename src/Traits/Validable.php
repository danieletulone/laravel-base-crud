<?php

namespace DanieleTulone\BaseCrud\Traits;

use Validator;

trait Validable
{
    /**
     * FormRequest used for validate data.
     * 
     * @var mixed
     */
    protected $formRequest;

    /**
     * Validate the request.
     * 
     * @return void 
     */
    public function validate($params)
    {        
        return Validator::make(
            $params, 
            $this->formRequest::getRules($params)
        )->validate();
    }
}