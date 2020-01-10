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
    <title>Запросы к нодам по телеграмам</title>

    <link rel="stylesheet" href="../lib/highlight/solarized-dark.css">
    <script src="../lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<h1>Запросы к нодам по сообщениям</h1>
<h2>Получаем сообщения по адресу</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'get' => array(
        'address' => '7Ka3LQg2tEvqZMNwqhJWhQ6Dx6kcJMWLTX'
    )
);

var_dump(
    $era->message->api('getbyaddress', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'get' => array(
        'address' => '7Ka3LQg2tEvqZMNwqhJWhQ6Dx6kcJMWLTX'
    )
);

var_dump($era->message->api('getbyaddress', $params));
?>
        </code>
    </pre>
</div>
</body>
</html>
