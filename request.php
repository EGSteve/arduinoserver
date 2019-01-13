<?php

    // 與資料庫做連結
    $dbusername = "arduinoUser";
    $dbpassword = "123456";
    $server = "localhost";
    $dbconnect = mysqli_connect($server, $dbusername, $dbpassword);
    $dbselect = mysqli_select_db($dbconnect, "request");
    //POST方法為測試時使用
    if(isset($_POST["x"])){
      $x_in = $_POST["x"];
      $y_in = $_POST["y"];
      echo "X : ".$x_in.", Y : ".$y_in;
      $sql = "INSERT INTO request.api (x_in, y_in) VALUES ('$x_in', '$y_in')";
      mysqli_query($dbconnect, $sql);
    }elseif(isset($_POST["x_map"])){
      $x_map = $_POST["x_map"];
      $y_map = $_POST["y_map"];
      echo "your input ==> X : ".$x_map.", Y : ".$y_map;
      $sql = "INSERT INTO request.request (x_map, y_map) VALUES ('$x_map', '$y_map')";
      mysqli_query($dbconnect, $sql);
      $json_array=array();
      $sql_r="SELECT id, x_map, y_map FROM request ORDER BY id ASC";
      $records=mysqli_query($dbconnect,$sql_r);
      while($row=mysqli_fetch_assoc($records))
      {
        $json_array[]=$row;
      }
      $json_latest = end($json_array);
      $json = json_encode($json_latest);
      $fp = fopen('map5.json', 'w');
      fwrite($fp, $json);
      fclose($fp);
      mysqli_close($dbconnect);

      //GET為Arduino傳資料時使用
    }elseif (isset($_GET["x_in"])) {
      $x_in = $_GET["x_in"];
      $y_in = $_GET["y_in"];
      echo "your input ==> X : ".$x_in.", Y : ".$y_in;
      $sql = "INSERT INTO request.api (x_in, y_in) VALUES ('$x_in', '$y_in')";
      mysqli_query($dbconnect, $sql);

    }elseif(isset($_GET["x_map"]) ){
      $x_map = $_GET['x_map'];
      $y_map = $_GET['y_map'];
      date_default_timezone_set("Asia/Taipei");
      echo date('m/d，ha:i:s');
      //Arduino回傳的x和y座標分別寫入database"request"中的table"request"裡的column"x_map"和column"y_map"
      $sql = "INSERT INTO request.request (x_map, y_map) VALUES ('$x_map', '$y_map')";
      mysqli_query($dbconnect, $sql);
      //將資料庫裡的column"x_map"和column"y_map"取出做成JSON檔map
      $sql_r="SELECT id, x_map, y_map FROM request ORDER BY id ASC";
      $records=mysqli_query($dbconnect,$sql_r);
      $json_array=array();
      while($row=mysqli_fetch_assoc($records)){$json_array[]=$row;}
      $json_latest = end($json_array);
      echo json_encode($json_latest);
      $json = json_encode($json_latest);
      $fp = fopen('map5.json', 'w');
      fwrite($fp, $json);
      fclose($fp);
      mysqli_close($dbconnect);
    }


?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>MAP</title>
  </head>
  <body>
    <form method="POST" action="request.php">
      <div>
        <label>X</label><br>
        <input type="text" name="x">
      </div>
      <div>
        <label>Y</label><br>
        <input type="text" name="y">
      </div>
      <input type="submit" value="Submit">
    </form>

    <form method="POST" action="request.php">
      <div>
        <label>X_map</label><br>
        <input type="text" name="x_map">
      </div>
      <div>
        <label>Y_map</label><br>
        <input type="text" name="y_map">
      </div>
      <input type="submit" value="Submit">
    </form>
  </body>
</html>
