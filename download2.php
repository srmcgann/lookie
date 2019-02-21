<?
	if(isset($_POST['url'])){
		require("functions.php");
		require("db.php");
		chdir("temp");	
		$url=$_POST['url'];
		
		if(strpos($url,"youtube.com/") || strpos($url,"youtu.be/")){
			if(strpos($url,"youtube.com")){
				$parts = parse_url($url);
				parse_str($parts['query'], $query);
				$key=$query['v'];		
			}
			if(strpos($url,"youtu.be")){
				$parts=explode("/",$url);
				$key=$parts[sizeof($parts)-1];
				$key=substr($key,0,(strpos($key,"?")?strpos($key,"?"):1000));
			}
			$key=str_replace(";","",$key);
			$key=str_replace("&&","",$key);
			if(strlen($key)>11){
				echo "not found";
			}else{
				$output=[];
				exec("youtube-dl -F --id https://www.youtube.com/watch?v=$key 2>&1", $output);
				$a = explode(" ",$output[sizeof($output)-1]);
				$b=[];
				for($i=0;$i<sizeof($a);++$i)if(strlen($a[$i]))array_push($b,$a[$i]);
				$fCode=$b[0];
				$ext=$b[1];
				$ok=0;
				switch($ext){
					case "mp4": $ok=1; break;
					case "webm": $ok=1; break;
					case "ogv": $ok=1; break;
				}
				if($ok){
					$output=[];
					exec("youtube-dl -f $fCode --id https://www.youtube.com/watch?v=$key --max-filesize 100m 2>&1", $output);
					if(strpos($output[sizeof($output)-1],"larger than max")){
						echo "too big";
					}elseif(strpos($output[sizeof($output)-1],"100%")){
						echo $key.".".$ext;
					}else{
						echo "not found"; 
					}
				}else{
					echo "wrong type";
				}
			}
		}else{
			if(exists($url)){
				$path_parts = pathinfo($url);
				$ext=strtolower(substr($path_parts['extension'],0,strpos($path_parts['extension'],"?")?strpos($path_parts['extension'],"?"):1000));
				$name=substr($path_parts['basename'],0,strpos($path_parts['basename'],"?")?strpos($path_parts['basename'],"?"):1000);
				$ok=0;
				switch($ext){
					case "jpg": $ok=1; break;
					case "jpeg": $ok=1; break;
					case "png": $ok=1; break;
					case "gif": $ok=1; break;
					case "bmp": $ok=1; break;
					case "mp4": $ok=1; break;
					case "webm": $ok=1; break;
					case "ogv": $ok=1; break;
				}
				if($ok){
					$size=retrieve_remote_file_size($url);
					if($size>100000000){
						echo "too big";
					}elseif($size){
						set_time_limit(0);
						$fp = fopen ( $name, 'w');
						$ch = curl_init(str_replace(" ","%20",$url));
						curl_setopt($ch, CURLOPT_FILE, $fp); 
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_exec($ch); 
						curl_close($ch);
						fclose($fp);
						echo $name;
					}else{
						echo "not found";
					}
				}else{
					echo "wrong type";
				}
			}else{
				echo "not found";
			}
		}
	}
?>