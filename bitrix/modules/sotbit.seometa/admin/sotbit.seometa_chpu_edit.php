<?
use Sotbit\Seometa\ChpuLinksTable;
use Sotbit\Seometa\SeometaUrlTable;
use Sotbit\Seometa\ChpuSeoDataTable;
//use Sotbit\Seometa\ChpuTagsTable;
use Sotbit\Seometa\ConditionTable;
use Sotbit\Seometa\SectionUrlTable;
use Sotbit\Seometa\SeoMetaMorphy;
use Bitrix\Iblock;
use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity;
use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Type;
use Bitrix\Main\Localization\Loc;

require_once ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

$id_module = 'sotbit.seometa';
if (!Loader::includeModule( 'iblock' ) || !Loader::includeModule( $id_module ))
    die();

// For menu
CJSCore::Init( array(
        "jquery"
) );

const MIN_SEO_TITLE = 50;
const MAX_SEO_TITLE = 70;

const MIN_SEO_KEY = 120;
const MAX_SEO_KEY = 150;

const MIN_SEO_DESCR = 130;
const MAX_SEO_DESCR = 180;

//$overrideProps = [
//    'N' => Loc::getMessage('SEO_META_EDIT_OVERRIDE_NO'),
//    'Y' => Loc::getMessage('SEO_META_EDIT_OVERRIDE_YES'),
//    'M' => Loc::getMessage('SEO_META_EDIT_OVERRIDE_MANUAL'),
//];

$POST_RIGHT = $APPLICATION->GetGroupRight( "sotbit.seometa" );
if ($POST_RIGHT == "D")
    $APPLICATION->AuthForm( Loc::GetMessage( "ACCESS_DENIED" ) );

IncludeModuleLangFile( __FILE__ );

$aTabs = array(
    array(
        "DIV" => "edit1",
        "TAB" => Loc::GetMessage( "SEO_META_EDIT_TAB_URL" ),
        "ICON" => "main_user_edit",
        "TITLE" => Loc::GetMessage( "SEO_META_EDIT_TAB_URL" )
    ),
    array(
        "DIV" => "edit2",
        "TAB" => Loc::GetMessage( "SEO_META_EDIT_TAB_SEO" ),
        "ICON" => "main_user_edit",
        "TITLE" => Loc::GetMessage( "SEO_META_EDIT_TAB_SEO" )
    ),
    array(
        "DIV" => "edit3",
        "TAB" => Loc::GetMessage( "SEO_META_EDIT_TAB_GRAPH_MENU" ),
        "ICON" => "main_user_edit",
        "TITLE" => Loc::GetMessage( "SEO_META_EDIT_TAB_GRAPH_MENU" )
    ),
    array(
        "DIV" => "edit4",
        "TAB" => Loc::GetMessage( "SEO_META_EDIT_TAB_BOTTOM_TAGS" ),
        "ICON" => "main_user_edit",
        "TITLE" => Loc::GetMessage( "SEO_META_EDIT_TAB_BOTTOM_TAGS" )
    ),

);
$tabControl = new CAdminForm( "tabControl", $aTabs );

$ID = intval( $_REQUEST['ID'] );

if ($ID > 0)
{
    $chpu = SeometaUrlTable::getById( $ID );
    $chpu['SITE_ID'] = unserialize($chpu['SITE_ID']);
}

if (isset( $_REQUEST["NAME"] ) && $_REQUEST["NAME"])
    $chpu["NAME"] = $_REQUEST["NAME"];
if (isset( $_REQUEST["ACTIVE"] ) && $_REQUEST["ACTIVE"])
    $chpu["ACTIVE"] = $_REQUEST["ACTIVE"];
if (isset( $_REQUEST["section"] ) && $_REQUEST["section"])
    $chpu["CATEGORY_ID"] = $_REQUEST["section"];
if (isset( $_REQUEST["CATEGORY_ID"] ) && $_REQUEST["CATEGORY_ID"])
    $chpu["CATEGORY_ID"] = $_REQUEST["CATEGORY_ID"];
if (isset( $_REQUEST["REAL_URL"] ) && $_REQUEST["REAL_URL"])
    $chpu["REAL_URL"] = $_REQUEST["REAL_URL"];
if (isset( $_REQUEST["NEW_URL"] ) && $_REQUEST["NEW_URL"])
    $chpu["NEW_URL"] = $_REQUEST["NEW_URL"];
if (isset( $_REQUEST["CONDITION_ID"] ) && $_REQUEST["CONDITION_ID"]!=='')
    $chpu["CONDITION_ID"] = $_REQUEST["CONDITION_ID"];
if (isset( $_REQUEST["SEOMETA_DATA"] ) && $_REQUEST["SEOMETA_DATA"])
    $chpu["SEOMETA_DATA"] = $_REQUEST["SEOMETA_DATA"];
if (isset( $_REQUEST["BOTTOM_TAG_OVERRIDE_TYPE"] ) && $_REQUEST["BOTTOM_TAG_OVERRIDE_TYPE"])
    $chpu["BOTTOM_TAG_OVERRIDE_TYPE"] = $_REQUEST["BOTTOM_TAG_OVERRIDE_TYPE"];
if (isset( $_REQUEST["BOTTOM_TAG_OVERRIDE_PROPERTIES"] ) && $_REQUEST["BOTTOM_TAG_OVERRIDE_PROPERTIES"])
    $chpu["BOTTOM_TAG_OVERRIDE_PROPERTIES"] = $_REQUEST["BOTTOM_TAG_OVERRIDE_PROPERTIES"];

$chpus=array('0'=>'-');
$conds = ConditionTable::getList(array('select'=>array('ID','NAME', 'SITES', 'META', 'INFOBLOCK'),'filter'=>array()));
$conditionMeta = [];
if(isset($conds)) {
    while ($c = $conds->fetch()) {
        if($chpu["CONDITION_ID"] == $c['ID'] && ($sites = unserialize($c['SITES']))) {
            $chpu['SITE_ID'] = $sites;
            $chpu['INFOBLOCK'] = $c['INFOBLOCK'];
        }

        if($c['ID'] == $chpu["CONDITION_ID"]) {
            $conditionMeta = unserialize($c['META']) ?: array();
        }

        $chpus[$c['ID']] = $c['ID'] . ' ' . $c['NAME'];
    }
}

if(is_array($chpu['SITE_ID']) && count($chpu['SITE_ID']) == 1) {
    $siteInfo = CSite::GetByID(current($chpu['SITE_ID']))->fetch();
    $protocol = ($request->isHttps() ? 'https' : 'http') . '://';
}

if(!empty($chpu['INFOBLOCK'])) {
    $res = CIBlockProperty::GetList(
        array(),
        array(
            'IBLOCK_ID' => $chpu['INFOBLOCK']
        )
    );

    while($property = $res->fetch()) {
        $arrProps['REFERENCE_ID'][] = $property['ID'];
        $arrProps['REFERENCE'][] = '['. $property['ID'] .'] '. $property['NAME'];
    }

    if(Loader::includeModule('catalog')) {
        $chpu['PRODUCT_BLOCK'] = CCatalog::GetList(
            array(),
            array(
                'IBLOCK_ID' => $chpu['INFOBLOCK']
            ),
            false,
            false,
            array('OFFERS_IBLOCK_ID')
        )->fetch();

        if($chpu['PRODUCT_BLOCK']) {
            $res = CIBlockProperty::GetList(
                array(),
                array(
                    'IBLOCK_ID' => $chpu['PRODUCT_BLOCK']['OFFERS_IBLOCK_ID']
                )
            );

            while ($property = $res->fetch()) {
                $arrProps['REFERENCE_ID'][] = $property['ID'];
                $arrProps['REFERENCE'][] = '['. $property['ID'] .'] '. $property['NAME'];
            }
        }
    }
}

$arrPropsSelected['REFERENCE_ID'] = $arrPropsSelected['REFERENCE'] = [];
if(is_array($chpu['TAG_DATA'])) {
    foreach ($chpu['TAG_DATA'] as $property) {
        $propID = array_search($property, $arrProps['REFERENCE_ID']);
        if($propID) {
            $arrPropsSelected['REFERENCE_ID'][$propID] = $property;
            $arrPropsSelected['REFERENCE'] = $arrProps['REFERENCE'];
        }
    }
}

if($conditionMeta) {
    foreach ($conditionMeta as $key => $item) {
        if($chpu['SEOMETA_DATA'][$key . '_REPLACE'] == 'Y') {
            $meta[$key] = $chpu['SEOMETA_DATA'][$key];

            $meta[$key . '_REPLACE'] = $chpu['SEOMETA_DATA'][$key . '_REPLACE'];
        } else {
            $meta[$key] = $conditionMeta[$key];
        }
    }
} else {
    $meta = $chpu['SEOMETA_DATA'];

    if($chpu['ELEMENT_FILE']) {
        $meta['ELEMENT_FILE'] = $chpu['ELEMENT_FILE'];
    }
}

if(is_array($meta) && !empty($chpu['section_id']) && !empty($chpu['PROPERTIES'])) {
    $morphyObject = SeoMetaMorphy::morphyLibInit();
    $sku = new \Bitrix\Iblock\Template\Entity\Section($chpu['section_id']);
    \CSeoMetaTagsProperty::$params = unserialize($chpu['PROPERTIES']);

    foreach ($meta as &$item) {
        if(mb_strpos($item, '{') !== false) {
            $item = \Bitrix\Iblock\Template\Engine::process($sku, SeoMetaMorphy::prepareForMorphy($item));
        }
    }
}

//***All section***
$AllSections['REFERENCE_ID'][0]=0;
$AllSections['REFERENCE'][0]=Loc::GetMessage( "SEO_META_CHECK_CATEGORY" );
$RsAllSections = SectionUrlTable::getList(array(
    'select' => array('*'),
    'filter' =>array('ACTIVE'=>'Y'),
    'order' => array('SORT' => 'ASC')
));
if(isset($RsAllSections)) {
    while ($AllSection = $RsAllSections->Fetch()) {
        $AllSections['REFERENCE_ID'][] = $AllSection['ID'];
        $AllSections['REFERENCE'][] = $AllSection['NAME'];
    }
}

$message = null;

