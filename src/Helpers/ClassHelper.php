<?php

namespace DanieleTulone\BaseCrud\Helpers;

/**
 * Helper for class.
 * 
 * @author Daniele Tulone <danieletulone.work@gmail.com>
 * 
 * @package DanieleTulone\BaseCrud\Helpers
 */
class ClassHelper
{
    /**
     * Get name of class without namespace.
     * 
     * @author Daniele Tulone <danieletulone.work@gmail.com>
     * 
     * @param mixed $class 
     * @return mixed 
     */
    public static function getName ($class) 
    {
        if (gettype($class) == "string") {
            $classSpace = explode("\\", $class);
        } else {
            $classSpace = explode("\\", get_class($class));
        }
        
        return array_pop($classSpace);
    }

    /**
     * Check if a class uses a trait.
     * 
     * @param string $class 
     * @param string $trait 
     * @return bool 
     */
    public static function hasTrait($class, $trait)
    {
        return in_array($trait, class_uses($class));
    }
}