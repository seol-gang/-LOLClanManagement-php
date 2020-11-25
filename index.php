<html>
<head>
    <style type="text/css">
    table, td{
        border: 1px solid black
    }
    table {
        width:auto;
        height:100%;
        margin: auto;
        text-align:center;
    }
    </style>
    <meta charset="utf-8">
    <title>Darwin Clan</title>
</head>
<body>
    <div style="text-align:center">
    <h1>Darwin Clan<br>2주 이내 게임 플레이 확인 사이트</h1>
    </div>
    <table border=1>
        <th>닉네임</th>
        <?php
            include "./riot_functions.php";
            $nick_list = getClanNick();
        ?>
        <form action="./show_result.php" method="post">
            <?php
                foreach ($nick_list as $key => $value) {
                    echo "<tr><td><input type=\"submit\" name=\"nick\" value=\"$value\"></td></tr>";
                }
            ?>
        </form>
    </table>
</body>

</html>