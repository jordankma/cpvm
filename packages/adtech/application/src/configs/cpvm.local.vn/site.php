<?php

$modulesConfig = [];
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : null;
if ($host) {
    $newString = 'adtech.core';
    $variable = 'APP_MODULES_' . strtoupper(str_replace('.', '_', $host));
    $path = base_path('.env');
    if (file_exists($path)) {
        if (strpos(file_get_contents($path), $variable . '=') > 0) {
            $appModules = (env($variable) != '') ? env($variable) : $newString;
            $packagesList = explode('_', $appModules);
            if (count($packagesList) > 0) {
                if (($key = array_search($newString, $packagesList)) !== false) {
                    unset($packagesList[$key]);
                }
                array_push($packagesList, $newString);
                foreach ($packagesList as $packages) {
                    $modules = explode('.', $packages);
                    $modulesConfig[$modules[0]] = explode(',', $modules[1]);
                }
            }
        } else {
            file_put_contents($path, file_get_contents($path) . "\r\n" . $variable . '=' . $newString);
            header("Refresh:0");
        }
    }
}

return [
    'version' => '1.0.0',
    /*
    |--------------------------------------------------------------------------
    | Google Recaptcha
    |--------------------------------------------------------------------------
    */
    'google_recaptcha' => [
        'site_key' => '6LcOqQ4TAAAAABtzBkFsBf9RoqXtKICQ9bqoEPKf',
        'secret' => '6LcOqQ4TAAAAAN4uiSDcki1-v9IGsc-U4tS1i3go',
    ],

    /*
    |--------------------------------------------------------------------------
    | Exception class
    |--------------------------------------------------------------------------
    */
    'exception_handler' => '\Adtech\Application\Cms\Exceptions\Handler',

    /*
    |--------------------------------------------------------------------------
    | Config user activation
    |--------------------------------------------------------------------------
    */
    'user_activation' => true,

    /*
    |--------------------------------------------------------------------------
    | Website backend url
    |--------------------------------------------------------------------------
    */
    'api_prefix' => '/resource/dev/get',
    'admin_prefix' => '/admin',
    'homepage' => [
        'frontend' => [
            'method' => 'get',
            'uri' => '/',
            'middleware' => [],
            'action' => 'Adtech\Core\App\Http\Controllers\DashboardController@home',
        ],
        'backend' => [
            'method' => 'get',
            'uri' => '/',
            'middleware' => [],
            'action' => 'Adtech\Core\App\Http\Controllers\DashboardController@index',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Website template
    |--------------------------------------------------------------------------
    */
    'group_name' => 'vnedutech-cms',
    'angular_js' => true,
    'desktop' => [
        'template' => 'default',
        'skin' => 'default'
    ],
    'mobile' => [
        'template' => 'default',
        'skin' => 'default'
    ],
    'modules' => $modulesConfig,
//    'modules' => [
//        /**
//         * vendor => [packages or modules]
//         * example: 'adtech' => ['core', 'blog']
//         */
//        'adtech' => ['core']
//    ],
    'firebase' => [
        'domain' => '',
        'app_key' => '',
        'url_message' => '',
    ],
];
