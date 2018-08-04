<?php

namespace Adtech\Core\App\Http\Controllers\Services;

use Adtech\Application\Cms\Controllers\Controller as Controller;
use Illuminate\Http\Request;
use Adtech\Application\Cms\Traits\ActivationTrait;
use Adtech\Core\App\Models\Activation;
use Adtech\Core\App\Models\User;
use Auth;

class HtmlController extends Controller
{
    public function get(Request $request, $path)
    {
        $file = 'angularjs-html/' . $path;
        return view($file);
    }
}