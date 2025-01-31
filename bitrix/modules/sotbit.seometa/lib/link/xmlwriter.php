<?php
namespace Sotbit\Seometa\Link;

use Bitrix\Main\Config\Option;

class XmlWriter extends AbstractWriter
{
    private static $Writer = false;
    private $dir = false;
    private $xmlVersion = '1.0';
    private $xmlAttr = 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    private $chpuAll = array();
    private $sitemapSettings = array();

    private static $urlCountWrited = 0;
    private static $addID = 1;

    private function __construct($id, $dir, $SiteUrl, $isProgress = false)
    {
        if (!is_string($dir))
            throw new \Exception('DIR must be an string, ' . gettype($dir) . ' given');

        if (!file_exists($dir))
            throw new \Exception('Not Found Directory "' . $dir . '"');

        $this->id = $id;
        $this->dir = $dir;
        $this->siteUrl = $SiteUrl;
        $this->chpuAll = \Sotbit\Seometa\SeometaUrlTable::getAll();

        $sitemap = \Sotbit\Seometa\SitemapTable::getById($this->id)->fetch();
        $this->sitemapSettings = unserialize($sitemap['SETTINGS']);
        $this->sitemapSettings['SITE_ID'] = $sitemap['SITE_ID'];
        if ($isProgress && !file_exists($dir . 'sitemap_seometa_'. $id .'_'. self::getAddID() .'.xml')) {
            self::createSeometaSitemapFile($dir . 'sitemap_seometa_'. $id .'_'. self::getAddID() .'.xml');
            Option::set('sotbit.seometa', 'SEOMETA_SITEMAP_XMLWRITER_URL_COUNT', '0', $this->sitemapSettings['SITE_ID']);
            Option::set('sotbit.seometa', 'SEOMETA_SITEMAP_XMLWRITER_ADD_ID', '1', $this->sitemapSettings['SITE_ID']);
        } else {
            self::$urlCountWrited = Option::get('sotbit.seometa', 'SEOMETA_SITEMAP_XMLWRITER_URL_COUNT', '0', $this->sitemapSettings['SITE_ID']);
            self::$addID = Option::get('sotbit.seometa', 'SEOMETA_SITEMAP_XMLWRITER_ADD_ID', '1', $this->sitemapSettings['SITE_ID']);

        }
    }

    function __destruct() {
         Option::set('sotbit.seometa', 'SEOMETA_SITEMAP_XMLWRITER_URL_COUNT', self::$urlCountWrited, $this->sitemapSettings['SITE_ID']);
         Option::set('sotbit.seometa', 'SEOMETA_SITEMAP_XMLWRITER_ADD_ID', self::$addID, $this->sitemapSettings['SITE_ID']);
    }

    public static function getInstance($id, $dir, $SiteUrl, $isProgress = false)
    {
        if (self::$Writer === false) {
            self::$Writer = new XmlWriter($id, $dir, $SiteUrl, $isProgress);
        }

        self::$Writer->setDir($dir);
        return self::$Writer;
    }

    private function urlCountInc() {
        self::$urlCountWrited++;
    }

    private function urlCountCompare($num) {
        if(self::$urlCountWrited >= $num) {
            self::$urlCountWrited = 0;
            return true;
        }

        return false;
    }

    private function incAddID() {
        self::$addID++;
    }

    public function getAddID() {
        return self::$addID;
    }

    private function getFileSize($file_path, $size, $scale) {
        $scaleSize['Mb'] = 1000000;
        if(file_exists($file_path) && isset($scaleSize[$scale])) {
            if(filesize($file_path) / $scaleSize[$scale] >= $size) {
                return true;
            }
        }

        return false;
    }

