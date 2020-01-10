<?php

namespace Erachain;

use Erachain\API\TransactionAPI;
use Erachain\Helpers\Error;
use Erachain\Request\TransactionRequest;
use Exception;
use Sirius\Validation\Validator;
use UnexpectedValueException;

class Transaction
{
    private $erachain_mode;
    private $validator;

    public function __construct($erachain_mode)
    {
        $this->validator = new Validator;

        $this->erachain_mode = $erachain_mode;
    }

    /**
     * Обращение к ноде по API для получения данных по транзакциям
     *
     * @param string $method Название запроса. Доступные запросы:
     *  [
     *      'height' => Получаем высоту последнего блока
     *      'record' => Получаем информацию транзакции по сигнатуре
     *      'getbynumber' => Получаем транзакцию по номеру блока с номером sequence
     *      'recordrawbynumber' => Получаем байт код по номеру блока и номеру sequence
     *      'incomingfromblock' => Поиск транзаций по адресу получателя и номеру блока
     *      'getbyaddress' => Поиск транзаций по адресу
     *      'find' => Поиск транзаций по заданным параметрам
     *  ]
     * @param array $params Массив параметров для запроса
     *
     * @return array|bool Возвращает ответ от ноды по запросу
     * @example /example/transaction/transaction_api.php
     */
    public function api($method = null, array $params = [])
    {
        try {
            $api     = new TransactionAPI();
            $request = new TransactionRequest($this->erachain_mode);

            switch ($method):
                case 'height':
                    $result = $api->api_height($request);
                    break;
                case 'record':
                    $result = $api->api_record($request, $params);
                    break;
                case 'getbynumber':
                    $result = $api->apirecords_getbynumber($request, $params);
                    break;
                case 'recordrawbynumber':
                    $result = $api->api_recordrawbynumber($request, $params);
                    break;
                case 'incomingfromblock':
                    $result = $api->apirecords_incomingfromblock($request, $params);
                    break;
                case 'getbyaddress':
                    $result = $api->apirecords_getbyaddress($request, $params);
                    break;
                case 'find':
                    $result = $api->apirecords_find($request, $params);
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