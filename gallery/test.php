<?
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	$_POST['scroll']=0;
	require("../db.php");
	require("../functions.php");
	$imagesPerScroll=2;
	$sql="SELECT * FROM images ORDER BY date DESC";
	$res=$link->query($sql);
	$count=0;
	for($i=0;$i<mysqli_num_rows($res);++$i){
		$row=mysqli_fetch_assoc($res);
		if($row['public']){
			$count++;
			if($count>$_POST['scroll']*$imagesPerScroll && $count<=$_POST['scroll']*$imagesPerScroll+$imagesPerScroll){
				switch($row['type']){
					case "image/jpeg":
						$fileName="../uploads/".$row['shortName'].".jpg";
						$video="";
						$dataimage=$fileName;
						break;
					case "image/png":
						$fileName="../uploads/".$row['shortName'].".png";
						$video="";
						$dataimage=$fileName;
						break;
					case "image/gif":
						$fileName="../uploads/".$row['shortName'].".gif";
						$video="";
						$dataimage=$fileName;
						break;
					case "image/bmp":
						$fileName="../uploads/".$row['shortName'].".bmp";
						$video="";
						$dataimage=$fileName;
						break;
					case "video/mp4":
						$fileName="../uploads/".$row['shortName'].".mp4";
						$video="data-type='html5video' data-videomp4='$fileName'";
						$dataimage=$fileName.".jpg";
						break;
					case "video/webm":
						$fileName="../uploads/".$row['shortName'].".webm";
						$video="data-type='html5video' data-videowebm='$fileName'";
						$dataimage=$fileName.".jpg";
						break;
					case "video/ogg":
						$fileName="../uploads/".$row['shortName'].".ogg";
						$video="data-type='html5video' data-videoogv='$fileName'";
						$dataimage=$fileName.".jpg";
						break;
				}
				$thumb="$fileName.jpg";
				list($width, $height) = getimagesize($thumb);
				echo "<div class='crop' style='background-image:url($thumb);width:".$width."px'></div>";
			}
		}
	}
?>