//<editor-fold desc="Action">
if (isset( $_REQUEST['action'] ))
{
    if ($_REQUEST['action'] == "copy")
    {
        if ($ID > 0)
        {
            $condition = SeometaUrlTable::getById( $ID );
            $arFields = Array(
                    "ACTIVE" => $condition['ACTIVE'],
                    "NAME" => $condition['NAME'],
                    "CATEGORY_ID" => $condition['CATEGORY_ID'],
                    "CONDITION_ID" => $condition['CONDITION_ID'],
                    "REAL_URL" => $condition['REAL_URL'],
                    "NEW_URL" => $condition['NEW_URL'],
                    "DATE_CHANGE" => new Type\DateTime( date( 'Y-m-d H:i:s' ), 'Y-m-d H:i:s' ),
            );
            $result = SeometaUrlTable::add( $arFields );
            if ($result && $result->isSuccess())
            {
                $ID = $result->getId();
                LocalRedirect( "/bitrix/admin/sotbit.seometa_chpu_edit.php?ID=" . $ID . "lang=" . LANG);
            }
        }
    } else if($_REQUEST['action'] == 'chpu_link_add' && intval($_REQUEST['chpu_id']) != 0) {
        if(!ChpuLinksTable::checkExist($ID, $_REQUEST['chpu_id'])) {
            $mainChpuData = SeometaUrlTable::getById($_REQUEST['chpu_id']);
            $result = ChpuLinksTable::add([
                'MAIN_CHPU_ID' => $ID,
                'LINK_CHPU_ID' => intval($_REQUEST['chpu_id']),
                'SEOMETA_DATA_CHPU_LINK' => serialize([
                    'NAME_CHPU_LINK_REPLACE' => 'N'
                ])
            ]);

            if($result->isSuccess()) {
                LocalRedirect( "/bitrix/admin/sotbit.seometa_chpu_edit.php?ID=" . $ID . "lang=" . LANG);
            }
        }
    } else if($_REQUEST['action'] == 'delete_link' && !empty($_REQUEST['id'])) {
        $result = ChpuLinksTable::delete($_REQUEST['id']);
        echo 'success';
        die;
    }
}
//</editor-fold>

//<editor-fold desc="POST">
if ($REQUEST_METHOD == "POST" && ($save != "" || $apply != "") && $POST_RIGHT == "W" && check_bitrix_sessid())
{
    //arrFields main chpu
    $arFields = Array(
        "ACTIVE" => ($_POST['ACTIVE'] != "Y" ? "N" : "Y"),
        "NAME" => $_POST['NAME'],
        "CATEGORY_ID" => $_POST['CATEGORY_ID'],
        "REAL_URL" => $_POST['REAL_URL'],
        "NEW_URL" => $_POST['NEW_URL'],
        "CONDITION_ID" => $_POST['CONDITION_ID'],
        "DATE_CHANGE" => new Type\DateTime( date( 'Y-m-d H:i:s' ), 'Y-m-d H:i:s' )
    );

    if(isset($_POST['TAG_OVERRIDE_TYPE'])) {
        $arChpuTags['TAG_OVERRIDE_TYPE'] = $_POST['TAG_OVERRIDE_TYPE'];

        if($_POST['TAG_OVERRIDE_TYPE'] == 'Y' && !empty($_POST['BOTTOM_TAG_OVERRIDE_PROPERTIES'])) {
            $arChpuTags['TAG_DATA'] = serialize($_POST['BOTTOM_TAG_OVERRIDE_PROPERTIES']);
        } else if($_POST['TAG_OVERRIDE_TYPE'] == 'M' && !empty($_POST['BOTTOM_TAG_MANUAL'])) {
            $arChpuTags['TAG_DATA'] = serialize($_POST['BOTTOM_TAG_MANUAL']);
        }
    }

    if($_POST['SEOMETA_DATA']['ELEMENT_FILE'] && $_POST['SEOMETA_DATA']['ELEMENT_FILE_del'] == 'Y') {
        $deleteFileResult = CFile::Delete($_POST['SEOMETA_DATA']['ELEMENT_FILE']);
    }else if(is_array($_POST['SEOMETA_DATA']['ELEMENT_FILE'])) {
        $_POST['SEOMETA_DATA']['ELEMENT_FILE']['tmp_name'] = CTempFile::GetAbsoluteRoot() . $_POST['SEOMETA_DATA']['ELEMENT_FILE']['tmp_name'];
        $_POST['SEOMETA_DATA']['ELEMENT_FILE'] = CFile::SaveFile($_POST['SEOMETA_DATA']['ELEMENT_FILE'], 'seo_images');
    }

    if($_POST['SEOMETA_DATA']) {
        $chpuSeoData['SEOMETA_DATA'] = serialize($_POST['SEOMETA_DATA']);
        $chpuSeoData['BITRIX_URL'] = $_POST['REAL_URL'];
        $chpuSeoData['CONDITION_ID'] = $_POST['CONDITION_ID'] ?: '';
    }

    $arFields['SITE_ID'] = serialize($_POST['SITE_ID']);

    //arrFields link chpu
    if(is_array($_POST['SEOMETA_DATA_CHPU_LINK']) && !empty($_POST['SEOMETA_DATA_CHPU_LINK'])) {
        $dataChpuLink = $_POST['SEOMETA_DATA_CHPU_LINK'];
        $dataChpuLinkImageDel = $_POST['SEOMETA_DATA_CHPU_LINK_del'];

        foreach ($dataChpuLink as $key => $value) {
            $elId = mb_substr($key, mb_strrpos($key, '_') + 1);

            if(mb_strpos($key, 'IMAGE') !== false && !empty($value)) {

                if($dataChpuLinkImageDel[$key] == 'Y') {
                    CFile::Delete($value);
                    $arChpuFields[$elId]['SEOMETA_DATA_CHPU_LINK']['IMAGE'] = '';
                }else if(is_array($value)) {
                    $value['tmp_name'] = CTempFile::GetAbsoluteRoot() . $value['tmp_name'];
                    $arChpuFields[$elId]['SEOMETA_DATA_CHPU_LINK']['IMAGE'] = CFile::SaveFile($value, 'seo_images');
                } else if(intval($value) !== 0) {
                    $arChpuFields[$elId]['SEOMETA_DATA_CHPU_LINK']['IMAGE'] = $value;
                }
            } elseif (mb_strpos($key, 'NAME_CHPU_LINK_REPLACE_') !== false) {
                $arChpuFields[$elId]['SEOMETA_DATA_CHPU_LINK']['NAME_CHPU_LINK_REPLACE'] = $value;
            } elseif (mb_strpos($key, 'NAME_CHPU_LINK_') !== false) {
                $arChpuFields[$elId]['SEOMETA_DATA_CHPU_LINK']['NAME_CHPU_LINK'] = $value;
            }
        }
    }

    if ($ID > 0)
    {
        $result = SeometaUrlTable::update( $ID, $arFields );
        if (!$result->isSuccess())
        {
            $errors = $result->getErrorMessages();
            $res = false;
        }
        else {
            $res = true;
        }
    }
    else
    {
        $isExistURL = SeometaUrlTable::getByRealUrl($arFields['REAL_URL']);
        if(!$isExistURL) {
            $result = SeometaUrlTable::add( $arFields );
            if ($result && $result->isSuccess())
            {
                $ID = $result->getId();
                $res = true;
            }
            else
            {
                $errors = $result->getErrorMessages();
                $res = false;
            }
        } else {
            $errors = Loc::getMessage('SEOMETA_ERROR_LINK_IS_EXIST');
            $res = false;
        }
    }

//    if($res && !empty($arChpuTags)) {
//        $tagChpuID = ChpuTagsTable::getByChpuID($ID);
//
//        if($tagChpuID['ID']) {
//            $chpuTagResult = ChpuTagsTable::update($tagChpuID['ID'], $arChpuTags);
//        } else {
//            $arChpuTags['CHPU_ID'] = $ID;
//            $chpuTagResult = ChpuTagsTable::add($arChpuTags);
//        }
//    }

    if($res && !empty($arChpuFields)) {
        foreach ($arChpuFields as $id => $chpuField) {
            $chpuField['SEOMETA_DATA_CHPU_LINK'] = serialize($chpuField['SEOMETA_DATA_CHPU_LINK']);
            $chpuRes = ChpuLinksTable::update($id, $chpuField);
            if(!$chpuRes->isSuccess()) {
                $chpuRes = ChpuLinksTable::add($chpuField);
            }
        }
    }

    if($res && !empty($chpuSeoData)) {
        $oneChpuSeoData = ChpuSeoDataTable::getByBitrixUrl($chpuSeoData['BITRIX_URL']);
        if($oneChpuSeoData) {
            ChpuSeoDataTable::update($oneChpuSeoData['ID'], $chpuSeoData);
        } else {
            ChpuSeoDataTable::add($chpuSeoData);
        }
    }


    if ($res) {
        if ($apply != "") {
            LocalRedirect("/bitrix/admin/sotbit.seometa_chpu_edit.php?ID=" . $ID . "&mess=ok&lang=" . LANG . "&" . $tabControl->ActiveTabParam());
        } else {
            if ($CATEGORY_ID > 0) {
                LocalRedirect("/bitrix/admin/sotbit.seometa_chpu_list.php?lang=" . LANG . '&parent=' . $CATEGORY_ID);
            } else {
                LocalRedirect("/bitrix/admin/sotbit.seometa_chpu_list.php?lang=" . LANG);
            }
        }
    }
}
//</editor-fold>

$arrChpuLinks = array();
if(intval($ID) > 0) {
    $arrChpuLinks = ChpuLinksTable::getByMainChpuId($ID);
}

$APPLICATION->SetTitle( ($ID > 0 ? Loc::GetMessage( "SEO_META_EDIT_EDIT" ) . $ID.' "'.$chpu['NAME'].'"' : Loc::GetMessage( "SEO_META_EDIT_ADD" )) );
require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

if( CCSeoMeta::ReturnDemo() == 2){
    ?>
    <div class="adm-info-message-wrap adm-info-message-red">
        <div class="adm-info-message">
            <div class="adm-info-message-title"><?=Loc::getMessage("SEO_META_DEMO")?></div>
            <div class="adm-info-message-icon"></div>
        </div>
    </div>
    <?
}
if( CCSeoMeta::ReturnDemo() == 3 || CCSeoMeta::ReturnDemo() == 0)
{
    ?>
    <div class="adm-info-message-wrap adm-info-message-red">
        <div class="adm-info-message">
            <div class="adm-info-message-title"><?=Loc::getMessage("SEO_META_DEMO_END")?></div>
            <div class="adm-info-message-icon"></div>
        </div>
    </div>
    <?
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
    return '';
}

$linkElems =
    '<div class="bottom-tags-section__input-item-links">'.
        '<span class="bottom-tags-section__input-title">'.
            '<label>'. Loc::getMessage('SEO_META_EDIT_LINK_NAME') .'</label>'.
        '</span>'.
        '<div class="bottom-tags-section__text-input-wrapper">'.
            '<input class="bottom-tags-section__text-input" type="text" name="BOTTOM_TAG_MANUAL[SECTION][0][LINKS][0][NAME]" value="">'.
        '</div>'.
        '<span class="bottom-tags-section__input-title">'.
            '<label>'. Loc::getMessage('SEO_META_EDIT_LINK_URL') .'</label>'.
        '</span>'.
        '<div class="bottom-tags-section__text-input-wrapper">'.
            '<input class="bottom-tags-section__text-input" type="text" name="BOTTOM_TAG_MANUAL[SECTION][0][LINKS][0][URL]" value="">'.
        '</div>'.
        '<a class="adm-btn adm-btn-delete hide" onclick="delRow(this)"></a>'.
    '</div>';

