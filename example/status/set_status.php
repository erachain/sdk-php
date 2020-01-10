<?php
header('Content-Type: text/html; charset=utf-8', true);
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', '1');

require_once '../../vendor/autoload.php';

use Erachain\Erachain;

$era = new Erachain();

$public_key  = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'key_status'  => 46,
    'item_type'   => 4,
    'key_item'    => 288,
    'date_start'  => 1577703000000,
    'date_end'    => 1609325400000,
    'value_1'     => 134,
    'value_2'     => 2754,
    'data_1'      => 'Автомобиль',
    'data_2'      => 'Пицца',
    'description' => 'Какое то описание'
); ?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Установка статуса</title>

    <link rel="stylesheet" href="../lib/highlight/solarized-dark.css">
    <script src="../lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<h1>Установка статуса</h1>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$public_key = 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11';
$private_key = 'Mo51Jj79UCKru1ruVNdsHvBdNEfsHvPtBJ8bki41pSi44vqa4AABv3yRKYaLwJ4ALpufNQLGQCzuQS4WeVfrFHH';

$params = array(
    'key_status'  => 46, // (int) ключ назначаемого статуса
    'item_type'  => 4, // (int) тип назначения статуса: 1 - ASSET_TYPE, 2 - IMPRINT_TYPE, 3 - NOTE_TYPE, 4 - PERSON_TYPE, 5 - STATUS_TYPE, 6 - UNION_TYPE
    'key_item'  => 288, // (int) ключ конкретного элемента выбранного типа
    'date_start'  => 1577703000000, // (int) время старта действия статуса
    'date_end'  => 1609325400000, // (int) время окончания действия статуса
    'value_1'  => 134, // (int) первое числовое значение для подстановки (%1)
    'value_2'  => 2754, // (int) второе числовое значение для подстановки (%2)
    'data_1'  => 'Автомобиль', // (string) первое строковое значение для подстановки (%3)
    'data_2'  => 'Пицца', // (string) второе строковое значение для подстановки (%4)
    'description'  => 'Какое то описание' // (string) текстовое описание для подстановки (%D)
);

var_dump(
    $era->status->set($public_key, $private_key, $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php var_dump($era->status->set($public_key, $private_key, $params)) ?>
        </code>
    </pre>
    <p>
        Посмотреть назначенный статус можно посмотреть на странице персоны:
        <a href="http://erachain.org:9067/index/blockexplorer.html?person=288&lang=en" target="_blank">http://erachain.org:9067/index/blockexplorer.html?person=288&lang=en</a>
        (примерно через 30 сек после отправления запроса)
    </p>
</div>
</body>
</html>
