<?php

namespace Erachain;

use Erachain\API\OrderAPI;
use Erachain\Helpers\Error;
use Erachain\Request\OrderRequest;
use Erachain\Transaction\CancelOrder;
use Erachain\Transaction\CreateOrder;
use Exception;
use Sirius\Validation\Validator;

class Order
{
    private $erachain_params;
    private $validator;

    public function __construct($erachain_params)
    {
        $this->validator = new Validator;

        $this->erachain_params = $erachain_params;
    }

    /**
     * Создание ордера
     *
     * @param string $public_key Публичный ключ
     * @param string $private_key Приватный ключ
     * @param array $params Массив данных для создания ордера
     *  $params = [
     *      'have_asset' => (int) ключ актива, который хотим обменять,
     *      'want_asset' => (int) ключ актива, который хотим получить,
     *      'have_amount' => (string) кол-во актива которое хотим передать,
     *      'want_amount' => (string) кол-во актива которое хотим получить,
     *  ]
     *
     * @return array Возвращает ответ от ноды со статусом, сигнатурой и байт-кодом
     * @example /example/order/create_order.php
     */
    public function create($public_key = null, $private_key = null, array $params = [])
    {
        try {
            $asset   = new CreateOrder($public_key, $private_key, $this->erachain_params);
            $request = new OrderRequest($this->erachain_params);

            $this->validator
                ->add('have_asset', 'required | integer() (' . Error::INTEGER . ')')
                ->add('want_asset', 'required | integer() (' . Error::INTEGER . ')')
                ->add('have_amount',
                    'required | Erachain\Validation\Rule\StringRule | Erachain\Validation\Rule\AmountRule')
                ->add('want_amount',
                    'required | Erachain\Validation\Rule\StringRule | Erachain\Validation\Rule\AmountRule');

            if ( ! $this->validator->validate($params)) {
                Error::validate($this->validator->getMessages());
            }

            $data = $asset->get($params);

            return $request->broadcast($data);
        } catch (Exception $e) {
            return array(
                'DATA'   => $e->getMessage(),
                'STATUS' => 'ERROR'
            );
        }
    }

    /**
     * Отмена ордера
     *
     * @param string $public_key Публичный ключ
     * @param string $private_key Приватный ключ
     * @param array $params Массив данных для отмены ордера
     *  $params = [
     *      'signature' => (string) сигнатура ордера, который нужно отменить
     *  ]
     *
     * @return array Возвращает ответ от ноды со статусом, сигнатурой и байт-кодом
     * @example /example/order/cancel_order.php
     */
    public function cancel($public_key = null, $private_key = null, array $params = [])
    {
        try {
            $asset   = new CancelOrder($public_key, $private_key, $this->erachain_params);
            $request = new OrderRequest($this->erachain_params);

            $this->validator
                ->add('signature', 'required | Erachain\Validation\Rule\Base58Rule');

            if ( ! $this->validator->validate($params)) {
                Error::validate($this->validator->getMessages());
            }

            $data = $asset->get($params);

            return $request->broadcast($data);
        } catch (Exception $e) {
            return array(
                'DATA'   => $e->getMessage(),
                'STATUS' => 'ERROR'
            );
        }
    }

    /**
     * Обращение к ноде по API для получения данных по бирже
     *
     * @param string $method Название запроса. Доступные запросы:
     *  [
     *      'order' => Получаем ордер по номеру блока с последовательностью или сигнатуре
     *      'ordersbook' => Получаем ордера по ключу передаваемого и получаемого актива
     *      'ordersbyaddress' => Получаем списка ордеров по адресу создателя
     *      'completedordersfrom' => Получаем завершенные ордера
     *      'allordersbyaddress' => Получаем все ордера по адресу
     *      'trades' => Получить сделки по временной метке
     *      'tradesfrom' => Получаем сделки по ключу передаваемого и получаемого актива
     *      'volume24' => Получаем сделки по ключу передаваемого и получаемого актива за последние 24 часа
     *      ... => все запросы из ->transaction->api()
     *  ]
     * @param array $params Массив параметров для запроса
     *
     * @return array|bool Возвращает ответ от ноды по запросу
     * @example /example/order/order_api.php
     */
    public function api($method = null, array $params = [])
    {
        try {
            $api     = new OrderAPI();
            $request = new OrderRequest($this->erachain_params);

            switch ($method):
                case 'order':
                    $result = $api->apiexchange_order($request, $params);
                    break;
                case 'ordersbook':
                    $result = $api->apiexchange_ordersbook($request, $params);
                    break;
                case 'ordersbyaddress':
                    $result = $api->apiexchange_ordersbyaddress($request, $params);
                    break;
                case 'completedordersfrom':
                    $result = $api->apiexchange_completedordersfrom($request, $params);
                    break;
                case 'allordersbyaddress':
                    $result = $api->apiexchange_allordersbyaddress($request, $params);
                    break;
                case 'trades':
                    $result = $api->apiexchange_trades($request, $params);
                    break;
                case 'tradesfrom':
                    $result = $api->apiexchange_tradesfrom($request, $params);
                    break;
                case 'volume24':
                    $result = $api->apiexchange_volume24($request, $params);
                    break;
                default:
                    $transaction = new Transaction($this->erachain_params);

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