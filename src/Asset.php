<?php

namespace Erachain;

use Erachain\API\AssetAPI;
use Erachain\Helpers\Error;
use Erachain\Request\AssetRequest;
use Erachain\Transaction\IssueAsset;
use Erachain\Transaction\SendAsset;
use Exception;
use Sirius\Validation\Validator;

class Asset
{
    private $erachain_mode;
    private $validator;

    public function __construct($erachain_mode)
    {
        $this->validator = new Validator;

        $this->erachain_mode = $erachain_mode;
    }

    /**
     * Создание актива
     *
     * @param string $public_key Публичный ключ
     * @param string $private_key Приватный ключ
     * @param array $params Массив данных для создания актива
     *  $params = [
     *      'owner' => (string) публичный ключ владельца актива (не обязательно, по умолчанию $public_key),
     *      'name' => (string) имя актива,
     *      'description' => (string) описание актива,
     *      'icon' => (string) путь к иконке актива (не обязательно),
     *      'image' => (string) путь к изображению актива (не обязательно),
     *      'quantity' => (int) кол-во актива,
     *      'scale' => (int) знаков после запятой (испульзуется при передачи актива, если задан 0, то можно передать только целое кол-во актива),
     *      'asset_type' => (int) тип актива
     *  ]
     *
     * @return array Возвращает ответ от ноды со статусом, сигнатурой и байт-кодом
     * @example /example/asset/issue_asset.php
     */
    public function issue($public_key = null, $private_key = null, array $params = array())
    {
        try {
            $asset   = new IssueAsset($public_key, $private_key, $this->erachain_mode);
            $request = new AssetRequest($this->erachain_mode);

            $this->validator
                ->add('owner', 'Erachain\Validation\Rule\Base58Rule')
                ->add('name', 'required | Erachain\Validation\Rule\StringRule')
                ->add('description', 'required | Erachain\Validation\Rule\StringRule')
                ->add('icon', 'file\image(allowed=jpg,jpeg,png,gif) | file\size(size=10K)')
                ->add('image', 'file\image(allowed=jpg,jpeg,png,gif) | file\size(size=1M)')
                ->add('quantity', 'required | integer() (' . Error::INTEGER . ')')
                ->add('scale', 'required | integer() (' . Error::INTEGER . ')')
                ->add('asset_type', 'required | integer() (' . Error::INTEGER . ')');

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
     * Отправка актива
     *
     * @param string $public_key Публичный ключ
     * @param string $private_key Приватный ключ
     * @param array $params Массив данных для передачи актива
     *  $params = [
     *      'recipient' => (string) адресс получателя,
     *      'asset_key' => (int) ключ актива,
     *      'amount' => (string) кол-во передаваемого актива,
     *      'head' => (string) заголовок для отправки актива,
     *      'message' => (string) сообщение для отправки актива (не обязательно),
     *      'encrypted' => (int) шифрование сообщения актива (0|1, не обязательно),
     *      'is_text' => (int) сообщение является текстом (0|1, не обязательно)
     *  ]
     *
     * @return array Возвращает ответ от ноды со статусом, сигнатурой и байт-кодом
     * @example /example/asset/send_asset.php
     */
    public function send($public_key = null, $private_key = null, array $params = array())
    {
        try {
            $asset   = new SendAsset($public_key, $private_key, $this->erachain_mode);
            $request = new AssetRequest($this->erachain_mode);

            $this->validator
                ->add('recipient', 'required | Erachain\Validation\Rule\Base58Rule')
                ->add('asset_key', 'required | integer() (' . Error::INTEGER . ')')
                ->add('amount', 'required | greaterthan(0) (' . Error::POSITIVE_NUMBER . ')')
                ->add('head', 'required | Erachain\Validation\Rule\StringRule')
                ->add('message', 'Erachain\Validation\Rule\StringRule')
                ->add('encrypted', 'integer() (' . Error::INTEGER . ')')
                ->add('is_text', 'integer() (' . Error::INTEGER . ')');

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
     * Обращение к ноде по API для получения данных по активу
     *
     * @param string $method Название запроса. Доступные запросы:
     *  [
     *      'addressassets' => Получаем список активов и остаток на счёте по адресу
     *      'addressassetbalance' => Получаем остаток актива по адресу владельца и ключу актива
     *      'assets' => Получаем список всех активов в сети Erachain
     *      'asset' => Получаем информацию об активе по ключу актива
     *      'asseticon' => Получаем иконку актива по ключу актива
     *      'assetimage' => Получаем изображение актива по ключу актива
     *      'assetdata' => Получаем иконку и изображение по ключу актива
     *      'assetsfilter' => Получаем данные активов по названию актива (полному/частичному)
     *      'assetheight' => Получаем высоту последнего добавленного актива
     *      ... => все запросы из ->transaction_api()
     *  ]
     * @param array $params Массив параметров для запроса
     *
     * @return array|bool Возвращает ответ от ноды по запросу
     * @example /example/asset/asset_api.php
     */
    public function api($method = null, array $params = array())
    {
        try {
            $api     = new AssetAPI();
            $request = new AssetRequest($this->erachain_mode);

            switch ($method):
                case 'addressassets':
                    $result = $api->api_addressassets($request, $params);
                    break;
                case 'addressassetbalance':
                    $result = $api->api_addressassetbalance($request, $params);
                    break;
                case 'assets':
                    $result = $api->api_assets($request);
                    break;
                case 'asset':
                    $result = $api->api_asset($request, $params);
                    break;
                case 'asseticon':
                    $result = $api->api_asseticon($request, $params);
                    break;
                case 'assetimage':
                    $result = $api->api_assetimage($request, $params);
                    break;
                case 'assetdata':
                    $result = $api->api_assetdata($request, $params);
                    break;
                case 'assetsfilter':
                    $result = $api->api_assetsfilter($request, $params);
                    break;
                case 'assetheight':
                    $result = $api->api_assetheight($request);
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