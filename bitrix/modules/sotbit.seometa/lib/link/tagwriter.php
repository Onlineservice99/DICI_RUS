<?
namespace Sotbit\Seometa\Link;

use Sotbit\Seometa\SeoMetaMorphy;

class TagWriter extends AbstractWriter
{
    private static $Writer = false;
    private $WorkingConditions = false;

    private $countTagWrite = 0;
    private $countTagWrited = 0;

    private function __construct($WorkingConditions, $countTagsForWrite)
    {
        $this->WorkingConditions = $WorkingConditions;
        $this->countTagWrite = $countTagsForWrite;
    }

    public static function getInstance($WorkingConditions, $countTagsForWrite)
    {
        if(self::$Writer === false)
            self::$Writer = new TagWriter($WorkingConditions, $countTagsForWrite);

        return self::$Writer;
    }

    public function AddRow(array $arFields)
    {
    }

    public function Write(array $arFields)
    {
        $morphyObject = SeoMetaMorphy::morphyLibInit();

        $sku = new \Bitrix\Iblock\Template\Entity\Section($arFields['section_id']);
        \CSeoMetaTagsProperty::$params = $arFields['properties'];

        $conditionTag = $this->arCondition['TAG'];

        if($arFields['strict_relinking'] != 'Y')
        {
            $Title = \Bitrix\Iblock\Template\Engine::process($sku, SeoMetaMorphy::prepareForMorphy($this->arCondition['TAG']));
        }
        else if(in_array($this->arCondition['ID'], $this->WorkingConditions) && $conditionTag)
        {
            $Title = \Bitrix\Iblock\Template\Engine::process($sku, SeoMetaMorphy::prepareForMorphy($conditionTag));
        }

        if(!empty($Title)) {
            $Title = SeoMetaMorphy::convertMorphy($Title, $morphyObject);
            $this->data[] = array(
                'URL' => trim($arFields['real_url']),
                'TITLE' => trim($Title),
                'PRODUCT_COUNT' => $arFields['product_count']
            );

            if(!empty($this->countTagWrite) && ($this->countTagWrited >= $this->countTagWrite)) {
                return true;
            } else if(!empty($this->countTagWrite)) {
                $this->countTagWrited++;
            }
        }
    }

    public function getData()
    {
        return $this->data;
    }
}
