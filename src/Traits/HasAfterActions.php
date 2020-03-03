<?php

namespace DanieleTulone\BaseCrud\Traits;

/**
 * This trait add methods that are performed after any action.
 * The controller which you want apply this trait must to
 * extend BaseCrudController. 
 * 
 * Actions are: 
 *          - Destroy
 *          - Index
 *          - Store
 *          - Update
 * 
 * @author Daniele Tulone <danieletulone.work@gmail.com>
 */
trait HasAfterActions
{
    /**
     * This method will be used after destroy action.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @param mixed $result 
     * @return void 
     */
    public function afterDestroy($result) {}

    /**
     * This method will be used after index action.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @param mixed $result 
     * @return void 
     */
    public function afterIndex($result) {}

    /**
     * This method will be used after store action.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @param mixed $result 
     * @return void 
     */
    public function afterStore($result) {}

    /**
     * This method will be used after update action.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @param mixed $result 
     * @return void 
     */
    public function afterUpdate($result) {}
}