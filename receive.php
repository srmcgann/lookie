<?
        require("db.php");
	require("functions.php");

	if(isset($_FILES['file'])){
		if($_FILES['file']['size'] < (100000000)){
			if($_FILES['file']['type']=="image/x-ms-bmp")$_FILES['file']['type']="image/bmp";
			if($_FILES['file']['type']=="application/x-shockwave-flash2-preview")$_FILES['file']['type']="application/x-shockwave-flash";
			if($_FILES['file']['type']=="application/x-shockwave-flash")$_FILES['file']['type']="application/x-shockwave-flash";
			if($_FILES['file']['type']=="application/futuresplash")$_FILES['file']['type']="application/x-shockwave-flash";
			if($_FILES['file']['type']=="image/vnd.rn-realflash")$_FILES['file']['type']="application/x-shockwave-flash";
			if($_FILES['file']['type']=="application/x-download")$_FILES['file']['type']="application/x-shockwave-flash";
			
			$shortName=getNewName($_FILES['file']['name'],$_FILES['file']['type']);
			$fileName='uploads/'.$shortName.suffix($_FILES['file']['type']);
			move_uploaded_file($_FILES['file']['tmp_name'], $fileName);
			
			$id=alphaToDec($shortName);
			$hash=hash_file("md5",$fileName);
			$sql="SELECT base FROM images WHERE hash=\"$hash\"";
			$res=$link->query($sql);
			if(mysqli_num_rows($res)){
				$row=mysqli_fetch_assoc($res);
				$base=$row['base'];
				unlink($fileName);
			}else{
				$base=$shortName;
				switch($_FILES['file']['type']){
					case "image/jpeg":
						if(strpos(strtoupper($fileName, '.JFIF')) === false) stripEXIF($fileName);
					case "image/png":
					case "image/gif":
					case "image/bmp":
						makeImageThumb($fileName,$fileName.".jpg");
						break;
					case "video/mp4":
					case "video/webm":
					case "video/ogg":
						makeVideoThumb($fileName,$fileName.".jpg",$shortName);
						break;
				}
			}
			$url="";
			$artist="";
			$description="";
			if(isset($_POST['origin']))$origin=htmlspecialchars(mysqli_real_escape_string($link,$_POST['origin']), ENT_QUOTES, 'utf-8');
			if(isset($_POST['artist']))$artist=htmlspecialchars(mysqli_real_escape_string($link,$_POST['artist']), ENT_QUOTES, 'utf-8');
			if(isset($_POST['description']))$description=htmlspecialchars(mysqli_real_escape_string($link,$_POST['description']), ENT_QUOTES, 'utf-8');
			$autodelete=mysqli_real_escape_string($link,$_POST['autodelete']);
			$sql="UPDATE images SET base = \"$base\", hash=\"$hash\", origin=\"$origin\", artist=\"$artist\", description=\"$description\", autodelete=$autodelete WHERE id = $id";
			//mysqli_query($link, $sql);
                        $link->query($sql);

			$url=prefix($shortName).$shortName;
			echo $url;
		}
	}
?>
