<?php
namespace Meven\Models;

use Bitrix\Main\ORM\Fields\DatetimeField,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\Entity;

class FavoriteTable extends \Bitrix\Main\ORM\Data\DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'favorite';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),
            new IntegerField('ITEM_ID', [
                'required' => true,
            ]),
            new IntegerField('USER_ID', [
                'required' => true,
            ]),
            new DatetimeField('CREATED_AT', [
                'required' => true
            ])
        ];
    }

    public static function onBeforeAdd(Entity\Event $event)
    {
        $object = $event->getParameter("object");

        $date = new \Bitrix\Main\Type\DateTime();
        $object->setCreatedAt($date);
    }
}