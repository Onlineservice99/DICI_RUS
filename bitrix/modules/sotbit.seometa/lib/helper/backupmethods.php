<?php

namespace Sotbit\Seometa\Helper;

class BackupMethods
{
    private $backupFileName = 'sitemap_seometa_';
    private $dir = '';
    private $arrFiles = [];

    public function makeBackup(
        $dir
    ) {
        $empty = true;
        if (is_dir($dir)) {
            if (($res = opendir($dir))) {
                while (($item = readdir($res))) {
                    if ($item == '..' || $item == '.') {
                        continue;
                    }

                    if (mb_strpos($item,
                            $this->backupFileName) !== false) {
                        $empty = false;
                        self::addFile($dir . $item);
                    }
                }

                if(!$empty) {
                    self::setDir($dir);
                }
                closedir($res);
            }
        }

        $result = self::archivePacking();

        return $result;
    }

    private function addFile(
        $fileName
    ) {
        if (is_file($fileName)) {
            $this->arrFiles[] = $fileName;
        }
    }

    private function setDir(
        $dir
    ) {
        if ($dir) {
            $this->dir = $dir;
        }
    }

    private function checkStatus(
        $archiveObject
    ) {
        $result = '';
        switch ($archiveObject)
        {
            case \IBXArchive::StatusSuccess:
                //OK
                break;
            case \IBXArchive::StatusError:
                //Not ok
                $result = $archiveObject->GetErrors();
                break;
        }

        return $result;
    }

    private function archivePacking(
    ) {
        if ($this->arrFiles) {
            $name = 'seometasitemap_backup_' . date('m-d-y_H-i') . '.zip';
            $archiveObject = \CBXArchive::GetArchive($this->dir . $name);

            if($archiveObject instanceof \IBXArchive) {
                $archiveObject->SetOptions(
                    array(
                        "COMPRESS"			=> true,
                        "ADD_PATH"			=> false,
                        "REMOVE_PATH"		=> $this->dir,
                    )
                );

                $archiveObject = $archiveObject->Pack($this->arrFiles);
                $result = self::checkStatus($archiveObject);
            }
        } else {
            //TODO: if not have a files
        }

        return $result;
    }
}
