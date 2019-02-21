<?
	if($_POST['i']){
		require("../db.php");
		require("../functions.php");
		$shortName=$_POST['i'];
		$id=alphaToDec($shortName);
		$sql="UPDATE images SET views = views + 1 WHERE id=$id";
		$link->query($sql);
		$sql="SELECT * FROM images WHERE id=$id";
		$res=$link->query($sql);
		$row=mysqli_fetch_assoc($res);
		$name=$row['name'];
		$views=$row['views'];
		$size=$row['size'];
		$url="http://$_SERVER[HTTP_HOST]/?i=$row[shortName]";
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
							echo '<td class="fileInfoLabel">Name </td>';
							echo '<td class="fileInfoData">'.(strlen($name)<38?$name:substr($name,0,38)."...").'</td>';
						echo '</tr>';
						echo '<tr>';
							echo '<td class="fileInfoLabel">Size </td>';
							echo '<td class="fileInfoData">'.formatBytes($size)."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(".number_format($size).' bytes)</td>';
						echo '</tr>';
					echo '</table>';
				echo '</div>';
			echo '</center>';
		echo '</div>';
	}
?>