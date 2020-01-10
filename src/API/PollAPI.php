<?php

namespace Erachain\API;

use Erachain\Helpers\Error;
use Erachain\Request\PollRequest;
use InvalidArgumentException;
use Sirius\Validation\Validator;

class PollAPI
{
    private $validator;

    public function __construct()
    {
        $this->validator = new Validator;
    }

    /**
     * Получаем список голосований по названию
     *
     * @param PollRequest $request Объект с реализацией API запросов к ноде
     * @param array $params Параметры запроса
     *  $params = [
     *      'get' => [
     *          'name' => (string) название голосования
     *      ]
     *  ]
     *
     * @return array|bool Возвращает ответ от ноды с списком голосований
     */
    public function api_getpoll(PollRequest $request, array $params)
    {
        $get = [];

        if ( ! empty($params['get'])) {

            if ( ! is_array($params['get'])) {
                throw new InvalidArgumentException(Error::ARRAY_GET);
            }

            $get = $params['get'];
        }

        $this->validator
            ->add('get[name]', 'required | Erachain\Validation\Rule\StringRule');

        if ( ! $this->validator->validate($params)) {
            Error::validate($this->validator->getMessages());
        }

        return $request->getpoll($get);
    }
}