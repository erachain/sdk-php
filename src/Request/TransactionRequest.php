<?php

namespace Erachain\Request;

use Erachain\Helpers\Request;

class TransactionRequest
{
    protected $request;

    public function __construct($erachain_params)
    {
        $this->request = new Request($erachain_params);
    }

    /**
     * Отправка транзакции
     *
     * @param array $data Массив данных для транзакции
     *  $data = [
     *      'raw' => (string) Байт код транзакции,
     *      'signature' => (string) Сигнатура транзакции
     * ]
     *
     * @param bool $telegram Отправлять как телеграму
     *
     * @return array|bool Возвращает ответ от ноды
     */
    public function broadcast(array $data, $telegram = false)
    {
        $url = '/api/broadcast/';

        if ($telegram) {
            $url = '/api/broadcasttelegram/';
        }

        $result = $this->request->send(
            $url,
            $data['raw'],
            'post'
        );

        if ($status = $this->request->is_json($result['DATA'], true)) {
            if ( ! empty($status['status']) && $status['status'] == 'ok') {
                $result['DATA']   = $data;
                $result['STATUS'] = $this->request->get_status_ok();
            } else {
                $result['STATUS'] = $this->request->get_status_error();
            }
        } else {
            $result['STATUS'] = $this->request->get_status_error();
        }

        return $result;
    }

    /**
     * Получаем высоту последнего блока
     *
     * @return array|bool Возвращает ответ от ноды с высотой последнего блока
     */
    public function height()
    {
        return $this->request->send(
            '/api/height',
            false,
            'get'
        );
    }

    /**
     * Получаем информацию транзакции по сигнатуре
     *
     * @param string $signature Сигнатура транзакции
     *
     * @return array|bool Возвращает ответ от ноды с информацией по транзакции
     */
    public function record($signature)
    {
        return $this->request->send(
            '/api/record/' . $signature,
            false,
            'get'
        );
    }

    /**
     * Получаем транзакцию по номеру блока с номером sequence
     *
     * @param string $height_seq Номер блока с sequence (напр: 666324-1)
     *
     * @return array|bool Возвращает ответ от ноды с информацией по транзакции
     */
    public function getbynumber($height_seq)
    {
        return $this->request->send(
            '/apirecords/getbynumber/' . $height_seq,
            false,
            'get'
        );
    }

    /**
     * Получаем байт код по номеру блока и номеру sequence
     *
     * @param int $block Номер блока (напр: 666324)
     * @param int $seq_no Номер sequence (напр: 1)
     *
     * @return array|bool Возвращает ответ от ноды с байт кодом транзакции
     */
    public function recordrawbynumber($block, $seq_no)
    {
        return $this->request->send(
            '/api/recordrawbynumber/' . $block . '/' . $seq_no,
            false,
            'get'
        );
    }

    /**
     * Поиск транзаций по адресу получателя и номеру блока
     *
     * @param string $address Адрес получателя
     * @param int $start_block С какого блока искать
     *
     * @param array $param
     *  $param = [
     *      'type' => (int) тип транзакции // TODO error: выкидывает 404 при любом типе
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с найденными транзакциями
     */
    public function incomingfromblock($address, $start_block, array $param = [])
    {
        return $this->request->send(
            '/apirecords/incomingfromblock/' . $address . '/' . $start_block,
            $param,
            'get'
        );
    }

    /**
     * Поиск транзаций по адресу
     *
     * @param array $param Параметры для поиска
     *  $param = [
     *      'address' => (string) адрес,
     *      'asset' => (int) номер актива,
     *      'recordType' => (string) тип записи (напр: SEND),
     *      'unconfirmed' => (bool) неподтвержденные транзакции // TODO error: при true выдаёт 500 error
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с найденными транзакциями
     */
    public function getbyaddress(array $param = [])
    {
        return $this->request->send(
            '/apirecords/getbyaddress',
            $param,
            'get'
        );
    }

    /**
     * Поиск транзаций по заданным параметрам
     *
     * @param array $param Параметры для поиска
     *  $param = [
     *      'address' => (string) адрес,
     *      'sender' => (string) адрес отправителя,
     *      'recipient' => (string) адрес получателя,
     *      'startblock' => (int) с какого блока искать,
     *      'endblock' => (int) по какой блок искать,
     *      'type' => (int) тип транзакции,
     *      'desc' => (bool) сортировка от последнего к первому, // TODO error: не меняет сортировку
     *      'offset' => (int) сдвиг,
     *      'sizeElements' => (int) лимит кол-ва транзакций, // TODO error: всегда выводит одинаковое кол-во
     *      'unconfirmed' => (bool) неподтвержденные транзакции // TODO error: при true выдаёт 500 error
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с найденными транзакциями
     */
    public function find(array $param = [])
    {
        return $this->request->send(
            '/apirecords/find',
            $param,
            'get'
        );
    }
}
