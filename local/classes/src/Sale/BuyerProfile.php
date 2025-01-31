<?php
namespace Meven\Sale;
use \Bitrix\Main\Service\GeoIp\Manager;
use \Bitrix\Main\Web\Cookie;
use \Bitrix\Main\Application;

class BuyerProfile
{
    private static $instance = null;

    public function __construct()
    {

    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function getPropsArray(): array
    {
        global $USER;
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();

        // if ($request->getPost('form_fields') == 'jur') {
            // формируем массив свойств
            $propsArray = [
                [
                    // "USER_PROPS_ID" => $request->getPost('PROFILE_ID'),
                    "ORDER_PROPS_ID" => \Bitrix\Main\Config\Option::get("meven.info", "order_prop_company"),
                    "NAME" => "Наименование организации",
                    "VALUE" => $request->getPost('COMPANY'),
                ],
                [
                    // "USER_PROPS_ID" => $request->getPost('PROFILE_ID'),
                    "ORDER_PROPS_ID" => \Bitrix\Main\Config\Option::get("meven.info", "order_prop_legal_address"),
                    "NAME" => "Юр. адрес",
                    "VALUE" => $request->getPost('LEGAL_ADDRESS')
                ],
                [
                    // "USER_PROPS_ID" => $request->getPost('PROFILE_ID'),
                    "ORDER_PROPS_ID" => \Bitrix\Main\Config\Option::get("meven.info", "order_prop_inn"),
                    "NAME" => "ИНН",
                    "VALUE" => $request->getPost('INN')
                ],
                [
                    // "USER_PROPS_ID" => $request->getPost('PROFILE_ID'),
                    "ORDER_PROPS_ID" => \Bitrix\Main\Config\Option::get("meven.info", "order_prop_kpp"),
                    "NAME" => "КПП",
                    "VALUE" => $request->getPost('KPP')
                ],
                [
                    // "USER_PROPS_ID" => $request->getPost('PROFILE_ID'),
                    "ORDER_PROPS_ID" => \Bitrix\Main\Config\Option::get("meven.info", "order_prop_check_acc"),
                    "NAME" => "Р/c",
                    "VALUE" => $request->getPost('CHECKING_ACC')
                ],
                [
                    // "USER_PROPS_ID" => $request->getPost('PROFILE_ID'),
                    "ORDER_PROPS_ID" => \Bitrix\Main\Config\Option::get("meven.info", "order_prop_cor_acc"),
                    "NAME" => "К/c",
                    "VALUE" => $request->getPost('COR_ACC')
                ],
                [
                    // "USER_PROPS_ID" => $request->getPost('PROFILE_ID'),
                    "ORDER_PROPS_ID" => \Bitrix\Main\Config\Option::get("meven.info", "order_prop_bank"),
                    "NAME" => "В банке...",
                    "VALUE" => $request->getPost('BANK')
                ],
                [
                    // "USER_PROPS_ID" => $request->getPost('PROFILE_ID'),
                    "ORDER_PROPS_ID" => \Bitrix\Main\Config\Option::get("meven.info", "order_prop_name"),
                    "NAME" => "Имя",
                    "VALUE" => $USER->GetFirstName(),
                ],
                [
                    // "USER_PROPS_ID" => $request->getPost('PROFILE_ID'),
                    "ORDER_PROPS_ID" => \Bitrix\Main\Config\Option::get("meven.info", "order_prop_email"),
                    "NAME" => "E-mail",
                    "VALUE" => $USER->GetEmail(),
                ],
            // ];
        // }

        // if ($request->getPost('form_fields') == 'addr') {

            // $concatStr = implode(' ', $request->getPost('address'));
            // $propsArray = [
                // [
                //     "ORDER_PROPS_ID" => \Bitrix\Main\Config\Option::get("meven.info", "order_prop_address"),
                //     "NAME" => "Адрес",
                //     "VALUE" => $concatStr
                // ],
                [
                    "ORDER_PROPS_ID" => \Bitrix\Main\Config\Option::get("meven.info", "order_prop_street"),
                    "NAME" => "Улица",
                    "VALUE" => $request->getPost('STREET')
                ],
                [
                    "ORDER_PROPS_ID" => \Bitrix\Main\Config\Option::get("meven.info", "order_prop_house"),
                    "NAME" => "Дом",
                    "VALUE" => $request->getPost('HOUSE')
                ],
                [
                    "ORDER_PROPS_ID" => \Bitrix\Main\Config\Option::get("meven.info", "order_prop_corpus"),
                    "NAME" => "Корпус",
                    "VALUE" => $request->getPost('CORPUS')
                ],
                [
                    "ORDER_PROPS_ID" => \Bitrix\Main\Config\Option::get("meven.info", "order_prop_entrance"),
                    "NAME" => "Подъезд",
                    "VALUE" => $request->getPost('ENTRANCE')
                ],
                [
                    "ORDER_PROPS_ID" => \Bitrix\Main\Config\Option::get("meven.info", "order_prop_ofis"),
                    "NAME" => "Офис/квартира",
                    "VALUE" => $request->getPost('OFIS')
                ],
            ];

        // }

        return $propsArray;
    }

    public function addProfile():int
    {
        global $USER;
        \Bitrix\Main\Loader::includeModule('sale');
        $request = \Bitrix\Main\Context::getCurrent()->getRequest();
        $userId = $USER->GetID();
        $userLogin = $USER->GetLogin();

        $propsArray = $this->getPropsArray();

        if (\Bitrix\Main\Loader::includeModule('sale')) {

            $profileCurrent = $request->get('PROFILE_ID');

            // если профиль уже есть, то обновляем
            if(!empty($profileCurrent)) {

                $resPropVals = \CSaleOrderUserPropsValue::GetList(array("ID" => "ASC"), Array("USER_PROPS_ID"=>$profileCurrent));
                $propIds = [];
                while ($arPropVals = $resPropVals->Fetch())
                {
                    $propIds[$arPropVals["ORDER_PROPS_ID"]] = $arPropVals["ID"];
                }

                foreach ($propsArray as $prop) {
                    $res = \CSaleOrderUserPropsValue::Update(
                        $propIds[$prop["ORDER_PROPS_ID"]],
                        array_merge($prop,
                            [
                                "ID" => $propIds[$prop["ORDER_PROPS_ID"]],
                            ]
                        )
                    );
                }
                return $profileCurrent;
                //\Bitrix\Main\Diag\Debug::dumpToFile($arPropVals, 'Fields',"debug.txt");
            }
            // иначе добавляем
            else {
                // создаём профиль
                $arProfileFields = [
                    "NAME" => "Профиль покупателя (" . $userLogin . ')',
                    "USER_ID" => $userId,
                    "PERSON_TYPE_ID" => $request->get('PERSON_TYPE')
                ];
                $profileId = \CSaleOrderUserProps::Add($arProfileFields);
                // если профиль создан
                if ($profileId) {
                    // добавляем значения свойств к созданному ранее профилю
                    foreach ($propsArray as $prop) {
                        \CSaleOrderUserPropsValue::Add(array_merge($prop,
                            [
                                "USER_PROPS_ID" => $profileId,
                            ]
                        ));
                    }
                }
                return $profileId;
            }

        }
    }

    public function delProfile($id):int {
        if (\Bitrix\Main\Loader::includeModule('sale')) {
            \CSaleOrderUserProps::Delete(
                $id
            );
            return true;
        }
        return false;
    }
}