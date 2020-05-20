<?php

namespace DanieleTulone\BaseCrud\Routing;

use Illuminate\Routing\ResourceRegistrar as OriginalRegistrar;
use Illuminate\Routing\Router;
use Illuminate\Routing\RouteCollection;

class ResourceRegistrar extends OriginalRegistrar
{
    // add data to the array
    /**
     * The default actions for a resourceful controller.
     *
     * @var array
     */
    protected $resourceDefaults = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'data'];

    /**
     * Create a new resource registrar instance.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Route a resource to a controller.
     *
     * @param  string  $name
     * @param  string  $controller
     * @param  array  $options
     * @return \Illuminate\Routing\RouteCollection
     */
    public function register($name, $controller, array $options = [])
    {
        if (isset($options['parameters']) && ! isset($this->parameters)) {
            $this->parameters = $options['parameters'];
        }

        // If the resource name contains a slash, we will assume the developer wishes to
        // register these resource routes with a prefix so we will set that up out of
        // the box so they don't have to mess with it. Otherwise, we will continue.
        if (Str::contains($name, '/')) {
            $this->prefixedResource($name, $controller, $options);

            return;
        }

        // We need to extract the base resource from the resource name. Nested resources
        // are supported in the framework, but we need to know what name to use for a
        // place-holder on the route parameters, which should be the base resources.
        $base = $this->getResourceWildcard(last(explode('.', $name)));

        $defaults = $this->resourceDefaults;

        $collection = new RouteCollection;

        foreach ($this->getResourceMethods($defaults, $options) as $m) {
            $collection->add($this->{'addResource'.ucfirst($m)}(
                $name, $base, $controller, $options
            ));
        }

        $actionsFromController = $controller::actions;
        // foreach ()

        return $collection;
    }

    /**
     * Add the data method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceData($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . "/data";

        $action = $this->getResourceAction($name, $controller, 'data', $options);

        return $this->router->get($uri, $action);
    }
}
