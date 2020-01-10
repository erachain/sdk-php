<?php

namespace Erachain;

use Erachain\API\PersonAPI;
use Erachain\Helpers\Error;
use Erachain\Request\PersonRequest;
use Erachain\Transaction\CertifyPerson;
use Erachain\Transaction\PersonInfo;
use Erachain\Transaction\IssuePerson;
use Exception;
use Sirius\Validation\Validator;

class Person
{
    private $erachain_params;
    private $validator;

    public function __construct($erachain_params)
    {
        $this->validator = new Validator;

        $this->erachain_params = $erachain_params;
    }

    /**
     * Создание байт-кода персоны
     *
     * @param string $public_key Публичный ключ
     * @param string $private_key Приватный ключ
     * @param array $params Массив данных для создания персоны
     *  $params = [
     *      'owner' => (string) публичный ключ владельца персоны (не обязательно, по умолчанию $public_key),
     *      'name' => (string) имя персоны,
     *      'description' => (string) описание персоны,
     *      'icon' => (string) путь к иконке персоны (не обязательно),
     *      'image' => (string) путь к изображению персоны (не обязательно),
     *      'birthday' => (int) день рождения, timestamp с миллисекундами,
     *      'death_day' => (int) день смерти, timestamp с миллисекундами,
     *      'gender' => (int) пол, 0 мужской, 1 женский,
     *      'race' => (string) расса,
     *      'birth_latitude' => (float) широта места рождения,
     *      'birth_longitude' => (float) долгота места рождения,
     *      'skin_color' => (string) цвет кожи,
     *      'eye_color' => (string) цвет глаз,
     *      'hair_color' => (string) цвет волос,
     *      'height' => (int) рост,
     *  ]
     *
     * @return array Возвращает байт-код персоны
     * @example /example/person/person_info.php
     */
    public function info($public_key = null, $private_key = null, array $params = [])
    {
        try {
            $person = new PersonInfo($public_key, $private_key, $this->erachain_params);

            $this->validator
                ->add('owner', 'Erachain\Validation\Rule\Base58Rule')
                ->add('name', 'required | fullname() (' . Error::FULLNAME . ')')
                ->add('description', 'required | Erachain\Validation\Rule\StringRule')
                ->add('icon', 'file\image(allowed=jpg,jpeg,png,gif) | file\size(size=10K)')
                ->add('image', 'file\image(allowed=jpg,jpeg,png,gif) | file\size(size=20K)')
                ->add('birthday', 'required | integer() (' . Error::INTEGER . ')')
                ->add('death_day', 'required | integer() (' . Error::INTEGER . ')')
                ->add('gender', 'required | integer() (' . Error::INTEGER . ')')
                ->add('race', 'required | Erachain\Validation\Rule\StringRule')
                ->add('birth_latitude', 'required | Erachain\Validation\Rule\FloatRule')
                ->add('birth_longitude', 'required | Erachain\Validation\Rule\FloatRule')
                ->add('skin_color', 'required | Erachain\Validation\Rule\StringRule')
                ->add('eye_color', 'required | Erachain\Validation\Rule\StringRule')
                ->add('hair_color', 'required | Erachain\Validation\Rule\StringRule')
                ->add('height', 'required | integer() (' . Error::INTEGER . ')');

            if ( ! $this->validator->validate($params)) {
                Error::validate($this->validator->getMessages());
            }

            return array(
                'DATA'   => $person->get($params),
                'STATUS' => 'OK'
            );
        } catch (Exception $e) {
            return array(
                'DATA'   => $e->getMessage(),
                'STATUS' => 'ERROR'
            );
        }
    }

