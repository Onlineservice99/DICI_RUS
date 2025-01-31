<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Sotbit\Seometa\SeometaUrlTable;
use Sotbit\Seometa\ChpuLinksTable;
use Sotbit\Seometa\SeoMetaMorphy;
use Bitrix\Main\Loader;
if(!Loader::includeModule('sotbit.seometa') || !Loader::includeModule('iblock'))
{
    return false;
}

global $APPLICATION;
global $SeoMetaWorkingConditions;

if(!$arParams['CACHE_TIME'])
{
    $arParams['CACHE_TIME'] = '36000000';
}
if(!$arParams['SORT'])
{
    $arParams['SORT'] = 'NAME';
}
$curPage = $APPLICATION->GetCurPage(false);
$cacheTime = $arParams['CACHE_TIME'];
$cache_id = md5(serialize(array($curPage, $arParams, $SeoMetaWorkingConditions, ($arParams['CACHE_GROUPS'] === 'N' ? false : $USER->GetGroups()))));
$cacheDir = '/sotbit.seometa.custom.tags/';
$cache = \Bitrix\Main\Application::getInstance()->getManagedCache();
$Tags = array();

if($cache->read($cacheTime, $cache_id, $cacheDir))
{
    $Tags = $cache->get($cache_id);
}
else
{
    $strict_relinking = false;
    $Conditions = array();
    $sections = \Sotbit\Seometa\Tags::findNeedSections($arParams['SECTION_ID'], $arParams['INCLUDE_SUBSECTIONS']); // list of all sections
    $SectionConditions = \Sotbit\Seometa\ConditionTable::GetConditionsBySections($sections); // list of all conditions by sections

    // if condition is active
    if($SeoMetaWorkingConditions)
    {
        foreach($SeoMetaWorkingConditions as $SeoMetaWorkingCondition)
        {
            $wasSections = false;
            if($SectionConditions[$SeoMetaWorkingCondition]) // if among all conditions by sections there is one that is active
            {
                if($SectionConditions[$SeoMetaWorkingCondition]['STRICT_RELINKING'] == 'Y')
                {
                    $strict_relinking = true;
                }

                if(sizeof($SectionConditions[$SeoMetaWorkingCondition]['SECTIONS']) > 0)
                {
                    $wasSections = true;
                }

                //unset($SectionConditions[$SeoMetaWorkingCondition]['SECTIONS'][array_search($arParams['SECTION_ID'], $SectionConditions[$SeoMetaWorkingCondition]['SECTIONS'])]);

                if(sizeof($SectionConditions[$SeoMetaWorkingCondition]['SECTIONS']) == 0 && $wasSections)
                {
                    unset($SectionConditions[$SeoMetaWorkingCondition]);
                }
            }
        }
    }

    $WorkingConditions = \Sotbit\Seometa\ConditionTable::GetConditionsFromWorkingConditions($SeoMetaWorkingConditions); // conditions selected in relinking

    if(is_array($SectionConditions) && is_array($WorkingConditions))
    {
        if(!$strict_relinking)
        {
            $Conditions = $SectionConditions;
        }

        // merge conditions selected in relinking with other
        foreach($WorkingConditions as $key => $WorkingCondition)
        {
            $Conditions[$key] = $WorkingCondition;
        }
    }
    elseif(is_array($SectionConditions))
    {
        $Conditions = $SectionConditions;
    }
    elseif(is_array($WorkingConditions))
    {
        $Conditions = $WorkingConditions;
    }

    $TagsObject = new \Sotbit\Seometa\Tags();
//	$Tags = $TagsObject->GenerateTags($Conditions, $SeoMetaWorkingConditions);
//    if($Conditions && is_array($Conditions)) {
//        $SeoMetaWorkingConditions = array_merge($SeoMetaWorkingConditions, array_keys($Conditions));
//    }

    $Tags = SeometaUrlTable::getAllByCondition($SeoMetaWorkingConditions);

    $morphyObject = SeoMetaMorphy::morphyLibInit();
    $arrIDTags = [];
    foreach ($Tags as &$tag) {
        if ($curPage == $tag['NEW_URL']) {
            $res = ChpuLinksTable::getList([
                'select' => array('*'),
                'filter' => array(
                    'MAIN_CHPU_ID' => $tag['ID']
                )
            ]);

            while ($item = $res->fetch()) {
                if (is_array($tmp = unserialize($item['SEOMETA_DATA_CHPU_LINK']))) {
                    $item = array_merge($item, $tmp);
                    if(!$Tags[$item['LINK_CHPU_ID']]) {
                        $arrIDTags[] = $item['LINK_CHPU_ID'];
                    }
                    $Tags[$item['LINK_CHPU_ID']]['SEOMETA_DATA_CHPU_LINK'] = $item;
                }
            }
            break;
        }
    }

    if($arrIDTags) {
        $arrExternalTags = SeometaUrlTable::getByArrId($arrIDTags);

        foreach ($arrExternalTags as $index => $arrExternalTag) {
            $Tags[$index] = array_merge($arrExternalTag, $Tags[$index]);
        }
    }

    $imageTags = $customTags = [];
    foreach ($Tags as $key => &$item) {
        if($item['SEOMETA_DATA_CHPU_LINK']['NAME_CHPU_LINK_REPLACE'] == 'Y') {
            $item['TITLE'] = $item['SEOMETA_DATA_CHPU_LINK']['NAME_CHPU_LINK'];
        } else {
            $item['TITLE'] = $item['NAME'];
        }

        if(!empty($item['SEOMETA_DATA_CHPU_LINK']['IMAGE'])) {
            $item['IMAGE'] = $item['SEOMETA_DATA_CHPU_LINK']['IMAGE'];
        }

        if(intval($item['IMAGE']) > 0)
        {
            $fileArray = CFile::GetFileArray($item['IMAGE']);
            $item['IMAGE'] = array();
            $item['IMAGE']['SRC'] = $fileArray['SRC'];
            $item['IMAGE']['DESCRIPTION'] = $fileArray['DESCRIPTION'];
        }

        if(!$item['SEOMETA_DATA_CHPU_LINK'] && empty($item['IMAGE'])) {
            unset($Tags[$key]);
            continue;
        }

        $item['PROPERTIES'] = unserialize($item['PROPERTIES']);
        \CSeoMetaTagsProperty::$params = $item['PROPERTIES'];
        $sku = new \Bitrix\Iblock\Template\Entity\Section($item['section_id']);
        $title = \Bitrix\Iblock\Template\Engine::process($sku, SeoMetaMorphy::prepareForMorphy($item['TITLE']));
        if(!empty($title)) {
            $title = SeoMetaMorphy::convertMorphy($title, $morphyObject);
//            $title = mb_strtolower($title, mb_internal_encoding());
//            $title[0] = mb_strtoupper($title[0], mb_internal_encoding());
            $item['TITLE'] = $title;
        }

        unset($item['SEOMETA_DATA_CHPU_LINK']);
        $item['URL'] = $item['NEW_URL'];

        if(!empty($item['IMAGE'])) {
            $imageTags[] = $item;
            unset($Tags[$key]);
        } else {
            $customTags[] = $item;
            unset($Tags[$key]);
        }
    }
    $Tags = array();

    $Tags['CUSTOM_TAGS'] = $customTags;
    $Tags['IMAGE_TAGS'] = $imageTags;

    if($strict_relinking)
    {
        foreach ($Tags as &$tagsArray) {
            foreach($tagsArray as $key => $tag)
            {
                if($tag['URL'] == $curPage) {
                    unset($tagsArray[$key]);
                    break;
                }
            }
            $tagsArray = array_values($tagsArray);

            $tagsArray = $TagsObject->SortTags($tagsArray, $arParams['SORT'], $arParams['SORT_ORDER']);
            $tagsArray = $TagsObject->CutTags($tagsArray, $arParams['CNT_TAGS']);
        }
    }

    unset($Conditions);
    if($Tags['CUSTOM_TAGS'] || $Tags['IMAGE_TAGS']) {
        $cache->set($cache_id ,$Tags);
    } else {
        $cache->clean($cache_id);
    }
}
if($Tags['CUSTOM_TAGS']) {
    $samePage = array_search($curPage,
        array_column($Tags['CUSTOM_TAGS'],
            'URL'));
    if ($samePage !== false) {
        unset($Tags['CUSTOM_TAGS'][$samePage]);
    }
}

if($Tags['IMAGE_TAGS']) {
    $samePage = array_search($curPage,
        array_column($Tags['IMAGE_TAGS'],
            'URL'));
    if ($samePage !== false) {
        unset($Tags['IMAGE_TAGS'][$samePage]);
    }
}

$arResult['ITEMS'] = $Tags;
unset($Tags);

$this->IncludeComponentTemplate();
?>