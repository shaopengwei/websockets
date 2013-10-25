<?php
//连接数据库
$q=$_GET["q"];

$con = mysql_connect('localhost', 'root', 'shao');
if (!$con)
 {
 die('连接数据库失败: ' . mysql_error());
 }

mysql_select_db("chatroom", $con);

$q=$_GET["q"];

$sql="INSERT INTO sent(message,add_time) VALUES ('{$q}',now())";

$result = mysql_query($sql);

mysql_close($con);
?>
