<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arParams */

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use \Bitrix\Iblock\Iblock;
use \Bitrix\Main\Data\Cache;
use \Bitrix\Main\Application;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\UI\PageNavigation;

class residents extends CBitrixComponent
{
    private int $iBlockId;
    private string $entityDataClass;

    public function onPrepareComponentParams($arParams): array
    {
        if (empty($arParams['LIMIT'])) {
            $arParams['LIMIT'] = 3;
        }

        return $arParams;
    }

    public function executeComponent(): array
    {
        if (!Loader::includeModule('iblock')) {
            throw new Exception('Не подключен модуль "Инфоблоки"');
        }

        $cache = Cache::createInstance();
        $taggedCache = Application::getInstance()->getTaggedCache();
        $cachePath = 'residents';
        $cacheTtl = 3153600;
        $cacheKey = 'residents_nav-residents' . Context::getCurrent()->getRequest()->getQuery('PAGEN_1');
        $items = [];
        $nav = null;

        if ($cache->initCache($cacheTtl, $cacheKey, $cachePath)) {
            $vars = $cache->getVars();
            $items = $vars['items'];
            $nav = $vars['nav'];
        } elseif ($cache->startDataCache()) {
            $taggedCache->startTagCache($cachePath);
            $nav = $this->getNavObject();
            $items = $this->getResidents($nav);
            $nav->setRecordCount($this->entityDataClass::getCount(['ACTIVE' => 'Y']));

            $vars = [
                'nav' => $nav,
                'items' => $items,
            ];

            $taggedCache->registerTag('iblock_id_' . $this->iBlockId);
            $taggedCache->endTagCache();
            $cache->endDataCache($vars);
        }

        $this->arResult['ITEMS'] = $items;
        $this->includeComponentTemplate();

        return ['NAV' => $nav];
    }

    public function getNavObject(): PageNavigation
    {
        $nav = new PageNavigation('nav-residents');
        $nav->allowAllRecords(false)
            ->setPageSize($this->arParams['LIMIT'])
            ->initFromUri();

        return $nav;
    }

    public function setIBlockId(): void
    {
        $this->iBlockId = IblockTable::getList([
            'filter' => ['CODE' => 'residents'],
            'select' => ['ID'],
            'limit' => 1
        ])->fetch()['ID'];
    }

    public function getResidents(PageNavigation $nav): array
    {
        $this->setIBlockId();
        $iblock = Iblock::wakeUp($this->iBlockId);
        $this->entityDataClass = $iblock->getEntityDataClass();
        $elements = $this->entityDataClass::getList([
            'filter' => ['ACTIVE' => 'Y'],
            'select' => ['Fio', 'Home.ELEMENT.Number', 'Home.ELEMENT.Street', 'Home.ELEMENT.City'],
            'offset' => $nav->getOffset(),
            'limit' => $nav->getLimit(),
        ])->fetchCollection();

        $residents = [];
        foreach ($elements as $element) {
            $homeElement = $element->getHome()->getElement();
            $residents[] = [
                'fio' => $element->getFio()->getValue(),
                'city' => $homeElement->getCity()->getValue(),
                'street' => $homeElement->getStreet()->getValue(),
                'number' => $homeElement->getNumber()->getValue()
            ];
        }

        return $residents;
    }
}
