<?php

namespace Adtech\Core\App\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Adtech\Core\App\Models\Setting;

class LocaleMiddleware
{
    public function handle($request, Closure $next)
    {
        $language = \Session::get('website_language', config('app.locale'));
        // Lấy dữ liệu lưu trong Session, không có thì trả về default lấy trong config

        config(['app.locale' => $language]);
        config(['translatable.locale' => $language]);
        // Chuyển ứng dụng sang ngôn ngữ được chọn

        $setting = Setting::where('name', 'language')->first();
        if (null != $setting) {
            config(['app.fallback_locale' => $setting->value]);
            config(['translatable.fallback_locale' => $setting->value]);
        }

        return $next($request);
    }
}
