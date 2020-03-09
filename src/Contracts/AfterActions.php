<?php

namespace DanieleTulone\BaseCrud\Contracts;

/**
 * Defines methods that are performed after any action.
 * 
 * @package DanieleTulone\BaseCrud\Contracts
 */
interface AfterActions
{
    /**
     * This method will be used after destroy action.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @param mixed &$params 
     * @return void 
     */
    public function afterDestroy(&$params);

    /**
     * This method will be used after index action.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @param mixed &$params 
     * @return void 
     */
    public function afterIndex(&$params);

    /**
     * This method will be used after store action.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @param mixed &$params 
     * @return void 
     */
    public function afterStore(&$params);

    /**
     * This method will be used after update action.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @param mixed &$params 
     * @return void 
     */
    public function afterUpdate(&$params);
}