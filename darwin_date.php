<?php
    header('Content-Type: text/html; charset=UTF-8');

    $text = $_GET['summoner_name'];
 
    $summoner_name = urlencode($text);
    $api_key = "RIOT_API_HERE";
    $url = "https://kr.api.riotgames.com/lol/summoner/v4/summoners/by-name/".$summoner_name."?api_key=".$api_key;
    $is_post = false;
    $ch = curl_init();
 
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
 
    $response = curl_exec ($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
 
    $result = json_decode($response, true);

    print_r($result);
?>