    private function createSeometaSitemapFile($filePath) {
        file_put_contents($filePath, '<?xml version="' . $this->xmlVersion . '" encoding="utf-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
    }

    public function AddRow(array $arFields)
    {
        getList([])->fetchAll();
    }

    public function setDir($dir)
    {
        if (!is_string($dir))
            throw new \Exception('DIR must be an string, ' . gettype($dir) . ' given');

        if (!file_exists($dir))
            throw new \Exception('Not found derictory "' . $dir . '"');

        $this->dir = $dir;
    }

    public function Write(array $arFields)
    {
        if (empty($this->dir) || empty($this->id))
            return; //can throw new \Exception('do not have dir or id');

        $LOC = $arFields['real_url'];
        $url = \Sotbit\Seometa\SeometaUrlTable::getByRealUrl(str_replace($this->siteUrl, '', $LOC));

        $newGenerateCHPU = array(
            'CONDITION_ID' => $arFields['condition_id'],
            'REAL_URL' => $arFields['real_url'],
            'NEW_URL' => $arFields['new_url'],
            'NAME' => $arFields['name'],
            'PROPERTIES' => serialize($arFields['properties']),
            'iblock_id' => $arFields['iblock_id'],
            'section_id' => $arFields['section_id'],
            'PRODUCT_COUNT' => $arFields['product_count'],
            'DATE_CHANGE' => new \Bitrix\Main\Type\DateTime(date('Y-m-d H:i:s'), 'Y-m-d H:i:s'),
            'IN_SITEMAP' => 'Y',
            'ACTIVE' => $arFields['active']
        );

        // if URL is active then replace REAL_URL with NEW_URL
        if (!empty($url) && isset($this->chpuAll[$url['ID']])) {
            $LOC = str_replace($url['REAL_URL'], $url['NEW_URL'], $LOC);
            unset($this->chpuAll[$url['ID']]);

            \Sotbit\Seometa\SeometaUrlTable::update($url['ID'], $newGenerateCHPU);
        } else {
            if (isset($this->sitemapSettings['EXCLUDE_NOT_SEF']) && $this->sitemapSettings['EXCLUDE_NOT_SEF'] == 'Y') {
                return;
            } else {
                $allUrlsByCond = \Sotbit\Seometa\SeometaUrlTable::getAllByCondition($arFields['condition_id']);

                $count = 0;
                if ($allUrlsByCond) {
                    foreach ($allUrlsByCond as $url) {
                        if ($LOC == $url['REAL_URL'] && $arFields['new_url'] == $url['NEW_URL']) {
                            $count++; // found a match
                            $urlID = $url['ID'];
                            break;
                        }
                    }
                }

                if ($count == 1) {
                    \Sotbit\Seometa\SeometaUrlTable::update($urlID, $newGenerateCHPU);
                } else {
                    \Sotbit\Seometa\SeometaUrlTable::add($newGenerateCHPU);
                }
            }
        }

        if (mb_substr($LOC, 0, 4) != 'http') {
            $LOC = $this->siteUrl . $LOC;
        }

        $url = "<url>";
        $url .= "<loc>" . str_replace('&', '&amp;', $LOC) . "</loc>";
        $url .= "<lastmod>" . $newGenerateCHPU['DATE_CHANGE']->format('Y-m-d\Th:m:sP') . "</lastmod>";

        if (!$this->arCondition['CHANGEFREQ']) {
            $this->arCondition['CHANGEFREQ'] = 'always';
        }

        $url .= "<changefreq>" . $this->arCondition['CHANGEFREQ'] . "</changefreq>";

        if ($this->arCondition['PRIORITY'] == '0' || !isset($this->arCondition['PRIORITY'])) {
            $this->arCondition['PRIORITY'] = '0.0';
        }

        $url .= "<priority>" . number_format($this->arCondition['PRIORITY'], 1) . "</priority>";

        $url .= "</url>";

        self::urlCountInc();
        if(
            self::urlCountCompare(Option::get('sotbit.seometa', 'SEOMETA_SITEMAP_COUNT_LINKS', '50000', $this->sitemapSettings['SITE_ID'])) ||
            self::getFileSize($this->dir . 'sitemap_seometa_' . $this->id .'_'. self::getAddID() .'.xml', Option::get('sotbit.seometa', 'SEOMETA_SITEMAP_FILE_SIZE', '50', $this->sitemapSettings['SITE_ID']), 'Mb')
        )
        {
            self::incAddID();
            self::createSeometaSitemapFile($this->dir . 'sitemap_seometa_' . $this->id .'_'. self::getAddID() .'.xml');
        }

        file_put_contents($this->dir . 'sitemap_seometa_' . $this->id .'_'. self::getAddID() .'.xml', $url, FILE_APPEND);
        unset($url);
    }

    public function WriteEnd()
    {
        if (empty($this->dir) || empty($this->id))
            return;

        if (!empty($this->dir) && !empty($this->id)) {
            for($i = 1; $i <= self::getAddID(); $i++) {
                file_put_contents($this->dir . 'sitemap_seometa_' . $this->id .'_'. $i .'.xml', '</urlset>', FILE_APPEND);
            }
        }
    }
}
