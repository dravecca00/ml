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