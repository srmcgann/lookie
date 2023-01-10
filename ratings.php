<?
	require("db.php");
	require("functions.php");
	function rating($id){
		global $link;
		$sql="SELECT rating, votes FROM images WHERE id = $id";
		$res=$link->query($sql);
		$row=mysqli_fetch_assoc($res);
        $data['number_votes'] = $row['votes'];
        $data['dec_avg'] = $row['rating'];
        $data['whole_avg'] = round($row['rating']/20+1);
		$IP=ipToDec($_SERVER['REMOTE_ADDR']);
		$sql="SELECT vote FROM votes WHERE asset_id = $id AND IP=$IP";
		$res=$link->query($sql);
		$row=mysqli_fetch_assoc($res);
        $data['user_vote'] = $row['vote'];
        return json_encode($data);		
	}
	if(isset($_POST['fetch'])&&$_POST['fetch']==1){
		$shortName=$_POST['shortName'];
		$id=alphaToDec($shortName);
		echo rating($id);
	}elseif(isset($_POST['shortName'])){
		preg_match('/cloud_([1-6]{1})/', $_POST['clicked_on'], $match);
		$vote=$match[1];
		$IP=ipToDec($_SERVER['REMOTE_ADDR']);
		$shortName=$_POST['shortName'];
		$id=alphaToDec($shortName);		
		$sql="SELECT * FROM votes WHERE IP=$IP AND asset_id=$id";
		$res=$link->query($sql);
		if(mysqli_num_rows($res)){
			$sql="UPDATE votes SET vote=$vote WHERE IP=$IP AND asset_id=$id";
			$link->query($sql);
		}else{
			$sql="INSERT INTO votes (IP,asset_id,vote) VALUES($IP,$id,$vote)";
			$link->query($sql);
		}
		$sql="SELECT vote FROM votes WHERE asset_id=$id";
		$res=$link->query($sql);
		$votes=mysqli_num_rows($res);
		$total=0;
		for($i=0;$i<$votes;++$i){
			$row=mysqli_fetch_assoc($res);
			$total+=($row['vote']-1);
		}
		$rating=$total/$votes*20;
		$sql="UPDATE images SET rating=$rating, votes=$votes WHERE id=$id";
		$link->query($sql);
		echo rating($id);
	}
?>
