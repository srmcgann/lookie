<?
	require("db.php");
	require("functions.php");

	if(isset($_POST['file'])){
		$type=mime_content_type("temp/".$_POST['file']);
		if($type=="image/x-ms-bmp")$type="image/bmp";
    if($type=="application/octet-stream")$type="video/mp4";
		if($type=="application/x-shockwave-flash2-preview")$type="application/x-shockwave-flash";
		if($type=="application/x-shockwave-flash")$type="application/x-shockwave-flash";
		if($type=="application/futuresplash")$type="application/x-shockwave-flash";
		if($type=="image/vnd.rn-realflash")$type="application/x-shockwave-flash";
		if($type=="application/x-download")$type="application/x-shockwave-flash";
		$shortName=getNewName($_POST['file'],$type);
		$fileName='uploads/'.$shortName.suffix($type);
		$src="temp/".$_POST['file'];
		rename($src, $fileName);

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
			switch($type){
				case "image/jpeg":
					stripEXIF($fileName);
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
        if($type=="video/webm")$type="video/mp4";
		$autodelete=mysqli_real_escape_string($link,$_POST['autodelete']);
		$sql="UPDATE images SET type=\"$type\", base = \"$base\", hash=\"$hash\", origin=\"$origin\", artist=\"$artist\", description=\"$description\", autodelete=$autodelete WHERE id = $id";
		$link->query($sql);
		
		$url=prefix($shortName).$shortName;
		echo $url;
 	}

?>
