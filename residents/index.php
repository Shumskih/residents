<?php

/** @global CMain $APPLICATION */

require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/header.php');

$data = $APPLICATION->IncludeComponent(
    'test:residents',
    '',
    [
        'LIMIT' => 3
    ],
    false,
    ['HIDE_ICONS' => 'Y']
);

$APPLICATION->IncludeComponent(
    'bitrix:main.pagenavigation',
    'modern',
    [
        'NAV_OBJECT' => $data['NAV'],
        'SEF_MODE' => 'Y'
    ],
    false
);

require($_SERVER["DOCUMENT_ROOT"] . '/bitrix/footer.php');