$sectionField =
    '<div class="bottom-tags-section__input-item">'.
        '<span class="bottom-tags-section__input-title">'.
            '<label>'. Loc::getMessage('SEO_META_EDIT_SECTION_NAME') .'</label>'.
        '</span>'.
        '<div class="bottom-tags-section__text-input-wrapper">'.
            '<input type="text" class="bottom-tags-section__text-input chpu-link-url-input" name="BOTTOM_TAG_MANUAL[SECTION][0][NAME]" value="">'.
        '</div>'.
    '</div>'.
    '<hr>'.
    $linkElems .
    '<a class="adm-btn adm-btn-add" onclick="addRow(this)"></a>';

$sectionTitle =
    '<div class="bottom-tags-item__title">'.
        '<div class="bottom-tags-item__title-collapse" onclick="this.parentNode.classList.toggle(\'active\')">'.
            '<span>'. Loc::getMessage('SEO_META_EDIT_SECTION_NAME') .': </span>'.
            '<span class="bottom-tags-item__handle"></span>'.
        '</div>'.
        '<span class="bottom-tags-item__delete hide" onclick="deleteSection(this)"></span>'.
    '</div>';

$elementsWrapper =
    '<div class="bottom-tags-item__content bottom-tags-section">'.
        '<div class="bottom-tags-section__input">'.
            $sectionField.
        '</div>'.
    '</div>';

$bottomTagsWrapper =
    '<div class="bottom-tags-item">'.
        $sectionTitle.
        $elementsWrapper.
    '</div>';

$aMenu[]=array(
    "TEXT" => Loc::GetMessage( "SEO_META_EDIT_LIST" ),
    "TITLE" => Loc::GetMessage( "SEO_META_EDIT_LIST_TITLE" ),
    "LINK" => "sotbit.seometa_chpu_list.php?lang=" . LANG,
    "ICON" => "btn_list"
);
if ($ID > 0)
{
    $aMenu[] = array(
            "SEPARATOR" => "Y"
    );
    $aMenu[] = array(
            "TEXT" => Loc::GetMessage( "SEO_META_EDIT_ADD" ),
            "TITLE" => Loc::GetMessage( "SEO_META_EDIT_ADD_TITLE" ),
            "LINK" => "sotbit.seometa_chpu_edit.php?lang=" . LANG,
            "ICON" => "btn_new"
    );
    $aMenu[] = array(
            "TEXT" => Loc::GetMessage( "SEO_META_EDIT_COPY" ),
            "TITLE" => Loc::GetMessage( "SEO_META_EDIT_COPY_TITLE" ),
            "LINK" => "sotbit.seometa_chpu_edit.php?action=copy&ID=" . $ID . "lang=" . LANG . "&" . bitrix_sessid_get() . "';",
            "ICON" => "btn_new"
    );
    $aMenu[] = array(
            "TEXT" => Loc::GetMessage( "SEO_META_EDIT_DEL" ),
            "TITLE" => Loc::GetMessage( "SEO_META_EDIT_DEL_TITLE" ),
            "LINK" => "javascript:if(confirm('" . Loc::GetMessage( "SEO_META_EDIT_DEL_CONF" ) . "'))window.location='sotbit.seometa_chpu_list.php?ID=P" . $ID . "&action=delete&lang=" . LANG . "&" . bitrix_sessid_get() . "';",
            "ICON" => "btn_delete"
    );
}
$context = new CAdminContextMenu( $aMenu );
$context->Show();

if(isset($errors) && is_array($errors) && count($errors)>0)
{
    CAdminMessage::ShowMessage( array(
            "MESSAGE" => $errors[0]
    ) );
}
if ($_REQUEST["mess"] == "ok" && $ID > 0)
    CAdminMessage::ShowMessage( array(
            "MESSAGE" => Loc::GetMessage( "SEO_META_EDIT_SAVED" ),
            "TYPE" => "OK"
    ) );

$tabControl->Begin( array(
        "FORM_ACTION" => $APPLICATION->GetCurPage()
) );



//<editor-fold desc="URL TAB">
$tabControl->BeginNextFormTab();

$tabControl->AddCheckBoxField( "ACTIVE", Loc::GetMessage( "SEO_META_EDIT_ENABLE_URL" ), false, "Y", ($chpu['ACTIVE'] == "Y") );
$tabControl->AddEditField( "NAME", Loc::GetMessage( "SEO_META_EDIT_NAME" ), true, array(
        "size" => 50,
        "maxlength" => 255
), htmlspecialcharsbx( $chpu['NAME'] ) );
$tabControl->AddViewField( 'DATE_CHANGE_TEXT', Loc::GetMessage( "SEO_META_EDIT_DATE_CHANGE" ), $chpu['DATE_CHANGE'], false );
$tabControl->BeginCustomField( "CATEGORY_ID", Loc::GetMessage( 'SEO_META_EDIT_CATEGORY_ID' ), false );
?>
<tr id="CATEGORY_ID">
    <td width="40%"><? echo $tabControl->GetCustomLabelHTML(); ?></td>
    <td width="60%">
<?echo SelectBoxFromArray('CATEGORY_ID', $AllSections, $chpu['CATEGORY_ID'],'',false,'','style="min-width:350px"');?>
</td>
</tr><?
$tabControl->EndCustomField( "CATEGORY_ID" );

$tabControl->BeginCustomField('REAL_URL', Loc::GetMessage( 'SEO_META_EDIT_REAL_URL' ));?>
<tr class="adm-detail-valign-top">
    <td width="40%"><? echo  Loc::GetMessage( "SEO_META_EDIT_REAL_URL" ); ?></td>
    <td width="60%">
        <?
        $chpu['REAL_URL'] = $chpu['REAL_URL'] ?: '';
        ?>

        <textarea style="width: 69%" name="REAL_URL"><?php echo $chpu['REAL_URL'];?></textarea>

        <?if(count($chpu['SITE_ID']) == 1 && $chpu['REAL_URL'] && $siteInfo['SERVER_NAME']):?>
            <a style="vertical-align: top !important;" class="adm-btn" href="<?php echo $protocol . $siteInfo['SERVER_NAME'] . $chpu['REAL_URL'];?>"><?php echo Loc::getMessage('SEO_META_EDIT_CHECK_URL');?></a>
        <?endif;?>
    </td>
<tr>
<?php $tabControl->EndCustomField('REAL_URL');
$tabControl->BeginCustomField('NEW_URL', Loc::GetMessage( 'SEO_META_EDIT_NEW_URL'));?>
<tr class="adm-detail-valign-top">
    <td width="40%"><? echo  Loc::GetMessage( "SEO_META_EDIT_NEW_URL" ); ?></td>
    <td width="60%">
        <?
            $chpu['NEW_URL'] = $chpu['NEW_URL'] ?: '';
        ?>

        <textarea style="width: 69%" name="NEW_URL"><?php echo $chpu['NEW_URL'];?></textarea>

        <?if(count($chpu['SITE_ID']) == 1 && $chpu['NEW_URL'] && $siteInfo['SERVER_NAME']):?>
            <a style="vertical-align: top !important;" class="adm-btn adm-detail-valign-top" href="<?php echo $protocol . $siteInfo['SERVER_NAME'] . $chpu['NEW_URL'];?>"><?php echo Loc::getMessage('SEO_META_EDIT_CHECK_URL');?></a>
        <?endif;?>
    </td>
<tr>
<?php $tabControl->EndCustomField('NEW_URL');
$tabControl->AddDropDownField( "CONDITION_ID", Loc::GetMessage( 'SEO_META_EDIT_CONDITION_ID' ), false, $chpus, $chpu['CONDITION_ID'] );

$tabControl->BeginCustomField( "SITE_ID_AREA", loc::getMessage('SEO_META_EDIT_SITES'), false );
?>
    <tr class="adm-detail-valign-middle">
    <td width="40%"><?= $tabControl->GetCustomLabelHTML(); ?></td>
    <td width="60%">
    <?
        $by = "sort";
        $order = "asc";
        $l = CLang::GetList($by, $order);

        $s = '<div class="adm-list">';
        while($l_arr = $l->Fetch())
        {
            $s .=
                '<div class="adm-list-item">'.
                '<div class="adm-list-control">'.
                '<input type="checkbox" name="SITE_ID[]" value="'.htmlspecialcharsex($l_arr["LID"]).'" id="'.htmlspecialcharsex($l_arr["LID"]).'" class="typecheckbox"'.($chpu['SITE_ID'] && $chpu['CONDITION_ID'] != 0 ? 'disabled ' : '') . ($chpu['SITE_ID'] && in_array($l_arr["LID"], $chpu['SITE_ID'])?' checked':'').'></div>'.
                '<div class="adm-list-label"><label for="'.htmlspecialcharsex($l_arr["LID"]).'">['.htmlspecialcharsex($l_arr["LID"]).']&nbsp;'.htmlspecialcharsex($l_arr["NAME"]).'</label></div>';
        }

        if($chpu['SITE_ID'] && $chpu['CONDITION_ID'] != 0) {
            $s .= '<span>'.Loc::getMessage('SEO_META_EDIT_SITES_NOTE',['#CONDITION_URL#' => 'sotbit.seometa_edit.php?ID='. $chpu['CONDITION_ID'] .'&lang=ru']).'</span>';
        }

        $s .= '</div></div>';

        echo $s;
    ?>
    </td>
    </tr><?
$tabControl->EndCustomField( "SITE_ID_AREA" );

$tabControl->BeginCustomField( "HID", '', false );
?>
<?echo bitrix_sessid_post();?>
<input type="hidden" name="lang" value="<?=LANG?>">
<?if($ID>0 && !$bCopy):?>
<input type="hidden" name="ID" value="<?=$ID?>">
<?endif;?>
<?
$tabControl->EndCustomField( "HID" );
//</editor-fold>

//<editor-fold desc="SEO TAB">
$tabControl->BeginNextFormTab();

$tabControl->BeginCustomField("ELEMENT_TITLE", Loc::GetMessage( "SEO_META_EDIT_META_TITLE" ),false);?>
    <tr class="adm-detail-valign-top">
        <td width="40%"><?= $tabControl->GetCustomLabelHTML(); ?></td>
        <td width="50%">
            <textarea style="width: 90%"
                      class="count_symbol"
                      name="SEOMETA_DATA[ELEMENT_TITLE]"
                      <?=$meta['ELEMENT_TITLE_REPLACE'] !== 'Y' ? 'disabled' : '';?>
            ><?=$meta['ELEMENT_TITLE'];?></textarea>
            <div class="count_symbol_print">
                <?= Loc::getMessage('SEO_META_SYMBOL_COUNT_FROM').MIN_SEO_TITLE.' - '.MAX_SEO_TITLE;?>
                <span class="meta_title"></span>
                <div class="progressbar" data-min="<?= MIN_SEO_TITLE;?>" data-max="<?= MAX_SEO_TITLE;?>"></div>
            </div>
            <div>
                <input  type="checkbox"
                        id="ELEMENT_TITLE_REPLACE"
                        name="SEOMETA_DATA[ELEMENT_TITLE_REPLACE]"
                        value="Y"
                        onclick="setDisabled('textarea[name*=ELEMENT_TITLE]')"
                    <?= $meta['ELEMENT_TITLE_REPLACE'] == 'Y' ? 'checked' : ''; ?>
                >
                <label
                        for="ELEMENT_TITLE_REPLACE"><?=Loc::GetMessage( "SEO_META_EDIT_REPLACE" )?></label>
            </div>
        </td>
        <td width="10%" align="left">
            <?=$PropMenu?>
        </td>
    </tr>
