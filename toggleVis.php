<?
	if($_POST['shortName']){
		require("db.php");
		require("functions.php");
		$id=alphaToDec($_POST['shortName']);
		$public=$_POST['pub'];
		$sql="UPDATE images SET public=$public WHERE id=$id";
		$link->query($sql);
		echo $public;
	}
?>