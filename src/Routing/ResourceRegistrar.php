<?php

namespace DanieleTulone\BaseCrud\Routing;

use Illuminate\Routing\ResourceRegistrar as OriginalRegistrar;
use Illuminate\Routing\Router;
use Illuminate\Routing\RouteCollection;
use Illuminate\Support\Str;

class ResourceRegistrar extends OriginalRegistrar
{

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

        $controllerNs = $this->router->getGroupStack()[0]['namespace'] . '\\' . $controller;
        $controllerClass = new $controllerNs;

        if (property_exists($controllerNs, 'actions')) {
            foreach ($controllerClass->getActions() as $action) {
                if (gettype($action) == 'array') {
                    if (method_exists($this, 'addResource'.ucfirst($action['name']))) {
                        $collection->add($this->{'addResource'.ucfirst($action['name'])}(
                            $name, $base, $controller, $options
                        ));
                    } else {
                        $collection->add($this->addResourceFromArray(
                            $name, $base, $controller, $options, $action
                        ));
                    }
                }

                if (gettype($action) == 'string') {
                    if (method_exists($this, 'addResource'.ucfirst($action))) {
                        $collection->add($this->{'addResource'.ucfirst($action)}(
                            $name, $base, $controller, $options
                        ));
                    } else {
                        $collection->add($this->addResourceGet(
                            $name, $base, $controller, $options, $action
                        ));
                    }
                }
            }
        }

        return $collection;
    }

    /**
     * Add generic method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceGet($name, $base, $controller, $options, $value)
    {
        $uri = $this->getResourceUri($name) . "/$value";

        $action = $this->getResourceAction($name, $controller, $value, $options);

        return $this->router->get($uri, $action);
    }

    protected function addResourceFromArray($name, $base, $controller, $options, $value)
    {
        $uri = $this->getResourceUri($name) . "/" . ($value['uri'] ?? '');

        $action = $this->getResourceAction($name, $controller, $value['action'], $options);

        return $this->router->{strtolower($value['method']) ?? 'get'}($uri, $action);
    }
}