<?$tabControl->EndCustomField("ELEMENT_TITLE");

$tabControl->BeginCustomField("ELEMENT_KEYWORDS", Loc::GetMessage( "SEO_META_EDIT_META_KEYWORDS" ),false);?>
    <tr class="adm-detail-valign-top">
        <td width="40%"><?= $tabControl->GetCustomLabelHTML(); ?></td>
        <td width="50%">
            <textarea style="width: 90%"
                      class="count_symbol"
                      name="SEOMETA_DATA[ELEMENT_KEYWORDS]"
                      <?=$meta['ELEMENT_KEYWORDS_REPLACE'] !== 'Y' ? 'disabled' : '';?>
            ><?=$meta['ELEMENT_KEYWORDS'];?></textarea>
            <div class="count_symbol_print">
                <?= Loc::getMessage('SEO_META_SYMBOL_COUNT_FROM').MIN_SEO_TITLE.' - '.MAX_SEO_TITLE;?>
                <span class="meta_keywords"></span>
                <div class="progressbar" data-min="<?= MIN_SEO_TITLE;?>" data-max="<?= MAX_SEO_TITLE;?>"></div>
            </div>
            <div>
                <input  type="checkbox"
                        id="ELEMENT_KEYWORDS_REPLACE"
                        name="SEOMETA_DATA[ELEMENT_KEYWORDS_REPLACE]"
                        value="Y"
                        onclick="setDisabled('textarea[name*=ELEMENT_KEYWORDS]')"
                        <?=$meta['ELEMENT_KEYWORDS_REPLACE'] == 'Y' ? 'checked' : '';?>
                >
                <label
                        for="ELEMENT_KEYWORDS_REPLACE"><?=Loc::GetMessage( "SEO_META_EDIT_REPLACE" )?></label>
            </div>
        </td>
        <td width="10%" align="left">
            <?=$PropMenu?>
        </td>
    </tr>
<?$tabControl->EndCustomField("ELEMENT_KEYWORDS");

$tabControl->BeginCustomField("ELEMENT_DESCRIPTION", Loc::GetMessage( "SEO_META_EDIT_META_DESCRIPTION" ),false);?>
    <tr class="adm-detail-valign-top">
        <td width="40%"><?= $tabControl->GetCustomLabelHTML(); ?></td>
        <td width="50%">
            <textarea style="width: 90%"
                      class="count_symbol"
                      name="SEOMETA_DATA[ELEMENT_DESCRIPTION]"
                      <?=$meta['ELEMENT_DESCRIPTION_REPLACE'] !== 'Y' ? 'disabled' : '';?>
            ><?=$meta['ELEMENT_DESCRIPTION'];?></textarea>
            <div class="count_symbol_print">
                <?= Loc::getMessage('SEO_META_SYMBOL_COUNT_FROM').MIN_SEO_TITLE.' - '.MAX_SEO_TITLE;?>
                <span class="meta_description"></span>
                <div class="progressbar" data-min="<?= MIN_SEO_TITLE;?>" data-max="<?= MAX_SEO_TITLE;?>"></div>
            </div>
            <div>
                <input  type="checkbox"
                        id="ELEMENT_DESCRIPTION_REPLACE"
                        name="SEOMETA_DATA[ELEMENT_DESCRIPTION_REPLACE]"
                        value="Y"
                        onclick="setDisabled('textarea[name*=ELEMENT_DESCRIPTION]')"
                        <?=$meta['ELEMENT_DESCRIPTION_REPLACE'] == 'Y' ? 'checked' : '';?>
                >
                <label
                        for="ELEMENT_DESCRIPTION_REPLACE"><?=Loc::GetMessage( "SEO_META_EDIT_REPLACE" )?></label>
            </div>
        </td>
        <td width="10%" align="left">
            <?=$PropMenu?>
        </td>
    </tr>
<?$tabControl->EndCustomField("ELEMENT_DESCRIPTION");

$tabControl->BeginCustomField("ELEMENT_PAGE_TITLE", Loc::GetMessage( "SEO_META_EDIT_META_PAGE_TITLE" ),false);?>
    <tr class="adm-detail-valign-top">
        <td width="40%"><?= $tabControl->GetCustomLabelHTML(); ?></td>
        <td width="50%">
            <textarea style="width: 90%"
                      class="count_symbol"
                      name="SEOMETA_DATA[ELEMENT_PAGE_TITLE]"
                      <?=$meta['ELEMENT_PAGE_TITLE_REPLACE'] !== 'Y' ? 'disabled' : '';?>
            ><?=$meta['ELEMENT_PAGE_TITLE'];?></textarea>
            <div>
                <span class="meta_section_title"></span>
                <input  type="checkbox"
                        id="ELEMENT_PAGE_TITLE_REPLACE"
                        name="SEOMETA_DATA[ELEMENT_PAGE_TITLE_REPLACE]"
                        value="Y"
                        onclick="setDisabled('textarea[name*=ELEMENT_PAGE_TITLE]')"
                        <?=$meta['ELEMENT_PAGE_TITLE_REPLACE'] == 'Y' ? 'checked' : '';?>
                >
                <label
                        for="ELEMENT_PAGE_TITLE_REPLACE"><?=Loc::GetMessage( "SEO_META_EDIT_REPLACE" )?></label>
            </div>
        </td>
        <td width="10%" align="left">
            <?=$PropMenu?>
        </td>
    </tr>
<?$tabControl->EndCustomField("ELEMENT_PAGE_TITLE");

$tabControl->BeginCustomField("ELEMENT_BREADCRUMB_TITLE", Loc::GetMessage( "SEO_META_EDIT_META_BREADCRUMB_TITLE" ),false);?>
    <tr class="adm-detail-valign-top">
        <td width="40%"><?= $tabControl->GetCustomLabelHTML(); ?></td>
        <td width="50%">
            <textarea style="width: 90%"
                      class="count_symbol"
                      name="SEOMETA_DATA[ELEMENT_BREADCRUMB_TITLE]"
                      <?=$meta['ELEMENT_BREADCRUMB_TITLE_REPLACE'] !== 'Y' ? 'disabled' : '';?>
            ><?=$meta['ELEMENT_BREADCRUMB_TITLE'];?></textarea>
            <div>
                <span class="meta_section_title"></span>
                <input  type="checkbox"
                        id="ELEMENT_BREADCRUMB_TITLE_REPLACE"
                        name="SEOMETA_DATA[ELEMENT_BREADCRUMB_TITLE_REPLACE]"
                        value="Y"
                        onclick="setDisabled('textarea[name*=ELEMENT_BREADCRUMB_TITLE]')"
                        <?=$meta['ELEMENT_BREADCRUMB_TITLE_REPLACE'] == 'Y' ? 'checked' : '';?>
                >
                <label
                        for="ELEMENT_BREADCRUMB_TITLE_REPLACE"><?=Loc::GetMessage( "SEO_META_EDIT_REPLACE" )?></label>
            </div>
        </td>
        <td width="10%" align="left">
            <?=$PropMenu?>
        </td>
    </tr>
<?$tabControl->EndCustomField("ELEMENT_BREADCRUMB_TITLE");

$tabControl->BeginCustomField("ELEMENT_TOP_DESC", Loc::GetMessage( "SEO_META_EDIT_TOP_DESC" ),false);?>
    <tr class="adm-detail-valign-top">
        <td width="40%"><?= $tabControl->GetCustomLabelHTML(); ?></td>
        <td width="50%">
            <textarea style="width: 90%"
                      class="count_symbol"
                      name="SEOMETA_DATA[ELEMENT_TOP_DESC]"
                      <?=$meta['ELEMENT_TOP_DESC_REPLACE'] !== 'Y' ? 'disabled' : '';?>
            ><?=$meta['ELEMENT_TOP_DESC'];?></textarea>
            <div>
                <span class="meta_section_title"></span>
                <input  type="checkbox"
                        id="ELEMENT_TOP_DESC_REPLACE"
                        name="SEOMETA_DATA[ELEMENT_TOP_DESC_REPLACE]"
                        value="Y"
                        onclick="setDisabled('textarea[name*=ELEMENT_TOP_DESC]')"
                        <?=$meta['ELEMENT_TOP_DESC_REPLACE'] == 'Y' ? 'checked' : '';?>
                >
                <label
                        for="ELEMENT_TOP_DESC_REPLACE"><?=Loc::GetMessage( "SEO_META_EDIT_REPLACE" )?></label>
            </div>
        </td>
        <td width="10%" align="left">
            <?=$PropMenu?>
        </td>
    </tr>
<?$tabControl->EndCustomField("ELEMENT_TOP_DESC");

$tabControl->BeginCustomField("ELEMENT_BOTTOM_DESC", Loc::GetMessage( "SEO_META_EDIT_BOTTOM_DESC" ),false);?>
    <tr class="adm-detail-valign-top">
        <td width="40%"><?= $tabControl->GetCustomLabelHTML(); ?></td>
        <td width="50%">
            <textarea style="width: 90%"
                      class="count_symbol"
                      name="SEOMETA_DATA[ELEMENT_BOTTOM_DESC]"
                      <?=$meta['ELEMENT_BOTTOM_DESC_REPLACE'] !== 'Y' ? 'disabled' : '';?>
            ><?=$meta['ELEMENT_BOTTOM_DESC'];?></textarea>
            <div>
                <span class="meta_section_title"></span>
                <input  type="checkbox"
                        id="ELEMENT_BOTTOM_DESC_REPLACE"
                        name="SEOMETA_DATA[ELEMENT_BOTTOM_DESC_REPLACE]"
                        value="Y"
                        onclick="setDisabled('textarea[name*=ELEMENT_BOTTOM_DESC]')"
                        <?=$meta['ELEMENT_BOTTOM_DESC_REPLACE'] == 'Y' ? 'checked' : '';?>
                >
                <label
                        for="ELEMENT_BOTTOM_DESC_REPLACE"><?=Loc::GetMessage( "SEO_META_EDIT_REPLACE" )?></label>
            </div>
        </td>
        <td width="10%" align="left">
            <?=$PropMenu?>
        </td>
    </tr>
<?$tabControl->EndCustomField("ELEMENT_BOTTOM_DESC");

