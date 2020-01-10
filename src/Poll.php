<?php

namespace Erachain;

use Erachain\API\PollAPI;
use Erachain\Helpers\Error;
use Erachain\Request\PollRequest;
use Erachain\Transaction\IssuePoll;
use Erachain\Transaction\VoteOnPoll;
use Exception;
use Sirius\Validation\Validator;

class Poll
{
    private $erachain_mode;
    private $validator;

    public function __construct($erachain_mode)
    {
        $this->validator = new Validator;

        $this->erachain_mode = $erachain_mode;
    }

    /**
     * Создание голосования
     *
     * @param string $public_key Публичный ключ
     * @param string $private_key Приватный ключ
     * @param array $params Массив данных для создания статуса
     *  $params = [
     *      'block_height' => (int) номер блока,
     *      'seq_number' => (int) номер транзакции в блоке
     *  ]
     *
     * @return array Возвращает ответ от ноды со статусом, сигнатурой и байт-кодом
     * @example /example/poll/issue_poll.php
     */
    public function issue($public_key = null, $private_key = null, array $params = [])
    {
        try {
            $poll    = new IssuePoll($public_key, $private_key, $this->erachain_mode);
            $request = new PollRequest($this->erachain_mode);

            $this->validator
                ->add('owner', 'Erachain\Validation\Rule\Base58Rule')
                ->add('name', 'required | Erachain\Validation\Rule\StringRule')
                ->add('description', 'required | Erachain\Validation\Rule\StringRule')
                ->add('icon', 'file\image(allowed=jpg,jpeg,png,gif) | file\size(size=10K)')
                ->add('image', 'file\image(allowed=jpg,jpeg,png,gif) | file\size(size=1M)')
                ->add('options', 'required | ArrayMinlength(1) (' . Error::ARRAY_NO_ITEMS . ')');

            if ( ! $this->validator->validate($params)) {
                Error::validate($this->validator->getMessages());
            }

            $data = $poll->get($params);

            return $request->broadcast($data);
        } catch (Exception $e) {
            return array(
                'DATA'   => $e->getMessage(),
                'STATUS' => 'ERROR'
            );
        }
    }

    /**
     * Голосовать в опросе
     *
     * @param string $public_key Публичный ключ
     * @param string $private_key Приватный ключ
     * @param array $params Массив данных для голосования в опросе
     *  $params = [
     *      'poll_key' => (int) ключ голосования,
     *      'option_number' => (int) номер варианта ответа
     *  ]
     *
     * @return array Возвращает ответ от ноды со статусом, сигнатурой и байт-кодом
     * @example /example/poll/vote_on_poll.php
     */
    public function vote($public_key = null, $private_key = null, array $params = [])
    {
        try {
            $poll    = new VoteOnPoll($public_key, $private_key, $this->erachain_mode);
            $request = new PollRequest($this->erachain_mode);

            $this->validator
                ->add('poll_key', 'required | integer() (' . Error::INTEGER . ')')
                ->add('option_number', 'required | integer() (' . Error::INTEGER . ')');

            if ( ! $this->validator->validate($params)) {
                Error::validate($this->validator->getMessages());
            }

            $data = $poll->get($params);

            return $request->broadcast($data);
        } catch (Exception $e) {
            return array(
                'DATA'   => $e->getMessage(),
                'STATUS' => 'ERROR'
            );
        }
    }

    /**
     * Обращение к ноде по API для получения данных по голосованию
     *
     * @param string $method Название запроса. Доступные запросы:
     *  [
     *      'getpoll' => Получаем список голосований по названию
     *      ... => все запросы из ->transaction_api()
     *  ]
     * @param array $params Массив параметров для запроса
     *
     * @return array|bool Возвращает ответ от ноды по запросу
     * @example /example/poll/poll_api.php
     */
    public function api($method = null, array $params = array())
    {
        try {
            $api     = new PollAPI();
            $request = new PollRequest($this->erachain_mode);

            switch ($method):
                case 'getpoll':
                    $result = $api->api_getpoll($request, $params);
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