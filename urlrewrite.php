<?php

$arUrlRewrite = [
    1 => [
        'CONDITION' => '#^/residents/.*(page-)+([0-9]+)+.*#',
        'RULE' => 'PAGEN_1=$2',
        'ID' => 'test:residents',
        'PATH' => '/residents/index.php',
        'SORT' => 100,
    ],
];