$tabControl->BeginCustomField("ELEMENT_ADD_DESC", Loc::GetMessage( "SEO_META_EDIT_ADD_DESC" ),false);?>
    <tr class="adm-detail-valign-top">
        <td width="40%"><?= $tabControl->GetCustomLabelHTML(); ?></td>
        <td width="50%">
            <textarea style="width: 90%"
                      class="count_symbol"
                      name="SEOMETA_DATA[ELEMENT_ADD_DESC]"
                      <?=$meta['ELEMENT_ADD_DESC_REPLACE'] !== 'Y' ? 'disabled' : '';?>
            ><?=$meta['ELEMENT_ADD_DESC'];?></textarea>
            <div>
                <span class="meta_section_title"></span>
                <input  type="checkbox"
                        id="ELEMENT_ADD_DESC_REPLACE"
                        name="SEOMETA_DATA[ELEMENT_ADD_DESC_REPLACE]"
                        value="Y"
                        onclick="setDisabled('textarea[name*=ELEMENT_ADD_DESC]')"
                        <?=$meta['ELEMENT_ADD_DESC_REPLACE'] == 'Y' ? 'checked' : '';?>
                >
                <label
                        for="ELEMENT_ADD_DESC_REPLACE"><?=Loc::GetMessage( "SEO_META_EDIT_REPLACE" )?></label>
            </div>
        </td>
        <td width="10%" align="left">
            <?=$PropMenu?>
        </td>
    </tr>
<?$tabControl->EndCustomField("ELEMENT_ADD_DESC");

$tabControl->BeginCustomField("ELEMENT_FILE", Loc::GetMessage('SEO_META_EDIT_FILE'),false);
?>
    <tr class="adm-detail-file-row">
        <td><?echo $tabControl->GetCustomLabelHTML()?></td>
        <td><?echo \Bitrix\Main\UI\FileInput::createInstance(array(
                "name" => "SEOMETA_DATA[ELEMENT_FILE]",
                "description" => true,
                "upload" => ($meta['ELEMENT_FILE_REPLACE'] == 'Y' ? true : false),
                "allowUpload" => "I",
                "medialib" => true,
                "fileDialog" => true,
                "cloud" => true,
                "delete" => true,
                "maxCount" => 1
            ))->show(($_REQUEST["ELEMENT_FILE"] ?: $meta['ELEMENT_FILE']), $_REQUEST["ELEMENT_FILE"]);?>
        </td>
    </tr>
    <tr class="adm-detail-file-row">
        <td></td>
        <td>
            <input
                type="checkbox"
                id="ELEMENT_FILE_REPLACE"
                name="SEOMETA_DATA[ELEMENT_FILE_REPLACE]"
                value="Y"
                <?=$meta['ELEMENT_FILE_REPLACE'] == 'Y' ? 'checked' : '';?>
            >
            <label
                for="ELEMENT_FILE_REPLACE"><?= Loc::GetMessage("SEO_META_EDIT_REPLACE") ?></label>
        </td>
    </tr>
<?
$tabControl->EndCustomField("ELEMENT_FILE");
//</editor-fold>


//<editor-fold desc="GRAPH MENU TAB">
$tabControl->BeginNextFormTab();

//$urlParams = '?lang='. LANGUAGE_ID .'&tabControl_active_tab='. $tabControl->tabs[$tabControl->tabIndex]['DIV'];
$urlParams = '?lang='. LANGUAGE_ID .'&tabControl_active_tab='. $tabControl->ActiveTabParam();
if($chpu['SITE_ID']) {
    $_SESSION['SEO_META']['CHPU_LIST']['SITE_ID'] = $chpu['SITE_ID'];
}

$tabControl->BeginCustomField("CHPU_LIST", '',false);
?>
    <tr id="tr_LISTCHPU">
        <td colspan="2">
            <a class="adm-btn adm-btn-add" onclick="jsUtils.OpenWindow('/bitrix/admin/templates/chpu_list.php<?=$urlParams?>', 900, 700);"><?=Loc::getMessage('SEO_META_EDIT_ADD_LINK')?></a>
        </td>
    </tr>
<?php
$tabControl->EndCustomField('CHPU_LIST');

if(!empty($arrChpuLinks)) {
    $arrChpu = array();
    foreach ($arrChpuLinks as $chpuLink) {
        $arrChpu[$chpuLink['ID']] = $chpuLink;
        $arrMainChpuId[] = $chpuLink['LINK_CHPU_ID'];
    }

    $mainChpuRes = SeometaUrlTable::getList([
        'select' => [
            'ID', 'NEW_URL', 'NAME'
        ],
        'filter' => [
            'ID' => $arrMainChpuId
        ]
    ]);

    while($res = $mainChpuRes->fetch()) {
        $arrMainChpu[$res['ID']] = $res;
    }

    if(!empty($arrChpu)) {
        ?>
        <style>
            .graph-item {
                margin-top: 15px;
            }

            .graph-item__title {
                position: relative;
                display: flex;
                justify-content: space-between;
                flex-wrap: nowrap;
                font-weight: bold;
                border: 1px solid #d7e2e3;
                background-color: #d7e2e3;
                cursor: pointer;
            }

            .graph-item__title.active + .graph-item__content{
                height: auto;
                padding: 10px;
                border: 1px solid #d7e2e3;
                overflow: hidden;
            }

            .graph-item__title.active .graph-item__handle {
                transform: rotate(180deg);
            }

            .graph-item__title-collapse {
                display: flex;
                justify-content: space-between;
                flex-grow: 1;
                padding: 10px 0 10px 10px;
            }

            .graph-item__content {
                height: 0;
                padding: 0;
                border: 0;
                overflow: hidden;
            }

            .graph-item__handle {
                position: relative;
                display: inline-block;
                width: 15px;
                height: 15px;
                transition: all 0.2s ease;
            }

            .graph-item__delete {
                position: relative;
                flex-shrink: 0;
                display: block;
                width: 30px;
            }

            .graph-item__delete::before,
            .graph-item__delete::after {
                content: '';
                position: absolute;
                top: calc(50% - 1px);
                left: calc(50% - 7px);
                width: 15px;
                height: 1px;
                background-color: #000000;
            }

            .graph-item__delete::before {
                transform: rotate(-45deg);
            }

            .graph-item__delete::after {
                transform: rotate(45deg);
            }

            .graph-item__handle::before {
                content: '';
                position: absolute;
                top: 5px;
                left: 2px;
                display: block;
                width: 10px;
                height: 10px;
                border-left: 1px solid #000000;
                border-bottom: 1px solid #000000;
                transform: rotate(135deg);
            }

            .graph-menu {
                display: flex;
            }

            .graph-menu__inputs {
                width: 50%;
                margin-right: 30px;
            }

            .graph-menu__inputs-item {
                display: flex;
            }

            .graph-menu__inputs-item:not(:last-child) {
                margin-bottom: 20px;
            }

            .graph-menu__input-title {
                flex-shrink: 0;
                width: 120px;
                line-height: 27px;
            }

            .graph-menu__change-name {
                display: flex;
                flex-wrap: nowrap;
            }

            .graph-menu__change-name-checkbox,
            .graph-menu__change-name-label {
                line-height: 17px;
            }

            .graph-menu__change-name {
                margin-top: 5px;
            }

            .graph-menu__change-name-label {
                margin-left: 5px;
            }

            .adm-fileinput-area.adm-fileinput-drag-area {
                width: 200px;
                height: 200px;
                min-height: 200px;
            }

            .graph-menu__text-input-wrapper,
            .graph-menu__text-input {
                width: 100%;
            }
        </style>
        <?foreach ($arrChpu as $id => $item) {
            $tabControl->BeginCustomField('CHPU_LINK_' . $id, '', false);
            ?>
            <tr>
                <td>
                    <div class="graph-item">
                        <div class="graph-item__title">
                            <div class="graph-item__title-collapse" onclick="this.parentNode.classList.toggle('active')">
                                <span><?=
                                    Loc::getMessage('SEO_META_EDIT_NAME_WITHOUT_DOTS') .': '.
                                    (
                                        $item['SEOMETA_DATA_CHPU_LINK']['NAME_CHPU_LINK_REPLACE'] == 'Y' ?
                                        $item['SEOMETA_DATA_CHPU_LINK']['NAME_CHPU_LINK'] :
                                        $arrMainChpu[$item['LINK_CHPU_ID']]['NAME']
                                    ) .
                                   ' | ' . Loc::getMessage('SEO_META_EDIT_TAB_IMAGE_REPLACED') .
                                    (
                                        $item['SEOMETA_DATA_CHPU_LINK']['IMAGE'] ?
                                        Loc::getMessage('SEO_META_EDIT_TAB_YES') :
                                        Loc::getMessage('SEO_META_EDIT_TAB_NO')
                                    )
                                    ?></span>
                                <span class="graph-item__handle"></span>
                            </div>
                            <span class="graph-item__delete" onclick="deleteLink(<?=$id?>, this)"></span>
                        </div>
                        <div class="graph-item__content graph-menu">
                            <div class="graph-menu__inputs">
                                <div class="graph-menu__inputs-item">
                                    <span class="graph-menu__input-title">
                                        <label for="chpu_url_<?= $id ?>"><?= Loc::getMessage('SEO_META_EDIT_TAB_URL') ?></label>
                                    </span>
                                    <div class="graph-menu__text-input-wrapper">
                                        <input type="text"
                                               class="graph-menu__text-input chpu-link-url-input"
                                               id="chpu_url_<?= $id ?>"
                                               value="<?= $arrMainChpu[$item['LINK_CHPU_ID']]['NEW_URL'] ?>"
                                               disabled
                                        >
                                    </div>
                                </div>
                                <div class="graph-menu__inputs-item">
                            <span class="graph-menu__input-title">
                                <label for="chpu_name_<?= $id ?>">
                                    <?= Loc::getMessage('SEO_META_EDIT_NAME_WITHOUT_DOTS') ?>
                                </label>
                            </span>
                                    <div class="graph-menu__text-input-wrapper">
                                        <input class="graph-menu__text-input chpu-link-name-input"
                                               type="text"
                                               id="name_chpu_link_<?= $id ?>"
                                               name="SEOMETA_DATA_CHPU_LINK[NAME_CHPU_LINK_<?= $id ?>]"
                                            <?= $item['SEOMETA_DATA_CHPU_LINK']['NAME_CHPU_LINK_REPLACE'] != 'Y' ? 'disabled' : ''; ?>
                                               value="<?= $item['SEOMETA_DATA_CHPU_LINK']['NAME_CHPU_LINK_REPLACE'] == 'Y' ? $item['SEOMETA_DATA_CHPU_LINK']['NAME_CHPU_LINK'] : $arrMainChpu[$item['LINK_CHPU_ID']]['NAME'] ?>"
                                        >

                                        <div class="graph-menu__change-name">
                                            <input class="graph-menu__change-name-checkbox"
                                                   type="hidden"
                                                   name="SEOMETA_DATA_CHPU_LINK[NAME_CHPU_LINK_REPLACE_<?= $id ?>]"
                                                   value="N"
                                            >
                                            <input class="graph-menu__change-name-checkbox"
                                                   type="checkbox"
                                                   id="name_chpu_link_replace_<?= $id ?>"
                                                   name="SEOMETA_DATA_CHPU_LINK[NAME_CHPU_LINK_REPLACE_<?= $id ?>]"
                                                   value="Y"
                                                   onclick="setDisabled('input[name*=NAME_CHPU_LINK_<?= $id ?>]')"
                                                <?= $item['SEOMETA_DATA_CHPU_LINK']['NAME_CHPU_LINK_REPLACE'] == 'Y' ? 'checked' : ''; ?>
                                            >
                                            <label class="graph-menu__change-name-label"
                                                   for="name_chpu_link_replace_<?= $id ?>">
                                                <?= Loc::GetMessage("SEO_META_EDIT_REPLACE") ?>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="graph-menu__drag-drop">
                                <?=
                                \Bitrix\Main\UI\FileInput::createInstance(array(
                                    "name" => "SEOMETA_DATA_CHPU_LINK[IMAGE_" . $id . "]",
                                    "description" => true,
                                    "upload" => true,
                                    "allowUpload" => "I",
                                    "medialib" => true,
                                    "fileDialog" => true,
                                    "cloud" => true,
                                    "delete" => true,
                                    "maxCount" => 1
                                ))->show($item['SEOMETA_DATA_CHPU_LINK']['IMAGE']);
                                ?>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <?php
            $tabControl->EndCustomField('CHPU_LINK_' . $id);
        }
    }
}

