<?php

namespace Erachain;

use Erachain\Request\BaseRequest;

/**
 * Библиотека для взаимодействия с блокчейн сетью Erachain
 *
 * @author Erachain <info@erachain.org>
 * @license MIT
 * @version 1.0.0
 * @package Erachain
 */
class Erachain
{
    private $erachain_mode;

    public $crypto;
    public $person;
    public $asset;
    public $message;
    public $telegram;
    public $order;
    public $status;
    public $poll;
    public $vouch;
    public $transaction;

    public function __construct($erachain_mode = 'dev')
    {
        $this->erachain_mode = $erachain_mode;

        $this->crypto      = new Crypto();
        $this->person      = new Person($erachain_mode);
        $this->asset       = new Asset($erachain_mode);
        $this->message     = new Message($erachain_mode);
        $this->telegram    = new Telegram($erachain_mode);
        $this->order       = new Order($erachain_mode);
        $this->status      = new Status($erachain_mode);
        $this->poll        = new Poll($erachain_mode);
        $this->vouch       = new Vouch($erachain_mode);
        $this->transaction = new Transaction($erachain_mode);
    }

    /**
     * Выполнение любых доступных запросов к ноде (полный список в документации API)
     *
     * @param string $request Запрос к ноде
     * @param array|string $params GET/POST параметры запроса. (GET - массив параметров, POST - строка с байт кодом)
     * @param string $method Метод запроса ('get'/'post')
     *
     * @return array|bool Возвращает ответ от ноды по запросу
     * @example https://app.swaggerhub.com/apis-docs/Erachain/era-api/1.0.0-oas3
     */
    public function api($request = null, $params = null, $method = 'get')
    {
        $base_request = new BaseRequest($this->erachain_mode);

        return $base_request->request_api($request, $params, $method);
    }
}
