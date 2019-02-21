<?
	require("db.php");
	require("functions.php");
?>
<!DOCTYPE html>
<html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="description" content="Image and Video Sharing Service">
		<meta name="keywords" content="share,images,videos">
		<link rel="stylesheet" type="text/css" href="gallery.css">
		<link rel="shortcut icon" type="image/png" href="favicon.png"/>
		<title>Lookie Gallery</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script type="text/javascript" src="html5gallery/jquery.js"></script>
		<script type="text/javascript" src="html5gallery/html5gallery.js"></script>
	</head>
	<body onresize="resize()">
		<? include_once("../analysis.php") ?>
		<script src="functions.js"></script>
		<div id="masterContainer">
			<center>
				<div id="header">
					<a href="<?echo $baseURL?>" style="color:#def;text-decoration:none;cursor: pointer;">
						LOOKIE.ml
					</a>
				</div>
				<div id="html5gallery" class="html5gallery" data-skin="horizontal" data-bgcolor="#222222" data-resizemode="fit" data-responsive="true" >
					<?
						$sql="SELECT * FROM images ORDER BY date DESC";
						$res=$link->query($sql);
						for($i=0;$i<mysqli_num_rows($res);++$i){
							$row=mysqli_fetch_assoc($res);
							if($row['public']){								
								switch($row['type']){
									case "image/jpeg":
										$fileName="uploads/".$row['shortName'].".jpg";
										break;
									case "image/png":
										$fileName="uploads/".$row['shortName'].".png";
										break;
									case "image/gif":
										$fileName="uploads/".$row['shortName'].".gif";
										break;
									case "image/bmp":
										$fileName="uploads/".$row['shortName'].".bmp";
										break;
									case "video/mp4":
										$fileName="uploads/".$row['shortName'].".mp4";
										break;
									case "video/webm":
										$fileName="uploads/".$row['shortName'].".webm";
										break;
									case "video/ogg":
										$fileName="uploads/".$row['shortName'].".ogg";
										break;
								}
								?><a href="<?echo $fileName;?>"><img src="<?echo $fileName.".jpg"?>" alt="<a href='<?echo $baseURL?>?i=<?echo $row['shortName']?>' target='_blank'><?echo $row['name']?><a>"></a><?
							}
						}
					?>
				</div>
				<script>
					function resize(){
						$("#html5gallery").attr('data-width',$(document).width()/1.25);
						$("#html5gallery").attr('data-height',$(document).height()/1.65);						
					}
					resize();
				</script>
				<div id="footer">
					<?
						?><a href="<?echo $baseURL?>" style="text-decoration:none;"><span class="footerLink">Home Page</span></a><?
						?><a href="admin" style="text-decoration:none;"><span class="footerLink">Admin Page</span></a><?
						?><a href="mailto:s.r.mcgann@hotmail.com" style="text-decoration:none;"><span class="footerLink">Email Author</span></a><?
						echo "&copy;".date("Y",strtotime("now")).' Scott McGann';
					?>
				</div>
			</center>
		</div>
	</body>
</html>