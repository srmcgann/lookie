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
		</script>
	</head>
	<body onresize="resize()">
		<? include_once("../analysis.php") ?>
		<div id="featuredItemContainer"></div>
		<center>
			<div id="sortDiv">
				Order By<hr>
				<div style="text-align:left;display:none;">
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
			<div id="gallery" style="width:100%"></div>
		</center>
		<script src="gallery.js"></script>
	</body>
</html>
