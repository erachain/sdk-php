<?php

namespace Erachain\Request;

use Erachain\Helpers\Request;

class BaseRequest
{
    protected $request;

    public function __construct($erachain_params)
    {
        $this->request = new Request($erachain_params);
    }

    /**
     * Получение публичного ключа из адресса
     *
     * @param string $address Адресс
     *
     * @return array|bool Возвращает ответ от ноды с публичным ключем
     */
    public function get_public_key_by_address($address)
    {
        return $this->request->send(
            '/api/addresspublickey/' . $address,
            false,
            'get'
        );
    }

    /**
     * Выполнение любых доступных запросов к ноде
     *
     * @param string $request Запрос к ноде
     * @param array|string $params GET/POST параметры запроса. (GET - массив параметров, POST - строка с байт кодом)
     * @param string $method Метод запроса (get/post)
     *
     * @return array|bool Возвращает ответ от ноды по запросу
     */
    public function request_api($request, $params, $method)
    {
        return $this->request->send(
            $request,
            $params,
            $method
        );
    }

}