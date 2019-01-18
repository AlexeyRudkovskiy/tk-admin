<?php

return [

    'entities' => [ /* Entities should be registered there */ ],
    'storage' => storage_path(),
    'images' => [
        'thumbnails' => [
            [
                'size' => [ 75, 75 ],
                'crop' => [
                    'intelligent' => true
                ],
            ],
            [
                'size' => [ 125, 125 ],
                'crop' => [
                    'intelligent' => true
                ]
            ],
            [ 75, 75, true ],
            [ 125, 125, true ]
        ]
    ],
    'files_browser' => [
        'all_on_one_page' => true
    ],
    'pagination' => [
        'per_page' => 15
    ],
    'configs' => [

    ]

];
