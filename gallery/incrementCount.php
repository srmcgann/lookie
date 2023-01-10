<?
	if($_POST['i']){
		require("../db.php");
		require("../functions.php");
		$shortName=$_POST['i'];
		$id=alphaToDec($shortName);
        $lastviewed=date("Y-m-d H:i:s",strtotime("now"));
		$sql="UPDATE images SET views = views + 1, lastviewed=\"$lastviewed\" WHERE id=$id";
		$link->query($sql);
		$sql="SELECT * FROM images WHERE id=$id";
		$res=$link->query($sql);
		$row=mysqli_fetch_assoc($res);
		$name=$row['name'];
		$views=$row['views'];
		$artist=$row['artist'];
		$description=$row['description'];
		$origin=$row['origin'];
		$base=$row['base'];
		$size=filesize("../uploads/$base".suffix($row['type']));
		$assetType=assetType($row['type']);
		$t=$assetType=="Image"?"i-":"v-";
		$url="http://$_SERVER[HTTP_HOST]/$t$row[shortName]";
		echo '<div id="fileInfoDivOuter">';
			echo '<center>';
				echo '<div id="fileInfoDiv">';
					echo '<table id="fileInfo">';
						echo '<tr>';
							echo '<td class="fileInfoLabel">Link </td>';
							echo '<td class="fileInfoData"><a target="_blank" href="'.$url.'">'.$url.'</a></td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td class="fileInfoLabel">Views </td>';
							echo '<td class="fileInfoData">'.number_format($views).'</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td class="fileInfoLabel">File Name </td>';
							echo '<td class="fileInfoData">'.(strlen($name)<38?$name:substr($name,0,38)."...").'</td>';
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
							echo '<td class="fileInfoLabel">Size </td>';
							echo '<td class="fileInfoData">'.formatBytes($size)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(".number_format($size).' bytes)</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td class="fileInfoLabel">Popularity</td>';
							echo '<td id="popCell" style="font-size:18px;">'.$rating.'&nbsp;&nbsp;'.$votes.'</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td class="fileInfoLabel">Rate this '.$assetType.'</td><td>';
							?>
							<div class='assetChoice'>
								<div id="rating_<?echo $shortName?>" class="rate_widget">
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
										shortName : widget.attr('id').substr(7)
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
										shortName : $(widget).attr('id').substr(7),
										fetch: 1
									};
									$.post(
										'ratings.php',
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
	}
?>
