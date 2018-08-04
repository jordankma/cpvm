<?php

namespace Adtech\Core\App\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard as Guard;
use Adtech\Core\App\Models\Acl;
use \Adtech\Application\Cms\Libraries\Acl as AdtechAcl;
use Mockery\CountValidator\Exception;

class AclMiddleware
{
    protected $adtechAcl;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;

        $this->adtechAcl = AdtechAcl::getInstance();
    }

    public function handle($request, Closure $next)
    {
        $route = $request->route();
        $routeName = isset($route->action) && isset($route->action['as']) ? $route->action['as'] : null;
        if ($routeName != null && $this->adtechAcl->isAllow($routeName) == false) {
            if ($request->ajax()) {
                $response = [
                    'status' => 'ACCESS_DENIED',
                    'message' => 'Forbidden'
                ];
                return response(json_encode($response), 403);
            } else {
                abort(403);
            }
        }

        $response = $next($request);

        return $response;
    }
}