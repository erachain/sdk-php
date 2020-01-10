<?php

namespace Erachain\Request;

class AssetRequest extends TransactionRequest
{
    /**
     * Получаем список активов и остаток на счёте по адресу
     *
     * @param string $address Адресс на котором ищем активы
     *
     * @return array|bool Возвращает ответ от ноды с списком активов и остатков
     */
    public function addressassets($address)
    {
        return $this->request->send(
            '/api/addressassets/' . $address,
            false,
            'get'
        );
    }

    /**
     * Получаем остаток актива по адресу владельца и ключу актива
     *
     * @param string $address Адресс владельца актива
     * @param int $asset_key Ключ актива
     *
     * @return array|bool Возвращает ответ от ноды с остатком по активу
     */
    public function addressassetbalance($address, $asset_key)
    {
        return $this->request->send(
            '/api/addressassetbalance/' . $address . '/' . $asset_key,
            false,
            'get'
        );
    }

    /**
     * Получаем список всех активов в сети Erachain
     *
     * @return array|bool Возвращает ответ от ноды с списком всех активов
     */
    public function assets()
    {
        return $this->request->send(
            '/api/assets',
            false,
            'get'
        );
    }

    /**
     * Получаем информацию об активе по ключу актива
     *
     * @param int $asset_key Ключ актива
     *
     * @return array|bool Возвращает ответ от ноды с информацией по активу
     */
    public function asset($asset_key)
    {
        return $this->request->send(
            '/api/asset/' . $asset_key,
            false,
            'get'
        );
    }

    /**
     * Получаем иконку актива по ключу актива
     *
     * @param int $asset_key Ключ актива
     *
     * @return array|bool Возвращает ответ от ноды с иконкой актива
     */
    public function asseticon($asset_key)
    {
        return $this->request->send(
            '/api/asseticon/' . $asset_key,
            false,
            'get'
        );
    }

    /**
     * Получаем изображение актива по ключу актива
     *
     * @param int $asset_key Ключ актива
     *
     * @return array|bool Возвращает ответ от ноды с изображением актива
     */
    public function assetimage($asset_key)
    {
        return $this->request->send(
            '/api/assetimage/' . $asset_key,
            false,
            'get'
        );
    }

    /**
     * Получаем высоту последнего добавленного актива
     *
     * @return array|bool Возвращает ответ от ноды с высотой последнего актива
     */
    public function assetheight()
    {
        return $this->request->send(
            '/api/assetheight',
            false,
            'get'
        );
    }

    /**
     * Получаем иконку и изображение по ключу актива
     *
     * @param int $asset_key Ключ актива
     *
     * @return array|bool Возвращает ответ от ноды с иконкой и изображением
     */
    public function assetdata($asset_key)
    {
        return $this->request->send(
            '/api/assetdata/' . $asset_key,
            false,
            'get'
        );
    }

    /**
     * Получаем данные активов по названию актива (полному/частичному)
     *
     * @param string $filter Название актива (полное/частичное)
     *
     * @return array|bool Возвращает ответ от ноды с данными активов
     */
    public function assetsfilter($filter)
    {
        return $this->request->send(
            '/api/assetsfilter/' . $filter,
            false,
            'get'
        );
    }
}
