# Prueba para leer api de Mercadolibre
Se presento el challenge de realizar un codigo para crear logs de publicaciones de Mercadolibre con ciertos requisitos.
El codigo se realizo en php constando de dos archivos
* ml.php es el entry point por web donde definimos parametros obtenidos al generar la aplicacion en Mercadolibre https://developers.mercadolibre.com.ar/
* mlredirect.php es el codigo que recibe el callback inicial con un parametro "code" y obtiene el access token para poder hacer la consulta a la API
* archivo txt ejemplo de log obtenido

## Prueba en vivo
El codigo se encuentra en vivo para probarlo..
[Link para verlo en funcionamiento](https://redengo.com/bots/qrcheck/ml.php)
https://redengo.com/bots/qrcheck/ml.php

Al seguir el link obtendremos un archivo de log y tambien tendremos acceso a la carpeta donde se muestran logs anteriores generados

## Pasos para utilizar el codigo
1. Generar una aplicacion en https://developers.mercadolibre.com.ar/
2. Obtenemos ID de la aplicacion, que colocamos como variable client_id en ml.php
3. Al crear la aplicacion nos piden definir una url de redireccion que sera la variable redirect_uri en ml.php (en este ejemplo seteamos mlredirect.php)
4. Luego en mlredirect.php debemos completar las variables de postfields con los valores anteriores y con client_secret que se obtiene al editar la aplicacion en https://developers.mercadolibre.com.ar/
5. En mlredirect.php existe un array sellers con los seller ids que queremos registrar en el log.


Tecnologias utilizadas PHP 
