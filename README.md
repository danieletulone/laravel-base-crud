# Laravel Base Crud - Controller
Welcome to Laravel Crud Controller repository. 

## What's the intent of this package?
This package may provide basic crud operations and allows you to customize completly the controller.
There are two controller: 
1. **CrudController**: The main controller that returns a response of type Illuminate\View\View.
2. **RestCrudController**: This controller extends the main controller but change the response type: it will be a JSON.

## Installation
Simply install with composer.

```bash
  composer require danieletulone/laravel-base-crud
```

# Usage
Before use the controller, you must create all resources for a new model:
```bash
php artisan make:model Pizza -a
```
<sup>The __-a__ flag meangin **all**: controller, model, migration, seeder and factory.</sup>

## Basic Usage
Edit the generated controller. 
1. Extends RestCrudController instead of Laravel standard Controller.
2. (optional) Set the property $model into controller with the namespace of your model.
 ```php
<?php

namespace App\Http\Controllers;

use DanieleTulone\BaseCrud\Http\Controllers\CrudController;

class PizzaController extends CrudController
{
    //
}
```

3. Define routes, for example, in ```routes/web.php```.
```php
Route::resource('pizzas', 'PizzaController');
```

4. Create views follow this standard:
```
|--- resources
|------ views
|--------- pizzas
|------------ index.blade.php
|------------ show.blade.php
```

5. By default the CrudController has store, index, show, update and delete method. <br>
If you want to use create and edit method and views, you must to add the trait HasFrontForms to Controller.
```php
<?php

namespace App\Http\Controllers;

use DanieleTulone\BaseCrud\Http\Controllers\CrudController;
use DanieleTulone\BaseCrud\Traits\HasFrontForms;

class PizzaController extends CrudController
{
    use HasFrontForms;
}
```
and in resources/views/pizzas add:
- create.blade.php
- edit.blade.php

Now, define rules into Pizza model, create the views and migrate table.
**All crud operations are implemented!**

## Basic Usage + Validation
1. Follow the basic usage instructions.
2. Create a FormRequest and define rules;
```
php artisan make:request PizzaRequest
```
3. (optional) Set $formRequest property into controller.

## Advanced Usage: Customization
The main controller has main operations and default queries. <bt>
You can edit the full flow of controller and edit queries.

### Customize queries
Every method has its query method.
- index : indexQuery
- delete: deleteQuery
- show:   showQuery
- create: No method for create
- store:  storeQuery
- update: updateQuery

So, in your controller, you can ovveride methods like that:
```php
class PizzaController extends CrudController
{
    // ... code
    
    /**
     * Query used for index method.
     * 
     * @param mixed $params 
     * @return mixed
     */
    public function indexQuery(&$params)
    {
        $modelsName = Str::plural($this->getModelName());

        $this->params[$modelsName] = $this->model::where('price', '>', 15)->paginate();
    }
    
    // ... code
}
```

## Customize operations
Each method follows this flow.
1. Get params from post, get or from route url (Ex: /pizza/{pizza} -> in params ypu will have pizza param).
2. Execute, if exists, a method called before{METHOD_NAME}. Ex: beforeIndex, beforeStore, etc.
3. Validate request, if there is Validable trait.
4. Call query method. Ex: indexQuery, storeQuery, etc.
5. Execute, if exists, a method called after{METHOD_NAME}. Ex: beforeIndex, beforeStore, etc.
6. Return a response (view or json).

So, you can customize operation creating after/before methods in controller. <br>
You can create them manually with or without implements interface. <br>
There are two interface:
- DanieleTulone\BaseCrud\Contracts\AfterActions;
- DanieleTulone\BaseCrud\Contracts\BeforeActions;
