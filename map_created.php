<html>
<head>
  <title>WiFi Controlled Robot</title>
</head>
<body style="background-color: #02005B;color: rgba(255,255,255,0.9);letter-spacing: 1px;">
<?php
//跟資料庫做連結
	$dbusername = "arduinoUser";
  $dbpassword = "123456";
  $server = "localhost";
  $dbconnect = mysqli_connect($server, $dbusername, $dbpassword);
  $dbselect = mysqli_select_db($dbconnect, "request");

	//read x_in,y_in from request.api (api table是用來做測試，確認點選button後有回傳正確的座標)
	$sql_r="SELECT id, x_in, y_in FROM api ORDER BY id ASC";
	$records=mysqli_query($dbconnect,$sql_r);
	$json_array=array();
//	$json_xarray=array();
//	$json_yarray=array();

	while($row=mysqli_fetch_assoc($records))
	{
		$json_array[]=$row;

	}
  $json_latest = end($json_array);
  $json = json_encode($json_latest);
  $fp = fopen('results.json', 'w');
  fwrite($fp, $json);
  fclose($fp);

  mysqli_close($dbconnect);
?>


<h2 style="display: flex;justify-content:center;"> <i> ~自動導航車車~ </i> </h2>
<h5 style="margin-left:90%;"> <i> by 智凱 冠瑜 正安 </i> </h5>


<br>
<hr>
<br>

<div id="message"></div>
<div id="map" style="padding-left: 5px;border-style: solid;border-width: 0px 0px 0px 8px ;border-color:white;white-space:nowrap; "></div>

<script src="jquery.js"></script>
<script type="text/javascript">
//生成50*50的按鈕地圖
for(y = 0; y <= 50; y++){
  var div = document.createElement("div");
  div.setAttribute("style","display: flex;")
  div.id = "row"+y;
  document.getElementById("map").appendChild(div);
  for(x = 0; x<= 50; x++){
    var btn = document.createElement("BUTTON");
    var t = document.createTextNode("("+x+","+y+")");
    if(x==0&y==0){btn.setAttribute("style","width:50px;height:50px;font-size: 10px;text-align: center;text-decoration: none;border-radius: 50%;flex-shrink: 0;text-shadow:0 1px 0;border:4px double #ccc;background: green;");}
    else{btn.setAttribute("style","width:50px;height:50px;font-size: 10px;text-align: center;text-decoration: none;border-radius: 50%;flex-shrink: 0;text-shadow:0 1px 0;border:4px double #ccc;background: red;");}
    btn.className = "position";
    btn.appendChild(t);
    btn.id = x+"_"+y;
    document.getElementById("row"+y).appendChild(btn);
  }
}

		$(document).ready(function() {
				setInterval(renew, 100)
        var temp_id = "";
        var temp_x = "";
				function renew() {
            $.getJSON('map5.json', function(data) {
              // begin accessing JSON data here
              console.log(data.x_map);
              x = Math.abs(data.x_map);
              y = Math.abs(data.y_map);
              document.getElementById(x+"_"+y).style.background = "green";
                temp_id = data.id;
                temp_x = x;
                console.log(temp_id);
						});
				}

				$(document).on("click", ".position", function() {
						var cmd = $(this).attr('id');
						console.log(cmd);
						var axis = cmd.split("_");
						var x = axis[0];
						var y = axis[1];
            //send data to Arduino
						$.ajax({
								url: "http://192.168.137.13",
								data: "x_in=" + x + "&y_in=" + y,
								type: "GET",
								dataType: 'text',
								success: function(message) {
										document.getElementById("message").innerHTML = message;
								},
								error: function(jqXHR, textStatus, errorThrown) {
										document.getElementById("message").innerHTML = errorThrown;
								}
						});

				});

		});
</script>
</body>
</html>
