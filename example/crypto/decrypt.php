<?php
header('Content-Type: text/html; charset=utf-8', true);
error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', '1');

require_once '../../vendor/autoload.php';

use Erachain\Erachain;

$era = new Erachain();

$message     = '7wrhFVTVDESUpaTrZ3X2n3bp3g9HLTfLyirnfwwYDdsa6yD533AtS3UbETzEUgKqbd8kAZNvisX1rMBeNJSCaxxW';
$public_key  = 'Cek7WfsAWgFfGsVVwNp6CFC3EXNM5YkFJRpRvnhqhvS4';
$private_key = '2BjWXep7dWrw9vwV5jG4idVAQrUmm6mvdFVsfrNHG3rMQRUT4EgwaCt7s9HdkPabBsFypkTaT1qJxmREgV4e3oWg';
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Расшифровка данных</title>

    <link rel="stylesheet" href="../lib/highlight/solarized-dark.css">
    <script src="../lib/highlight/highlight.pack.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
</head>
<body>
<h1>Расшифровка данных</h1>
<div>
    <p>Код:</p>
    <pre>
        <code class="php">
use Erachain\Erachain;

$era = new Erachain();

$message = '7wrhFVTVDESUpaTrZ3X2n3bp3g9HLTfLyirnfwwYDdsa6yD533AtS3UbETzEUgKqbd8kAZNvisX1rMBeNJSCaxxW'; //зашифрованное сообщение
$public_key = 'Cek7WfsAWgFfGsVVwNp6CFC3EXNM5YkFJRpRvnhqhvS4'; //публичный ключ отправителя
$private_key = '2BjWXep7dWrw9vwV5jG4idVAQrUmm6mvdFVsfrNHG3rMQRUT4EgwaCt7s9HdkPabBsFypkTaT1qJxmREgV4e3oWg'; //приватный ключ получателя

var_dump($era->crypto->decrypt($message, $public_key, $private_key));
        </code>
    </pre>
    <p>Результат:</p>
    <pre>
        <code class="php">
<?php var_dump($era->crypto->decrypt($message, $public_key, $private_key)) ?>
        </code>
    </pre>
</div>
</body>
</html>
