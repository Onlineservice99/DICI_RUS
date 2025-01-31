<?php
namespace Russvet\Api;

use \GuzzleHttp\Exception\RequestException;

class Api
{
    protected $client = null;
    protected $data = [
        "urlApi" => "https://cdis.russvet.ru",
        "uriApi" => null,
    ];
    protected $requestData = [

    ];
    protected $authData = [
        "username" => "rus_electro_denis",
        "password" => "420M79PL",
    ];
    protected $uriRequest = [];

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $this->data['urlApi'],
            //'http_errors' => false,
            //'exceptions' => false
        ]);
    }

    /**
     * установить логин для авторизации запросов
     * @param string $login
     */
    public function setAuthLogin(string $login): void
    {
        $this->authData["username"] = $login;
    }

    /**
     * установить пароль для авторизации запросов
     * @param string $pass
     */
    public function setAuthPassword(string $pass): void
    {
        $this->authData["password"] = $pass;
    }

    public function send ()
    {
        try {
            $res = $this->client->get($this->data["uriApi"], [
                'auth' => [
                    $this->authData["username"],
                    $this->authData["password"],
                ],
                'query' => $this->requestData,
            ]);

            return $res->getBody()->getContents();
        } catch (RequestException $e) {
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}