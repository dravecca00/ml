<?php
// pasado desde ml.php que inicia el proceso y llega aca por estar
// definida como redirect_uri en app que creamos en MercadoLibre
// nos pasan el valor de {code}
$codeapp = $_GET['code'];

function SendPost($url, $params){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
function conseguirListado($url, $custom_headers){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $custom_headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
// Authorization: Bearer APP_USR-12345678-031820-X-12345678
// App ID: 3573083926674623
// Secret Key: xxcgpBPmpMBSuUijvfsfbYNJrSp16hoQ
// http://auth.mercadolibre.com.ar/authorization?response_type=code&client_id=3573083926674623&redirect_uri=https://redengo.com/bots/qrcheck/mlredirect.php
// https://redengo.com/bots/qrcheck/mlredirect.php?code=TG-604d7d4572032500084a36b0-221128757


/*
curl -X POST \
-H 'accept: application/json' \
-H 'content-type: application/x-www-form-urlencoded' \
'https://api.mercadolibre.com/oauth/token' \
-d 'grant_type=authorization_code' \
-d 'client_id=3573083926674623' \
-d 'client_secret=xxcgpBPmpMBSuUijvfsfbYNJrSp16hoQ' \
-d 'code=
TG-604d763674750900075091f1-221128757
TG-604f4fcf87c6ed000871b8bf-221128757' \
-d 'redirect_uri=https://redengo.com/bots/qrcheck/mlredirect.php'

{
    "access_token": "APP_USR-3573083926674623-031403-e13b49ee1cf4f4536d301ba26b26577b-221128757",
    "token_type": "bearer",
    "expires_in": 21600,
    "scope": "read",
    "user_id": 221128757
}

curl -X GET -H 'Authorization: Bearer $ACCESS_TOKEN' https://api.mercadolibre.com/sites/$SITE_ID/search?seller_id=$SELLER_ID
https://api.mercadolibre.com/sites/MLA/search?seller_id=179571326";

*/
// buscar access token
$postfields = [
    'grant_type' => 'authorization_code', 
    'client_id' => '3573083926674623',
    'client_secret' => 'xxcgpBPmpMBSuUijvfsfbYNJrSp16hoQ',
    'code' => $codeapp,
    'redirect_uri' => 'https://redengo.com/bots/qrcheck/mlredirect.php'
];
// obtenemos el access token y lo pasamos a un array
$oauthToken =json_decode(SendPost("https://api.mercadolibre.com/oauth/token", $postfields), true);

//{"access_token":"APP_USR-3573083926674623-031512-2120937247b77e09d98d4d42fc7ccae9-221128757","token_type":"bearer","expires_in":21600,"scope":"read","user_id":221128757}" 
//array(5) { ["access_token"]=> string(74) "APP_USR-3573083926674623-031512-49f8a0d81d61e9b62f33419d07f6b8dd-221128757" ["token_type"]=> string(6) "bearer" ["expires_in"]=> int(21600) ["scope"]=> string(4) "read" ["user_id"]=> int(221128757) }

$ACCESS_TOKEN = $oauthToken["access_token"];
$SITE_ID = "MLA";
$sellers = array(179571326);
foreach ($sellers as $SELLER_ID){
    $url = "https://api.mercadolibre.com/sites/$SITE_ID/search?seller_id=$SELLER_ID";
    $custom_headers = array("Authorization: Bearer $ACCESS_TOKEN");
    $listado = conseguirListado($url,$custom_headers );
    $arr = json_decode($listado, true);
    $textlog = "";
    $salidahtml = "";

    if(array_key_exists('results',$arr)){
        foreach($arr["results"] as $item){
            // id, title, category_id, name
            $textlog.= $item["id"].", ".$item["title"].", ".$item["category_id"].", ".$item["domain_id"]."\n";
            }
        $nombrearchivo = date("Y-m-d_H-i-s")."+".$SELLER_ID.".txt";
        $archivolog=$_SERVER["DOCUMENT_ROOT"]."/logs/".$nombrearchivo;
        $fp = fopen($archivolog,"w");
        fwrite($fp,utf8_decode($textlog));
        //fclose($fp);
        $salidahtml.="<a href='https://redengo.com/logs/$nombrearchivo'>$nombrearchivo  -- OK</a><br><h2><a href='https://redengo.com/logs/'>Link a carpeta de logs</a></h2>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php echo $salidahtml;?>
</body>
</html>