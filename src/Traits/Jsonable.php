<?php

namespace DanieleTulone\BaseCrud\Traits;

trait Jsonable
{
    /**
     * Return json response.
     *
     * @param mixed $flag
     * @param mixed $method
     * @return mixed
     */
    protected function response($params, $method)
    {
        return $params;
    }
}
