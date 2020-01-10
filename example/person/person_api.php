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
    <title>Запросы к нодам по персоне</title>

    <link rel="stylesheet" href="../lib/highlight/solarized-dark.css">
    <script src="../lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<h1>Запросы к нодам по персоне</h1>
<h2>Получаем высоту цепочки персон</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

var_export(
    $era->person->api('personheight')
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php var_export($era->person->api('personheight')) ?>
        </code>
    </pre>
</div>
<h2>Получаем данные персоны по ключу персоны</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'person_key' => 288, // (int) ключ персоны
);

var_export(
    $era->person->api('person', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'person_key' => 288
);

var_export($era->person->api('person', $params)) ?>
        </code>
    </pre>
</div>
<h2>Получаем иконку и изображение персоны по ключу персоны</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'person_key' => 288, // (int) ключ персоны
);

var_export(
    $era->person->api('persondata', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'person_key' => 288
);

var_export($era->person->api('persondata', $params)) ?>
        </code>
    </pre>
</div>
<h2>Получаем ключ персоны по адресу (счёту)</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'address' => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k', // (string) адрес счёта
);

var_export(
    $era->person->api('personkeybyaddress', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'address' => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k',
);

var_export($era->person->api('personkeybyaddress', $params)) ?>
        </code>
    </pre>
</div>
<h2>Получаем данные персоны по адресу (счёту)</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'address' => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k', // (string) адрес счёта
);

var_export(
    $era->person->api('personbyaddress', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'address' => '76kos2Xe3KzhQ5K7HyKtWXF1kwNRWmTW9k',
);

var_export($era->person->api('personbyaddress', $params)) ?>
        </code>
    </pre>
</div>
<h2>Получаем ключ персоны по публичному ключу</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'public_key' => 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11', // (string) публичный ключ
);

var_export(
    $era->person->api('personkeybypublickey', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'public_key' => 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11',
);

var_export($era->person->api('personkeybypublickey', $params)) ?>
        </code>
    </pre>
</div>
<h2>Получаем данные персоны по публичному ключу</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'public_key' => 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11', // (string) публичный ключ
);

var_export(
    $era->person->api('personbypublickey', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'public_key' => 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11',
);

var_export($era->person->api('personbypublickey', $params)) ?>
        </code>
    </pre>
</div>
<h2>Получаем данные персон по имени персоны (полному/частичному)</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'filter' => 'Иван', // (string) именя персоны (полное/частичное)
);

var_export(
    $era->person->api('personsfilter', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'filter' => 'Иван',
);

var_export($era->person->api('personsfilter', $params)) ?>
        </code>
    </pre>
</div>
<h2>Получение ключа персоны по публичному ключу создателя персоны</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$params = array(
    'public_key' => 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11', // публичный ключ создателя персоны
);

var_export(
    $era->person->api('personkeybyownerpublickey', $params)
);
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php
$params = array(
    'public_key' => 'J2U4UVuJd4zFxCbwg2YemAtS24GxApEJsNzphYrfd11',
);

var_export($era->person->api('personkeybyownerpublickey', $params)) ?>
        </code>
    </pre>
</div>
</body>
</html>
