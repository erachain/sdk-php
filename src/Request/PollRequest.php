<?php

namespace Erachain\Request;

class PollRequest extends TransactionRequest
{
    /**
     * Получение списка голосований по названию
     *
     * @param array $param Название голосования (частичное/полное)
     *
     * @return array|bool Возвращает ответ от ноды с списком голосований
     */
    public function getpoll($param)
    {
        return $this->request->send(
            '/apipoll/getPoll',
            false,
            $param
        );
    }
}