<?php

namespace Erachain;

use Erachain\Request\BaseRequest;

/**
 * Библиотека для взаимодействия с блокчейн сетью Erachain
 *
 * @author Erachain <info@erachain.org>
 * @license MIT
 * @version 1.0
 * @package Erachain
 */
class Erachain
{
    private $erachain_params;

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

    public function __construct($erachain_mode = 'dev', $server_link = null)
    {
        $this->erachain_params = array(
            'mode'   => $erachain_mode,
            's_link' => $server_link,
        );

        $this->crypto      = new Crypto();
        $this->person      = new Person($this->erachain_params);
        $this->asset       = new Asset($this->erachain_params);
        $this->message     = new Message($this->erachain_params);
        $this->telegram    = new Telegram($this->erachain_params);
        $this->order       = new Order($this->erachain_params);
        $this->status      = new Status($this->erachain_params);
        $this->poll        = new Poll($this->erachain_params);
        $this->vouch       = new Vouch($this->erachain_params);
        $this->transaction = new Transaction($this->erachain_params);
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
        $base_request = new BaseRequest($this->erachain_params);

        return $base_request->request_api($request, $params, $method);
    }
}
