<?php

namespace Erachain\API;

use Erachain\Helpers\Error;
use Erachain\Request\AssetRequest;
use Sirius\Validation\Validator;

class AssetAPI
{
    private $validator;

    public function __construct()
    {
        $this->validator = new Validator;
    }

    /**
     * Получаем список активов и остаток на счёте по адресу
     *
     * @param AssetRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'address' => (string) адресс на котором ищем активы
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с списком активов и остатков
     */
    public function api_addressassets(AssetRequest $request, array $params)
    {
        $this->validator
            ->add('address', 'required | Erachain\Validation\Rule\Base58Rule');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->addressassets($params['address']);
    }

    /**
     * Получаем остаток актива по адресу владельца и ключу актива
     *
     * @param AssetRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'address' => (string) адресс владельца актива,
     *      'asset_key' => (int) ключ актива
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с остатком по активу
     */
    public function api_addressassetbalance(AssetRequest $request, array $params)
    {
        $this->validator
            ->add('address', 'required | Erachain\Validation\Rule\Base58Rule')
            ->add('asset_key', 'required | integer() (' . Error::INTEGER . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->addressassetbalance($params['address'], $params['asset_key']);
    }

    /**
     * Получаем список всех активов в сети Erachain
     *
     * @param AssetRequest $request Объект с реализацией API запросов к ноде
     *
     * @return array|bool Возвращает ответ от ноды с списком всех активов
     */
    public function api_assets(AssetRequest $request)
    {
        return $request->assets();
    }

    /**
     * Получаем информацию об активе по ключу актива
     *
     * @param AssetRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'asset_key' => (int) ключ актива
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с информацией по активу
     */
    public function api_asset(AssetRequest $request, array $params)
    {
        $this->validator
            ->add('asset_key', 'required | integer() (' . Error::INTEGER . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->asset($params['asset_key']);
    }

    /**
     * Получаем иконку актива по ключу актива
     *
     * @param AssetRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'asset_key' => (int) ключ актива
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с иконкой актива
     */
    public function api_asseticon(AssetRequest $request, array $params)
    {
        $this->validator
            ->add('asset_key', 'required | integer() (' . Error::INTEGER . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->asseticon($params['asset_key']);
    }

    /**
     * Получаем изображение актива по ключу актива
     *
     * @param AssetRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'asset_key' => (int) ключ актива
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с изображением актива
     */
    public function api_assetimage(AssetRequest $request, array $params)
    {
        $this->validator
            ->add('asset_key', 'required | integer() (' . Error::INTEGER . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->assetimage($params['asset_key']);
    }

    /**
     * Получаем высоту последнего добавленного актива
     *
     * @param AssetRequest $request Объект с реализацией API запросов к ноде
     *
     * @return array|bool Возвращает ответ от ноды с высотой последнего актива
     */
    public function api_assetheight(AssetRequest $request)
    {
        return $request->assetheight();
    }

    /**
     * Получаем иконку и изображение по ключу актива
     *
     * @param AssetRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'asset_key' => (int) ключ актива
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с иконкой и изображением
     */
    public function api_assetdata(AssetRequest $request, array $params)
    {
        $this->validator
            ->add('asset_key', 'required | integer() (' . Error::INTEGER . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->assetdata($params['asset_key']);
    }

    /**
     * Получаем данные активов по названию актива (полному/частичному)
     *
     * @param AssetRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'filter' => (string) название актива (полное/частичное)
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с данными активов
     */
    public function api_assetsfilter(AssetRequest $request, array $params)
    {
        $this->validator
            ->add('filter', 'required | Erachain\Validation\Rule\StringRule');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->assetsfilter($params['filter']);
    }
}