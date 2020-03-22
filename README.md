# Laravel Crud Controller
Welcome to Laravel Crud Controller repository. 

## What's the intent of this package?
This package may provide basic crud operations and allows you to customize completly the controller.
There are two controller: 
1. **CrudController**: The main controller that returns a response of type Illuminate\View\View.
2. **RestCrudController**: This controller extends the main controller but change the response type: it will be a JSON.

## Installation
Simply install with composer.

```bash
  composer require danieletulone/laravel-crud-controller
```

## Usage
Before use the controller, you must create all resources for a new model:
```bash
php artisan make:model Todo -a
```
<sup>The __-a__ flag meangin **all**: controller, model, migration, seeder and factory.s</sup>

#### Basic Usage
Edit the generated controller. 
1. Extends RestCrudController instead of Laravel standard Controller.
2. Set the property $model with the full name of generated model.
 ```php
<?php

namespace App\Http\Controllers;

use DanieleTulone\BaseCrud\Controllers\RestCrudController;

class LogoController extends RestCrudController
{
    /**
     * Model to use with this controller.
     * 
     * @var mixed
     */
    protected $model = 'App\Todo';
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
|--------- pizza
|------------ index.blade.php
|------------ show.blade.php
```

5. By default the CrudController has store, index, show, update and delete method. <br>
If you want to use create and edit method and views, you must to add HasFrontForms to Controller.
```php
<?php

namespace App\Http\Controllers;

use DanieleTulone\BaseCrud\Controllers\RestCrudController;
use DanieleTulone\BaseCrud\Traits\HasFrontForms;

class LogoController extends RestCrudController
{
    use HasFrontForms;

    /**
     * Model to use with this controller.
     * 
     * @var mixed
     */
    protected $model = 'App\Todo';
}
```

and in resources/views/pizza add:
- create.blade.php
- edit.blade.php
