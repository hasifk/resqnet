<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',

     'customized' => [
    'newsfeed_image' => [
        [
            'height' => 136,
            'width'  => 136,
        ],
        [
            'height' => 90,
            'width'  => 90,
        ],
        [
            'height' =>168,
            'width'  => 300,
        ],

    ],
         'profile_avatar' => [
             [
                 'height' => 90,
                 'width'  => 90,
             ],

         ],
         'gp_image' => [
             [
                 'height' => 90,
                 'width'  => 90,
             ],

         ],


]

);
