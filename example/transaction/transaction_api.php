<?php
header('Content-Type: text/html; charset=utf-8', true);
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', '1');

require_once '../../vendor/autoload.php';

use Erachain\Erachain;

$era = new Erachain(); ?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Запросы к нодам по транзакциям</title>

    <link rel="stylesheet" href="../lib/highlight/solarized-dark.css">
    <script src="../lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<h1>Запросы к нодам по транзакциям</h1>
<h2>Получаем высоту последнего блока</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

var_export(
    $era->transaction->api('height')
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$height = $era->transaction->api('height');
var_export($height) ?>
        </code>
    </pre>
</div>
<h2>Получаем информацию транзакции по сигнатуре</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'signature' => '36P1xGNN656WfaEJ6cBnExYz34bV9XsavCJGvxx1wS5uV4VbMBxxroKDjcydhRjrJ7Su3HmczYtf3mfBaiHtgupD', // (string) сигнатура транзакции
);

var_export(
    $era->transaction->api('record', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'signature' => '36P1xGNN656WfaEJ6cBnExYz34bV9XsavCJGvxx1wS5uV4VbMBxxroKDjcydhRjrJ7Su3HmczYtf3mfBaiHtgupD',
);

var_export($era->transaction->api('record', $params))
?>
        </code>
    </pre>
</div>
<h2>Получаем транзакцию по номеру блока с номером sequence</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'height_seq' => '675453-1', // (string) номер блока с sequence
);

var_export(
    $era->transaction->api('getbynumber', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'height_seq' => '675453-1',
);

var_export($era->transaction->api('getbynumber', $params));
?>
        </code>
    </pre>
</div>
<h2>Получаем байт код по номеру блока и номеру sequence</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'block' => 675453, // (int) номер блока с sequence
    'seq_no' => 1, // (int) номер блока с sequence
);

var_export(
    $era->transaction->api('recordrawbynumber', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'block'  => 675453,
    'seq_no' => 1,
);

var_export($era->transaction->api('recordrawbynumber', $params))
?>
        </code>
    </pre>
</div>
<h2>Поиск транзаций по адресу получателя и номеру блока</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'address'     => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k', // (string) адрес получателя транзакции
    'start_block' => <?= (int)$height['DATA'] - 3000 ?> // (int) с какого блока начинаем искать
);

$result = array();

do {
    $transaction = $era->transaction->api('incomingfromblock', $params);
    if ( ! empty($transaction['DATA'])) {
        $transaction = json_decode($transaction['DATA'], true);

        if ( ! empty($transaction['txs'])) {
            $result['data'][] = $transaction['txs'];
        }

        if ( ! empty($transaction['next'])) {
            $params['start_block'] = $transaction['next'];
        }
    } else {
        $result['error'][] = $transaction;
    }
} while (isset($transaction['next']));

var_export($result)
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'address'     => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k',
    'start_block' => (int)$height['DATA'] - 3000
);

$result = array();

do {
    $transaction = $era->transaction->api('incomingfromblock', $params);
    if ( ! empty($transaction['DATA'])) {
        $transaction = json_decode($transaction['DATA'], true);

        if ( ! empty($transaction['txs'])) {
            $result['data'][] = $transaction['txs'];
        }

        if ( ! empty($transaction['next'])) {
            $params['start_block'] = $transaction['next'];
        }
    } else {
        $result['error'][] = $transaction;
    }
} while (isset($transaction['next']));

var_export($result)
?>
        </code>
    </pre>
</div>
<h2>Поиск транзаций по адресу</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'get' => array(
        'address'    => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k', // (string) адрес по которому ищем
        'asset'      => 1179, // (int) ключ актива (не обязательно)
        'recordType' => 'SEND' // (string) тип записи (не обязательно)
    )
);

var_dump(
    $era->transaction->api('getbyaddress', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'get' => array(
        'address'    => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k',
        //'asset'      => 1179,
        'recordType' => 'Letter'
    )
);

var_dump($era->transaction->api('getbyaddress', $params))
?>
        </code>
    </pre>
</div>
<h2>Поиск транзаций по заданным параметрам</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'get' => array(
        //'address'    => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k', // (string) адрес (не обязательно)
        'sender'     => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k', // (string) адрес отправителя (не обязательно)
        'recipient'  => '7Ka3LQg2tEvqZMNwqhJWhQ6Dx6kcJMWLTX', // (string) адрес получателя (не обязательно)
        'startblock' => 675000, // (int) с какого блока ищем (не обязательно)
        'endblock'   => 675200, // (int) по какой блок ищем (не обязательно)
        'type'       => 31, // (int) тип транзакции (не обязательно)
        'offset'     => 0, // (int) сдвиг в результате, сколько пропускаем (не обязательно)
    )
);

var_export(
    $era->transaction->api('find', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'get' => array(
        //'address'    => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k',
        'sender'     => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k',
        'recipient'  => '7Ka3LQg2tEvqZMNwqhJWhQ6Dx6kcJMWLTX',
        'startblock' => 675000,
        'endblock'   => 675200,
        'type'       => 31,
        'offset'     => 0,
    )
);

var_export($era->transaction->api('find', $params))
?>
        </code>
    </pre>
</div>
</body>
</html>
