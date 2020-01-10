<?php
header('Content-Type: text/html; charset=utf-8', true);
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', '1');

require_once '../../vendor/autoload.php';

use Erachain\Erachain;

$era = new Erachain();
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Запросы к нодам по ордерам</title>

    <link rel="stylesheet" href="../lib/highlight/solarized-dark.css">
    <script src="../lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<h1>Запросы к нодам по ордерам</h1>
<h2>Получаем ордер по номеру блока с последовательностью или сигнатуре</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'height_seq' => '125368-1', // (string) номер блока с последовательностью
    //'signature' => '2pWD8oHiR8GvddFnqDVvXpENqTgKY5akdiTJRRVtPTWXs51jNnSrV2qUiyJqud3tR6KULHsTV7PVxcFmCF1rb141' // (string) сигнатура транзакции ордера
);

var_dump(
    $era->order->api('order', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'height_seq' => '125368-1',
    //'signature' => '2pWD8oHiR8GvddFnqDVvXpENqTgKY5akdiTJRRVtPTWXs51jNnSrV2qUiyJqud3tR6KULHsTV7PVxcFmCF1rb141'
);

var_dump($era->order->api('order', $params));
?>
        </code>
    </pre>
</div>
<h2>Получение списка ордеров по ключам выставляемого и получаемого активов</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'have' => 2, // (int) ключ выставляемого актива
    'want' => 1, // (int) ключ получаемого актива
    'get'  => array(
        'limit' => 30 //  (int) ограничение кол-ва выводимых ордеров (не обязательно, по умолчанию - 20)
    )
);

var_dump(
    $era->order->api('ordersbook', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'have' => 2,
    'want' => 1,
    'get'  => array(
        'limit' => 30
    )
);

var_dump($era->order->api('ordersbook', $params));
?>
        </code>
    </pre>
</div>
<h2>Получение списка ордеров по адресу создателя</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();


$params = array(
    'address' => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k', // (string) адрес создателя ордера
    'get'  => array(
        'limit' => 200 // (int) лимит вывода ордеров
    )
);

var_dump(
    $era->order->api('ordersbyaddress', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'address' => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k',
    'get'     => array(
        'limit' => 200
    )
);

var_dump($era->order->api('ordersbyaddress', $params));
?>
        </code>
    </pre>
</div>
<h2>Получение списка завершенных ордеров</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'have' => 2, // (int) ключ выставляемого актива
    'want' => 1, // (int) ключ получаемого актива
    'get'  => array(
        'order' => 2890405616025603, // (int) до какого ордера выводить
        //'height_seq' => '534110-4', // (string) высота блока с номером, до какой высоты с номером выводить
        //'height' => 444338, // (int) высота блока, до какого блока выводить
        //'time' => 1573948800, // (int) временная метка, до какой даты выводить
        'limit' => 200 // (int) сколько сделок выводить (по умолчанию 50, максимум 200)
    )
);

var_dump(
    $era->order->api('completedordersfrom', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'have' => 2,
    'want' => 1,
    'get'  => array(
        'order' => 2890405616025603,
        //'height_seq' => '534110-4',
        //'height'     => 444338,
        //'time'       => 1573948800,
        'limit' => 200
    )
);

var_dump($era->order->api('completedordersfrom', $params));
?>
        </code>
    </pre>
</div>
<h2>Получение всех ордеров по адресу</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'address' => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k', // (string) адрес создателя счёта
    'height_seq' => '125368-1', // (string) номер блока с последовательностью
    //'order' => 538623258656769, // (int) номер ордера
    'get'  => array(
        'limit' => 200 // (int) сколько сделок выводить (по умолчанию 50, максимум 200)
    )
);

var_dump(
    $era->order->api('allordersbyaddress', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'address'    => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k',
    'height_seq' => '125368-1',
    //'order' => 538623258656769,
    'get'        => array(
        'limit' => 200
    )
);

var_dump($era->order->api('allordersbyaddress', $params));
?>
        </code>
    </pre>
</div>
<h2>Получение сделок по временной метке</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'amount_asset_key' => 2, // (int) ключ выставляемого актива
    'price_asset_key'  => 1, // (int) ключ получаемого актива
    'get'              => array(
        'timestamp' => 1518702660, // (int) временная метка
            'limit' => 200 // (int) сколько сделок выводить (по умолчанию 50, максимум 200)
    )
);

var_dump(
    $era->order->api('trades', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'amount_asset_key' => 2,
    'price_asset_key'  => 1,
    'get'              => array(
        'timestamp' => 1518702660,
        'limit'     => 200
    )
);

var_dump($era->order->api('trades', $params));
?>
        </code>
    </pre>
</div>
<h2>Получение сделок по ключу передаваемого и получаемого актива за последние 24 часа</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();


// Версия 1

$params = array(
    'address'          => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k',
    'amount_asset_key' => 2, // (int) ключ выставляемого актива
    'price_asset_key'  => 1, // (int) ключ получаемого актива
    'get'              => array(
        'order' => 1908352753860626,
        'height_seq' => '444323-18',
        'height' => 62819,
        'time' => 1576677630,
        'limit' => 200
    )
);

// Версия 2

$params = array(
    'amount_asset_key' => 2, // (int) ключ выставляемого актива
    'price_asset_key'  => 1, // (int) ключ получаемого актива
    'get'              => array(
        'order' => 1908352753860626,
        'height_seq' => '444323-18',
        'height' => 62819,
        'time' => 1576677630,
        'limit' => 200
    )
);

// Версия 3

$params = array(
    'get'              => array(
        'trade' => 123123123
        'order' => 1908352753860626,
        'height_seq' => '444323-18',
        'height' => 62819,
        'time' => 1576677630,
        'limit' => 200
    )
);

var_dump(
    $era->order->api('tradesfrom', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'amount_asset_key' => 2,
    'price_asset_key'  => 1,
    'get'              => array(
        'height_seq' => '444323-18',
        'limit' => 200
    )
);

var_dump($era->order->api('tradesfrom', $params));
?>
        </code>
    </pre>
</div>
<h2>Получение сделок по ключу передаваемого и получаемого актива за последние 24 часа</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'have' => 2, // (int) ключ выставляемого актива
    'want' => 1, // (int) ключ получаемого актива
);

var_dump(
    $era->order->api('volume24', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'have' => 2,
    'want' => 1
);

var_dump($era->order->api('volume24', $params));
?>
        </code>
    </pre>
</div>
</body>
</html>
