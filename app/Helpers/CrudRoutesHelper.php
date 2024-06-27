<?php

namespace App\Helpers;

use Illuminate\Routing\Router;

final class CrudRoutesHelper
{
    public static function registerApiCrudRoutes(
        Router $router,
        string $controllerClass,
        array $methods = ['index', 'show', 'store', 'update', 'delete']
    ) {
        if (in_array('index', $methods)) {
            $router->post('/', [$controllerClass, 'index'])->name('index');
        }
        if (in_array('show', $methods)) {
            $router->get('/{id}', [$controllerClass, 'show'])->name('show');
        }
        if (in_array('store', $methods)) {
            $router->post('/store', [$controllerClass, 'store'])->name('store');
        }
        if (in_array('update', $methods)) {
            $router->post('/{id}', [$controllerClass, 'update'])->name('update');
        }
        if (in_array('delete', $methods)) {
            $router->delete('/{id}', [$controllerClass, 'delete'])->name('delete');
        }
    }
}
