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
<h1>Выполнение любого запроса к ноде</h1>
<p>Все доступные запросы находятся в документации: <a
            href="https://app.swaggerhub.com/apis-docs/Erachain/era-api/1.0.0-oas3">https://app.swaggerhub.com/apis-docs/Erachain/era-api/1.0.0-oas3</a>
</p>
<h2>Без параметров</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

var_export($era->api('/api/person/288'));
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php var_export($era->api('/api/person/288')); ?>
        </code>
    </pre>
</div>
<h2>С параметрами</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'address' => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k',
    'type'    => 31
);

var_dump($era->api('/apirecords/find', $params));
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'address' => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k',
    'type'    => 31
);

var_dump($era->api('/apirecords/find', $params));
?>
        </code>
    </pre>
</div>
</body>
</html>
