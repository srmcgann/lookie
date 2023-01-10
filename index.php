<!DOCTYPE html>
<?
	require("db.php");
	if($_GET['i']){
		require("functions.php");
		$shortName=mysqli_real_escape_string($link, $_GET['i']);
		if(strpos($shortName,"v-") !==false || strpos($shortName,"i-") !== false) $shortName=substr($shortName,2);
		$id=alphaToDec($shortName);
		$sql="SELECT * FROM images WHERE id=$id";
		$res=$link->query($sql);
		if(mysqli_num_rows($res)){
			$row=mysqli_fetch_assoc($res);
			$type=$row['type'];
			$name=$row['name'];
			$base=$row['base'];
			$artist=$row['artist'];
			$description=$row['description'];
			$origin=$row['origin'];
			$views=$row['views']+1;
			$rating=$row['rating']."%";
			$votes=$row['votes']." votes";
			$size=filesize("uploads/$base".suffix($type));
			$url="//$_SERVER[HTTP_HOST]/$row[shortName]";
            $lastviewed=date("Y-m-d H:i:s",strtotime("now"));
			$sql="UPDATE images SET views = $views, size=$size, lastviewed=\"$lastviewed\" WHERE id=$id";
			$link->query($sql);
			$src="//{$_SERVER['HTTP_HOST']}/uploads/$base".suffix($type);
				?>
				<html>
					<head>
						<meta charset="UTF-8">
						<meta name="description" content="Image and Video Sharing">
						<meta name="keywords" content="share,images,videos">
						<meta name="viewport" content="width=device-width, initial-scale=.01">
						<meta name="og:image" content="<?=$src?>.jpg" />
						<link rel="stylesheet" type="text/css" href="//<?echo $_SERVER["HTTP_HOST"];?>/render.css">
						<link rel="shortcut icon" type="image/png" href="//<?echo $_SERVER["HTTP_HOST"];?>/favicon.png"/>
						<title>Lookie! (<?=$name?>)</title>
						<link href="//<?echo $_SERVER["HTTP_HOST"];?>/zoom/imageviewer.css"  rel="stylesheet" type="text/css" />
						<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
						<script src="//<?echo $_SERVER["HTTP_HOST"];?>/functions.js?4"></script>
						<script src="//<?echo $_SERVER["HTTP_HOST"];?>/zoom/imageviewer.js"></script>
						<script>
							init=0;
							setInterval(resize,1000);
							url="//<?echo $_SERVER["HTTP_HOST"]."/$shortName";?>";
							pimg1=new Image();
							pimg1.src="/grey_cloud.png";
							pimg2=new Image();
							pimg2.src="/green_cloud.png";
							pimg3=new Image();
							pimg3.src="/blue_cloud.png";
						</script>
					</head>
					<body onresize="resize()" onload="resize()">
						<div id="header">
							<a href="." style="color:#abc;text-decoration:none;cursor: pointer;">
								<div style="width:170px;">
									<div style="background:url(//<?echo $_SERVER['HTTP_HOST']?>/cloud.png);background-size:100% 100%;width:45px;height:35px;float:left;margin-right:10px;"></div>
									LOOKIE
								</div>
							</a>
							<br><br>
							<a href="gallery" style="position:absolute;z-index:10;margin-left:0px;margin-top:-10px;color:#abc;cursor:pointer;text-decoration:none;">Gallery</a>
							<a href="admin" style="position:absolute;z-index:10;margin-left:0px;margin-top:80px;color:#abc;cursor:pointer;text-decoration:none;">Admin</a>
						</div>
						
						
						<div id="status" style="position:absolute;font-size:24px;color:white;top:50%;"></div>
						
						
						<div id="masterContainer">
							<center>
								<div id="mainDiv">
										<?
										switch($type){
											case "image/jpeg":
											case "image/png":
											case "image/gif":
											case "image/bmp":
												$assetType="Image";
											break;
											case "video/mp4":
											case "video/webm":
											case "video/ogg":
												$assetType="Video";
											break;
											case "application/x-shockwave-flash":
												$assetType="Flash";
											break;
										}
										switch($assetType){
											case "Image":
												echo "<img onload=\"$(this).data('loaded', 'loaded');\" src=\"$src\" data-high-res-src=\"$src\" class=\"pannable-image\" id=\"asset\"/>";
												echo '<script>assetType="img";$("#asset").load(resize());</script>';
												echo "<script>applyZoom();</script>";
											break;
											case "Video":
												echo "<video controls loop onloadedmetadata=\"$(this).data('loaded', 'loaded');\" src=\"$src\" id=\"asset\"/>Your browser does not support the video tag.</video>";
												echo '<script>assetType="vid";$("#asset").bind("loadedmetadata", resize());</script>';
											break;
											case "Flash":
												echo "<object id=\"asset\" >";
												echo "<param name=\"movie\" value=\"$src\">";
												echo "<param name=\"allowFullScreen\" value=\"true\">";
												echo "<embed id=\"embed\" src=\"$src\" onload=\"$(this).data('loaded', 'loaded');\"></embed>";
												echo "</object>";
												echo '<script>assetType="flash";$("#asset").load(resize());</script>';
											break;
										}
										echo '<div id="fileInfoDivOuter">';
											echo '<center>';
												echo '<div id="fileInfoDiv">';
													echo '<table id="fileInfo">';
														/*
														echo '<tr>';
															echo '<td class="fileInfoLabel">Link</td>';
															echo '<td class="fileInfoData"><a target="_blank" href="'.$url.'">'.$url.'</a></td>';
														echo '</tr>';
														*/
														echo '<tr>';
															echo '<td class="fileInfoLabel">Views</td>';
															echo '<td class="fileInfoData">'.number_format($views).'</td>';
														echo '</tr>';
														echo '<tr>';
															echo '<td class="fileInfoLabel">File Name</td>';
															echo '<td class="fileInfoData">'.(strlen($name)<32?$name:substr($name,0,32)."...").'</td>';
														echo '</tr>';
														if($artist){
															echo '<tr>';
																echo '<td class="fileInfoLabel">Artist</td>';
																echo '<td class="fileInfoData">'.(strlen($artist)<32?$artist:substr($artist,0,32)."...").'</td>';
															echo '</tr>';															
														}
														if($description){
															echo '<tr>';
																echo '<td class="fileInfoLabel">Description</td>';
																echo '<td class="fileInfoData">'.(strlen($description)<32?$description:substr($description,0,32)."...").'</td>';
															echo '</tr>';															
														}
														if($origin){
															echo '<tr>';
																echo '<td class="fileInfoLabel">Origin</td>';
																echo '<td class="fileInfoData"><a href="'.$origin.'" target="_blank">'.(strlen($origin)<32?$origin:substr($origin,0,32)."...").'</a></td>';
															echo '</tr>';															
														}
														echo '<tr>';
															echo '<td class="fileInfoLabel">Size</td>';
															echo '<td class="fileInfoData">'.formatBytes($size)."&nbsp;&nbsp;&nbsp;( ".number_format($size).' bytes 
															)</td>';
														echo '</tr>';
														echo '<tr>';
															echo '<td class="fileInfoLabel">Popularity</td>';
															echo '<td id="popCell" style="font-size:18px;">'.$rating.'&nbsp;&nbsp;'.$votes.'</td>';
														echo '</tr>';
														echo '<tr>';
															echo '<td class="fileInfoLabel">Rate this '.$assetType.'</td><td>';
															?>
															<div class='assetChoice'>
																<div id="<?echo $shortName ?>" class="rate_widget">
																	<div class="cloud_1 ratings_clouds"></div>
																	<div class="cloud_2 ratings_clouds"></div>
																	<div class="cloud_3 ratings_clouds"></div>
																	<div class="cloud_4 ratings_clouds"></div>
																	<div class="cloud_5 ratings_clouds"></div>
																	<div class="cloud_6 ratings_clouds"></div>
																</div>
															</div>
															<script>
																$('.ratings_clouds').hover(
																	function() {
																		$(this).prevAll().andSelf().addClass('ratings_over');
																		$(this).nextAll().removeClass('ratings_vote'); 
																	},
																	function() {
																		$(this).prevAll().andSelf().removeClass('ratings_over');
																		set_votes($(this).parent());
																	}
																);
																
																function set_votes(widget) {
																	var avg = $(widget).data('fsr').whole_avg;
																	var votes = $(widget).data('fsr').number_votes;
																	var exact = $(widget).data('fsr').dec_avg;
																	var user_vote = $(widget).data('fsr').user_vote;
																	$(widget).find('.cloud_' + user_vote).prevAll().andSelf().addClass('ratings_vote');
																	$(widget).find('.cloud_' + user_vote).nextAll().removeClass('ratings_vote'); 
																	$('#popCell').html(exact+'%&nbsp;&nbsp;&nbsp;&nbsp;'+votes+" vote"+(votes==1?"":"s"));
																	$('#popCell').css("background",rgb(-.5+Math.PI-Math.PI/90*exact));
																}
																
																$('.ratings_clouds').bind('click', function() {
																	var cloud = this;
																	var widget = $(this).parent();
																	 
																	var clicked_data = {
																		clicked_on : $(cloud).attr('class'),
																		shortName : widget.attr('id')
																	};
																	$.post(
																		'ratings.php',
																		clicked_data,
																		function(INFO) {
																			widget.data( 'fsr', INFO );
																			set_votes(widget);
																			$('.ratings_clouds').prevAll().andSelf().removeClass('ratings_over');
																		},
																		'json'
																	); 
																});
																$('.rate_widget').each(function(i) {
																	var widget = this;
																	var out_data = {
																		shortName : $(widget).attr('id'),
																		fetch: 1
																	};
																	$.post(
																		'//<?echo $_SERVER['HTTP_HOST']?>/ratings.php',
																		out_data,
																		function(INFO) {
																			$(widget).data( 'fsr', INFO );
																			set_votes(widget);
																		},
																		'json'
																	);
																});
															</script>
															<?
														echo '</td></tr>';
													echo '</table>';
												echo '</div>';
											echo '</center>';
										echo '</div>';
									?>
								</div>
							</center>
						</div>
						<script>
							$(window).resize(resize());
							<?
								if(isset($_GET['x']) && isset($_GET['y']) && isset($_GET['zoom'])){
									echo "viewer.zoom({$_GET['zoom']},{x:{$_GET['x']},y:{$_GET['y']} });";
								}
							?>
						</script>
					</body>
				</html>
			<?
			die();
		}else{
			?>
				<meta http-equiv="refresh" content="0; url=/" />
			<?
			die();
		}
	}
