<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Class Namespace
    |--------------------------------------------------------------------------
    |
    */

    'namespace' => 'Http\\Livewire',

    /*
    |--------------------------------------------------------------------------
    | View Path
    |--------------------------------------------------------------------------
    |
    */

    'view' => 'Resources/views/livewire',

    /*
    |--------------------------------------------------------------------------
    | Custom modules setup
    |--------------------------------------------------------------------------
    |
    */

    // 'custom_modules' => [
    //     'Chat' => [
    //         'path' => base_path('libraries/Chat'),
    //         'module_namespace' => 'Libraries\\Chat',
    //         // 'namespace' => 'Livewire',
    //         // 'view' => 'Resources/views/livewire',
    //         // 'name_lower' => 'chat',
    //     ],
    // ],

    'custom_modules' => [
        'Almacen' => [
            'path' => base_path('libraries/Almacen'),
            'module_namespace' => 'Libraries\\Almacen',
            'namespace' => 'Livewire',
            'view' => 'Resources/views/livewire',
            'name_lower' => 'almacen',
        ],
    ],

];
