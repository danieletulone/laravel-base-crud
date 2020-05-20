<?php

namespace DanieleTulone\BaseCrud\Http\Controllers;

use DanieleTulone\BaseCrud\Http\Controllers\Controller;
use DanieleTulone\BaseCrud\Traits\Jsonable;

/**
 * Crud controller for rest application.
 *
 * @package DanieleTulone\BaseCrud\Controllers
 */
class ApiController extends Controller
{
    use Jsonable;

    /**
     * List of default actions.
     *
     * @var array
     */
    protected $actions = [
        'destroy',
        'index',
        'show',
        'store',
        'update',
    ];
}
