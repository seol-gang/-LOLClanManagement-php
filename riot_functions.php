<?php
error_reporting(E_ALL&~E_NOTICE&~E_WARNING);

$api = "RIOT_API_KEY";

function getClanUserIdList()
{
    $clanid_list = array();
    $conn = mysqli_connect("localhost", "ID", "PW", "DB_NAME");
    $result = mysqli_query($conn, "SELECT user_id FROM TABLE_NAME");
    while($row = mysqli_fetch_array($result)){
        array_push($clanid_list, $row["user_id"]);
    }
    return $clanid_list;
}

function getClanNick()
{
    $clan_nick = array();
    $conn = mysqli_connect("localhost", "ID", "PW", "DB_NAME");
    $result = mysqli_query($conn, "SELECT nickname FROM TABLE_NAME");
    while($row = mysqli_fetch_array($result)){
        array_push($clan_nick, $row["nickname"]);
    }
    return $clan_nick;
}

function getUserId($user_name)
{
    global $api;
    $url = "https://kr.api.riotgames.com/lol/summoner/v4/summoners/by-name/" . urlencode(preg_replace("/\s+/", "", $user_name)) . "?api_key=" . $api;
    $is_port = false;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

    return $result['accountId'];
}

function getUserMatchList($user_id)
{
    global $api;
    $url = "https://kr.api.riotgames.com/lol/match/v4/matchlists/by-account/" . $user_id . "?beginTime=" . (round(microtime(true) * 1000) - 1209600000) . "&api_key=" . $api;
    $is_port = false;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $response = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);
    $match_list = array();
    foreach ($result['matches'] as $key => $value) {
        foreach ($value as $k => $v) {
            if (!strcmp($k, "gameId")) {
                array_push($match_list, $v);
            }
        }
    }
    return $match_list;
}

function check2WeeksClanMatch($match_list, $user_id)
{
    global $api;
    $player_list = array();
    $url = "https://kr.api.riotgames.com/lol/match/v4/matches/";
    foreach ($match_list as $key => $match_id) {
        $tmp_url = $url . trim($match_id) . "?api_key=" . $api;
        $is_port = false;
        usleep(500000);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tmp_url);
        curl_setopt($ch, CURLOPT_POST, $is_post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        foreach($result["participantIdentities"] as $k => $v){
            array_push($player_list, $v["player"]["accountId"]);
        }
    }
    $clan_list = getClanUserIdList();

    foreach ($player_list as $k => $v){
        foreach ($clan_list as $k_1 => $v_2){
            if(!strcmp($v, $user_id)){
                continue;
            }
            if(!strcmp($v, $v_2)) {
                return 1;
            }
        }
    }
    return 0;
}

?>