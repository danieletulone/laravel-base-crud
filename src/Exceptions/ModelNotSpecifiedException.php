<?php

namespace DanieleTulone\BaseCrud\Exceptions;

use Exception;
use Facade\IgnitionContracts\Solution;
use Facade\IgnitionContracts\BaseSolution;
use Facade\IgnitionContracts\ProvidesSolution;

class ModelNotSpecifiedException extends Exception implements ProvidesSolution
{
    protected $className;

    public function __construct($className)
    {
        $this->className = $className;

        parent::__construct("Model not specified in $className.");
    }

     /**
     * Get the solution.
     *
     * @return \Facade\IgnitionContracts\Solution
     */
    public function getSolution(): Solution
    {
        return BaseSolution::create("You must define model in {$this->className}.")
            ->setSolutionDescription(
                "Go to your controller and define a model property: `protected \$model = App\Models\YourModelName`"
            );
    }
}
