<?php

return [
    'mode' => 'utf-8',
    'format' => 'A4',
    'default_font_size' => '12',
    'default_font' => 'sans-serif',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 5,
    'margin_bottom' => 20,
    'margin_header' => 2,
    'margin_footer' => 2,
    'orientation' => 'P',
    'title' => 'Dokan Khata',
    'author' => '',
    'watermark' => '',
    'show_watermark' => false,
    'watermark_font' => 'sans-serif',
    'display_mode' => 'fullpage',
    'watermark_text_alpha' => 0.1,
    'auto_language_detection' => false,
    'temp_dir' => rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR),
    'pdfa' => false,
    'pdfaauto' => false,
    'custom_font_dir' => base_path('resources/fonts/'), // don't forget the trailing slash!
    'custom_font_data' => [
        'kalpurush' => [
            'R' => 'kalpurush.ttf',    // regular font
            'B' => 'kalpurush.ttf',       // optional: bold font
            'I' => 'kalpurushi.ttf',     // optional: italic font
            'BI' => 'kalpurush.ttf', // optional: bold-italic font
            'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
            'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
        ],
        'adorsholipi' => [
            'R' => 'AdorshoLipi/AdorshoLipi.ttf',    // regular font
            'B' => 'AdorshoLipi/AdorshoLipi.ttf',       // optional: bold font
            'I' => 'AdorshoLipi/AdorshoLipi.ttf',     // optional: italic font
            'BI' => 'AdorshoLipi/AdorshoLipi.ttf', // optional: bold-italic font
            'useOTL' => 0xFF,    // required for complicated langs like Persian, Arabic and Chinese
            'useKashida' => 75,  // required for complicated langs like Persian, Arabic and Chinese
        ]
        // ...add as many as you want.
    ]

];
