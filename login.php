<?
	$loginError="";
	if($_POST['logout']){
		setcookie("state","0",time()-1);
		$_COOKIE["state"]=0;
	}
	if(isset($_POST['userName']) && $_POST['userName'] != ""){
		$name=mysqli_real_escape_string($link,$_POST['userName']);
		$sql="SELECT * FROM users WHERE name LIKE \"$name\"";
		$res=$link->query($sql);
		if(mysqli_num_rows($res)){
			$row=mysqli_fetch_assoc($res);
			if(md5($_POST['pass'])==$row['pass']){
				setcookie("state","1",time()+86400);
				$_COOKIE["state"]=1;
				$id=$row['id'];
				$last_login=date("Y-m-d H:i:s",strtotime("now"));
				$IP=$_SERVER['REMOTE_ADDR'];
				$sql="UPDATE users SET IP= \"$IP\", last_login=\"$last_login\" WHERE id=$id";
				$link->query($sql);
			}else{
				$loginError="Incorrect Password!";
				setcookie("state","0",time()-1);
				$_COOKIE["state"]=0;
			}
		}else{
			$loginError="User Name ($name) Not Found!";
			setcookie("state","0",time()-1);
			$_COOKIE["state"]=0;
		}
	}
	$showAdminControls=$_COOKIE["state"];
?>