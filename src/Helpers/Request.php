<?php

namespace Erachain\Helpers;

use UnexpectedValueException;

class Request
{
    const STATUS_OK = 'OK';
    const STATUS_ERROR = 'ERROR';

    protected $erachain_mode;

    public function __construct($erachain_mode)
    {
        $this->erachain_mode = $erachain_mode;
    }

    /**
     * Формирование запроса к API
     *
     * @param string $url Строка запроса к API
     * @param array|string $param Параметры запроса
     * @param string $type Тип запроса
     *
     * @return array|bool Возвращает ответ от ноды по запросу
     */
    public function send($url, $param = null, $type = 'get')
    {
        $server_list = $this->get_servers($this->erachain_mode);

        if (empty($server_list)) {
            throw new UnexpectedValueException(Error::SERVER_LIST);
        }

        foreach ($server_list as $server) {
            $full_url = $server . $url;
            $response = $this->make_curl($full_url, $param, $type);
            if ($response['STATUS'] == self::STATUS_OK) {
                $res = $this->is_json($response['DATA'], true);

                if ( ! empty($res['error'])) {
                    if ( ! empty($res['message'])) {
                        $response['DATA'] = $res['message'];
                    }
                    $response['STATUS'] = self::STATUS_ERROR;
                }

                return $response;
            }
        }

        return array(
            'DATA'   => Error::SEND_REQUEST,
            'STATUS' => 'ERROR'
        );
    }

    /**
     * Проверяет json на корректность
     *
     * @param string $string Json строка
     * @param bool $return_data Выводить результат или нет
     *
     * @return bool|mixed Результат, является строка json или нет
     */
    public function is_json($string, $return_data = false)
    {
        $data = json_decode($string, true);

        if (json_last_error() == JSON_ERROR_NONE) {
            if ($return_data) {
                return $data;
            } else {
                return true;
            }
        }

        return false;
    }

    /**
     * Статус успешного запроса
     *
     * @return string Возвращает статус
     */
    public function get_status_ok()
    {
        return self::STATUS_OK;
    }

    /**
     * Статус запроса с ошибкой
     *
     * @return string Возвращает статус
     */
    public function get_status_error()
    {
        return self::STATUS_ERROR;
    }

    /**
     * Отправка запроса по API
     *
     * @param string $url Строка запроса к API
     * @param array|string $param Параметры запроса
     * @param string $type Тип запроса
     *
     * @return array Возвращает ответ от ноды по запросу
     */
    private function make_curl($url, $param, $type)
    {
        $result = [];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        if ($type == 'post' && $param) {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        } else {
            if ($param) {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($param));
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }
        $response = curl_exec($ch);

        if ($response === false) {
            $result['DATA']   = curl_error($ch);
            $result['STATUS'] = self::STATUS_ERROR;
        } else {
            $result['DATA']   = $response;
            $result['STATUS'] = self::STATUS_OK;
        }
        curl_close($ch);

        return $result;
    }

    /**
     * Получение списка активных нод
     *
     * @param string $erachain_mode Режим работы (тестовый / боевой)
     *
     * @return array|bool Возвращает список доступных серверов
     */
    private function get_servers($erachain_mode)
    {
        if ( ! in_array($erachain_mode, ['dev', 'live'])) {
            throw new UnexpectedValueException(Error::MODE_ERACHAIN);
        }

        $file_servers = dirname(dirname(__FILE__)) . '/server.json';

        $this->load_servers_json($file_servers);

        $servers_json = file_get_contents($file_servers);

        $arr_servers = json_decode($servers_json, true)[$erachain_mode];

        if (empty($arr_servers)) {
            throw new UnexpectedValueException(Error::SERVER_LIST);
        }

        return $arr_servers;
    }

    /**
     * Загрузка списка нод, если данные устарели (по умолчанию 10 минут)
     *
     * @param string $file путь к файлу
     */
    private function load_servers_json($file)
    {
        if ( ! file_exists($file) || filectime($file) < time() - 600) {

            $json = $this->make_curl('https://erachain.org/get-hosts', false, 'get');
            if ($json['STATUS'] === self::STATUS_OK) {
                $server_list = json_decode($json['DATA'], true);

                if ( ! empty($server_list['dev']) && ! empty($server_list['live'])) {
                    $result = [];

                    $result['dev']  = $this->parse_server_list($server_list['dev']);
                    $result['live'] = $this->parse_server_list($server_list['live']);

                    file_put_contents($file, json_encode($result));
                }
            }
        }
    }

    /**
     * Парсинг списка серверов и формирование отсортированного списка доступных и наиболее актуальных серверов
     *
     * @param array $server_list Список серверов с параметрами
     *
     * @return array Возвращает отсортированный список серевров
     */
    private function parse_server_list(array $server_list)
    {
        $height = [];
        $time   = [];

        foreach ($server_list as $key => $value) {
            if (empty($value['status']) || $value['status'] != 200 || empty($value['time']) || empty($value['height'])) {
                unset($server_list[$key]);
                continue;
            }

            $height[$key] = $value['height'];
            $time[$key]   = $value['time'];

            $server_list[$key] = $value['host'];
        }

        array_multisort($height, SORT_DESC, $time, SORT_ASC, $server_list);

        return $server_list;
    }

}