?>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="description" content="Lookie! Image and Video Sharing">
		<meta name="keywords" content="share,images,videos">
		<meta name="viewport" content="width=device-width, initial-scale=.01">
		<title>Lookie</title>
		<link rel="stylesheet" type="text/css" href="style.css?5">
		<link rel="shortcut icon" type="image/png" href="favicon.png"/>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	</head>
	<body>
		<? include_once("../analysis.php") ?>
		<script src="functions.js?4"></script>
		<div id="advancedDiv">
			<div id="advancedControlsOuter">
				<div id="advancedControlsInner">
					<div class="upload">
						<div id="uploadInner2">
							<div class="downloadURL_div">
								<span class="advancedTitle">Advanced Upload</span><br><br>
								<table id="advancedTable">
									<tr>
										<td>
											<div class="file_button_container2">
												<input type="file" name="file2" id="file2">
											</div>
										</td>
										<td>
											<div id="fileInfo2">No File Chosen</div>
										</td>
									</tr>
									<tr>
										<td>Or, URL</td>
										<td>
											<input onclick="$(this).select()" id="inputURL2" class="inputURL2" type="text">
										</td>
									</tr>
									<tr>
										<td>Artist</td>
										<td>
											<input onclick="$(this).select()" id="artist" class="inputURL2" type="text">
										</td>
									</tr>
									<tr>
										<td>Description</td>
										<td>
											<input onclick="$(this).select()" id="description" class="inputURL2" type="text">
										</td>
									</tr>									
								</table>
								<br>
								<button onclick="downloadAdvanced()" class="goButton" style="width:150px;">Go</button>&nbsp;&nbsp;&nbsp;
								<button onclick="$('#advancedDiv').hide()" class="cancelButton">Cancel</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="masterContainer">
			<center>
				<div id="header">
						<div style="width:270px;">
							<img src="cloud.png" style="width:75px;float:left;" >
							<div style="color:#bbb;">LOOKIE</div>
						</div>
						<div class="clear"></div>
						<div class="viewCount">
						<?
							require_once("db.php");
							$sql="SELECT views FROM images";
							$res=$link->query($sql);
							$total=0;
							for($i=0;$i<mysqli_num_rows($res);++$i){
								$row=mysqli_fetch_assoc($res);
								$total+=$row['views'];
							}
							echo number_format($total)." Content Views";
						?>
						</div>
				</div>
				<div id="mainDiv">
					<div id="innerDivLeft">
						<div class="upload">
							<div id="uploadInner">
								<table class="ADTable">
									<tr><td><input type="radio" name="autodelete" checked id="noAD" value="0"></td>
									<td><span onclick="$('#noAD').prop('checked', true);" class="ADOptionLabel">Consider for Gallery</span></td></tr>
									<tr><td><input type="radio" name="autodelete" id="AD" value="1"></td>
									<td><span onclick="$('#AD').prop('checked', true);" class="ADOptionLabel" >Delete after 18 hours</span></td></tr>
								</table>
								<hr>
								<div class="file_button_container">
									<input type="file" name="file1" id="file1">
									<script>addListeners()</script>
								</div>
								<div class="downloadURL_div">
									Or, URL
									<input onclick="$(this).select()" onkeypress="handleKeypress(event)" id="inputURL" class="inputURL" type="text" name="downloadURL">
									<button onclick="download()" class="goButton">Go</button>
								</div>
							</div>
							<div id="fileInfo1"></div>
						</div>
					</div>
					<div id="innerDivRight">
						<div id="introText">
							Share an image or video.
							<br><br>
							<div id="formatDiv">
								<span style="font-size:22px;color:#aa8;">Accepted Formats</span>
								<table id="formats">
									<tr><td style="color:#fff;font-weight:300;">Images</td><td>jpg</td><td>png</td><td>gif</td><td>bmp</td></tr>
									<tr><td style="color:#fff;font-weight:300;">Videos</td><td>mp4</td><td>webm</td><td>swf</td><td>ogv</td></tr>
								</table>
							</div>
							<div style="font-size:16px;">
								<br>Max File Size 100 MB
								<br><br>If used, a URL may point to any<br>supported file, or a Youtube video.
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
				<div id="footer">
					<a href="#" onclick="$('#advancedDiv').show();return false;"><span class="footerLink2">Advanced</span></a>
					<a href="gallery"><span class="footerLink">Gallery</span></a>
					<a href="admin"><span class="footerLink">Admin</span></a>
					<a href="mailto:s.r.mcgann@hotmail.com"><span class="footerLink">Contact</span></a>
					<?
					echo "<span style='color:#aaa;'>&copy;".date("Y",strtotime("now")).' Scott McGann</span>';
					?>
				</div>
			</center>
		</div>
		<script>
			$("html").on("dragover", function(e) {
				e.preventDefault();
				e.stopPropagation();
				e.originalEvent.dataTransfer.setData('text/plain', 'anything');
			});

			$("html").on("dragleave", function(e){
				e.preventDefault();
				e.stopPropagation();
			});
			$("html").on("drop", function(e) {
				e.preventDefault();  
				e.stopPropagation();
				validateFile(e.originalEvent.dataTransfer.files[0]);
			});
			$("#inputURL").focus();
		</script>
	</body>
</html>