//</editor-fold>

//<editor-fold desc="Bottom tags">

?>
<style>
    .bottom-tags-item {
        margin-top: 15px;
        margin-bottom: 15px;
    }

    .bottom-tags-item__title {
        position: relative;
        display: flex;
        justify-content: space-between;
        flex-wrap: nowrap;
        font-weight: bold;
        border: 1px solid #d7e2e3;
        background-color: #d7e2e3;
        cursor: pointer;
    }

    .bottom-tags-item__title.active + .bottom-tags-item__content{
        height: auto;
        padding: 10px;
        border: 1px solid #d7e2e3;
        overflow: hidden;
    }

    .bottom-tags-item__title.active .bottom-tags-item__handle {
        transform: rotate(180deg);
    }

    .bottom-tags-item__title-collapse {
        display: flex;
        justify-content: space-between;
        flex-grow: 1;
        padding: 10px 0 10px 10px;
    }

    .bottom-tags-item__content {
        height: 0;
        padding: 0;
        border: 0;
        overflow: hidden;
    }

    .bottom-tags-item__handle {
        position: relative;
        display: inline-block;
        width: 15px;
        height: 15px;
        transition: all 0.2s ease;
    }

    .bottom-tags-item__delete {
        position: relative;
        flex-shrink: 0;
        display: block;
        width: 30px;
    }

    .bottom-tags-item__delete::before,
    .bottom-tags-item__delete::after {
        content: '';
        position: absolute;
        top: calc(50% - 1px);
        left: calc(50% - 7px);
        width: 15px;
        height: 1px;
        background-color: #000000;
    }

    .bottom-tags-item__delete::before {
        transform: rotate(-45deg);
    }

    .bottom-tags-item__delete::after {
        transform: rotate(45deg);
    }

    .bottom-tags-item__handle::before {
        content: '';
        position: absolute;
        top: 5px;
        left: 2px;
        display: block;
        width: 10px;
        height: 10px;
        border-left: 1px solid #000000;
        border-bottom: 1px solid #000000;
        transform: rotate(135deg);
    }

    .bottom-tags-section__input {
        width: 100%;
        margin-right: 12px;
    }

    .bottom-tags-section__input hr {
        margin: 15px 0px 15px 10px;
    }

    .bottom-tags-section,
    .bottom-tags-section__input-item,
    .bottom-tags-section__input-item-links {
        display: flex;
    }

    .bottom-tags-section__input-title {
        flex-shrink: 0;
        padding-right: 10px;
        line-height: 27px;
    }

    .bottom-tags-section__text-input-wrapper,
    .bottom-tags-section__text-input {
        width: 100%;
    }

    .bottom-tags-section__input-item-links .bottom-tags-section__text-input-wrapper .bottom-tags-section__text-input{
        width: 90%;
    }

    .bottom-tags-section__input-item-links .adm-btn.adm-btn-delete.hide,
    .bottom-tags-item__title .bottom-tags-item__delete.hide{
        display: none;
    }

    .bottom-tags-section__input-item-links {
        margin-bottom: 10px;
    }

    .bottom-tags-section__input-item-links .adm-btn.adm-btn-delete {
        padding-top: 4px;
        padding-bottom: 4px;
    }

    #property-search {
        min-width: 339px;
        margin-bottom: 10px;
    }

    .avaliable-props,
    .selected-props {
        display: block;
    }
</style>
<?php
//$tabControl->BeginNextFormTab();
//
//$tabControl->AddDropDownField( "TAG_OVERRIDE_TYPE", Loc::GetMessage( 'SEO_META_EDIT_BOTTOM_TAG_OVERRIDE_TYPE' ), false, $overrideProps, $chpu['TAG_OVERRIDE_TYPE'] );
//
//if($chpu['TAG_OVERRIDE_TYPE'] == 'Y') {
//    $tabControl->BeginCustomField( "PROPERTY_LIST_AVAL", loc::getMessage( 'SEO_META_EDIT_PROPERTY_LIST' ), false );
//    ?>
<!--    <td>-->
<!--        <label class="avaliable-props">-->
<!--        --><?// echo $tabControl->GetCustomLabelHTML(); ?>
<!--        </label>-->
<!--        <input type="text"-->
<!--               id="property-search"-->
<!--               onkeyup="searchOption(this)"-->
<!--        >-->
<!--        --><?// echo SelectBoxMFromArray('PROPERTY_LIST_AVALIABLE[]', $arrProps, array(),'',false,'','onchange="PropSync();" style="min-width: 350px; min-height: 500px;"');?>
<!--    </td>-->
<!--    --><?//
//    $tabControl->EndCustomField( "PROPERTY_LIST_AVAL" );
//
//    $tabControl->BeginCustomField( "PROPERTY_ADD", '>', false );
//    ?>
<!--    <td align="center">-->
<!--        <input type="button" name="tabs_copy" id="tabs_copy" value="&nbsp; > &nbsp;" disabled="" onclick="PropOnAdd(this.id, {'tabPrefix':'cedit'});">-->
<!--    </td>-->
<!--    --><?//
//    $tabControl->EndCustomField( "PROPERTY_ADD" );
//
//    $tabControl->BeginCustomField( "PROPERTY_LIST_SEL", loc::getMessage( 'SEO_META_EDIT_PROPERTY_LIST_SEL' ), false );
//    ?>
<!--    <td>-->
<!--        <label class="selected-props">-->
<!--        --><?// echo $tabControl->GetCustomLabelHTML(); ?>
<!--        </label>-->
<!--        --><?// echo SelectBoxMFromArray('BOTTOM_TAG_OVERRIDE_PROPERTIES[]', $arrPropsSelected, array(),'',false,'','onchange="PropSync();" style="min-width: 350px; min-height: 500px;"');?>
<!--    </td>-->
<!--    --><?//
//    $tabControl->EndCustomField( "PROPERTY_LIST_SEL" );
//
//    $tabControl->BeginCustomField( "PROPERTY_DEL", '>', false );
//    ?>
<!--    <td align="center">-->
<!--        <input type="button" name="tabs_delete" id="tabs_delete" value="--><?//=Loc::getMessage('SEO_META_EDIT_PROPERTY_LIST_DELETE')?><!--"disabled="" onclick="PropOnDel(this.id, {'tabPrefix':'cedit'});">-->
<!--    </td>-->
<!--    --><?//
//    $tabControl->EndCustomField( "PROPERTY_DEL" );
//
//    //<editor-fold desc="Script for control override tables">
//    ?>
<!--    <script>-->
<!--        function PropOnAdd(id, params)-->
<!--        {-->
<!--            var tabPrefix = 'cedit';-->
<!--            if (!!params && typeof params === 'object')-->
<!--            {-->
<!--                if (!!params.tabPrefix)-->
<!--                    tabPrefix = params.tabPrefix;-->
<!--            }-->
<!--            var frm=document.form_settings;-->
<!--            if(id == 'tabs_copy')-->
<!--            {-->
<!--                var oSelectFrom = document.getElementById('PROPERTY_LIST_AVALIABLE[]');-->
<!--                var oSelectTo = document.getElementById('BOTTOM_TAG_OVERRIDE_PROPERTIES[]');-->
<!--                if(oSelectFrom && oSelectTo)-->
<!--                {-->
<!--                    var n = oSelectFrom.length;-->
<!--                    var k = oSelectTo.length;-->
<!--                    var c = 0;-->
<!--                    for(var i=0; i<n; i++)-->
<!--                        if(oSelectFrom[i].selected)-->
<!--                        {-->
<!--                            var found = false;-->
<!--                            for(var j=0; j<k; j++)-->
<!--                                if(oSelectTo[j].value == oSelectFrom[i].value)-->
<!--                                    found = true;-->
<!--                            if(!found)-->
<!--                            {-->
<!--                                jsSelectUtils.addNewOption('BOTTOM_TAG_OVERRIDE_PROPERTIES[]', oSelectFrom[i].value, oSelectFrom[i].text, true);-->
<!--                            }-->
<!--                        }-->
<!--                }-->
<!--            }-->
<!--            PropSync();-->
<!--        }-->
<!---->
<!--        function PropOnDel(id)-->
<!--        {-->
<!--            if(id == 'tabs_delete')-->
<!--            {-->
<!--                var selected_tabs = document.getElementById('BOTTOM_TAG_OVERRIDE_PROPERTIES[]');-->
<!---->
<!--                jsSelectUtils.deleteSelectedOptions(selected_tabs.id);-->
<!--            }-->
<!--            PropSync();-->
<!--        }-->
<!---->
<!--        function PropSync()-->
<!--        {-->
<!--            var i,j,n,found;-->
<!--            var available_tabs = document.getElementById('PROPERTY_LIST_AVALIABLE[]');-->
<!--            var selected_tabs = document.getElementById('BOTTOM_TAG_OVERRIDE_PROPERTIES[]');-->
<!---->
<!--            //1 available_tabs-->
<!--            //1.1 Save selection-->
<!--            var available_tabs_selection = '';-->
<!--            for(i = 0; i < available_tabs.length; i++)-->
<!--                if(available_tabs[i].selected)-->
<!--                    available_tabs_selection = available_tabs[i].value;-->
<!---->
<!--            //5 disable and enable buttons-->
<!--            //5.0 calculate selections counters-->
<!--            var selected_tabs_count = 0;-->
<!--            for(i = 0; i < selected_tabs.length; i++)-->
<!--                if(selected_tabs[i].selected)-->
<!--                    selected_tabs_count++;-->
<!--            var available_tabs_count = 0;-->
<!--            for(i = 0; i < available_tabs.length; i++)-->
<!--                if(available_tabs[i].selected)-->
<!--                    available_tabs_count++;-->
<!---->
<!--            //tabs_delete enabled if selected_tabs have selection-->
<!--            document.getElementById('tabs_delete').disabled = selected_tabs_count <= 0;-->
<!---->
<!--            //tabs_copy enabled if available_tabs have selection and this selection does not exists in-->
<!--            //		selected fields-->
<!--            if(available_tabs_count <= 0)-->
<!--            {-->
<!--                document.getElementById('tabs_copy').disabled = true;-->
<!--            }-->
<!--            else-->
<!--            {-->
<!--                found = false;-->
<!--                for(i = 0; i < selected_tabs.length; i++)-->
<!--                    if(selected_tabs[i].value == available_tabs_selection)-->
<!--                        found = true;-->
<!--                document.getElementById('tabs_copy').disabled = found;-->
<!--            }-->
<!--        }-->
<!--    </script>-->
<!--    --><?php
//    //</editor-fold>
// } else if($chpu['TAG_OVERRIDE_TYPE'] == 'M') {
//    if(is_array($chpu['TAG_DATA']) && isset($chpu['TAG_DATA']['SECTION'])) {
//        foreach ($chpu['TAG_DATA']['SECTION'] as $key => $items) {
//            $tabControl->BeginCustomField('BOTTOM_TAG_MANUAL_' . $key, '', false);
//            ?>
<!--            <tr>-->
<!--                <td colspan="2">-->
<!--                    <div class="bottom-tags-item">-->
<!--                        <div class="bottom-tags-item__title">-->
<!--                            <div class="bottom-tags-item__title-collapse" onclick="this.parentNode.classList.toggle('active')">-->
<!--                                <span>--><?//=Loc::getMessage('SEO_META_EDIT_NAME_WITHOUT_DOTS') .': '. $items['NAME']?><!--</span>-->
<!--                                <span class="bottom-tags-item__handle"></span>-->
<!--                            </div>-->
<!--                            <span class="bottom-tags-item__delete --><?//=count($chpu['TAG_DATA']['SECTION']) == 1 ? 'hide' : ''?><!--" onclick="deleteSection(this)"></span>-->
<!--                        </div>-->
<!--                        <div class="bottom-tags-item__content bottom-tags-section">-->
<!--                            <div class="bottom-tags-section__input">-->
<!--                                <div class="bottom-tags-section__input-item">-->
<!--                                    <span class="bottom-tags-section__input-title">-->
<!--                                        <label>--><?//= Loc::getMessage('SEO_META_EDIT_SECTION_NAME') ?><!--</label>-->
<!--                                    </span>-->
<!--                                    <div class="bottom-tags-section__text-input-wrapper">-->
<!--                                        <input type="text"-->
<!--                                               class="bottom-tags-section__text-input chpu-link-url-input"-->
<!--                                               name="BOTTOM_TAG_MANUAL[SECTION][--><?//=$key?><!--][NAME]"-->
<!--                                               value="--><?//= $items['NAME'] ?><!--"-->
<!--                                        >-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                                <hr>-->
<!--                                --><?// foreach ($items['LINKS'] as $linkKey => $link) {?>
<!--                                    <div class="bottom-tags-section__input-item-links">-->
<!--                                        <span class="bottom-tags-section__input-title">-->
<!--                                            <label>-->
<!--                                                --><?//= Loc::getMessage('SEO_META_EDIT_LINK_NAME') ?>
<!--                                            </label>-->
<!--                                        </span>-->
<!--                                        <div class="bottom-tags-section__text-input-wrapper">-->
<!--                                            <input class="bottom-tags-section__text-input"-->
<!--                                                   type="text"-->
<!--                                                   name="BOTTOM_TAG_MANUAL[SECTION][--><?//= $key ?><!--][LINKS][--><?//= $linkKey ?><!--][NAME]"-->
<!--                                                   value="--><?//= $link['NAME'] ?><!--"-->
<!--                                            >-->
<!--                                        </div>-->
<!--                                        <span class="bottom-tags-section__input-title">-->
<!--                                            <label>-->
<!--                                                --><?//= Loc::getMessage('SEO_META_EDIT_LINK_URL') ?>
<!--                                            </label>-->
<!--                                        </span>-->
<!--                                        <div class="bottom-tags-section__text-input-wrapper">-->
<!--                                            <input class="bottom-tags-section__text-input"-->
<!--                                                   type="text"-->
<!--                                                   name="BOTTOM_TAG_MANUAL[SECTION][--><?//= $key ?><!--][LINKS][--><?//= $linkKey ?><!--][URL]"-->
<!--                                                   value="--><?//= $link['URL'] ?><!--"-->
<!--                                            >-->
<!--                                        </div>-->
<!--                                        <a class="adm-btn adm-btn-delete --><?//=count($items['LINKS']) == 1 ? 'hide' : '' ?><!--" onclick="delRow(this)"></a>-->
<!--                                    </div>-->
<!--                                --><?//}?>
<!--                                <a class="adm-btn adm-btn-add" onclick="addRow(this)"></a>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </td>-->
<!--            </tr>-->
<!--            --><?php
//            $tabControl->EndCustomField('BOTTOM_TAG_MANUAL_' . $key);
//        }
//    } else {
//        $tabControl->BeginCustomField('BOTTOM_TAG_MANUAL_1', '', false);
//        ?>
<!--        <tr>-->
<!--            <td colspan="2">-->
<!--                --><?//=$bottomTagsWrapper?>
<!--            </td>-->
<!--        </tr>-->
<!--        --><?//
//        $tabControl->EndCustomField('BOTTOM_TAG_MANUAL_1');
//    }
//
//    $tabControl->BeginCustomField("BOTTOM_TAG_MANUAL_PROPS", '',false);
//    ?>
<!--    <tr id="tr_BOTTOM_TAG_MANUAL_PROPS">-->
<!--        <td colspan="2">-->
<!--            <a class="adm-btn adm-btn-add" onclick="addSection(this)">--><?//=Loc::getMessage('SEO_META_EDIT_BOTTOM_TAG_MANUAL_ADD_SECTION')?><!--</a>-->
<!--        </td>-->
<!--    </tr>-->
<!--    --><?php
//    $tabControl->EndCustomField('BOTTOM_TAG_MANUAL_PROPS');
//}

