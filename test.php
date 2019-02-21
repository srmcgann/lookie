<?
	
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	echo long2ip(2886049363);
	/*
	function stripEXIF($src){
		//strip EXIF data
		$img = new Imagick($src);
		$img->stripImage();
		$img->writeImage($src);
		$img->clear();
		$img->destroy();		
	}
	stripEXIF("uploads/zhVHS.jpg");
	echo "done";
	*/

	
	/*
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
	echo alphaToDec("19LzFv");
	*/


	/*
	require("db.php");
	$sql="SELECT * FROM images";
	$res=$link->query($sql);
	while($row=mysqli_fetch_assoc($res)){
		if($row['shortName']!=$row['base']){
			$sql="SELECT type FROM images WHERE shortName=\"{$row['base']}\"";
			$res2=$link->query($sql);
			$row2=mysqli_fetch_assoc($res2);
			$sql="UPDATE images SET type = \"{$row2['type']}\" WHERE shortName=\"{$row['shortName']}\"";
			$link->query($sql);
		}
	}
	echo "done.";
	*/
	
	/*
	require("ffprobe.php");
	function getInfo(){
		$file="city_movie.mp4";
		$info = new ffprobe($file,false);
		echo $file.": bitrate: ".$info->format->bit_rate."<br>"; 		
	}
	getInfo();
	*/


		
	/*
	require("db.php");
	require("functions.php");
	
	$url="http://image.prntscr.com/image/52eb2167584c4cd08435deef6f83e5ba.png";
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_NOBODY, true);
	curl_exec($ch);
	$r = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	echo $r;
	*/
	
	/*
	require("db.php");
	require("functions.php");
	$sql="SELECT views FROM images";
	$res=$link->query($sql);
	$total=0;
	for($i=0;$i<mysqli_num_rows($res);++$i){
		$row=mysqli_fetch_assoc($res);
		$total+=$row['views'];
	}
	echo "Total Views: $total";
	*/

/*
    // Read 24bit BMP files
    // Author: de77
    // Licence: MIT
    // Webpage: de77.com
    // Version: 07.02.2010
    function imageCreateFromBmp($filename) {
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

    $file="http://lookie.ml/uploads/2hcPUn.bmp";
    //$file="http://lookie.ml/uploads/Xv2JT.jpg";
    makeImagethumb($file,"herp.jpg");
    echo "success!";
*/

    

/* populate hashes and bases
		echo "Populating Hashes and bases...<br>";
		require("db.php");
		require("functions.php");
		$sql="SELECT * FROM images";
		$res=$link->query($sql);
		for($i=0;$i<mysqli_num_rows($res);++$i){
			$row=mysqli_fetch_assoc($res);
			$shortName=$row['shortName'];
			$suffix=suffix($row['type']);
			$fileName="uploads/$shortName$suffix";
			$hash=hash_file("md5",$fileName);
			$id=alphaToDec($shortName);
			$sql="UPDATE images SET hash = \"$hash\", base = \"$shortName\" WHERE id = $id";
			$link->query($sql);
		}
		echo "done.";
	*/
	
	
	/* find duplicate files
	$images = glob('uploads/*');
	echo "Generating File Hashes...<br>";
	$hashes=[];
	$collisions=0;
	foreach($images as $image){
		$hash=hash_file("md5",$image);
		//echo "$image -> $hash<br>";
		for($j=0;$j<count($hashes);++$j){
			if($hash==$hashes[$j][0]){
				echo "<span style='color:#fff;background:#a00;'>Collision: $image {$hashes[$j][1]}</span><br>";
				$collisions++;
			}
		}
		array_push($hashes,[$hash,$image]);
	}
	echo "<br>Hashing Complete. $collisions collisions detected.";
	*/
	
	/* set sizes in db
	require("db.php");
	require("functions.php");
	$sql="SELECT * FROM images";
	$res=$link->query($sql);
	echo "Updating file sizes for ".mysqli_num_rows($res)." records...<br>";
	while($row=mysqli_fetch_assoc($res)){
		$fileName="uploads/$row[shortName]".suffix($row['type']);
		$size=filesize($fileName);
		$sql="UPDATE images SET size=$size WHERE id=$row[id]";
		$link->query($sql);
	}
	echo "done.";
	*/

	/* remove exif data
	$images = glob('uploads/*.jpg');

	echo "Stripping Exif Data...<br>";
	foreach($images as $image){   
		try
		{   
			$img = new Imagick($image);
			$img->stripImage();
			$img->writeImage($image);
			$img->clear();
			$img->destroy();

		} catch(Exception $e) {
			echo 'Exception caught: ',  $e->getMessage(), "\n";
		}   
	}
	echo "done.";
	*/
?>
