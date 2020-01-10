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
    'recipient' => '7Ka3LQg2tEvqZMNwqhJWhQ6Dx6kcJMWLTX',
    'head'      => 'Тестовое сообщение в телеграме PHP SDK',
    'message'   => 'Это тестовое сообщение отправленное по PHP SDK'
);

$telegram = $era->telegram->send($public_key, $private_key, $params);

?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Запросы к нодам по телеграмам</title>

    <link rel="stylesheet" href="../lib/highlight/solarized-dark.css">
    <script src="../lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<h1>Запросы к нодам по телеграмам</h1>
<h2>Получаем телеграму по сигнатуре</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'signature' => '<?= $telegram['DATA']['signature'] ?>', // (string) сигнатура телеграмы
);

var_dump(
    $era->telegram->api('getbysignature', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params           = array(
    'signature' => $telegram['DATA']['signature'],
);
$content_telegram = $era->telegram->api('getbysignature', $params);
$content_decode   = json_decode($content_telegram['DATA'], true);

var_dump($content_telegram)
?>
        </code>
    </pre>
</div>
<h2>Получаем телеграму по адресу и фильтру</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'get' => array(
        'address' => '7Ka3LQg2tEvqZMNwqhJWhQ6Dx6kcJMWLTX', // (string) Адрес получателя телеграмы
        'filter' => 'Тестовое сообщение в телеграме PHP SDK', // (string) Заголовок сообщения (не обязательно)
    )
);

var_dump(
    $era->telegram->api('get', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'get' => array(
        'address' => '7Ka3LQg2tEvqZMNwqhJWhQ6Dx6kcJMWLTX',
        'filter'  => 'Тестовое сообщение в телеграме PHP SDK',
    )
);

var_dump($era->telegram->api('get', $params))
?>
        </code>
    </pre>
</div>
<h2>Получаем список телеграм по стартовой временной метке и заголовку</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'timestamp' => 1574938835046, // (int) Временная метка старта поиска телеграм
    'get'       => array(
        'filter'  => 'Тестовое сообщение в телеграме PHP SDK', // (string) Заголовок сообщения (не обязательно)
    )
);

var_dump(
    $era->telegram->api('timestamp', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'timestamp' => $content_decode['transaction']['timestamp'] - 10,
    'get'       => array(
        'filter'  => 'Тестовое сообщение в телеграме PHP SDK',
    )
);

var_dump($era->telegram->api('timestamp', $params))
?>
        </code>
    </pre>
</div>
<h2>Проверяем наличие телеграмы по сигнатуре</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'signature' => '<?= $telegram['DATA']['signature'] ?>', // (string) сигнатура телеграмы
);

var_dump(
    $era->telegram->api('check', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'signature' => $telegram['DATA']['signature']
);

var_dump($era->telegram->api('check', $params));
?>
        </code>
    </pre>
</div>
</body>
</html>
