<?

	class ffprobe
	{
		public function __construct($filename, $prettify = false)
		{
			if (!file_exists($filename)) {
				throw new Exception(sprintf('File not exists: %s', $filename));
			}
			$this->__metadata = $this->__probe($filename, $prettify);
		}
		private function __probe($filename, $prettify)
		{
			// Start time
			$init = microtime(true);
			// Default options
			$options = '-loglevel quiet -show_format -show_streams -print_format json';
			if ($prettify) {
				$options .= ' -pretty';
			}
			// Avoid escapeshellarg() issues with UTF-8 filenames
			setlocale(LC_CTYPE, 'en_US.UTF-8');
			// Run the ffprobe, save the JSON output then decode
			$json = json_decode(shell_exec(sprintf('ffprobe %s %s', $options,
				escapeshellarg($filename))));
			if (!isset($json->format)) {
				throw new Exception('Unsupported file type');
			}
			// Save parse time (milliseconds)
			$this->parse_time = round((microtime(true) - $init) * 1000);
			return $json;
		}
		public function __get($key)
		{
			if (isset($this->__metadata->$key)) {
				return $this->__metadata->$key;
			}
			throw new Exception(sprintf('Undefined property: %s', $key));
		}
	}

    // Read 24bit BMP files
    // Author: de77
    // Licence: MIT
    // Webpage: de77.com
    // Version: 07.02.2010
    function imageCreateFromBmp2($filename) {
        $f = fopen($filename, "rb");

        //read header    
        $header = fread($f, 54);
        $header = unpack('c2identifier/Vfile_size/Vreserved/Vbitmap_data/Vheader_size/'.
        'Vwidth/Vheight/vplanes/vbits_per_pixel/Vcompression/Vdata_size/'.
        'Vh_resolution/Vv_resolution/Vcolors/Vimportant_colors', $header);
        if ($header['identifier1'] != 66 or $header['identifier2'] != 77)
            return false;

        if ($header['bits_per_pixel'] != 24)
            return false;

        $wid2 = ceil((3 * $header['width']) / 4) * 4;

        $wid = $header['width'];
        $hei = $header['height'];
        $img = imagecreatetruecolor($header['width'], $header['height']);

        //read pixels
        for ($y = $hei - 1; $y >= 0; $y--) {
            $row = fread($f, $wid2);
            $pixels = str_split($row, 3);

            for ($x = 0; $x < $wid; $x++) {
                imagesetpixel($img, $x, $y, dwordize($pixels[$x]));
            }
        }
        fclose($f);
        return $img;
    }
    function dwordize($str) {
        $a = ord($str[0]);
        $b = ord($str[1]);
        $c = ord($str[2]);
        return $c * 256 * 256 + $b * 256 + $a;
    }


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
				$im = imageCreateFromBmp2($filepath); 
			break; 
		}    
		return $im;
	}
	
	function stripEXIF($src){
		if(strpos(strtoupper($src), '.JFIF')) return;
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

	function makeVideoThumb($src, $dest, $shortName){
		global $link;
		if(filesize($src)){
			$info = new ffprobe($src,false);
			$bitrate=$info->format->bit_rate;
		}
		if($bitrate>2500000 && substr($src,strlen($src)-3)=="mp4"){
			$cmd="ffmpeg -i \"$src\" -c:v libx264 -profile:v high -preset veryfast -b:v 2000k -maxrate 2000k -bufsize 4000k -c:a aac -strict -2 \"$src.mp4\"";
			exec($cmd);
			rename("$src.mp4",$src);
		}
		if(substr($src,strlen($src)-4)=="webm"){
			$new="uploads/$shortName.mp4";
			$dest="$new.jpg";
			$cmd="ffmpeg -fflags +genpts -i \"$src\" -r 24 -strict -2 $new";
			exec($cmd);
			unlink($src);
			$src=$new;
			$id=alphaToDec($shortName);
			$sql="UPDATE images SET type=\"video/mp4\" WHERE id=$id";
			$link->query($sql);
		}
		$cmd="ffmpeg -ss \"00:00:02\" -i \"$src\" -vframes 1 -filter:v scale=\"280:-1\" -y \"$dest\"";
		exec($cmd);
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
		$fileName=str_replace("%20"," ",mysqli_real_escape_string($link,$fileName));
		do{
			$id=rand();
			$sql="SELECT * FROM images WHERE id=$id";
			$res=$link->query($sql);
		}while(mysqli_num_rows($res));
		if($type=="video/webm") $type="video/mp4";
    if($type=="application/octet-stream") $type="video/mp4";
		$sql1="INSERT INTO images (lastviewed,date,IP,type,name,id,public,shortName,views) VALUES(\"$date\",\"$date\",\"$IP\",\"$type\",\"$fileName\",$id,0,\"\",0)";
		$link->query($sql1);
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
			case "video/ogg":return ".ogv"; break;
			case "application/x-shockwave-flash":return ".swf"; break;
		}
	}
	
	function ipToDec($ip){
		$parts=explode(".",$ip);
		return $parts[0]*pow(2,24)+$parts[1]*pow(2,16)+$parts[2]*pow(2,8)+$parts[3];
	}
	
	function assetType($type){		
		switch($type){
			case "image/jpeg": return "Image"; break;
			case "image/png": return "Image"; break;
			case "image/gif": return "Image"; break;
			case "image/bmp": return "Image"; break;
			case "video/mp4": return "Video"; break;
			case "video/webm": return "Video"; break;
			case "video/ogg": return "Video"; break;
			case "application/x-shockwave-flash": return "Flash"; break;
		}
	}
	function retrieve_remote_file_size($url){

		/*
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
		curl_setopt($ch, CURLOPT_NOBODY, TRUE);
		curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		$data = curl_exec($ch);
		$size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		curl_close($ch);
		return $size;
		*/
		$head = array_change_key_case(get_headers($url, TRUE));
		return $head['content-length'];
	}
	
	function exists($url){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$r = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $r==200?1:0;
	}
	
	function prefix($shortName){
		global $link;
		$sql="SELECT type FROM images WHERE shortName = \"$shortName\"";
		$res=$link->query($sql);
		$row=mysqli_fetch_assoc($res);
		if($row['type']=="image/jpeg" ||
		   $row['type']=="image/png" ||
		   $row['type']=="image/gif" ||
		   $row['type']=="image/bmp")$t="i-";
		if($row['type']=="video/mp4" ||
		   $row['type']=="application/x-shockwave-flash" ||
		   $row['type']=="video/webm" ||
		   $row['type']=="video/ogg")$t="v-";
		return $t;
	}
?>
