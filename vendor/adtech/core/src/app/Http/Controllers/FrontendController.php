<?php

namespace Adtech\Core\App\Http\Controllers;

use Illuminate\Http\Request;
use Adtech\Application\Cms\Controllers\MController as Controller;

class FrontendController extends Controller
{
    public function index(Request $request)
    {
        return view('ADTECH-CORE::modules.core.dashboard.frontend');
    }
}
