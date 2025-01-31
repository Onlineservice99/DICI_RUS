<?php
namespace Meven\Helper;

class Json extends \Bitrix\Main\Web\Json
{
    public static function dumpErrors(array $errors): void
    {
        $result = [
            'errors' => $errors
        ];
        header('Content-Type: application/json');
        http_response_code(422);
        echo self::encode($result);
        die();
    }

    /**
     * @param array $data
     * @throws \Bitrix\Main\ArgumentException
     *
     */
    public static function dumpSuccess(array $data = []): void
    {

        http_response_code(200);
        if (!empty($data)) {
            header('Content-Type: application/json');
            echo self::encode($data);
        }
        die();
    }
}
