<?php
namespace Meven\Helper;


class Dates
{
    public static function getMonthName(int $key) {
        $monthes = [
            '',
            'Января',
            'Февраля',
            'Марта',
            'Апреля',
            'Мая',
            'Июня',
            'Июля',
            'Августа',
            'Сентября',
            'Октября',
            'Ноября',
            'Декабря'
        ];
        return $monthes[$key];
    }
}