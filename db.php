<?
	$db_user="user";
  $db_pass=explode("\n", file_get_contents('/home/cantelope/plorgpw'))[0];
	$db_host="localhost";
	$db="lookie";
	$baseURL="http://jsbot.whitehot.ninja/";

	$link = mysqli_connect($db_host, $db_user, $db_pass, $db);
?>
