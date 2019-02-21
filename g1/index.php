<?
	chdir("../");
	require("db.php");
	require("functions.php");
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="description" content="Image and Video Sharing Service">
		<meta name="keywords" content="share,images,videos">
		<link rel="stylesheet" type="text/css" href="../unitegallery.css">
		<link rel="shortcut icon" type="image/png" href="../favicon.png"/>
		<title>Lookie Gallery</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

		<script src='../unitegallery/js/unitegallery.min.js'></script>
		<link href="../unitegallery/css/unite-gallery.css" rel="stylesheet" >
		<script type='text/javascript' src='../unitegallery/themes/tiles/ug-theme-tiles.js'></script>

	</head>
	<body onresize="resize()">
		<? include_once("../analysis.php") ?>
		<script src="../functions.js"></script>
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
				<div id="menu">
					<?
						?><a href="../" style="text-decoration:none;"><span class="footerLink">Home</span></a><?
						?><a href="../g2" style="text-decoration:none;"><span class="footerLink">Gallery 2</span></a><?
						?><a href="../admin" style="text-decoration:none;"><span class="footerLink">Admin</span></a><?
						?><a href="mailto:s.r.mcgann@hotmail.com" style="text-decoration:none;"><span class="footerLink">Email Author</span></a><?
					?>
				</div>
				<div id="gallery" style="display:none;">
				<?
					$sql="SELECT * FROM images ORDER BY date DESC";
					$res=$link->query($sql);
					for($i=0;$i<mysqli_num_rows($res);++$i){
						$row=mysqli_fetch_assoc($res);
						if($row['public']){								
							switch($row['type']){
								case "image/jpeg":
									$fileName="../uploads/".$row['base'].".jpg";
									$video="";
									$dataimage=$fileName;
									break;
								case "image/png":
									$fileName="../uploads/".$row['base'].".png";
									$video="";
									$dataimage=$fileName;
									break;
								case "image/gif":
									$fileName="../uploads/".$row['base'].".gif";
									$video="";
									$dataimage=$fileName;
									break;
								case "image/bmp":
									$fileName="../uploads/".$row['base'].".bmp";
									$video="";
									$dataimage=$fileName;
									break;
								case "video/mp4":
									$fileName="../uploads/".$row['base'].".mp4";
									$video="data-type='html5video' data-videomp4='$fileName'";
									$dataimage=$fileName.".jpg";
									break;
								case "video/webm":
									$fileName="../uploads/".$row['base'].".webm";
									$video="data-type='html5video' data-videowebm='$fileName'";
									$dataimage=$fileName.".jpg";
									break;
								case "video/ogg":
									$fileName="../uploads/".$row['base'].".ogg";
									$video="data-type='html5video' data-videoogv='$fileName'";
									$dataimage=$fileName.".jpg";
									break;
							}
							$altText="<a href='../?i=".$row['shortName']."' target='_blank'><span style='border-radius:5px;border:1px solid white;background:#003;color:#ff0;padding-left:10px;padding-right:10px;'>".$row[name]."</span><a>";
							?>
								<img alt="<?echo $altText?>" src="<?echo "$fileName.jpg"?>" <?echo $video?> data-image="<?echo $dataimage?>">
							<?
						}
					}
				?>
				</div>
				<script>
					function resize(){
						//$("#html5gallery").attr('data-width',$(document).width()/1.25);
						//$("#html5gallery").attr('data-height',$(document).height()/1.65);
						
						jQuery("#gallery").unitegallery({
							gallery_width:$(document).width()/1.25,
							gallery_height:$(document).height()/1.65,
							tiles_type:"nested",
							lightbox_type: "compact",
							gallery_skin:"alexis",
							theme_autoplay: true
						});
					}
					api=jQuery("#gallery").unitegallery({
						gallery_width:$(document).width()/1.25,
						gallery_height:$(document).height()/1.65,
						tiles_type:"nested",
						lightbox_type: "compact",
						gallery_skin:"alexis",
						theme_autoplay: true
					});
					$(document).ready(function(){
						resize();
					});
		
				</script>
				<div id="menu">
					<?
						?><a href="../" style="text-decoration:none;"><span class="footerLink">Home</span></a><?
						?><a href="../g2" style="text-decoration:none;"><span class="footerLink">Gallery 2</span></a><?
						?><a href="../admin" style="text-decoration:none;"><span class="footerLink">Admin</span></a><?
						?><a href="mailto:s.r.mcgann@hotmail.com" style="text-decoration:none;"><span class="footerLink">Email Author</span></a><?
						echo "<span style='color:#aaa;'>&copy;".date("Y",strtotime("now")).' Scott McGann</span>';
					?>
					<br><br><br>
				</div>
			</center>
		</div>
	</body>
</html>
