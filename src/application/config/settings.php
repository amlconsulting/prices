<?php

return [
    'displayErrorDetails' => getenv('APP_DEBUG'),
    'addContentLengthHeader' => false,
    'db' => [
        'db_host' => getenv('DB_URL'),
        'db_name' => getenv('DB_NAME'),
        'db_user' => getenv('DB_USER'),
        'db_pass' => getenv('DB_PASS')
    ]
];