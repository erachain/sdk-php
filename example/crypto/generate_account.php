<?php
header('Content-Type: text/html; charset=utf-8', true);
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', '1');

require_once '../../vendor/autoload.php';

use Erachain\Erachain;

$era = new Erachain();

$seed_base58    = 'BXe6d6TqrfoBFGW1TxJcJZPzcm3zpgLLht75MLSJa1aM';
$number_account = 0;
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Генерация аккаунта</title>

    <link rel="stylesheet" href="../lib/highlight/solarized-dark.css">
    <script src="../lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<h1>Генерация аккаунта</h1>
<h2>Без указания сида</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();
var_dump($era->crypto->generate_account());
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php var_dump($era->crypto->generate_account()) ?>
        </code>
    </pre>
</div>
<h2>Генерация по сиду и номеру аккаунта</h2>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();
$seed_base58 = 'BXe6d6TqrfoBFGW1TxJcJZPzcm3zpgLLht75MLSJa1aM'; // Cид в base58
$number_account = 0; // Порядковый номер аккаунта с данным сидом (по умолчанию для первого аккаунта - 0)

var_dump($era->crypto->generate_account($seed_base58, $number_account));
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php var_dump($era->crypto->generate_account($seed_base58, $number_account)) ?>
        </code>
    </pre>
</div>
</body>
</html>
