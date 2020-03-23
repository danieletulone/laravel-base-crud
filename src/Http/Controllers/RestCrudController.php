<?php

namespace DanieleTulone\BaseCrud\Http\Controllers;

use DanieleTulone\BaseCrud\Http\Controllers\Controller;
use DanieleTulone\BaseCrud\Traits\Jsonable;

/**
 * Crud controller for rest application.
 * 
 * @package DanieleTulone\BaseCrud\Controllers
 */
class RestCrudController extends Controller
{
    use Jsonable;
}