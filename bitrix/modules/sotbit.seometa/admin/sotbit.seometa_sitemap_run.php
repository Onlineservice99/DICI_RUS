<?

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/xml.php');

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\Json;
use Bitrix\Seo\SitemapRuntime;
use Sotbit\Seometa\Helper\Linker;
use Sotbit\Seometa\SitemapTable;
use Sotbit\Seometa\ConditionTable;
use Sotbit\Seometa\SeometaUrlTable;
use Sotbit\Seometa\Helper\XMLMethods;
use Sotbit\Seometa\Helper\BackupMethods;

Loc::loadMessages(__FILE__);

global $APPLICATION, $USER;

if (!$USER->CanDoOperation('sotbit.seometa')) {
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

Loader::includeModule('sotbit.seometa');

$ID = intval($_REQUEST['ID']);
$arSitemap = null;

if ($ID > 0) {
    $dbSitemap = SitemapTable::getById($ID);
    $arSitemap = $dbSitemap->fetch();
}

if (!is_array($arSitemap)) {
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
    ShowError(Loc::getMessage("SEO_META_ERROR_SITEMAP_NOT_FOUND"));
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
} else {
    $arSitemap['SETTINGS'] = unserialize($arSitemap['SETTINGS']);
}

$arSites = array();
$rsSites = CSite::GetById($arSitemap['SITE_ID']);

$arSite = $rsSites->Fetch();
$SiteUrl = "";
$error = array();

if (isset($arSitemap['SETTINGS']['PROTO']) && $arSitemap['SETTINGS']['PROTO'] == 1) {
    $SiteUrl .= 'https://';
} elseif (isset($arSitemap['SETTINGS']['PROTO']) && $arSitemap['SETTINGS']['PROTO'] == 0) {
    $SiteUrl .= 'http://';
} else {
    $error = [
        'TYPE' => 'ERROR',
        'MSG' => ShowError(Loc::getMessage("SEO_META_ERROR_SITE_SITEMAP_NOT_FOUND"))
    ];
}

if (isset($arSitemap['SETTINGS']['DOMAIN']) && !empty($arSitemap['SETTINGS']['DOMAIN'])) {
    $SiteUrl .= $arSitemap['SETTINGS']['DOMAIN'] . mb_substr($arSite['DIR'],
            0,
            -1);
} else {
    $error = [
        'TYPE' => 'ERROR',
        'MSG' => ShowError(Loc::getMessage("SEO_META_ERROR_SITE_SITEMAP_NOT_FOUND"))
    ];
}

if (isset($arSitemap['SETTINGS']['FILENAME_INDEX']) && !empty($arSitemap['SETTINGS']['FILENAME_INDEX'])) {
    $mainSitemapName = $arSitemap['SETTINGS']['FILENAME_INDEX'];
} else {
    $error = [
        'TYPE' => 'ERROR',
        'MSG' => ShowError(Loc::getMessage("SEO_META_ERROR_SITE_SITEMAP_NOT_FOUND"))
    ];
}

if (isset($arSitemap['SETTINGS']['FILTER_TYPE']) && !is_null($arSitemap['SETTINGS']['FILTER_TYPE'])) {
    $FilterTypeKey = key($arSitemap['SETTINGS']['FILTER_TYPE']);
    $FilterCHPU = $arSitemap['SETTINGS']['FILTER_TYPE'][$FilterTypeKey];

    $FilterType = mb_strtolower($FilterTypeKey . ((!$FilterCHPU) ? '_not' : '') . '_chpu');
} else {
    $error = [
        'TYPE' => 'ERROR',
        'MSG' => ShowError(Loc::getMessage("SEO_META_ERROR_SITEMAP_FILTER_TYPE_NOT_FOUND"))
    ];
}

if ($error['TYPE'] == 'ERROR' && !empty($error['MSG'])) {
    echo json_encode($error);
    exit;
}

$mainSitemapUrl = $arSite['ABS_DOC_ROOT'] . $arSite['DIR'] . $mainSitemapName;

//initialize params
if ($_REQUEST['params']) {
    $conditions = $_REQUEST['params']['conditions'];
}
//---/-------------

if (file_exists($mainSitemapUrl)) {
    $FoundSeoMetaSitemap = false;
    $xml = simplexml_load_file($mainSitemapUrl);
    $connection = Application::getConnection();

    if (isset($action) && ($action == 'get_section_list')) {
        if ((new BackupMethods)->makeBackup($arSite['ABS_DOC_ROOT'] . $arSite['DIR']) == '') {
            (new CSeoMetaSitemapLight)->deleteOldSeometaSitemaps($arSite['ABS_DOC_ROOT'] . $arSite['DIR']);
        }

        $link = Linker::getInstance();

        $_SESSION['SEOMETA_SITEMAP_XMLWRITER']['URL_COUNT'] = 0;
        $_SESSION['SEOMETA_SITEMAP_XMLWRITER']['ADD_ID'] = 1;

        // reset all 'IN_SITEMAP' statuses before new generation of sitemap
        $sql = "UPDATE `b_sotbit_seometa_chpu` SET `IN_SITEMAP` = 'N'";
        $res = $connection->query($sql);

        echo json_encode($link->getConditionList($arSite['LID']));
        exit;
    }

    // START GENERATE XML ARRAY
    $rsCondition = ConditionTable::getList(array(
        'select' => array(
            'ID',
            'DATE_CHANGE',
            'INFOBLOCK',
            'STRONG',
            'NO_INDEX',
            'RULE',
            'SITES',
            'SECTIONS',
            'PRIORITY',
            'CHANGEFREQ',
        ),
        'filter' => array(
            'ACTIVE' => 'Y',
            '!=NO_INDEX' => 'Y',
            'ID' => $conditions[$currentCondition]
        ),
        'order' => array(
            'ID' => 'asc'
        )
    ))->fetch();

    $writer = \Sotbit\Seometa\Link\XmlWriter::getInstance($ID,
        $arSite['ABS_DOC_ROOT'] . $arSite['DIR'],
        $SiteUrl,
        $_REQUEST['action']);

    $conditionSites = array();
    $conditionSites = unserialize($rsCondition['SITES']);

    // if condition belongs to the site for which sitemap is generated
    if (is_array($conditionSites) && in_array($arSitemap['SITE_ID'],
            $conditionSites)) {
        $rule = unserialize($rsCondition['RULE']);
        if (empty($rule['CHILDREN'])) {
            exit;
        }

        $link = Linker::getInstance();
        if (isset($currentSection)) {
            if (in_array($params['sections'][$currentSection],
                unserialize($rsCondition['SECTIONS']))) {
                $link->Generate($writer,
                    $rsCondition['ID'],
                    array($params['sections'][$currentSection]));
            }

            //last iteration
            if (($iteration + 1) == $countIterations) {
                $writer->WriteEnd();
                SitemapTable::update($ID,
                    array('DATE_RUN' => new Bitrix\Main\Type\DateTime()));

                //work with mainsitemap
                if ($writer->getAddID() > 0) {
                    $xml = file_get_contents($mainSitemapUrl);
                    $data = (new XMLMethods)->xml2ary($xml);
                    (new XMLMethods)->delSeometaFromMainSitemap($data['sitemapindex']['_c']['sitemap']);

                    for ($i = 0; $i < count((array)$xml->sitemap); $i++) {
                        if (isset($xml->sitemap[$i]->loc) && mb_strpos($xml->sitemap[$i]->loc,
                                $SiteUrl . "/sitemap_seometa_") !== false) {
                            $xml->sitemap[$i]->loc = '';
                        }
                    }

                    $item = (new XMLMethods)->seometaMainSitemapFiles($writer->getAddID(),
                        $ID,
                        $SiteUrl);

                    if (is_array($item) && !empty($item)) {
                        (new XMLMethods)->ins2ary($data['sitemapindex']['_c']['sitemap'],
                            $item,
                            count($data['sitemapindex']['_c']['sitemap']));
                        $xmlData = (new XMLMethods)->ary2xml($data);

                        $writeStatus = (new XMLMethods)->writeSiteMap($mainSitemapUrl,
                            $xmlData);
                        if (isset($writeStatus['TYPE'])) {
                            ShowError(Loc::getMessage("SEO_META_ERROR_SITE_SITEMAP_NOT_FOUND") . ' ' . $mainSitemapUrl);
                        }
                    }
                }
            }
            //-----

            echo SitemapRuntime::showProgress(Loc::getMessage('SEO_META_SITEMAP_RUN_INIT'),
                Loc::getMessage('SEO_META_SITEMAP_RUN_TITLE'),
                ($iteration + 1) * 100 / $countIterations);
            exit;
        } else {
            $link->Generate($rsCondition['ID'],
                $writer);
        }
    }
} else {
    $error = [
        'TYPE' => 'ERROR',
        'MSG' => Loc::getMessage("SEO_META_ERROR_SITE_SITEMAP_NOT_FOUND") . ' ' . $mainSitemapUrl
    ];

    echo Json::encode($error);
    exit;
}
?>
