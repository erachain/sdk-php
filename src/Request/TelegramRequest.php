<?php

namespace Erachain\Request;

class TelegramRequest extends TransactionRequest
{
    /**
     * Получаем телеграму по сигнатуре
     *
     * @param string $signature Сигнатура телеграмы
     *
     * @return array|bool Возвращает телеграму
     */
    public function getbysignature($signature)
    {
        return $this->request->send(
            '/apitelegrams/getbysignature/' . $signature,
            false,
            'get'
        );
    }

    /**
     * Получаем список телеграм по адресу получателя и фильтру
     *
     * @param array $params Параметры запроса
     *  $params = [
     *      'address' => (string) адрес получателя телеграмы
     *      'timestamp' => (int) временная метка телеграмы //TODO error: не работает фильтрация по timestamp
     *      'filter' => (string) заголовок телеграмы
     *  ]
     *
     * @return array|bool Возвращает список телеграм
     */
    public function get(array $params)
    {
        return $this->request->send(
            '/apitelegrams/get/',
            $params,
            'get'
        );
    }

    /**
     * Получаем список телеграм по стартовой временной метке и заголовку
     *
     * @param int $timestamp Временная метка старта поиска телеграм
     * @param array $params Параметры запроса
     *  $params = [
     *      'filter' => (string) заголовок телеграмы
     *  ]
     *
     * @return array|bool Возвращает список телеграм
     */
    public function timestamp($timestamp, array $params)
    {
        return $this->request->send(
            '/apitelegrams/timestamp/' . $timestamp,
            $params,
            'get'
        );
    }

    /**
     * Проверяем наличие телеграмы по сигнатуре
     *
     * @param string $signature Сигнатура телеграмы
     *
     * @return array|bool Возвращает статус наличия (true/false)
     */
    public function check($signature)
    {
        return $this->request->send(
            '/apitelegrams/check/' . $signature,
            false,
            'get'
        );
    }
}
