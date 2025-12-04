<?php

return [

    'class_namespace' => 'App\\Livewire',

    // Layout default (opcional)
    'layout' => 'components.layouts.app',

    'temporary_file_upload' => [
        // 👇 ESTA ES LA CLAVE
        'disk' => 'public',

        // Puedes dejar esto null
        'rules' => null,
    ],
];