    /**
     * Отправка байт-кода на создание персоны
     *
     * @param string $public_key Публичный ключ регистратора
     * @param string $private_key Приватный ключ регистратора
     * @param array $params Массив данных для ввода персоны в блокчейн
     *  $params = [
     *      'raw' => (string) байт-код создания персоны
     *  ]
     *
     * @return array Возвращает ответ от ноды со статусом, сигнатурой и байт-кодом
     * @example /example/person/issue_person.php
     */
    public function issue($public_key, $private_key, array $params = [])
    {
        try {
            $person  = new IssuePerson($public_key, $private_key, $this->erachain_params);
            $request = new PersonRequest($this->erachain_params);

            $this->validator
                ->add('raw', 'required | Erachain\Validation\Rule\Base58Rule');

            if ( ! $this->validator->validate($params)) {
                Error::validate($this->validator->getMessages());
            }

            $data = $person->get($params);

            return $request->broadcast($data);
        } catch (Exception $e) {
            return array(
                'DATA'   => $e->getMessage(),
                'STATUS' => 'ERROR'
            );
        }
    }

    /**
     * Подтверждение персоны
     *
     * @param string $public_key Публичный ключ
     * @param string $private_key Приватный ключ
     * @param array $params Массив данных для подтверждения персоны
     *  $params = [
     *      'person_key' => (int) ключ персоны,
     *      'public_key' => (string) публичный ключ персоны которую подтверждаем
     *  ]
     *
     * @return array Возвращает ответ от ноды со статусом, сигнатурой и байт-кодом
     * @example /example/person/certify_person.php
     */
    public function certify($public_key = null, $private_key = null, array $params = [])
    {
        try {
            $person  = new CertifyPerson($public_key, $private_key, $this->erachain_params);
            $request = new PersonRequest($this->erachain_params);

            $this->validator
                ->add('person_key', 'required | integer() (' . Error::INTEGER . ')')
                ->add('public_key', 'required | Erachain\Validation\Rule\Base58Rule');

            if ( ! $this->validator->validate($params)) {
                Error::validate($this->validator->getMessages());
            }

            $data = $person->get($params);

            return $request->broadcast($data);
        } catch (Exception $e) {
            return array(
                'DATA'   => $e->getMessage(),
                'STATUS' => 'ERROR'
            );
        }
    }

    /**
     * Обращение к ноде по API для получения данных по персоне
     *
     * @param string $method Название запроса. Доступные запросы:
     *  [
     *      'personheight' => Получаем высоту цепочки персон
     *      'person' => Получаем данные персоны по ключу персоны
     *      'persondata' => Получаем иконку и изображение персоны по ключу персоны
     *      'personkeybyaddress' => Получаем ключ персоны по адресу (счёту)
     *      'personbyaddress' => Получаем данные персоны по адресу (счёту)
     *      'personkeybypublickey' => Получаем ключ персоны по публичному ключу
     *      'personbypublickey' => Получаем данные персоны по публичному ключу
     *      'personsfilter' => Получаем данные персон по имени персоны (полному/частичному)
     *      'personkeybyownerpublickey' => Получение ключа персоны по публичному ключу создателя персоны
     *      ... => все запросы из ->transaction_api()
     *  ]
     * @param array $params Массив параметров для запроса
     *
     * @return array|bool Возвращает ответ от ноды по запросу
     * @example /example/person/person_api.php
     */
    public function api($method = null, $params = [])
    {
        try {
            $api     = new PersonAPI();
            $request = new PersonRequest($this->erachain_params);

            switch ($method):
                case 'personheight':
                    $result = $api->api_personheight($request);
                    break;
                case 'person':
                    $result = $api->person($request, $params);
                    break;
                case 'persondata':
                    $result = $api->api_persondata($request, $params);
                    break;
                case 'personkeybyaddress':
                    $result = $api->api_personkeybyaddress($request, $params);
                    break;
                case 'personbyaddress':
                    $result = $api->api_personbyaddress($request, $params);
                    break;
                case 'personkeybypublickey':
                    $result = $api->api_personkeybypublickey($request, $params);
                    break;
                case 'personbypublickey':
                    $result = $api->api_personbypublickey($request, $params);
                    break;
                case 'personsfilter':
                    $result = $api->api_personsfilter($request, $params);
                    break;
                case 'personkeybyownerpublickey':
                    $result = $api->api_personkeybyownerpublickey($request, $params);
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