<?php
// luego de generar una aplicacion en MercadoLibre asociada a una cuenta
// obtenemos el client_id y la url de redireccion fue seteada por nosotros
// al crear la aplicacion antes mencionada
// en la redirect_uri obtenemos un codigo para continuar con el proceso
// en este caso continuamos con el archivo mlredirect.php

$client_id = 3573083926674623;
$redirect_uri = "https://redengo.com/bots/qrcheck/mlredirect.php";
$url =  "http://auth.mercadolibre.com.ar/authorization?response_type=code&client_id=$client_id&redirect_uri=$redirect_uri";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test MercadoLibre Api</title>
</head>
<body>
    <a href="<?php echo $url;?>">Proceder</a>
</body>
</html>