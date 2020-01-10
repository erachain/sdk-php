<?php

namespace Erachain;

use Erachain\API\TransactionAPI;
use Erachain\Helpers\Error;
use Erachain\Request\MessageRequest;
use Erachain\Transaction\SendMessage;
use Exception;
use Sirius\Validation\Validator;

class Message
{
    private $erachain_mode;
    private $validator;

    public function __construct($erachain_mode)
    {
        $this->validator = new Validator;

        $this->erachain_mode = $erachain_mode;
    }

    /**
     * Отправка сообщения
     *
     * @param string $public_key Публичный ключ
     * @param string $private_key Приватный ключ
     * @param array $params Массив данных для отправки сообщения
     *  $params = [
     *      'recipient' => (string) адресс получателя,
     *      'head' => (string) заголовок сообщения,
     *      'message' => (string) сообщение,
     *      'encrypted' => (int) шифрование сообщения (0|1, не обязательно),
     *      'is_text' => (int) сообщение является текстом (0|1, не обязательно)
     *  ]
     *
     * @param bool $telegram Является телеграмой или нет
     *
     * @return array Возвращает ответ от ноды со статусом, сигнатурой и байт-кодом
     * @example /example/message/send_message.php
     */
    public function send($public_key = null, $private_key = null, array $params = [], $telegram = false)
    {
        try {
            $message = new SendMessage($public_key, $private_key, $this->erachain_mode);
            $request = new MessageRequest($this->erachain_mode);

            $this->validator
                ->add('recipient', 'required | Erachain\Validation\Rule\Base58Rule')
                ->add('head', 'required | Erachain\Validation\Rule\StringRule')
                ->add('message', 'required | Erachain\Validation\Rule\StringRule')
                ->add('encrypted', 'integer() (' . Error::INTEGER . ')')
                ->add('is_text', 'integer() (' . Error::INTEGER . ')');

            if ( ! $this->validator->validate($params)) {
                Error::validate($this->validator->getMessages());
            }

            $data = $message->get($params);

            return $request->broadcast($data, $telegram);
        } catch (Exception $e) {
            return array(
                'DATA'   => $e->getMessage(),
                'STATUS' => 'ERROR'
            );
        }
    }

    /**
     * Обращение к ноде по API для получения данных по сообщениям
     *
     * @param string $method Название запроса. Доступные запросы:
     *  [
     *      'getbyaddress' => Получаем сообщения по адресу
     *      ... => все запросы из ->transaction_api()
     *  ]
     * @param array $params Массив параметров для запроса
     *
     * @return array|bool Возвращает ответ от ноды по запросу
     * @example /example/message/message_api.php
     */
    public function api($method = null, array $params = [])
    {
        try {
            $api     = new TransactionAPI();
            $request = new MessageRequest($this->erachain_mode);

            switch ($method):
                case 'getbyaddress':
                    $result = $api->apirecords_getbyaddress($request, $params);
                    break;
                default:
                    $transaction = new Transaction($this->erachain_mode);

                    $result = $transaction->api($method, $params);
            endswitch;

            return $result;
        } catch (Exception $e) {
            return array(
                'DATA'   => $e->getMessage(),
                'STATUS' => 'ERROR'
            );
        }
    }
}