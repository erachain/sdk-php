<?php

namespace Erachain\API;

use Erachain\Helpers\Error;
use Erachain\Request\TelegramRequest;
use InvalidArgumentException;
use Sirius\Validation\Validator;

class TelegramAPI
{
    private $validator;

    public function __construct()
    {
        $this->validator = new Validator;
    }

    /**
     * Получаем телеграму по сигнатуре
     *
     * @param TelegramRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'signature' => (string) сигнатура телеграмы
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с телеграмой
     */
    public function apitelegrams_getbysignature(TelegramRequest $request, array $params)
    {
        $this->validator
            ->add('signature', 'required | Erachain\Validation\Rule\Base58Rule');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->getbysignature($params['signature']);
    }

    /**
     * Получаем список телеграм по адресу получателя и фильтру
     *
     * @param TelegramRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'get' => [
     *          'address' => (string) адрес получателя телеграмы
     *          'filter' => (string) заголовок телеграмы
     *      ]
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с списком телеграм
     */
    public function apitelegrams_get(TelegramRequest $request, array $params)
    {
        $get = [];

        if ( ! empty($params['get'])) {

            if ( ! is_array($params['get'])) {
                throw new InvalidArgumentException(Error::ARRAY_GET);
            }

            $get = $params['get'];
        }

        $this->validator
            ->add('get[address]', 'required | Erachain\Validation\Rule\Base58Rule')
            ->add('get[timestamp]', 'integer() (' . Error::INTEGER . ')')
            ->add('get[filter]', 'Erachain\Validation\Rule\StringRule');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->get($get);
    }

    /**
     * Получаем список телеграм по стартовой временной метке и заголовку
     *
     * @param TelegramRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'timestamp' => (int) временная метка телеграмы
     *      'get' => [
     *          'filter' => (string) заголовок телеграмы
     *      ]
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с списком телеграм
     */
    public function apitelegrams_timestamp(TelegramRequest $request, array $params)
    {
        $get = [];

        if ( ! empty($params['get'])) {

            if ( ! is_array($params['get'])) {
                throw new InvalidArgumentException(Error::ARRAY_GET);
            }

            $get = $params['get'];
        }

        $this->validator
            ->add('timestamp', 'required | integer() (' . Error::INTEGER . ')')
            ->add('get[filter]', 'Erachain\Validation\Rule\StringRule');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->timestamp($params['timestamp'], $get);
    }

    /**
     * Проверяем наличие телеграмы по сигнатуре
     *
     * @param TelegramRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'signature' => (string) сигнатура телеграмы
     *  ]
     *
     * @return array|bool Возвращает статус наличия (true/false)
     */
    public function apitelegrams_check(TelegramRequest $request, array $params)
    {
        $this->validator
            ->add('signature', 'required | Erachain\Validation\Rule\Base58Rule');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->check($params['signature']);
    }
}