<?php

namespace Erachain\API;

use Erachain\Helpers\Error;
use Erachain\Request\TransactionRequest;
use InvalidArgumentException;
use Sirius\Validation\Validator;

class TransactionAPI
{
    private $validator;

    public function __construct()
    {
        $this->validator = new Validator;
    }

    /**
     * Получаем высоту последнего блока
     *
     * @param TransactionRequest $request Объект с реализацией API запросов к ноде
     *
     * @return array|bool Возвращает ответ от ноды с высотой последнего блока
     */
    public function api_height(TransactionRequest $request)
    {
        return $request->height();
    }

    /**
     * Получаем информацию транзакции по сигнатуре
     *
     * @param TransactionRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'signature' => (string) сигнатура транзакции
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с информацией по транзакции
     */
    public function api_record(TransactionRequest $request, array $params)
    {
        $this->validator
            ->add('signature', 'required | Erachain\Validation\Rule\Base58Rule');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->record($params['signature']);
    }

    /**
     * Получаем байт код по номеру блока и номеру sequence
     *
     * @param TransactionRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'block' => (int) номер блока,
     *      'seq_no' => (int) номер sequence
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с байт кодом транзакции
     */
    public function api_recordrawbynumber(TransactionRequest $request, array $params)
    {
        $this->validator
            ->add('block', 'required | integer() (' . Error::INTEGER . ')')
            ->add('seq_no', 'required | integer() (' . Error::INTEGER . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->recordrawbynumber($params['block'], $params['seq_no']);
    }

    /**
     * Получаем транзакцию по номеру блока с номером sequence
     *
     * @param TransactionRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'height_seq' => (string) номер блока с sequence (напр: 666324-1)
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с информацией по транзакции
     */
    public function apirecords_getbynumber(TransactionRequest $request, array $params)
    {
        $this->validator
            ->add('height_seq', 'required | regex(/^\d*\-\d*$/i) (' . Error::HEIGHT_SEQ . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->getbynumber($params['height_seq']);
    }

    /**
     * Поиск транзаций по адресу получателя и номеру блока
     *
     * Используется для перебора блоков, с целью найти поступившие транзакции после start_block
     *
     * @param TransactionRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'address' => (string) адрес получателя,
     *      'start_block' => (int) блок по которому ищем,
     *      'get' => [ (array) массив доп параметров для поиска транзакций
     *          'type' => (string) тип записи
     *      ]
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с найденными транзакциями
     */
    public function apirecords_incomingfromblock(TransactionRequest $request, array $params)
    {
        $get = [];

        if ( ! empty($params['get'])) {

            if ( ! is_array($params['get'])) {
                throw new InvalidArgumentException(Error::ARRAY_GET);
            }

            $get = $params['get'];
        }

        $this->validator
            ->add('address', 'required | Erachain\Validation\Rule\Base58Rule')
            ->add('start_block', 'required | integer() (' . Error::INTEGER . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->incomingfromblock($params['address'], $params['start_block'], $get);
    }

    /**
     * Поиск транзаций по адресу
     *
     * @param TransactionRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'get' => [ (array) массив доп параметров для поиска транзакций
     *          'address' => (string) адрес,
     *          'asset' => (int) ключ актива,
     *          'recordType' => (string) тип записи (напр: SEND)
     *      ]
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с найденными транзакциями
     */
    public function apirecords_getbyaddress(TransactionRequest $request, array $params)
    {
        $this->validator
            ->add('get[address]', 'required | Erachain\Validation\Rule\Base58Rule')
            ->add('get[asset]', 'integer() (' . Error::INTEGER . ')')
            ->add('get[recordType]', 'Erachain\Validation\Rule\StringRule');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->getbyaddress($params['get']);
    }

    /**
     * Поиск транзаций по заданным параметрам
     *
     * @param TransactionRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'get' => [ (array) массив доп параметров для поиска транзакций
     *          'address' => (string) адрес,
     *          'sender' => (string) адрес отправителя,
     *          'recipient' => (string) адрес получателя,
     *          'startblock' => (int) с какого блока искать,
     *          'endblock' => (int) по какой блок искать,
     *          'type' => (int) тип транзакции,
     *          'offset' => (int) сдвиг
     *      ]
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с найденными транзакциями
     */
    public function apirecords_find(TransactionRequest $request, array $params)
    {
        $get = [];

        if ( ! empty($params['get'])) {

            if ( ! is_array($params['get'])) {
                throw new InvalidArgumentException(Error::ARRAY_GET);
            }

            $get = $params['get'];
        }

        $this->validator
            ->add('get[address]', 'Erachain\Validation\Rule\Base58Rule')
            ->add('get[sender]', 'Erachain\Validation\Rule\Base58Rule')
            ->add('get[recipient]', 'Erachain\Validation\Rule\Base58Rule')
            ->add('get[startblock]', 'integer() (' . Error::INTEGER . ')')
            ->add('get[endblock]', 'integer() (' . Error::INTEGER . ')')
            ->add('get[type]', 'integer() (' . Error::INTEGER . ')')
            ->add('get[offset]', 'integer() (' . Error::INTEGER . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->find($get);
    }
}