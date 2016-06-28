## Laravel View Crud Generator

View CRUD Generation using barryvdh/ide-helper:models

### Install

Require this package with composer using the following command:

```bash
composer require gadyb/laravel-model-view-generator
```

After updating composer, add the service provider to the `providers` array in `config/app.php`

```php
GBarak\ViewCrudGenerator\ViewCrudServiceProvider::class,
```

### Generating phpDocs for models

> You need to require `barryvdh/laravel-ide-helper` in your own composer.json to generate the phpDocs for the models.
> For usage please refer to https://github.com/barryvdh/laravel-ide-helper

## Usage
```bash
php artisan vcgen:make PostModel --viewPath="path/to/output/views"
```


### License

The Laravel View Crud Generator is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
