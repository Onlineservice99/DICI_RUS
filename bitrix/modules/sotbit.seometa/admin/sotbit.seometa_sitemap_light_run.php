<?

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/xml.php');

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Sotbit\Seometa\SeometaUrlTable;
use Sotbit\Seometa\SitemapTable;
use \Sotbit\Seometa\ConditionTable;
use Sotbit\Seometa\Helper\BackupMethods;
use Bitrix\Main\Loader;

global $USER, $APPLICATION;

Loc::loadMessages(__FILE__);

if (!Loader::includeModule(CSeoMeta::MODULE_ID) || !$USER->CanDoOperation(CSeoMeta::MODULE_ID)) {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

if (!empty($_REQUEST['ID'])) {
    $requestData['ID'] = intval($_REQUEST['ID']);
    $requestData['offset'] = intval($_REQUEST['offset']);
    $requestData['sitemap_index'] = intval($_REQUEST['sitemap_index']);
    $requestData['count_link_writed'] = 0;
} else {
    if (!empty($_REQUEST['data'])) {
        $requestData = \Bitrix\Main\Web\Json::decode($_REQUEST['data']);
    }
}
$arSitemap = null;

if ($requestData['ID'] > 0) {
    $arSitemap = SitemapTable::getById($requestData['ID'])->fetch();

    if ($arSitemap['SITE_ID']) {
        $_REQUEST['limit'] = $requestData['limit'] =
            Option::get(CSeoMeta::MODULE_ID,
                'SEOMETA_SITEMAP_COUNT_LINKS_FOR_OPERATION',
                '10000',
                $arSitemap['SITE_ID']) < Option::get(CSeoMeta::MODULE_ID,
                'SEOMETA_SITEMAP_COUNT_LINKS',
                '50000',
                $arSitemap['SITE_ID']) ?
                Option::get(CSeoMeta::MODULE_ID,
                    'SEOMETA_SITEMAP_COUNT_LINKS_FOR_OPERATION',
                    '10000',
                    $arSitemap['SITE_ID']) :
                Option::get(CSeoMeta::MODULE_ID,
                    'SEOMETA_SITEMAP_COUNT_LINKS',
                    '50000',
                    $arSitemap['SITE_ID']);
    }
    $seometaSitemap = new CSeoMetaSitemapLight();
    $sitePaths = $seometaSitemap->pathMainSitemap($requestData['ID']);
    $_REQUEST['SITE_ID'] = $requestData['SITE_ID'] = $sitePaths['site_id'];
}

if (!is_array($arSitemap) || $sitePaths['TYPE'] == 'ERROR') {
    ShowError(Loc::getMessage("SEO_META_ERROR_SITE_SITEMAP_NOT_FOUND"));
    die();
} else {
    $arSitemap['SETTINGS'] = unserialize($arSitemap['SETTINGS']);
}

$SiteUrl = '';
if ($sitePaths['domain_dir']) {
    $SiteUrl = $sitePaths['domain_dir'];
} else {
    ShowError(Loc::getMessage("SEO_META_ERROR_SITE_SITEMAP_NOT_FOUND"));
    die();
}

$mainSitemapName = '';
if (isset($arSitemap['SETTINGS']['FILENAME_INDEX']) && !empty($arSitemap['SETTINGS']['FILENAME_INDEX'])) {
    $mainSitemapName = $arSitemap['SETTINGS']['FILENAME_INDEX'];
} else {
    ShowError(Loc::getMessage("SEO_META_ERROR_SITE_SITEMAP_NOT_FOUND"));
    die();
}

if (isset($arSitemap['SETTINGS']['FILTER_TYPE']) && !is_null($arSitemap['SETTINGS']['FILTER_TYPE'])) {
    $FilterTypeKey = key($arSitemap['SETTINGS']['FILTER_TYPE']);
    $FilterCHPU = $arSitemap['SETTINGS']['FILTER_TYPE'][$FilterTypeKey];
    $FilterType = mb_strtolower($FilterTypeKey . ((!$FilterCHPU) ? '_not' : '') . '_chpu');
} else {
    ShowError(Loc::getMessage("SEO_META_ERROR_SITEMAP_FILTER_TYPE_NOT_FOUND"));
    die();
}

$mainSitemap = $sitePaths['abs_path'] . $mainSitemapName;

if (file_exists($mainSitemap)) {
    if ($_REQUEST['action'] == 'write_sitemap') {
        if ((new BackupMethods)->makeBackup($sitePaths['abs_path']) == '') {
            $seometaSitemap->deleteOldSeometaSitemaps($sitePaths['abs_path']);
        }
    }

    $arrConditionsParams = ConditionTable::getConditionBySiteId($sitePaths['site_id']);

    $filter['ACTIVE'] = 'Y';

    if ($arSitemap['SETTINGS']['EXCLUDE_NOT_SEF'] == 'Y') {
        $filter['CONDITION_ID'] = [];

        foreach ($arrConditionsParams as $conditionParam) {
            $filter['CONDITION_ID'] = array_merge($filter['CONDITION_ID'],
                [$conditionParam['ID']]);
        }
    }

    $arrUrls = [];
    $arrUrls = SeometaUrlTable::getList(
        array(
            'select' => array(
                'ID',
                'NEW_URL',
                'REAL_URL',
                'DATE_CHANGE',
                'CONDITION_ID'
            ),
            'filter' => $filter,
            'order' => array('ID'),
            'limit' => $requestData['limit'],
            'offset' => $requestData['offset'] !== 0 ? $requestData['limit'] * $requestData['offset'] : 0
        )
    )->fetchAll();

    if ($arrUrls !== false && count($arrUrls) > 0) {
        echo $seometaSitemap->generateSitemap($arrUrls,
            $SiteUrl);
    } else {
        echo $seometaSitemap->generateSitemapFinish($requestData['ID'],
            $requestData['sitemap_index']);
    }

    die();
}
?>
