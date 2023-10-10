<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arResult */

$this->setFrameMode(true); ?>
<h1>Тест</h1>

<?php
foreach ($arResult['ITEMS'] as $item) { ?>
    <div><?= $item['fio'] ?> - <?= $item['city'] ?>, <?= $item['street'] ?>, <?= $item['number'] ?></div>
<?php
}
?>
