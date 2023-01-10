<?
	if(isset($_POST['scroll'])){
		require("../db.php");
		require("../functions.php");
		$sort=mysqli_real_escape_string($link,$_POST['sort']);
		$imagesPerScroll=12;
		$sql="SELECT * FROM images ORDER BY";
		if($sort=="rating"){
			$sql.=" rating*votes DESC, views DESC";
		}else{
			$sql.=" $sort DESC";
		}
		$res=$link->query($sql);
		$count=0;
		for($i=0;$i<mysqli_num_rows($res);++$i){
			$row=mysqli_fetch_assoc($res);
			if($row['public']){
				$count++;
				if($count>$_POST['scroll']*$imagesPerScroll && $count<=$_POST['scroll']*$imagesPerScroll+$imagesPerScroll){
					$shortName=$row['shortName'];
					$base=$row['base'];
					switch($row['type']){
						case "image/jpeg":
							$fileName="../uploads/$base.jpg";
							$type="pic";
							break;
						case "image/png":
							$fileName="../uploads/$base.png";
							$type="pic";
							break;
						case "image/gif":
							$fileName="../uploads/$base.gif";
							$type="pic";
							break;
						case "image/bmp":
							$fileName="../uploads/$base.bmp";
							$type="pic";
							break;
						case "video/mp4":
							$fileName="../uploads/$base.mp4";
							$type="vid";
							break;
						case "video/webm":
							$fileName="../uploads/$base.webm";
							$type="vid";
							break;
						case "video/ogg":
							$fileName="../uploads/$base.ogv";
							$type="vid";
							break;
						case "application/x-shockwave-flash":
							$fileName="../uploads/$base.swf";
							$type="flash";
							break;
					}
					$thumb="$fileName.jpg";
					list($width, $height) = getimagesize($thumb);
					echo "<div class='crop' id='$shortName' style='background-image:url($thumb);width:".$width."px' onclick=\"view('$thumb','$type','$shortName')\"><img class='hover' src='".($type=="pic"?"pic.png":($type=="vid"?"vid.png":"swf.png"))."'></div> ";
				}
			}
		}
	}
?>
