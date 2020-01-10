<?php

namespace Erachain\Helpers;

class Ripemd160
{
    private $working_ptr;
    private $arg;
    private $index;
    private $working;
    private $md_buf;
    private $msg_len;

    function __construct()
    {
        $this->arg         = array(
            array(
                11, 14, 15, 12, 5, 8, 7, 9, 11, 13, 14, 15, 6, 7, 9, 8, 7, 6, 8, 13, 11, 9, 7, 15, 7, 12, 15, 9, 11, 7,
                13, 12, 11, 13, 6, 7, 14, 9, 13, 15, 14, 8, 13, 6, 5, 12, 7, 5, 11, 12, 14, 15, 14, 15, 9, 8, 9, 14, 5,
                6, 8, 6, 5, 12, 9, 15, 5, 11, 6, 8, 13, 12, 5, 12, 13, 14, 11, 8, 5, 6
            ),
            array(
                8, 9, 9, 11, 13, 15, 15, 5, 7, 7, 8, 11, 14, 14, 12, 6, 9, 13, 15, 7, 12, 8, 9, 11, 7, 7, 12, 7, 6, 15,
                13, 11, 9, 7, 15, 11, 8, 6, 6, 14, 12, 13, 5, 14, 13, 13, 7, 5, 15, 5, 8, 11, 14, 14, 6, 14, 6, 9, 12,
                9, 12, 5, 15, 8, 8, 5, 12, 9, 12, 5, 14, 6, 8, 13, 6, 5, 15, 13, 11, 11
            )
        );
        $this->index       = array(
            array(
                0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 7, 4, 13, 1, 10, 6, 15, 3, 12, 0, 9, 5, 2, 14, 11,
                8, 3, 10, 14, 4, 9, 15, 8, 1, 2, 7, 0, 6, 13, 11, 5, 12, 1, 9, 11, 10, 0, 8, 12, 4, 13, 3, 7, 15, 14, 5,
                6, 2, 4, 0, 5, 9, 7, 12, 2, 10, 14, 1, 3, 8, 11, 6, 15, 13
            ),
            array(
                5, 14, 7, 0, 9, 2, 11, 4, 13, 6, 15, 8, 1, 10, 3, 12, 6, 11, 3, 7, 0, 13, 5, 10, 14, 15, 8, 12, 4, 9, 1,
                2, 15, 5, 1, 3, 7, 14, 6, 9, 11, 8, 12, 2, 10, 0, 4, 13, 8, 6, 4, 1, 3, 11, 15, 0, 5, 12, 2, 13, 9, 7,
                10, 14, 12, 15, 10, 4, 1, 5, 8, 7, 6, 2, 13, 14, 0, 3, 9, 11
            )
        );
        $this->working     = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
        $this->md_buf      = array(1732584193, 4023233417, 2562383102, 271733878, 3285377520);
        $this->msg_len     = 0;
        $this->working_ptr = 0;
    }

    public function digest($input)
    {
        $this->update_160($input);

        return $this->digest_bin();
    }

    private function update_160($input)
    {
        for ($i = 0; $i < count($input); $i++) {
            if ($input[$i] >= 128) {
                $input[$i] = $input[$i] - 256;
            }
            $this->working[$this->working_ptr >> 2] ^= (($input[$i]) << (($this->working_ptr & 3) << 3));
            $this->working_ptr++;
            if ($this->working_ptr == 64) {
                $this->compress($this->working);
                for ($j = 0; $j < 16; $j++) {
                    $this->working[$j] = 0;
                }
                $this->working_ptr = 0;
            }
        }
        $this->msg_len += count($input);
    }

