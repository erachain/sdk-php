<?php

namespace Erachain\Request;

class OrderRequest extends TransactionRequest
{
    /**
     * Получение ордера по блоку с последовательностью или сигнатуре
     *
     * @param string $param Номер блока с последовательностью или сигнатура
     *
     * @return array|bool Возвращает ответ от ноды с ордером
     */
    public function order($param)
    {
        return $this->request->send(
            '/apiexchange/order/' . $param,
            false,
            'get'
        );
    }

    /**
     * Получение списка ордеров по ключам выставляемого и получаемого активов
     *
     * @param int $have Ключ выставляемого актива
     * @param int $want Ключ получаемого актива
     * @param array $param Параметры запроса
     *  $param = [
     *      'limit' => (int) ограничение кол-ва выводимых ордеров (не обязательно, по умолчанию - 20)
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с списком ордеров
     */
    public function ordersbook($have, $want, array $param = [])
    {
        return $this->request->send(
            '/apiexchange/ordersbook/' . $have . '/' . $want,
            $param,
            'get'
        );
    }

    /**
     * Получение списка ордеров по адресу создателя
     *
     * @param string $address Адрес создателя ордеров
     * @param array $param Параметры запроса
     *  $param = [
     *      'limit' => (int) ограничение кол-ва выводимых ордеров (не обязательно, по умолчанию - 20, максимум - 200)
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с списком ордеров
     */
    public function ordersbyaddress($address, array $param = [])
    {
        return $this->request->send(
            '/apiexchange/ordersbyaddress/' . $address,
            $param,
            'get'
        );
    }

    /**
     * Получение списка ордеров по ключам выставляемого и получаемого активов
     *
     * @param int $have Ключ выставляемого актива
     * @param int $want Ключ получаемого актива
     * @param array $param Параметры запроса
     *  $param = [
     *      'order' => (int) id ордера или номер блока с последовательностью (не обязательно)
     *      'height' => (int) номер блока (не обязательно)
     *      'time' => (int) timestamp сделки (не обязательно)
     *      'limit' => (int) ограничение кол-ва выводимых ордеров (не обязательно, по умолчанию - 50, максимум - 200)
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с списком ордеров
     */
    public function completedordersfrom($have, $want, array $param = [])
    {
        return $this->request->send(
            '/apiexchange/completedordersfrom/' . $have . '/' . $want,
            $param,
            'get'
        );
    }

    /**
     * Получение всех ордеров по адресу
     *
     * @param string $address Адрес создателя ордера
     * @param int|string $param_order Номер ордера или номер блока с последовательностью
     * @param array $param Параметры запроса
     *  $param = [
     *      'limit' => (int) ограничение кол-ва выводимых сделок (не обязательно, по умолчанию - 50)
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с списком сделок
     */
    public function allordersbyaddress($address, $param_order, array $param = [])
    {
        return $this->request->send(
            '/apiexchange/allordersbyaddress/' . $address . '/' . $param_order,
            $param,
            'get'
        );
    }

    /**
     * Получение списка сделок по временной метке
     *
     * @param int $amount_asset_key Ключ выставляемого актива
     * @param int $price_asset_key Ключ получаемого актива
     * @param array $param Параметры запроса
     *  $param = [
     *      'timestamp' => (int) timestamp сделки
     *      'limit' => (int) ограничение кол-ва выводимых сделок (не обязательно, по умолчанию - 50)
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с списком сделок за последние 24 часа
     */
    public function trades($amount_asset_key, $price_asset_key, array $param = [])
    {
        return $this->request->send(
            '/apiexchange/trades/' . $amount_asset_key . '/' . $price_asset_key,
            $param,
            'get'
        );
    }

    /**
     * Получение списка сделок по ключам выставляемого и получаемого активов
     *
     * @param string $address Адрес создателя сделки
     * @param int $amount_asset_key Ключ выставляемого актива
     * @param int $price_asset_key Ключ получаемого актива
     * @param array $param Параметры запроса
     *  $param = [
     *      'timestamp' => (int) timestamp сделки
     *      'limit' => (int) ограничение кол-ва выводимых сделок (не обязательно, по умолчанию - 50)
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с списком сделок
     */
    public function tradesfrom(
        $address = null,
        $amount_asset_key = null,
        $price_asset_key = null,
        array $param = []
    ) {
        $url = '/apiexchange/tradesfrom';

        if ( ! empty($address)) {
            $url .= '/' . $address;
        }

        if ( ! empty($amount_asset_key)) {
            $url .= '/' . $amount_asset_key;
        }

        if ( ! empty($price_asset_key)) {
            $url .= '/' . $price_asset_key;
        }

        return $this->request->send(
            $url,
            $param,
            'get'
        );
    }

    /**
     * Получение списка сделок по ключам выставляемого и получаемого активов за последние 24 часа
     *
     * @param int $have Ключ выставляемого актива
     * @param int $want Ключ получаемого актива
     *
     * @return array|bool Возвращает ответ от ноды с списком сделок за последние 24 часа
     */
    public function volume24($have, $want)
    {
        return $this->request->send(
            '/apiexchange/volume24/' . $have . '/' . $want,
            false,
            'get'
        );
    }
}
