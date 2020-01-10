<?php

namespace Erachain\Request;

class PersonRequest extends TransactionRequest
{
    /**
     * Получаем ключ персоны по публичному ключу создателя персоны
     *
     * @param string $owner_public_key Публичный ключ создателя персоны
     *
     * @return array|bool Возвращает ответ от ноды с ключем персоны
     */
    public function personkeybyownerpublickey($owner_public_key)
    {
        return $this->request->send(
            '/api/personkeybyownerpublickey/' . $owner_public_key,
            false,
            'get'
        );
    }

    /**
     * Получаем высоту цепочки персон
     *
     * @return array|bool Возвращает ответ от ноды с высотой цепочки
     */
    public function personheight()
    {
        return $this->request->send(
            '/api/personheight/',
            false,
            'get'
        );
    }

    /**
     * Получаем данные персоны по ключу персоны
     *
     * @param int $person_key Ключ персоны
     *
     * @return array|bool Возвращает ответ от ноды с данными по персоне
     */
    public function person($person_key)
    {
        return $this->request->send(
            '/api/person/' . $person_key,
            false,
            'get'
        );
    }

    /**
     * Получаем иконку и изображение персоны по ключу персоны
     *
     * @param int $person_key Ключ персоны
     *
     * @return array|bool Возвращает ответ от ноды с иконкой и изображением персоны
     */
    public function persondata($person_key)
    {
        return $this->request->send(
            '/api/persondata/' . $person_key,
            false,
            'get'
        );
    }

    /**
     * Получаем ключ персоны по адресу (счёту)
     *
     * @param string $address Адрес (счёт)
     *
     * @return array|bool Возвращает ответ от ноды с ключем персоны
     */
    public function personkeybyaddress($address)
    {
        return $this->request->send(
            '/api/personkeybyaddress/' . $address,
            false,
            'get'
        );
    }

    /**
     * Получаем данные персоны по адресу (счёту)
     *
     * @param string $address Адрес (счёт)
     *
     * @return array|bool Возвращает ответ от ноды с данными персоны
     */
    public function personbyaddress($address)
    {
        return $this->request->send(
            '/api/personbyaddress/' . $address,
            false,
            'get'
        );
    }

    /**
     * Получаем ключ персоны по публичному ключу
     *
     * @param string $public_key Публичный ключ
     *
     * @return array|bool Возвращает ответ от ноды с ключем персоны
     */
    public function personkeybypublickey($public_key)
    {
        return $this->request->send(
            '/api/personkeybypublickey/' . $public_key,
            false,
            'get'
        );
    }

    /**
     * Получаем данные персоны по публичному ключу
     *
     * @param string $public_key Публичный ключ
     *
     * @return array|bool Возвращает ответ от ноды с данными персоны
     */
    public function personbypublickey($public_key)
    {
        return $this->request->send(
            '/api/personbypublickey/' . $public_key,
            false,
            'get'
        );
    }

    /**
     * Получаем данные персон по имени персоны (полному/частичному)
     *
     * @param string $filter Имя персоны (полное/частичное)
     *
     * @return array|bool Возвращает ответ от ноды с данными персон
     */
    public function personsfilter($filter)
    {
        return $this->request->send(
            '/api/personsfilter/' . $filter,
            false,
            'get'
        );
    }
}