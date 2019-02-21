<?
	chdir("../");
	require("db.php");
	require("functions.php");
	require("login.php");
	if(!isset($_POST['maxResultsPerPage']))$_POST['maxResultsPerPage']=5;
	if(isset($_POST['IPAddress']) && strpos(strtoupper($_POST['IPAddress']),"SCRIPT")!==false)$_POST['IPAddress']='';
        if(isset($_POST['fromDate']) && strpos(strtoupper($_POST['fromDate']),"SCRIPT")!==false)$_POST['fromDate']='';
	$maxResultsPerPage=$_POST['maxResultsPerPage'];
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="description" content="lookie.ml Admin Page">
		<meta name="keywords" content="share,images,videos">
		<link rel="stylesheet" type="text/css" href="../admin.css">
		<link rel="shortcut icon" type="image/png" href="favicon.png"/>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
		<title>Lookie Admin Tool</title>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
		<script>
			function rgb(col){

					col+=.000001;
					var r = parseInt((.25+Math.sin(col)*.25)*16);
					var b = parseInt((.25+Math.cos(col)*.25)*16);
					var g = 0;//parseInt((.5-Math.sin(col)*.5)*16);
					return "#"+r.toString(16)+r.toString(16)+g.toString(16)+g.toString(16)+b.toString(16)+b.toString(16);
			}
			$( function() { $( "#fromDate" ).datepicker(); } );
			$( function() { $( "#toDate" ).datepicker(); } );

			function resize(asset,type){
				a = $(asset)[0];
				switch(type){
					case "img":
						w=a.naturalWidth;
						h=a.naturalHeight;
						break;
					case "vid":
						w=a.videoWidth;
						h=a.videoHeight;
						break;
					case "flash":
						w=$(asset).width();
						h=$(asset).height();
						break;
				}
				w2 = $(window).width();
				h2 = Math.floor(h * (w2 / w));
				if(h2 < $(window).height()){
					w2 = $(window).width()/2.5;
					h2 = Math.floor(h * (w2 / w));
				}else{
					h2 = $(window).height()/2;
					w2 = Math.floor(w * (h2 / h));
				}
				$(asset).width(w2+"px");
				$(asset).height(h2+"px");
			}
			assets=new Array();
			visibilities=new Array();
			function resizeAll(){
				for(i=0;i<assets.length;++i){
					resize("#asset"+(i+<?echo $_POST['page']*$maxResultsPerPage?>),assets[i]);
				}
			}
		</script>
  </head>
	<body onresize="resizeAll()" onload="resizeAll()">
		<div id="loginDivOuter">
			<center>
				<div id="loginDivInner">
					<table style="float:left;">
						<tr><td>User Name</td><td><input type="text" id="preUserName"></td></tr>
						<tr><td>Password</td><td><input type="password" id="prePass"></td></tr>
						<script>
							$('#preUserName').keyup(function(e) {
								code = e.keyCode || e.which;
								if(code==13){
									$('#pass').val($('#prePass').val());
									$('#userName').val($('#preUserName').val());
									$('#selections').submit();
								}
								if(code==27){
									$('#loginDivOuter').hide();
								}
							});
							$('#prePass').keyup(function(e) {
								code = e.keyCode || e.which;
								if(code==13){
									$('#pass').val($('#prePass').val());
									$('#userName').val($('#preUserName').val());
									$('#selections').submit();
								}
								if(code==27){
									$('#loginDivOuter').hide();
								}
							});
						</script>
					</table>
					<button onclick="$('#pass').val($('#prePass').val());$('#userName').val($('#preUserName').val());$('#selections').submit();" style="width:100px;font-size:16px;">Login</button><br>
					<button style="margin-top:10px;width:100px;background:#66a;font-size:16px;" onclick="$('#loginDivOuter').hide();">Cancel</button>
					<div class="clear"></div>
				</div>
			</center>
		</div>
		<?
			if($showAdminControls){
				?>
					<button id="logoutButton" onclick="$('#logout').val(1);$('#selections').submit();">Log Out</button>
				<?
			}
		?>
		<div id="header">
			<a href="./" style="color:#abc;text-decoration:none;	cursor: pointer;">
				LOOKIE
				<br>
				<div style="font-size:16px;">
				<?
					echo "&copy;".date("Y",strtotime("now")).' Scott McGann';
				?>
				</div>
			</a>
		</div>
		<div id="controlPanel">
			<center>
				<br>
				<button class="timeButtons" onclick="$('#fromDate').val('<?echo date("m/d/Y",strtotime("now -1 day"))?>');$('#toDate').val('<?echo date("m/d/Y",strtotime("now"))?>');$('#selections').submit();">Last Day</button>
				<button class="timeButtons" onclick="$('#fromDate').val('<?echo date("m/d/Y",strtotime("now -1 week"))?>');$('#toDate').val('<?echo date("m/d/Y",strtotime("now"))?>');$('#selections').submit();">Last Week</button>
				<button class="timeButtons" onclick="$('#fromDate').val('<?echo date("m/d/Y",strtotime("now -1 month"))?>');$('#toDate').val('<?echo date("m/d/Y",strtotime("now"))?>');$('#selections').submit();">Last Month</button>
				<button class="timeButtons" onclick="$('#fromDate').val('12/31/1969');$('#toDate').val('<?echo date("m/d/Y",strtotime("now"))?>');$('#selections').submit();">All of Time</button>
				<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post" id="selections">
					Public<input type="radio" name="visibility" value="public" onclick="$('#selections').submit();" <?if($_POST['visibility']=="public")echo "checked";?>>
					Private<input type="radio" name="visibility" value="private" onclick="$('#selections').submit();" <?if($_POST['visibility']=="private")echo "checked";?>>
					Both<input type="radio" name="visibility" value="both" onclick="$('#selections').submit();" <?if($_POST['visibility']=="both"||!isset($_POST['visibility']))echo "checked";?>>
					<br>
					<table id="controlsTable">
						<tr><td style="text-align:right;">From Date:</td><td><input type="text" id="fromDate" name="fromDate" value="<?echo $_POST['fromDate']?$_POST['fromDate']:date("m/d/Y",strtotime("epoch"));?>"></td></tr>
						<tr><td style="text-align:right;">To Date:</td><td><input type="text" id="toDate" name="toDate" value="<?echo date("m/d/Y",strtotime("now"));?>"></td></tr>
						<tr><td style="text-align:right;">Sort:</td><td>
							<input type="radio" id="ascending" onchange="$('#selections').submit();" name="sort" value="ASC" <?if($_POST['sort']=="ASC")echo "checked";?>>Ascending<br>
							<input type="radio" id="descending" onchange="$('#selections').submit();" name="sort" value="DESC" <?if($_POST['sort']=="DESC")echo "checked";if(!isset($_POST['sort']))echo "checked";?>>Descending
						</td></tr>
						<tr><td style="text-align:right;">IP Address:</td><td><input type="text" id="IPAddress" name="IPAddress" onclick="$('#IPAddress').val('')" value="<?echo isset($_POST['IPAddress'])?$_POST['IPAddress']:"*";?>" style="font-size:16px;height:18px;"><button type="submit" style="font-size:14px;">Go!</button></td></tr>
						<tr><td style="text-align:right;">Format:</td><td>
						<select id="format" name="format" onchange="$('#selections').submit();">
							<option value="*">*
							<option value="image/jpeg" <?if($_POST['format']=="image/jpeg")echo "selected";?>>JPG
							<option value="image/png" <?if($_POST['format']=="image/png")echo "selected";?>>PNG
							<option value="image/gif" <?if($_POST['format']=="image/gif")echo "selected";?>>GIF
							<option value="image/bmp" <?if($_POST['format']=="image/bmp")echo "selected";?>>BMP
							<option value="video/mp4" <?if($_POST['format']=="video/mp4")echo "selected";?>>MP4
							<option value="video/webm" <?if($_POST['format']=="video/webm")echo "selected";?>>WEBM
							<option value="video/ogg" <?if($_POST['format']=="video/ogg")echo "selected";?>>OGG
							<option value="application/x-shockwave-flash" <?if($_POST['format']=="application/x-shockwave-flash")echo "selected";?>>SWF
						</select></td></tr>
						<tr><td style="text-align:right;">Results/Page:</td><td>
						<select id="maxResultsPerPage" name="maxResultsPerPage" onchange="$('#selections').submit();">
							<option value="1" <?if($_POST['maxResultsPerPage']==1)echo "selected";?>><?echo 1?>
							<option value="5" <?if($_POST['maxResultsPerPage']==5)echo "selected";?>><?echo 5?>
							<option value="10" <?if($_POST['maxResultsPerPage']==10)echo "selected";?>><?echo 10?>
							<option value="25" <?if($_POST['maxResultsPerPage']==25)echo "selected";?>><?echo 25?>
							<option value="50" <?if($_POST['maxResultsPerPage']==50)echo "selected";?>><?echo 50?>
							<option value="100" <?if($_POST['maxResultsPerPage']==100)echo "selected";?>><?echo 100?>
							<option value="200" <?if($_POST['maxResultsPerPage']==200)echo "selected";?>><?echo 200?>
							<option value="500" <?if($_POST['maxResultsPerPage']==500)echo "selected";?>><?echo 500?>
						</select></td></tr>
					</table>
					<br>
					<input type="hidden" name="page" id="page" value="<?echo $_POST['page']?$_POST['page']:0;?>">
					<input type="hidden" name="maxPage" id="maxPage">
					<input type="hidden" name="delete" id="delete">
					<input type="hidden" name="promote" id="promote">
					<input type="hidden" name="demote" id="demote">
					<input type="hidden" name="userName" id="userName">
					<input type="hidden" name="pass" id="pass">
					<input type="hidden" name="logout" id="logout">
				</form>
				<div id="pageDiv"></div>				
			</center>
		</div>
		<div id="masterContainer">
			<center>
				<span style="font-size:48px;font-style:italic;">LOOKIE ADMIN PANEL</span>
				<div id="mainDiv">
					<?
						if($_POST['sort']){
							$fromDate=date("Y-m-d 00:00:00",strtotime($_POST['fromDate']));
							$toDate=date("Y-m-d 23:59:59",strtotime($_POST['toDate']));
							$sort=$_POST['sort'];
							$IPAddress=$_POST['IPAddress'];
							$format=$_POST['format'];
							$visibility=$_POST['visibility'];
							$sql="SELECT * FROM images WHERE date BETWEEN '$fromDate' AND '$toDate'";
							if($IPAddress!="" && $IPAddress!="*") $sql.=" AND IP = \"$IPAddress\"";
							if($format!="*") $sql.=" AND type = \"$format\"";
							if($visibility=="public") $sql.=" AND public = 1";
							if($visibility=="private") $sql.=" AND public = 0";
							$sql.=" ORDER BY date $sort";
							$res=$link->query($sql);
							if(mysqli_num_rows($res)){
								$pages=ceil(mysqli_num_rows($res)/$maxResultsPerPage);
								if($_POST['page']>$pages){
									$_POST['page']=$pages-1;
									echo "<script>$('#page').val($pages-1);</script>";
								}
								$pageDiv="<table><tr><td>Total Results:</td><td>".number_format(mysqli_num_rows($res))."</td></tr>";
								$pageDiv.="<tr><td>Total Pages:</td><td>".number_format($pages)."</td></tr></table><br>";
								$pageDiv.="<div style='margin:20px;margin-top:0px;'>";
								if($_POST['page']>0){
									$pageDiv .= "<a style='float:left;' href=\\\"javascript:$('#page').val($('#page').val()-1);$('#selections').submit();\\\"><img class='arrow' src=\\\"../left_arrow.png\\\"/></a>";
									$pageDiv .= "<div style='margin-top:0px;width:150px;float:left;margin-left:20px;font-size:28px;'> PAGE ";
									$pageDiv .= "<br><select style='width:150px;' id='pageSelect' onchange=\\\"$('#page').val($('#pageSelect').val());$('#selections').submit();\\\">";
									for($j=0;$j<$pages;++$j){
										$pageDiv .= "<option value='$j' ".($j==$_POST['page']?"selected":"").">".($j+1);
									}
									$pageDiv .= "</select></div>";
								}else{
									$pageDiv .= "<img class='arrow' style='float:left;' src=\\\"../left_arrow_disabled.png\\\"/>";
									$pageDiv .= "<div style='margin-top:0px;width:150px;float:left;margin-left:20px;font-size:28px;'> PAGE ";
									$pageDiv .= "<br><select style='width:150px;' id='pageSelect' onchange=\\\"$('#page').val($('#pageSelect').val());$('#selections').submit();\\\">";
									for($j=0;$j<$pages;++$j){
										$pageDiv .= "<option value='$j' ".($j==$_POST['page']?"selected":"").">".($j+1);
									}
									$pageDiv .= "</select></div>";
								}
								if($_POST['page']<$pages-1){
									$pageDiv.="<a href=\\\"javascript:$('#page').val(parseInt($('#page').val())+1);$('#selections').submit();\\\"><img class='arrow' style='float:right;' src=\\\"../right_arrow.png\\\"/></a>";
								}else{
									$pageDiv.="<img class='arrow' style='float:right;' src=\\\"../right_arrow_disabled.png\\\"/>";
								}
								$pageDiv.="<div class='clear'></div>";
								$pageDiv.="</div>";
								echo '<script>$("#pageDiv").html("'.$pageDiv.'")</script>';

								for($i=0;$i<$_POST['page']*$maxResultsPerPage;++$i)$row=mysqli_fetch_assoc($res);
								for($i=$_POST['page']*$maxResultsPerPage;$i<(mysqli_num_rows($res)<($_POST['page']+1)*$maxResultsPerPage?mysqli_num_rows($res):($_POST['page']+1)*$maxResultsPerPage);++$i){
									$row=mysqli_fetch_assoc($res);
									$shortName=$row['shortName'];
									$name=$row['name'];
									$IP=$row['IP'];
									$date=$row['date'];
									$base=$row['base'];
									$hash=$row['hash'];
									$views=$row['views'];
									$type=$row['type'];
									$artist=$row['artist'];
									$description=$row['description'];
									$autodelete=$row['autodelete'];
									$origin=$row['origin'];
									$public=$row['public'];
									$size=$row['size'];
									$votes=$row['votes'];
									$rating=$row['rating'];
									?><div class="assetItem"><?
									switch($type){
										case "image/jpeg":
											echo "<img src=\"../uploads/$base.jpg\" id=\"asset$i\"/>";
											echo '<script>$("#asset'.$i.'").load(resize("#asset'.$i.'","img"));assets.push("img");</script>';
											$t="i-";
											break;
										case "image/png":
											echo "<img src=\"../uploads/$base.png\" id=\"asset$i\"/>";
											echo '<script>$("#asset'.$i.'").load(resize("#asset'.$i.'","img"));assets.push("img");</script>';
											$t="i-";
											break;
										case "image/gif":
											echo "<img src=\"../uploads/$base.gif\" id=\"asset$i\"/>";
											echo '<script>$("#asset'.$i.'").load(resize("#asset'.$i.'","img"));assets.push("img");</script>';
											$t="i-";
											break;
										case "image/bmp":
											echo "<img src=\"../uploads/$base.bmp\" id=\"asset$i\"/>";
											echo '<script>$("#asset'.$i.'").load(resize("#asset'.$i.'","img"));assets.push("img");</script>';
											$t="i-";
											break;
										case "video/mp4":
											echo "<video controls loop src=\"../uploads/$base.mp4\" id=\"asset$i\"/>Your browser does not support the video tag.</video>";
											echo '<script>$("#asset'.$i.'").bind("loadedmetadata", resize("#asset'.$i.'","vid"));assets.push("vid");</script>';
											$t="v-";
											break;
										case "video/webm":
											echo "<video controls loop src=\"../uploads/$base.webm\" id=\"asset$i\"/>Your browser does not support the video tag.</video>";
											echo '<script>$("#asset'.$i.'").bind("loadedmetadata", resize("#asset'.$i.'","vid"));assets.push("vid");</script>';
											$t="v-";
											break;
										case "video/ogg":
											echo "<video controls loop src=\"../uploads/$base.ogv\" id=\"asset$i\"/>Your browser does not support the video tag.</video>";
											echo '<script>$("#asset'.$i.'").bind("loadedmetadata", resize("#asset'.$i.'","vid"));assets.push("vid");</script>';
											$t="v-";
											break;
										case "application/x-shockwave-flash":
											echo "<object id=\"asset$i\">";
											echo "<param name=\"movie\" value=\"../uploads/$base.swf\">";
											echo "<param name=\"play\" value=\"false\">";
											echo "<param name=\"menu\" value=\"true\">";
											echo "<param name=\"allowFullScreen\" value=\"true\">";
											echo "<embed src=\"../uploads/$base.swf\"></embed>";
											echo "</object>";
											echo '<script>assets.push("flash");</script>';
											$t="v-";
											break;
									}
									?>
										<center>
											<div class="fileInfoDiv">
												<table class="fileInfo">
                                                                                                        <? if($showAdminControls){ ?>
													<tr>
														<td class="fileInfoLabel">File Name </td>
														<td class="fileInfoData"><?echo strlen($name)<32?$name:substr($name,0,32)."...";?></td>
													</tr>
													<? } ?>
													<?
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
													?>
													<tr>
														<td class="fileInfoLabel">Views </td>
														<td class="fileInfoData"><?echo number_format($views);?></td>
													</tr>
													<tr>
														<td class="fileInfoLabel">Auto-Delete </td>
														<td class="fileInfoData"><?echo $autodelete?"True":"False";?></td>
													</tr>
													<tr>
														<td class="fileInfoLabel">Type </td>
														<td class="fileInfoData"><?echo $type;?></td>
													</tr>
													<tr>
														<td class="fileInfoLabel">Hash </td>
														<td class="fileInfoData"><?echo $hash;?></td>
													</tr>
													<? if($showAdminControls){ ?>
													<tr>
														<td class="fileInfoLabel">IP Address </td>
														<td class="fileInfoData"><?echo $IP;?></td>
													</tr>
													<? } ?>
													<tr>
														<td class="fileInfoLabel">Date & Time </td>
														<td class="fileInfoData"><?echo $date;?></td>
													</tr>
													<tr>
														<td class="fileInfoLabel">URL </td>
														<td class="fileInfoData"><a href="<?echo "../$t$shortName"?>" target="_blank"><?echo "//$_SERVER[HTTP_HOST]/$t$shortName"?></a></td>
													</tr>
													<tr>
														<td class="fileInfoLabel">Gallery </td>
														<td id="galleryVisible<?echo $i?>" class="fileInfoData"><?echo $public?"Visible":"Hidden";?></td>
													</tr>
													<tr>
														<td class="fileInfoLabel">File Size </td>
														<td class="fileInfoData"><?echo formatBytes($size)."&nbsp;&nbsp;&nbsp;(".number_format($size).' bytes)';?></td>
													</tr>
													<tr>
														<td class="fileInfoLabel">Votes </td>
														<td class="fileInfoData"><?echo number_format($votes);?></td>
													</tr>
													<tr>
														<td class="fileInfoLabel">Popularity </td>
														<td id="popCell<?echo $i?>" class="fileInfoData" style="font-size:18px;"><?echo $rating."%";?></td>
														<script>
															$('#popCell<?echo $i?>').css("background",rgb(-.5+Math.PI-Math.PI/90*<?echo $rating?>));
														</script>
													</tr>
												</table>
												<? if($showAdminControls){ ?>
													<div id="visDiv<?echo $i?>">
														<?
															if(!$autodelete){
																if($public){
																	?>
																		<div id="visStatus<?echo $i?>"> This asset is public.</div>
																		<button class="publicButton" id="visButton<?echo $i;?>">Make Private</button>
																		<script>visibilities.push(1);</script>
																	<?
																}else{
																	?>
																		<div id="visStatus<?echo $i?>"> This asset is private.</div>
																		<button class="privateButton" id="visButton<?echo $i;?>">Make Public</button>
																		<script>visibilities.push(0);</script>
																	<?
																}
																echo "<br><hr>";
															}else{
																echo "<br><br>";
															}
														?>
														<button class="deleteButton" id="deleteButton<?echo $i?>">Delete Asset</button>
													</div>
													<script>
														$("#visButton<?echo $i?>").click(function(){
															visibilities[<?echo $i?>]=visibilities[<?echo $i?>]?0:1;
															$.post("toggleVis.php",
															{
																shortName: "<?echo $shortName?>",
																pub: visibilities[<?echo $i?>]
															},
															function(data){
																if(data=="0"){
																	$( "#visButton<?echo $i?>" ).removeClass( "publicButton" ).addClass( "privateButton" );
																	$( "#visButton<?echo $i?>" ).html( "Make Public" );
																	$( "#visStatus<?echo $i?>" ).html( "This asset is private." );
																	$( "#galleryVisible<?echo $i?>" ).html( "Hidden." );
																}else{
																	$( "#visButton<?echo $i?>" ).removeClass( "privateButton" ).addClass( "publicButton" );
																	$( "#visButton<?echo $i?>" ).html( "Make Private" );
																	$( "#visStatus<?echo $i?>" ).html( "This asset is public." );
																	$( "#galleryVisible<?echo $i?>" ).html( "Visible." );
																}
															});
														});
														$("#deleteButton<?echo $i?>").click(function(){
															if(confirm("Are you sure?!?\n\nThis action cannot be undone!")){															
																$.post("deleteAsset.php",
																{
																	shortName: "<?echo $shortName?>"
																},
																function(data){
																	document.location.reload();
																});
															}
														});
													</script>
												<? }else{ ?>
													<div style="margin-top:20px;">
														You must login to<br>access admin controls!<br><br>
														<button class="loginButton" onclick="$('#loginDivOuter').show();$('#preUserName').focus();">Login</button>
													</div>
												<? } ?>
												<div class="clear"></div>
											</div>
										</center>
										</div>
									<?
								}
							}else{
								?>
									<div style="margin-top:25%;font-size:28px;">
										No Results.<br><br><br>
									</div>
								<?
							}
						}else{
							?>
							<script>
								$("#selections").submit();
							</script>
							<?
						}
					?>
				</div>
			</center>
		</div>
		<script>
			window.history.pushState("admin","Lookie Admin Page", "../admin");
			setTimeout(resizeAll,3000);
		</script>
		<?
			if($loginError!=""){
				?>
					<script>
						alert("<?echo $loginError;?>");
					</script>
				<?
			}
		?>
	</body>
</html>
