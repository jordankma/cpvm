<?php

namespace Adtech\Core\App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Support\Collection;
use Adtech\Application\Cms\Controllers\Controller as Controller;

class RouteController extends Controller
{
    public function manage()
    {
        return view('modules.core.route.manage');
    }

    //Table Data to index page
    public function data()
    {
        $app = app();
        $list = array();
        $routes = $app->routes->getRoutes();

        foreach ($routes as $route) {

            $wheres = '';
            if ($route->wheres) {
                foreach ($route->wheres as $k => $v) {
                    if ($k == 'as')
                        continue;
                    $wheres .= '<div>' . $k . ': ' . $v . '</div>';
                }
            }

            $list[] = [
                'uri' => $route->uri . $wheres,
                'name' => (isset($route->action['as'])) ? $route->action['as'] : 'N/A',
                'controller' => (isset($route->action['controller'])) ? $route->action['controller'] : 'N/A',
                'middleware' => (isset($route->action['middleware'])) ? (is_array($route->action['middleware']) ? implode(', ', $route->action['middleware'])  : $route->action['middleware']) : 'N/A'
            ];
        }
        $collection = Collection::make($list);
        return Datatables::of($collection)->make(true);
    }
}
