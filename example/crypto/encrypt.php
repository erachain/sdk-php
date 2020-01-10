<?php
header('Content-Type: text/html; charset=utf-8', true);
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', '1');

require_once '../../vendor/autoload.php';

use Erachain\Erachain;

$era = new Erachain();

$message     = 'Это сообщение нужно зашифровать';
$public_key  = '4MCNiC7ziMvufkMs2rihvrQCQepXkpTW3Jku6hR5bDBn';
$private_key = '5wndPtWGG1EyEWxcv5eyqnamo5VyPCGqpn7T8TwyrQTB9oLT8de331mtDiMHztdxwDVQo2JFoAzHyyf922RmJxnC';
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Шифрование данных</title>

    <link rel="stylesheet" href="../lib/highlight/solarized-dark.css">
    <script src="../lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<h1>Шифрование данных</h1>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$message = 'Это сообщение нужно зашифровать'; //сообщение которое нужно зашифровать
$public_key = '4MCNiC7ziMvufkMs2rihvrQCQepXkpTW3Jku6hR5bDBn'; //публичный ключ получателя
$private_key = '5wndPtWGG1EyEWxcv5eyqnamo5VyPCGqpn7T8TwyrQTB9oLT8de331mtDiMHztdxwDVQo2JFoAzHyyf922RmJxnC'; //приватный ключ отправителя

var_dump($era->crypto->encrypt($message, $public_key, $private_key));
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php var_dump($era->crypto->encrypt($message, $public_key, $private_key)) ?>
        </code>
    </pre>
</div>
</body>
</html>
