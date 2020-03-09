<?php

namespace DanieleTulone\BaseCrud\Contracts;

/**
 * Define methods that are performed before any action.
 * 
 * @package DanieleTulone\BaseCrud\Contracts
 */
interface BeforeActions
{
    /**
     * This method will be used before destroy action.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @param mixed $params Pointer to parameters.
     * @return void 
     */
    public function beforeDestroy(&$params);

    /**
     * This method will be used before index action.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @param mixed $params Pointer to parameters.
     * @return void 
     */
    public function beforeIndex(&$params);
    
    /**
     * This method will be used before store action.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @param mixed $params Pointer to parameters.
     * @return void 
     */
    public function beforeStore(&$params);

    /**
     * This method will be used before update action.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @param mixed $params Pointer to parameters.
     * @return void 
     */
    public function beforeUpdate(&$params);
}