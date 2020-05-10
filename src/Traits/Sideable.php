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
    final protected function callAfterAction($method)
    {
        $this->callSideAction("after", $method);
    }

    /**
     * Make operations before action.
     *
     * @param mixed $method
     * @param mixed $params
     * @return void
     */
    final protected function callBeforeAction($method)
    {
        $this->callSideAction("before", $method);
    }

    /**
     * Make operation by side.
     *
     * @param mixed $side
     * @param mixed $method
     * @param mixed $params
     * @return void
     */
    final private function callSideAction($side, $method)
    {
        $method = ucfirst($method);
        $sideMethod = "{$side}{$method}";
        $params = $this->getParams();

        if (method_exists($this, $sideMethod)) {
            $this->{$sideMethod}($params);
        }
    }
}
