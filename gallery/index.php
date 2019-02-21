<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="description" content="Image and Video Sharing">
		<meta name="keywords" content="share,images,videos">
		<link rel="shortcut icon" type="image/png" href="../favicon.png"/>
		<link rel="stylesheet" type="text/css" href="gallery.css?4">
		<title>Lookie Gallery</title>
		<link href="//<?echo $_SERVER["HTTP_HOST"];?>/zoom/imageviewer.css"  rel="stylesheet" type="text/css" />
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="//<?echo $_SERVER["HTTP_HOST"];?>/zoom/imageviewer.js"></script>
		<script>
			<?
			$dir=explode("/",getcwd());
			echo "galleryDir=\"//$_SERVER[HTTP_HOST]/".$dir[count($dir)-1]."\";";
			
			?>
			pimg1=new Image();
			pimg1.src="/grey_cloud.png";
			pimg2=new Image();
			pimg2.src="/green_cloud.png";
			pimg3=new Image();
			pimg3.src="/blue_cloud.png";
		</script>
	</head>
	<body onresize="resize()">
		<? include_once("../analysis.php") ?>
		<div id="featuredItemContainer"></div>
		<center>
			<div id="header">
				<a href="../">
					<div style="width:270px;">
						<img src="../cloud.png" style="width:75px;float:left;" >
						LOOKIE
					</div>
				</a>
			</div>
			<div id="sortDiv">
				Order By<hr>
				<div style="text-align:left;">
					<input name="sort" type="radio" value="date"  onchange="resort()" id="date"><div class="rblabel" id="dateLabel" onclick="$('#date').click()">Date</div><br>
					<input name="sort" type="radio" value="rating" onchange="resort()" id="rating"><div class="rblabel" id="ratingLabel" onclick="$('#rating').click()">Votes</div><br>
					<input name="sort" type="radio" value="size" onchange="resort()" id="size"><div class="rblabel" id="sizeLabel" onclick="$('#size').click()">Size</div>
					<script>
					<?
						if($_GET['sort']=="date"||!isset($_GET['sort'])) echo 'sort="date";$("#date").prop("checked", true);$("#dateLabel").css({"background-color": "#4f8", "color": "#000"});';
						if($_GET['sort']=="rating") echo 'sort="rating";$("#rating").prop("checked", true);$("#ratingLabel").css({"background-color": "#4f8", "color": "#000"});';
						if($_GET['sort']=="size") echo 'sort="size";$("#size").prop("checked", true);$("#sizeLabel").css({"background-color": "#4f8", "color": "#000"});';
					?>
					</script>
				</div>
			</div>
			<div id="menu">
				<?
					?><a href="../"><span class="footerLink">Home</span></a><?
					?><a href="../admin"><span class="footerLink">Admin</span></a><?
					?><a href="mailto:s.r.mcgann@hotmail.com"><span class="footerLink" style="margin-right:0;">Contact</span></a><?
				?>
			</div>
			<div id="gallery"></div>
		</center>
		<script src="gallery.js"></script>
	</body>
</html>
