<?php

header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000');
header('Content-type:application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
header('X-Robots-Tag: noindex, nofollow', true);

function CORS_HEADERS_HANDLER(){
    if (isset($_SERVER['HTTP_ORIGIN'])){
      switch($_SERVER['HTTP_ORIGIN']){
        case 'https://challenges.cloudflare.com':
          header('Access-Control-Allow-Origin: https://challenges.cloudflare.com');
          break;
        case 'https://challenges.cloudflare.com/':
          header('Access-Control-Allow-Origin: https://challenges.cloudflare.com/');
          break;
       }
    } else{ 
      header('Access-Control-Allow-Origin: https://yourdomain.com');
    }
}
CORS_HEADERS_HANDLER();

$msg = [];
$url = '';
$userip = '';

if (!empty($_SERVER['REMOTE_ADDR'])) {
    $userip = $_SERVER['REMOTE_ADDR'];
}

if(isset($_GET['cfresponse'])){
    if(!empty($_GET['cfresponse'])){

        $user_data = htmlspecialchars($_GET['cfresponse'], ENT_COMPAT);

        $url = "https://challenges.cloudflare.com/turnstile/v0/siteverify";
            $headers = [
                "Content-Type: application/x-www-form-urlencoded"
            ];
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url);                                                              
            curl_setopt( $ch, CURLOPT_POST, 1);
            curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch,CURLOPT_POSTFIELDS, "secret=<YOUR TURNSTILE SECRET KEY>&remoteip=$userip&response=$user_data");
            $cfstatus = curl_exec($ch);
            curl_close ($ch);
           $msg = $cfstatus;
           echo $msg;
    } else {
        $msg['message'] = 'Oops! empty field detected. Please fill all the fields';
        echo json_encode($msg);
      }
    } else {
        $msg['message'] = 'Please fill all the fields';
        echo json_encode($msg);
}