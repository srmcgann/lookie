<?
	if($_POST['shortName']){
		require("db.php");
		require("functions.php");
		$shortName=$_POST['shortName'];
		$id=alphaToDec($shortName);
		$sql="SELECT type, base FROM images WHERE id = $id";
		$res=$link->query($sql);
		$row=mysqli_fetch_assoc($res);
		$base=$row['base'];
		$suffix=suffix($row['type']);
		$sql="DELETE FROM images WHERE id=$id";
		$link->query($sql);
		$sql="DELETE FROM votes WHERE asset_id=$id";
		$link->query($sql);
		$sql="SELECT shortName FROM images WHERE base = \"$base\"";
		$res=$link->query($sql);
		if(mysqli_num_rows($res)==0){
			unlink("uploads/$base$suffix");
			unlink("uploads/$base$suffix.jpg");
		}
		echo $public;
	}
?>