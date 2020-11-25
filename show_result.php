<?php
header('Content-Type: text/html; charset=UTF-8');
include "./riot_functions.php";

$nick = $_POST['nick'];

$conn = mysqli_connect("localhost", "ID", "PW", "DB_NAME");
$result = mysqli_query($conn, "SELECT user_id FROM TABLE_NAME WHERE nickname='$nick'");
$row = mysqli_fetch_array($result);
$user_id=$row['user_id'];

$match_list = getUserMatchList($user_id);
$result = check2WeeksClanMatch($match_list, $user_id);

if ($result == 1) {
    print_r("$nick 님은 2주 이내에 클랜원들과 함께 게임을 플레이하셨습니다.");
} else {
    print_r("$nick 님은 2주 이내에 클랜원들과 함께 게임을 플레이하지 않았습니다.");
}

?>