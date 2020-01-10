<?php

namespace Erachain\API;

use Erachain\Helpers\Error;
use Erachain\Request\OrderRequest;
use InvalidArgumentException;
use Sirius\Validation\Validator;

class OrderAPI
{
    private $validator;

    public function __construct()
    {
        $this->validator = new Validator;
    }

    /**
     * Получение ордера по номеру блока с последовательностью или сигнатуре
     *
     * @param OrderRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'height_seq' => (string) номер блока с последовательностью,
     *      || 'signature' => (string) сигнатура ордера
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды ордером
     */
    public function apiexchange_order(OrderRequest $request, array $params)
    {
        $this->validator
            ->add('height_seq', 'regex(/^\d*\-\d*$/i) (' . Error::HEIGHT_SEQ . ')')
            ->add('signature', 'Erachain\Validation\Rule\Base58Rule');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        if ( ! empty($params['height_seq'])) {
            $param_order = $params['height_seq'];
        } elseif ( ! empty($params['signature'])) {
            $param_order = $params['signature'];
        } else {
            throw new InvalidArgumentException(Error::NO_PARAMS);
        }

        return $request->order($param_order);
    }

    /**
     * Получение списка ордеров по ключам выставляемого и получаемого активов
     *
     * @param OrderRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'have' => (int) ключ выставляемого актива,
     *      'want' => (int) ключ получаемого актива,
     *      'get' => [ (array) массив доп параметров для поиска ордеров
     *          'limit' => (int) ограничение кол-ва выводимых ордеров (не обязательно, по умолчанию - 20)
     *      ]
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с списком ордеров
     */
    public function apiexchange_ordersbook(OrderRequest $request, array $params)
    {
        $get = [];

        if ( ! empty($params['get'])) {

            if ( ! is_array($params['get'])) {
                throw new InvalidArgumentException(Error::ARRAY_GET);
            }

            $get = $params['get'];
        }

        $this->validator
            ->add('have', 'required | integer() (' . Error::INTEGER . ')')
            ->add('want', 'required | integer() (' . Error::INTEGER . ')')
            ->add('get[limit]', 'integer() (' . Error::INTEGER . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->ordersbook($params['have'], $params['want'], $get);
    }

    /**
     * Получение списка ордеров по адресу создателя
     *
     * @param OrderRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'address' => (string) адрес создателя ордера,
     *      'get' => [ (array) массив доп параметров для поиска ордеров
     *          'limit' => (int) ограничение кол-ва выводимых ордеров (не обязательно, по умолчанию - 20)
     *      ]
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с списком ордеров
     */
    public function apiexchange_ordersbyaddress(OrderRequest $request, array $params)
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
            ->add('get[limit]', 'integer() (' . Error::INTEGER . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->ordersbyaddress($params['address'], $get);
    }

    /**
     * Получение списка завершенных ордеров
     *
     * @param OrderRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'have' => (int) ключ выставляемого актива,
     *      'want' => (int) ключ получаемого актива,
     *      'get' => [ (array) массив доп параметров для поиска сделок
     *          'order' => (int) id ордера (не обязательно)
     *          || 'height_seq' => (string) номер блока с seq (не обязательно)
     *          'height' => (int) номер блока (не обязательно)
     *          'time' => (int) timestamp сделки (не обязательно)
     *          'limit' => (int) ограничение кол-ва выводимых сделок (не обязательно, по умолчанию - 50, максимум - 200)
     *      ]
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с списком сделок
     */
    public function apiexchange_completedordersfrom(OrderRequest $request, array $params)
    {
        $get = [];

        if ( ! empty($params['get'])) {

            if ( ! is_array($params['get'])) {
                throw new InvalidArgumentException(Error::ARRAY_GET);
            }

            $get = $params['get'];
        }

        $this->validator
            ->add('have', 'required | integer() (' . Error::INTEGER . ')')
            ->add('want', 'required | integer() (' . Error::INTEGER . ')')
            ->add('get[order]', 'integer() (' . Error::INTEGER . ')')
            ->add('get[height_seq]', 'regex(/^\d*\-\d*$/i) (' . Error::HEIGHT_SEQ . ')')
            ->add('get[height]', 'integer() (' . Error::INTEGER . ')')
            ->add('get[time]', 'integer() (' . Error::INTEGER . ')')
            ->add('get[limit]', 'integer() (' . Error::INTEGER . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        if ( ! empty($params['get']['height_seq']) && empty($params['get']['order'])) {
            $params['get']['order'] = $params['get']['height_seq'];
        }

        return $request->completedordersfrom($params['have'], $params['want'], $get);
    }

    /**
     * Получение всех ордеров по адресу
     *
     * @param OrderRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'address' => (string) адрес создателя ордера,
     *      'height_seq' => (string) номер блока с последовательностью,
     *      || 'order' => (string) номер ордера
     *      'get' => [ (array) массив доп параметров
     *          'limit' => (int) ограничение кол-ва выводимых сделок (не обязательно, по умолчанию - 50)
     *      ]
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды ордером
     */
    public function apiexchange_allordersbyaddress(OrderRequest $request, array $params)
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
            ->add('order', 'integer() (' . Error::INTEGER . ')')
            ->add('height_seq', 'regex(/^\d*\-\d*$/i) (' . Error::HEIGHT_SEQ . ')')
            ->add('get[limit]', 'integer() (' . Error::INTEGER . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        if ( ! empty($params['order'])) {
            $param_order = $params['order'];
        } elseif ( ! empty($params['height_seq'])) {
            $param_order = $params['height_seq'];
        } else {
            throw new InvalidArgumentException(Error::NO_PARAMS);
        }

        return $request->allordersbyaddress($params['address'], $param_order, $get);
    }


    /**
     * Получение сделок по временной метке
     *
     * @param OrderRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'amount_asset_key' => (int) ключ выставляемого актива,
     *      'price_asset_key' => (int) ключ получаемого актива,
     *      'get' => [ (array) массив доп параметров для поиска сделок
     *          'timestamp' => (int) timestamp сделки (не обязательно)
     *          'limit' => (int) ограничение кол-ва выводимых сделок (не обязательно, по умолчанию - 50, максимум - 200)
     *      ]
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с списком сделок
     */
    public function apiexchange_trades(OrderRequest $request, array $params)
    {
        $get = [];

        if ( ! empty($params['get'])) {

            if ( ! is_array($params['get'])) {
                throw new InvalidArgumentException(Error::ARRAY_GET);
            }

            $get = $params['get'];
        }

        $this->validator
            ->add('amount_asset_key', 'required | integer() (' . Error::INTEGER . ')')
            ->add('price_asset_key', 'required | integer() (' . Error::INTEGER . ')')
            ->add('get[timestamp]', 'integer() (' . Error::INTEGER . ')')
            ->add('get[limit]', 'integer() (' . Error::INTEGER . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->trades($params['amount_asset_key'], $params['price_asset_key'], $get);
    }

    /**
     * Получение списка сделок
     *
     * @param OrderRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *
     * @return array|bool Возвращает ответ от ноды с списком сделок
     */
    public function apiexchange_tradesfrom(OrderRequest $request, array $params)
    {
        $get = [];

        if ( ! empty($params['get'])) {

            if ( ! is_array($params['get'])) {
                throw new InvalidArgumentException(Error::ARRAY_GET);
            }

            $get = $params['get'];
        }

        $this->validator
            ->add('address', 'Erachain\Validation\Rule\Base58Rule')
            ->add('amount_asset_key', 'integer() (' . Error::INTEGER . ')')
            ->add('price_asset_key', 'integer() (' . Error::INTEGER . ')')
            ->add('get[trade]', 'integer() (' . Error::INTEGER . ')')
            ->add('get[order]', 'integer() (' . Error::INTEGER . ')')
            ->add('get[height_seq]', 'regex(/^\d*\-\d*$/i) (' . Error::HEIGHT_SEQ . ')')
            ->add('get[height]', 'integer() (' . Error::INTEGER . ')')
            ->add('get[time]', 'integer() (' . Error::INTEGER . ')')
            ->add('get[limit]', 'integer() (' . Error::INTEGER . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        if ( ! empty($params['get']['height_seq']) && empty($params['get']['order'])) {
            $params['get']['order'] = $params['get']['height_seq'];
        }

        if ( ! empty($params['address']) && ! empty($params['amount_asset_key']) && ! empty($params['price_asset_key'])) {
            return $request->tradesfrom($params['address'], $params['amount_asset_key'], $params['price_asset_key'],
                $get);
        } elseif ( ! empty($params['amount_asset_key']) && ! empty($params['price_asset_key'])) {
            return $request->tradesfrom(null, $params['amount_asset_key'], $params['price_asset_key'], $get);
        } else {
            return $request->tradesfrom(null, null, null, $get);
        }
    }

    /**
     * Получение списка сделок по ключам выставляемого и получаемого активов за последние 24 часа
     *
     * @param OrderRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'have' => (int) ключ выставляемого актива,
     *      'want' => (int) ключ получаемого актива
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с списком сделок за последние 24 часа
     */
    public function apiexchange_volume24(OrderRequest $request, array $params)
    {
        $this->validator
            ->add('have', 'required | integer() (' . Error::INTEGER . ')')
            ->add('want', 'required | integer() (' . Error::INTEGER . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->volume24($params['have'], $params['want']);
    }
}