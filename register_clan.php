<?php
header('Content-Type: text/html; charset=UTF-8');
include "./PHPExcel-1.8/Classes/PHPExcel.php";
include "./riot_functions.php";

function uploadFileSave()
{
  $file = $_FILES["excelFile"];
  move_uploaded_file($file["tmp_name"], "./" . $file["name"]);
}

function dbUpload($data)
{
  try {
    $conn = mysqli_connect("localhost", "ID", "PW", "DB_NAME");

    mysqli_query($conn, "delete from TABLE_NAME");
    mysqli_query($conn, "ALTER TABLE TABLE_NAME AUTO_INCREMENT=1");


    foreach ($data as $key => $line) {
      $col = 0;
      $item = array("A" => $line[$col++], "B" => $line[$col++], "C" => $line[$col++], "D" => PHPExcel_Style_NumberFormat::toFormattedString($line[$col++], PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2), "E" => $line[$col++], "F" => $line[$col++], "G" => $line[$col++], "H" => $line[$col++], "I" => $line[$col++], "J" => $line[$col++], "K" => $line[$col++]);
      if ($key == 0) continue;
      $user_id = getUserId($item["F"]);
      usleep(100000);
      $sql = "INSERT INTO TABLE_NAME (odd_num,position,join_date,nickname,tier,age,gender,1st_line,2st_line,user_id) VALUES ('" . $item["B"] . "','" . $item["C"] . "','" . $item["D"] . "','" . $item["F"] . "','" . $item["G"] . "','" . $item["H"] . "','" . $item["I"] . "','" . $item["J"] . "','" . $item["K"] . "','" . $user_id ."')";
      mysqli_query($conn, $sql);
    }
    mysqli_close($conn);
  } catch (exception $exception) {
    echo $exception;
  }
}

function getExcelData()
{
  $objPHPExcel = new PHPExcel();
  $allData = array();
  $filename = $_FILES['excelFile']['name'];

  try {
    $objPHPExcel = PHPExcel_IOFactory::load($filename);
    $sheet = $objPHPExcel->getSheet(0);
    $maxRow = $sheet->getHighestRow();
    $maxColumn = $sheet->getHighestColumn();
    $target = "A" . "1" . ":" . "$maxColumn" . "$maxRow";
    $lines = $sheet->rangeToArray($target, NULL, TRUE, FALSE);

    return $lines;
  } catch (exception $exception) {
    echo $exception;
  }
}

uploadFileSave();
$data = getExcelData();
dbUpload($data);
echo "<script>location.replace('MAIN_PAGE')</script>";
?>