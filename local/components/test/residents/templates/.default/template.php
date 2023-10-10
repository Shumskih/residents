<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var array $arParams
 * @var array $arResult
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 * @var CBitrixComponent $component
 */

$this->setFrameMode(true); ?>
<h1>Тест</h1>

<?php
foreach ($arResult['ITEMS'] as $item) { ?>
    <div><?= $item['fio'] ?> - <?= $item['city'] ?>, <?= $item['street'] ?>, <?= $item['number'] ?></div>
<?php
}
?>
