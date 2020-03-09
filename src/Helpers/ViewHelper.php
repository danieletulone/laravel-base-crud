<?php

namespace DanieleTulone\BaseCrud\Helpers;

/**
 * This class helps you to generate dinamically view/route name.
 * Ex: admin.pages.product.index
 * 
 * @package DanieleTulone\BaseCrud\Helpers
 */
class ViewHelper
{
    private const ADMIN_PREFIX = "admin";

    /**
     * Get view string. Also usable for route names.
     * 
     * @param mixed $class 
     * @param string $method 
     * @param bool $pages 
     * @return string 
     */
    public static function getView($class, $method = "index", $pages = true)
    {
        $modelName = strtolower(ClassHelper::getName($class));

        $pieces = self::getPieces($modelName, $method, $pages);

        return implode(".", $pieces);
    }

    /**
     * Add admin.
     * 
     * @return string
     */
    public static function addAdmin(&$pieces)
    {
        if (request()->route()->getPrefix() == "admin") {
            array_unshift($pieces, self::ADMIN_PREFIX);
        }

        return $pieces;
    }

    /**
     * Add model.
     * 
     * @return string 
     */
    public static function addModel(&$pieces, $modelName)
    {
        array_push($pieces, $modelName);

        return $pieces;
    }

    /**
     * Add method
     * 
     * @return string 
     */
    public static function addMethod(&$pieces, $method)
    {
        array_push($pieces, $method);

        return $pieces;
    }

    /**
     * Add 'admin.' to view name or empty string.
     * 
     * @return string 
     */
    public static function addPath(&$pieces, $string)
    {
        array_push($pieces, $string);

        return $pieces;
    }

    public static function getPieces($modelName, $method, $pages = true)
    {
        $pieces = [];
        self::addAdmin($pieces);

        if ($pages) {
            self::addPath($pieces, "pages");
        }

        self::addModel($pieces, $modelName);
        self::addMethod($pieces, $method);

        return $pieces;
    }
}