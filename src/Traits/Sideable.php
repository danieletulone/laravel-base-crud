<?php

namespace DanieleTulone\BaseCrud\Traits;

/**
 * This trait add to methods the capacity to execute 
 * actions before of after primary code of method
 * It is like oppositive concept of middleware
 * 
 * @package DanieleTulone\BaseCrud\Trais
 */
trait Sideable
{
    /**
     * Make operations after action.
     * 
     * @param mixed $method 
     * @param mixed $params
     * @return void 
     */
    final protected function callAfterAction($method, &$params)
    {
        $this->callSideAction("after", $method, $params);
    }

    /**
     * Make operations before action.
     * 
     * @param mixed $method 
     * @param mixed $params 
     * @return void
     */
    final protected function callBeforeAction($method, &$params)
    {        
        $this->callSideAction("before", $method, $params);
    }

    /**
     * Make operation by side.
     * 
     * @param mixed $side 
     * @param mixed $method 
     * @param mixed $params 
     * @return void 
     */
    final private function callSideAction($side, $method, &$params)
    {
        $method = ucfirst($method);
        $sideMethod = "{$side}{$method}";

        if (method_exists($this, $sideMethod)) {
            $this->{$sideMethod}($params);
        }
    }
}