    private function compress($X)
    {
        $i   = 0;
        $arr = array();

        $arr['aa'] = $arr['a'] = $this->md_buf[0];
        $arr['bb'] = $arr['b'] = $this->md_buf[1];
        $arr['cc'] = $arr['c'] = $this->md_buf[2];
        $arr['dd'] = $arr['d'] = $this->md_buf[3];
        $arr['ee'] = $arr['e'] = $this->md_buf[4];

        for (; $i < 16; $i++) {
            $temp = $this->get4byte($this->get4byte($arr['a'] + ($arr['b'] ^ $arr['c'] ^ $arr['d'])) + $X[$this->index[0][$i]]);
            $arr  = $this->compress_get4byte_first($arr, $temp, $i);
            $temp = $this->get4byte($this->get4byte($arr['aa'] + ($arr['bb'] ^ ($arr['cc'] | ~$arr['dd']))) + $X[$this->index[1][$i]] + 1352829926);
            $arr  = $this->compress_get4byte_last($arr, $temp, $i);
        }
        for (; $i < 32; $i++) {
            $temp = $this->get4byte($this->get4byte($arr['a'] + (($arr['b'] & $arr['c']) | (~$arr['b'] & $arr['d']))) + $X[$this->index[0][$i]] + 1518500249);
            $arr  = $this->compress_get4byte_first($arr, $temp, $i);
            $temp = $this->get4byte($this->get4byte($arr['aa'] + (($arr['bb'] & $arr['dd']) | ($arr['cc'] & ~$arr['dd']))) + $X[$this->index[1][$i]] + 1548603684);
            $arr  = $this->compress_get4byte_last($arr, $temp, $i);
        }
        for (; $i < 48; $i++) {
            $temp = $this->get4byte($this->get4byte($arr['a'] + $this->get4byte(($arr['b'] | ~$arr['c']) ^ $arr['d'])) + $X[$this->index[0][$i]] + 1859775393);
            $arr  = $this->compress_get4byte_first($arr, $temp, $i);
            $temp = $this->get4byte($this->get4byte($arr['aa'] + (($arr['bb'] | ~$arr['cc']) ^ $arr['dd'])) + $X[$this->index[1][$i]] + 1836072691);
            $arr  = $this->compress_get4byte_last($arr, $temp, $i);
        }
        for (; $i < 64; $i++) {
            $temp = $this->get4byte($this->get4byte($arr['a'] + $this->get4byte(($arr['b'] & $arr['d']) | ($arr['c'] & ~$arr['d']))) + $X[$this->index[0][$i]] + 2400959708);
            $arr  = $this->compress_get4byte_first($arr, $temp, $i);
            $temp = $this->get4byte($this->get4byte($arr['aa'] + (($arr['bb'] & $arr['cc']) | (~$arr['bb'] & $arr['dd']))) + $X[$this->index[1][$i]] + 2053994217);
            $arr  = $this->compress_get4byte_last($arr, $temp, $i);
        }
        for (; $i < 80; $i++) {
            $temp = $this->get4byte($this->get4byte($arr['a'] + $this->get4byte($arr['b'] ^ ($arr['c'] | ~$arr['d']))) + $X[$this->index[0][$i]] + 2840853838);
            $arr  = $this->compress_get4byte_first($arr, $temp, $i);
            $temp = $this->get4byte($this->get4byte($arr['aa'] + ($arr['bb'] ^ $arr['cc'] ^ $arr['dd'])) + $X[$this->index[1][$i]]);
            $arr  = $this->compress_get4byte_last($arr, $temp, $i);
        }

        $arr['dd'] = (int)($arr['dd'] + $arr['c'] + $this->md_buf[1]);

        $this->md_buf[1] = (int)($this->md_buf[2] + $arr['d'] + $arr['ee']);
        $this->md_buf[2] = (int)($this->md_buf[3] + $arr['e'] + $arr['aa']);
        $this->md_buf[3] = (int)($this->md_buf[4] + $arr['a'] + $arr['bb']);
        $this->md_buf[4] = (int)($this->md_buf[0] + $arr['b'] + $arr['cc']);
        $this->md_buf[0] = (int)$arr['dd'];
    }

    private function compress_get4byte_first($arr, $temp, $i)
    {
        $arr['a'] = $this->get4byte($arr['e']);
        $arr['e'] = $this->get4byte($arr['d']);
        $arr['d'] = $this->get4byte(($arr['c'] << 10) | $this->shift($arr['c'], 22));
        $arr['c'] = $this->get4byte($arr['b']);
        $arr['s'] = $this->get4byte($this->arg[0][$i]);
        $arr['b'] = $this->get4byte($this->get4byte(($temp << $arr['s']) | $this->shift($temp,
                    (32 - $arr['s']))) + $arr['a']);

        return $arr;
    }

    private function compress_get4byte_last($arr, $temp, $i)
    {
        $arr['aa'] = $this->get4byte($arr['ee']);
        $arr['ee'] = $this->get4byte($arr['dd']);
        $arr['dd'] = $this->get4byte(($arr['cc'] << 10) | $this->shift($arr['cc'], 22));
        $arr['cc'] = $this->get4byte($arr['bb']);
        $arr['s']  = $this->get4byte($this->arg[1][$i]);
        $arr['bb'] = $this->get4byte($this->get4byte(($temp << $arr['s']) | $this->shift($temp,
                    (32 - $arr['s']))) + $arr['aa']);

        return $arr;
    }

    private function digest_bin()
    {
        $this->md_finish($this->working, $this->msg_len, 0);
        $res = array();
        for ($i = 0; $i < 20; $i++) {
            $res[$i] = ($this->shift($this->md_buf[$i >> 2], (($i & 3) << 3)) & 255);
        }

        return $res;
    }

    private function md_finish($array, $lswlen, $mswlen)
    {
        $X = $array;

        $X[($lswlen >> 2) & 15] ^= 1 << ((($lswlen & 3) << 3) + 7);
        if (($lswlen & 63) > 55) {
            $this->compress($X);
            for ($i = 0; $i < 14; $i++) {
                $X[$i] = 0;
            }
        }
        $X[14] = $lswlen << 3;
        $X[15] = ($lswlen >> 29) | ($mswlen << 3);
        $this->compress($X);
    }

    private function shift($a, $n)
    {
        if ($n == 0) {
            return $a;
        }

        $k    = decbin($a);
        $rest = substr($k, -32, 32);

        return bindec(substr($rest, 0, -$n));
    }

    private function get4byte($val)
    {
        return $val & 4294967295;
    }

}