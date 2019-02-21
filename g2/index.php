<?
	chdir("../");
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
		<link rel="stylesheet" type="text/css" href="../gallery.css">
		<link rel="shortcut icon" type="image/png" href="../favicon.png"/>
		<title>Lookie Gallery</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script type="text/javascript" src="../html5gallery/jquery.js"></script>
		<script type="text/javascript" src="../html5gallery/html5gallery.js"></script>
	</head>
	<body onresize="resize()">
		<? include_once("../analysis.php") ?>
		<script src="functions.js"></script>
		<div id="masterContainer">
			<center>
				<div id="header">
					<a href="../" style="color:#def;text-decoration:none;cursor: pointer;">
						<div style="width:270px;">
							<img src="../cloud.png" style="width:75px;float:left;" >
							<div style="padding-top:0px;">LOOKIE</div>
						</div>
					</a>
				</div>
				<div style="display:none;" id="html5gallery" class="html5gallery" data-skin="horizontal" data-bgcolor="#222222" data-resizemode="fit" data-responsive="true" >
					<?
						$sql="SELECT * FROM images ORDER BY date DESC";
						$res=$link->query($sql);
						for($i=0;$i<mysqli_num_rows($res);++$i){
							$row=mysqli_fetch_assoc($res);
							if($row['public']){								
								switch($row['type']){
									case "image/jpeg":
										$fileName="../uploads/".$row['base'].".jpg";
										break;
									case "image/png":
										$fileName="../uploads/".$row['base'].".png";
										break;
									case "image/gif":
										$fileName="../uploads/".$row['base'].".gif";
										break;
									case "image/bmp":
										$fileName="../uploads/".$row['base'].".bmp";
										break;
									case "video/mp4":
										$fileName="../uploads/".$row['base'].".mp4";
										break;
									case "video/webm":
										$fileName="../uploads/".$row['base'].".webm";
										break;
									case "video/ogg":
										$fileName="../uploads/".$row['base'].".ogg";
										break;
								}
								?><a href="<?echo $fileName;?>"><img src="<?echo $fileName.".jpg"?>" alt="<a href='../?i=<?echo $row['shortName']?>' target='_blank'><?echo $row['name']?><a>"></a><?
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
						?><a href="../" style="text-decoration:none;"><span class="footerLink">Home</span></a><?
						?><a href="../g1" style="text-decoration:none;"><span class="footerLink">Gallery 1</span></a><?
						?><a href="../admin" style="text-decoration:none;"><span class="footerLink">Admin</span></a><?
						?><a href="mailto:s.r.mcgann@hotmail.com" style="text-decoration:none;"><span class="footerLink">Email Author</span></a><?
						echo "<span style='color:#aaa;'>&copy;".date("Y",strtotime("now")).' Scott McGann</span>';
					?>
				</div>
			</center>
		</div>
	</body>
</html>