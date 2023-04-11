<?php

header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000');
header('Content-type:application/json; charset=utf-8');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
header('X-Robots-Tag: noindex, nofollow', true);

function CORS_HEADERS_HANDLER(){
    if (isset($_SERVER['HTTP_ORIGIN'])){
      switch($_SERVER['HTTP_ORIGIN']){
        case 'http://localhost:6001':
          header('Access-Control-Allow-Origin: http://localhost:6001');
          break;
        case 'https://n8n.yoursite.com':
          header('Access-Control-Allow-Origin: https://n8n.yoursite.com');
          break;
       }
    } else{ 
      header('Access-Control-Allow-Origin: https://yoursite.com');
    }
}
CORS_HEADERS_HANDLER();

$msg = [];
$url = '';

if(isset($_GET['message'])){
    if(!empty($_GET['message'])){

        $user_data = htmlspecialchars($_GET['message'], ENT_COMPAT);

        $url = "<YOUR n8n webhook POST method URL>";
            $headers = [
                "Content-Type: application/x-www-form-urlencoded"
            ];

        if ($user_data == 'n8n') {
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url);                                                              
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, "message=$user_data");
            curl_exec($ch);
            curl_close ($ch);
           $msg['message'] = 'true';
           echo json_encode($msg);
        } else {
            $msg['message'] = 'false';
            echo json_encode($msg);
        }

    } else {
        $msg['message'] = 'Oops! empty field detected. Please fill all the fields';
        echo json_encode($msg);
      }
    } else {
        $msg['message'] = 'Please fill all the fields';
        echo json_encode($msg);
}
