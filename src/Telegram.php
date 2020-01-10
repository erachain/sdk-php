<?php

namespace Erachain;

use Erachain\API\TelegramAPI;
use Erachain\Helpers\Error;
use Erachain\Request\TelegramRequest;
use Exception;
use Sirius\Validation\Validator;
use UnexpectedValueException;

class Telegram
{
    private $erachain_params;
    private $validator;

    public function __construct($erachain_params)
    {
        $this->validator = new Validator;

        $this->erachain_params = $erachain_params;
    }

    /**
     * Отправка телеграммы
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
     * @return array Возвращает ответ от ноды со статусом, сигнатурой и байт-кодом
     * @example /example/telegram/send_telegram.php
     */
    public function send($public_key = null, $private_key = null, array $params = [])
    {
        $message = new Message($this->erachain_params);

        return $message->send($public_key, $private_key, $params, true);
    }

    /**
     * Обращение к ноде по API для получения данных по телеграмам
     *
     * @param string $method Название запроса. Доступные запросы:
     *  [
     *      'getbysignature' => Получаем телеграму по сигнатуре
     *      'get' => Получаем список телеграм по адресу получателя и фильтру
     *      'timestamp' => Получаем список телеграм по стартовой временной метке и заголовку
     *      'check' => Проверяем наличае телеграмы по сигнатуре
     *      ... => все запросы из ->transaction_api()
     *  ]
     * @param array $params Массив параметров для запроса
     *
     * @return array|bool Возвращает ответ от ноды по запросу
     * @example /example/message/telegram_api.php
     */
    public function api($method = null, array $params = [])
    {
        try {
            $api     = new TelegramAPI();
            $request = new TelegramRequest($this->erachain_params);

            switch ($method):
                case 'getbysignature':
                    $result = $api->apitelegrams_getbysignature($request, $params);
                    break;
                case 'get':
                    $result = $api->apitelegrams_get($request, $params);
                    break;
                case 'timestamp':
                    $result = $api->apitelegrams_timestamp($request, $params);
                    break;
                case 'check':
                    $result = $api->apitelegrams_check($request, $params);
                    break;
                default:
                    throw new UnexpectedValueException(Error::INVALID_REQUEST);
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