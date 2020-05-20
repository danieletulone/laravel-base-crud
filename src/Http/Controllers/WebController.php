<?php

namespace DanieleTulone\BaseCrud\Http\Controllers;

use DanieleTulone\BaseCrud\Http\Controllers\Controller;
use DanieleTulone\BaseCrud\Traits\HasFrontForms;
use DanieleTulone\BaseCrud\Traits\Viewable;

class WebController extends Controller
{
    use Viewable, HasFrontForms;

    /**
     * List of default actions.
     *
     * @var array
     */
    protected $actions = [
        'create',
        'destroy',
        'edit',
        'index',
        'show',
        'store',
        'update',
    ];
}