//</editor-fold>

$backUrl = "/bitrix/admin/sotbit.seometa_chpu_list.php?lang=" . LANG;
$arButtonsParams = array(
    "disabled" => $readOnly,
    "back_url" => $backUrl,
);

$tabControl->Buttons( $arButtonsParams );

//$tabControl->setSelectedTab();

$tabControl->Show();

//<editor-fold desc="Mini progress bars for fields, and menu">
    Asset::getInstance()->AddString( "
        <link rel='stylesheet' href='//code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css'>
        <script src='//code.jquery.com/ui/1.12.0/jquery-ui.js'></script>
        <script>

            $(document).ready(function() {

                $('.progressbar').each(function(){
                    val = $(this).parent().parent().find('textarea').val().length;

                    v = (val/$(this).attr('data-max'))*100;
                    if(v>100)
                        v = 100;
                    $(this).progressbar({value: v});

                    if(val>0 && val<$(this).attr('data-min')) {
                        $(this).find('.ui-progressbar-value').addClass('orange-color-bg');
                    } else if(val == 0 || val>$(this).attr('data-max')){
                        $(this).find('.ui-progressbar-value').addClass('red-color-bg');
                    } else {
                        $(this).find('.ui-progressbar-value').addClass('green-color-bg');
                    }

                });

                $('.count_symbol_print span').each(function() {
                    l = $(this).parent().parent().find('textarea.count_symbol').val().length;
                    $(this).html(l);
                    if($(this).hasClass('meta_title')){
                        limit_min = ".MIN_SEO_TITLE.";
                        limit_max = ".MAX_SEO_TITLE.";
                    }
                    if($(this).hasClass('meta_key')){
                        limit_min = ".MIN_SEO_KEY.";
                        limit_max = ".MAX_SEO_KEY.";
                    }
                    if($(this).hasClass('meta_descr')){
                        limit_min = ".MIN_SEO_DESCR.";
                        limit_max = ".MAX_SEO_DESCR.";
                    }
                    if(l>0 && l<limit_min){
                        $(this).addClass('orange-color');
                    } else {
                        if(l==0 || l>limit_max){
                            $(this).addClass('red-color');
                        }
                        else{
                            $(this).addClass('green-color');
                        }
                    }
                })

                $('textarea.count_symbol').keyup(function(){
                    triggerTextarea($(this));
                });
            });

            function triggerTextarea(t){
                v = t.parent().find('.count_symbol_print span');
                l = t.val().length;
                v.html(l);

                if(v.hasClass('meta_title')){
                    limit_min = ".MIN_SEO_TITLE.";
                    limit_max = ".MAX_SEO_TITLE.";
                }
                if(v.hasClass('meta_key')){
                    limit_min = ".MIN_SEO_KEY.";
                    limit_max = ".MAX_SEO_KEY.";
                }
                if(v.hasClass('meta_descr')){
                    limit_min = ".MIN_SEO_DESCR.";
                    limit_max = ".MAX_SEO_DESCR.";
                }

                bar = t.parent().find('.progressbar');
                vl = (l/bar.attr('data-max'))*100;
                if(vl>100)
                    vl = 100;
                bar.progressbar({value: vl});

                if(l>0 && l<limit_min){
                    v.removeClass('green-color').removeClass('red-color').addClass('orange-color');
                    t.parent().find('.ui-progressbar-value').removeClass('green-color-bg').removeClass('red-color-bg').addClass('orange-color-bg');
                } else {
                    if(l==0 || l>limit_max){
                        v.removeClass('green-color').removeClass('orange-color').addClass('red-color');
                        t.parent().find('.ui-progressbar-value').removeClass('orange-color-bg').removeClass('green-color-bg').addClass('red-color-bg');
                    } else {
                        v.removeClass('red-color').removeClass('orange-color').addClass('green-color');
                        t.parent().find('.ui-progressbar-value').removeClass('orange-color-bg').removeClass('red-color-bg').addClass('green-color-bg');
                    }
                }

                return true;
            }

            $(document).on('click','#SotbitSeoMenuButton',function(){
                var NavMenu=$(this).siblings( '.navmenu-v' );
                if(NavMenu.css('display')=='none')
                {
                    $('.navmenu-v').css('display','none');
                    NavMenu.css('display','block');
                    NavMenu.find('ul').css('right',NavMenu.innerWidth());
                }
                else
                {
                    $('.navmenu-v').css('display','none');
                    NavMenu.css('display','none');
                }
            });

            $(document).on('click','.navmenu-v li.with-prop ',function(){
                if($(this).data( 'prop' )!== 'undefined')
                {
                    if($(this).closest('tr').find('iframe').length>0)
                    {
                        $(this).closest('tr').find('iframe').contents().find('body').append($(this).data( 'prop' ));
                        $(this).closest('tr').find('textarea').insertAtCaret($(this).data( 'prop' ));
                    }
                    else
                    {
                        $(this).closest('tr').find('textarea').insertAtCaret($(this).data( 'prop' ));
                        $(this).closest('tr').find('input[name=\"META_TEMPLATE[TEMPLATE_NEW_URL]\"]').insertAtCaret($(this).data( 'prop' ));
                        if($(this).closest('tr').find('textarea').length > 0)
                            triggerTextarea($(this).closest('tr').find('textarea'));
                    }

                }
            });

            //For add in textarea in focus place
            jQuery.fn.extend({
                insertAtCaret: function(myValue){
                    return this.each(function(i) {
                        if (document.selection) {
                            // Internet Explorer
                            this.focus();
                            var sel = document.selection.createRange();
                            sel.text = myValue;
                            this.focus();
                        }
                        else if (this.selectionStart || this.selectionStart == '0') {
                            //  Firefox and Webkit
                            var startPos = this.selectionStart;
                            var endPos = this.selectionEnd;
                            var scrollTop = this.scrollTop;
                            this.value = this.value.substring(0, startPos)+myValue+this.value.substring(endPos,this.value.length);
                            this.focus();
                            this.selectionStart = startPos + myValue.length;
                            this.selectionEnd = startPos + myValue.length;
                            this.scrollTop = scrollTop;
                        } else {
                            this.value += myValue;
                            this.focus();
                        }
                    })
                }
            });

            //For menu
            navHover = function() {
                var lis = document.getElementByClass('navmenu-v').getElementsByTagName('LI');
                for (var i=0; i<lis.length; i++) {
                    lis[i].onmouseover=function() {
                        this.className+=' iehover';
                    }
                    lis[i].onmouseout=function() {
                        this.className=this.className.replace(new RegExp(' iehover\\b'), '');
                    }
                }
            }
            if (window.attachEvent) window.attachEvent('onload', navHover);

            function setDisabled(item) {
                document.querySelector(item).hasAttribute('disabled')
                    ? document.querySelector(item).removeAttribute('disabled')
                    :document.querySelector(item).setAttribute('disabled', 'true');
            }
            
            function deleteLink(id, element) {
                if(id !== undefined && id != '') {
                    BX.ajax({
                        url: '/bitrix/admin/sotbit.seometa_chpu_edit.php',
                        data: {
                            id : id,
                            action : 'delete_link',
                            'type' : 'ajax'
                        },
                        method: 'POST',
                        dataType: 'json',
                        timeout: 30,
                        async: true,
                        processData: true,
                        scriptsRunFirst: true,
                        emulateOnload: true,
                        start: true,
                        cache: false,
                        onsuccess: function(data){
                            //console.log(data);
                        },
                        onfailure: function(data){
                            //console.log(data);
                        }
                    });
                }
                
                element = element.closest('tr');
                if(element !== undefined && element != '') {
                    element.remove();
                }
            } 
            
        </script>
    <style>
        .count_symbol_print {
            font-size: 12px;
            color: gray;
            width: 92%;
        }
        .count_symbol_print span {
            display: inline-block;
            width: 20px;
            float: right;
            text-align: right;
        }
        .progressbar{
            display: inline-block;
            height: 3px;
            width: 100px;
            float: right;
            margin-top: 4px;
        }
        .orange-color {
            color: orange;
        }
        .orange-color-bg {
            background: orange;
        }
        .green-color {
            color: green;
        }
        .green-color-bg {
            background: green;
        }
        .red-color {
            color: red;
        }
        .red-color-bg {
            background: red;
        }
        ul.navmenu-v
        {
            position:absolute;
            margin: 0;
            border: 0 none;
            padding: 0;
            list-style: none;
            z-index:9999;
            display:none;
            right:20px;
        }
        ul.navmenu-v li,
        ul.navmenu-v ul {
            margin: 0;
            border: 0 none;
            padding: 0;
            list-style: none;
            z-index:9999;
        }
        ul.navmenu-v li:hover
        {
            background:#ebf2f4;
        }
        ul.navmenu-v:after {
            clear: both;
            display: block;
            font: 1px/0px serif;
            content: " . ";
            height: 0;
            visibility: hidden;
        }

        ul.navmenu-v li {
            font-size:13px;
            font-weight:normal;
            font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;
            white-space:nowrap;
            height:30px;
            line-height:27px;
            padding-left:21px;
            padding-right:21px;
            text-shadow:0 1px white;
            display: block;
            position: relative;
            background: #FFF;
            color: #303030;
            text-decoration: none;
            cursor:pointer;
        }
        ul.navmenu-v,
        ul.navmenu-v ul,
        ul.navmenu-v ul ul,
        ul.navmenu-v ul ul ul {
            border:1px solid #d5e1e4;
            border-radius:4px;
            box-shadow:0 18px 20px rgba(72, 93, 99, 0.3);
            background:#FFF;
        }


        ul.navmenu-v ul,
        ul.navmenu-v ul ul,
        ul.navmenu-v ul ul ul {
            display: none;
            position: absolute;
            top: 0;
            right: 292px;
        }


        ul.navmenu-v li:hover ul ul,
        ul.navmenu-v li:hover ul ul ul,
        ul.navmenu-v li.iehover ul ul,
        ul.navmenu-v li.iehover ul ul ul {
            display: none;
        }

        ul.navmenu-v li:hover ul,
        ul.navmenu-v ul li:hover ul,
        ul.navmenu-v ul ul li:hover ul,
        ul.navmenu-v li.iehover ul,
        ul.navmenu-v ul li.iehover ul,
        ul.navmenu-v ul ul li.iehover ul {
            display: block;
        }
    </style>", true );
//</editor-fold>
?>
<script>
    var messages = {
        title: "<?= Loc::getMessage('SEO_META_EDIT_NAME_WITHOUT_DOTS') ?>: "
    };

    var urlParams = {
        protocol: "<?= $protocol; ?>",
        domain: "<?= $siteInfo['SERVER_NAME']; ?>"
    };

    function searchOption(input) {
        const options = input.nextElementSibling.querySelectorAll('option');

        if(input.value.length > 2) {

            options.forEach(
                function (option) {
                    if (!option.textContent.toLowerCase().includes(input.value.toLowerCase())) {
                        option.style.display = 'none';
                    } else {
                        option.style.display = 'block';
                    }
                }
            )
        } else {
            options.forEach(
                function (option) {
                    option.style.display = 'block';
                }
            )
        }
    }

    function delRow(el) {
        let rows = el.parentElement.parentElement.querySelectorAll('.bottom-tags-section__input-item-links');
        if(rows.length === 2) {
            rows.forEach(
                function (element) {
                    element.querySelector('.adm-btn-delete').classList.add('hide');
                }
            );
        }

        el.parentElement.remove();
    }

    function deleteSection(element) {
        if(element) {
            element.parentElement.parentElement.remove();
        }

        let sections = document.querySelectorAll('.bottom-tags-item');

        if(sections && sections.length === 1) {
            sections[0].querySelector('.bottom-tags-item__delete').classList.add('hide');
        }
    }

    function addRow(button) {
        let fields = button.previousElementSibling.cloneNode(true);
        let elementKey = button.parentElement.querySelectorAll('.bottom-tags-section__input-item-links').length;
        let sectionKey = button.parentElement.parentElement.parentElement.parentElement.querySelectorAll('.bottom-tags-item').length;

        if(elementKey && sectionKey && fields) {
            const inputs = fields.querySelectorAll('input[type=text]');

            inputs.forEach(
                function (input, index) {
                    input.value = '';
                    if(index === 0) {
                        input.setAttribute('name', 'BOTTOM_TAG_MANUAL[SECTION]['+ (sectionKey - 1) +'][LINKS]['+ elementKey +'][NAME]');
                    } else {
                        input.setAttribute('name', 'BOTTOM_TAG_MANUAL[SECTION]['+ (sectionKey - 1) +'][LINKS]['+ elementKey +'][URL]');
                    }
                }
            );

            button.parentElement.insertBefore(fields, button);

            let elementsAfter = button.parentElement.querySelectorAll('.bottom-tags-section__input-item-links');
            if(elementsAfter) {
                elementsAfter.forEach(
                    function (element) {
                        element.querySelector('.adm-btn-delete').classList.remove('hide');
                    }
                );
            }
        }
    }

    function addSection(addSectionBtn) {
        let elements = addSectionBtn.parentElement.parentElement.parentElement.querySelectorAll('.bottom-tags-item');

        if(elements) {
            let sectionKey = elements.length;
            let parentEl = elements[sectionKey - 1].parentElement;
            let cloneEl = elements[sectionKey - 1].cloneNode(true);

            let inputElements = cloneEl.querySelectorAll('.bottom-tags-section__input-item-links');

            inputElements.forEach(
                function (element, index) {
                    if(index == 0) {
                        element.querySelectorAll('input[type=text]').forEach(function (input, index) {
                            input.value = '';
                            if (index === 0) {
                                input.setAttribute('name', 'BOTTOM_TAG_MANUAL[SECTION][' + sectionKey + '][LINKS][0][NAME]');
                            } else {
                                input.setAttribute('name', 'BOTTOM_TAG_MANUAL[SECTION][' + sectionKey + '][LINKS][0][URL]');
                                element.querySelector('.adm-btn-delete').classList.add('hide');
                            }
                        });
                    } else {
                        element.remove();
                    }
                }
            );

            cloneEl.querySelector('.bottom-tags-section__text-input').setAttribute('name', 'BOTTOM_TAG_MANUAL[SECTION]['+ sectionKey +'][NAME]');
            cloneEl.querySelector('.bottom-tags-section__text-input').value = '';
            cloneEl.querySelector('.bottom-tags-item__title-collapse span').innerHTML = messages.title;

            parentEl.append(cloneEl);

            let sectionBlocks = document.querySelectorAll('.bottom-tags-item');
            sectionBlocks.forEach(function(element) {
                element.querySelector('.bottom-tags-item__delete').classList.remove('hide');
            });
        }
    }

    function allOptionsSelect() {
        let options = document.getElementById('BOTTOM_TAG_OVERRIDE_PROPERTIES[]');
        let emptyInputItems = document.querySelectorAll('.bottom-tags-item input[type=text]');

        if(options) {
            Array.prototype.slice.call(options.options).forEach(
                function (currentItem) {
                    currentItem.selected = true;
                }
            );
        }

        if(emptyInputItems) {
            emptyInputItems.forEach(
                function (item) {
                    if (!item.value) {
                        item.remove();
                    }
                }
            );
        }
    }

    function checkboxEnable () {
        let checkboxSelector = 'input[name^=SITE_ID][type=checkbox]';
        let checkboxes = document.querySelectorAll(checkboxSelector);
        if(checkboxes) {
            checkboxes.forEach(
                function (el) {
                    el.disabled = 0;
                }
            )
        }
    }

    document.getElementsByName('apply')[0].addEventListener('click', allOptionsSelect);
    document.getElementsByName('save')[0].addEventListener('click', allOptionsSelect);
    document.getElementsByName('apply')[0].addEventListener('click', checkboxEnable);
    document.getElementsByName('save')[0].addEventListener('click', checkboxEnable);

    function urlChange(event) {
        let url = event.currentTarget.value;
        let aLink = event.currentTarget.nextElementSibling;

        if(event.currentTarget.value && aLink) {
            if(urlParams['protocol'] && urlParams['domain']) {
                aLink.href = urlParams['protocol'] + urlParams['domain'] + url;
            }
        }
    }

    document.getElementsByName('REAL_URL')[0].addEventListener('input', urlChange);
    document.getElementsByName('NEW_URL')[0].addEventListener('input', urlChange);

</script>
<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>
