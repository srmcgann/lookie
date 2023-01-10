<?php
	require("db.php");
	require("functions.php");
	$sql="SELECT * FROM images WHERE autodelete=1";
	$res=$link->query($sql);
	for($i=0;$i<mysqli_num_rows($res);++$i){
		$row=mysqli_fetch_assoc($res);
		$age=strtotime("now")-strtotime($row['date']);
		if($age>64800){
			$shortName=$row['shortName'];
			$name=$row['name'];
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
			echo "Deleted $name\n";
		}
	}
?>
