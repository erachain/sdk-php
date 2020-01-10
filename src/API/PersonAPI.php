<?php

namespace Erachain\API;

use Erachain\Helpers\Error;
use Erachain\Request\PersonRequest;
use Sirius\Validation\Validator;

class PersonAPI
{
    private $validator;

    public function __construct()
    {
        $this->validator = new Validator;
    }

    /**
     * Получаем ключ персоны по публичному ключу создателя персоны
     *
     * @param PersonRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'public_key' => (string) публичный ключ создателя персоны
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с ключем персоны
     */
    public function api_personkeybyownerpublickey(PersonRequest $request, array $params)
    {
        $this->validator
            ->add('public_key', 'required | Erachain\Validation\Rule\Base58Rule');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->personkeybyownerpublickey($params['public_key']);
    }

    /**
     * Получаем высоту цепочки персон
     *
     * @param PersonRequest $request Объект с реализацией API запросов к ноде
     *
     * @return array|bool Возвращает ответ от ноды с высотой цепочки
     */
    public function api_personheight(PersonRequest $request)
    {
        return $request->personheight();
    }

    /**
     * Получаем данные персоны по ключу персоны
     *
     * @param PersonRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'person_key' => (int) ключ персоны
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с данными по персоне
     */
    public function person(PersonRequest $request, array $params)
    {
        $this->validator
            ->add('person_key', 'required | integer() (' . Error::INTEGER . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->person($params['person_key']);
    }

    /**
     * Получаем иконку и изображение персоны по ключу персоны
     *
     * @param PersonRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'person_key' => (int) ключ персоны
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с иконкой и изображением персоны
     */
    public function api_persondata(PersonRequest $request, array $params)
    {
        $this->validator
            ->add('person_key', 'required | integer() (' . Error::INTEGER . ')');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->persondata($params['person_key']);
    }

    /**
     * Получаем ключ персоны по адресу (счёту)
     *
     * @param PersonRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'address' => (string) адрес (счёт)
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с ключем персоны
     */
    public function api_personkeybyaddress(PersonRequest $request, array $params)
    {
        $this->validator
            ->add('address', 'required | Erachain\Validation\Rule\Base58Rule');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->personkeybyaddress($params['address']);
    }

    /**
     * Получаем данные персоны по адресу (счёту)
     *
     * @param PersonRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'address' => (string) адрес (счёт)
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с данными персоны
     */
    public function api_personbyaddress(PersonRequest $request, array $params)
    {
        $this->validator
            ->add('address', 'required | Erachain\Validation\Rule\Base58Rule');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->personbyaddress($params['address']);
    }

    /**
     * Получаем ключ персоны по публичному ключу
     *
     * @param PersonRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'public_key' => (string) публичный ключ
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с ключем персоны
     */
    public function api_personkeybypublickey(PersonRequest $request, array $params)
    {
        $this->validator
            ->add('public_key', 'required | Erachain\Validation\Rule\Base58Rule');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->personkeybypublickey($params['public_key']);
    }

    /**
     * Получаем данные персоны по публичному ключу
     *
     * @param PersonRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'public_key' => (string) публичный ключ
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с данными персоны
     */
    public function api_personbypublickey(PersonRequest $request, array $params)
    {
        $this->validator
            ->add('public_key', 'required | Erachain\Validation\Rule\Base58Rule');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->personbypublickey($params['public_key']);
    }

    /**
     * Получаем данные персон по имени персоны (полному/частичному)
     *
     * @param PersonRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'filter' => (string) имя персоны (полное/частичное)
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с данными персон
     */
    public function api_personsfilter(PersonRequest $request, array $params)
    {
        $this->validator
            ->add('filter', 'required | Erachain\Validation\Rule\StringRule');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->personsfilter($params['filter']);
    }
}