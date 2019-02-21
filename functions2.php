<?	
	function imageCreateFromAny($filepath) { 
		$type = exif_imagetype($filepath);
		$allowedTypes = array( 
			1,  // [] gif 
			2,  // [] jpg 
			3,  // [] png 
			6   // [] bmp 
		); 
		if (!in_array($type, $allowedTypes)) { 
			return false; 
		} 
		switch ($type) { 
			case 1 : 
				$im = imageCreateFromGif($filepath); 
			break; 
			case 2 : 
				$im = imageCreateFromJpeg($filepath); 
			break; 
			case 3 : 
				$im = imageCreateFromPng($filepath); 
			break; 
			case 6 : 
				$im = imageCreateFromBmp($filepath); 
			break; 
		}    
		return $im;
	}
	
	function stripEXIF($src){
		//strip EXIF data
		$img = new Imagick($src);
		$img->stripImage();
		$img->writeImage($src);
		$img->clear();
		$img->destroy();		
	}
	
	function makeImageThumb($src, $dest) {

		$source_image = imagecreatefromAny($src);
		$width = imagesx($source_image);
		$height = imagesy($source_image);		
		if($width>$height){
			$w=250;
			$h = floor($height * ($w / $width));
		}else{
			$h=250;
			$w = floor($width * ($h / $height));			
		}
		$virtual_image = imagecreatetruecolor($w,$h);
		imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $w, $h, $width, $height);
		imagejpeg($virtual_image, $dest, 75);
	}

	function makeVideoThumb($src, $dest){
		
		$cmd="ffmpeg -ss \"00:00:02\" -i \"$src\" -vframes 1 -filter:v scale=\"280:-1\" -y \"$dest\"";
		exec($cmd);
		if(substr($src,strlen($src)-3)=="mp4"){
			$cmd="ffmpeg -i \"$src\" -c:v libx264 -profile:v high -preset veryfast -b:v 1600k -maxrate 1600k -bufsize 3200k -c:a aac -strict -2 \"$src.mp4\"";
			exec($cmd);
			if(filesize($src.".mp4")<filesize($src) || filesize($src)<16000000){
				$cmd="mv \"$src.mp4\" \"$src\"";
			}else{
				unlink($src.".mp4");
			}
			exec($cmd);
		}
	}
	
	function decToAlpha($val){
		$alphabet="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$ret="";
		while($val){
			$r=floor($val/62);
			$frac=$val/62-$r;
			$ind=(int)round($frac*62);
			$ret=$alphabet[$ind].$ret;
			$val=$r;
		}
		return $ret==""?"0":$ret;
	}

	function alphaToDec($val){
		$pow=0;
		$res=0;
		while($val!=""){
			$cur=$val[strlen($val)-1];
			$val=substr($val,0,strlen($val)-1);
			$mul=ord($cur)<58?$cur:ord($cur)-(ord($cur)>96?87:29);
			$res+=$mul*pow(62,$pow);
			$pow++;
		}
		return $res;
	}
	
	function formatBytes($bytes, $precision = 2) { 
		$units = array('B', 'KB', 'MB', 'GB', 'TB');
		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
		// Uncomment one of the following alternatives
		//$bytes /= pow(1024, $pow);
		$bytes /= (1 << (10 * $pow)); 
		return round($bytes, $precision) . ' ' . $units[$pow]; 
	}

	function getNewName($fileName, $type){
		global $link;
		$date=date("Y-m-d H:i:s",strtotime("now"));
		$IP=$_SERVER['REMOTE_ADDR'];
		$fileName=mysqli_real_escape_string($link,$fileName);
		do{
			$id=rand();
			$sql="SELECT * FROM images WHERE id=$id";
			$res=$link->query($sql);
		}while(mysqli_num_rows($res));
		$sql="INSERT INTO images (date,IP,type,name,id,public,shortName,views) VALUES(\"$date\",\"$IP\",\"$type\",\"$fileName\",$id,0,\"\",0)";
		$link->query($sql);
		$newName=decToAlpha($id);
		$sql="UPDATE images SET shortName = \"$newName\" WHERE id = $id";
		$link->query($sql);
		return $newName;
	}
	
	function suffix($type){
		switch($type){
			case "image/jpeg":return ".jpg"; break;
			case "image/png":return ".png"; break;
			case "image/gif":return ".gif"; break;
			case "image/bmp":return ".bmp"; break;
			case "video/mp4":return ".mp4"; break;
			case "video/webm":return ".webm"; break;
			case "video/ogg":return ".ogg"; break;
		}
	}
	function ipToDec($ip){
		$parts=explode(".",$ip);
		return $parts[0]*pow(2,24)+$parts[1]*pow(2,16)+$parts[2]*pow(2,8)+$parts[3];
	}
?>