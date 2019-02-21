<?
	set_time_limit(0);
	$nick="lookie_you";
	$channel="#maybe";
	$server="irc.rizon.net";
	
	function postData($fields,$url){

		//$fields = array( $key => $data);
		$postvars = http_build_query($fields);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	function linkCheck($text){
		$controlCodes = array(
			'/(\x03(?:\d{1,2}(?:,\d{1,2})?)?)/',    // Color code
			'/\x02/',                               // Bold
			'/\x0F/',                               // Escaped
			'/\x16/',                               // Italic
			'/\x1F/'                                // Underline
		);
		$text=preg_replace($controlCodes,'',$text);		
		preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $text, $match);
		return $match[0]; 
	}

	function processURL($url,$artist,$description){
		global $channel, $socket;
		$data=[];
		$data['url']=$url;
		$response=postData($data,"https://lookie.ml/download.php");
		switch($response){
			case "too big":
				$send="File is too big! File must be smaller than 100MB.";
				break;
			case "not found":
				$send="Sorry. Could not download from this link: ( $url )";
				break;
			case "wrong type":
				$send="Incorrect file type. Supported file types: jpg, png, gif, bmp, mp4, webm, ogv, swf, or Youtube links.";
				break;
			default:
				$data=[];
				$data['file']=$response;
				$data['origin']=$url;
				$data['artist']=$artist;
				$data['description']=$description;
				$data['autodelete']=0;
				$send="https://lookie.ml/".postData($data,"https://lookie.ml/receive_local.php");
				break;
		}
		fputs($socket, "PRIVMSG " . $channel . " :$send\n");
	}

	$socket = fsockopen($server, 6667) or die();
	fputs($socket,"USER $nick 0 $nick :$nick\n");
	fputs($socket,"NICK $nick\n");
	fputs($socket,"JOIN $channel\n");

	while(1) {

	   while($data = fgets($socket, 2048)) {
		  echo $data;
		  if(strpos($data,"VERSION"))fputs($socket,"JOIN $channel\n");
		  flush();
		  $ex = explode(' ', $data);
		  if($ex[0] == "PING") fputs($socket, "PONG ".$ex[1]."\n");
			$test=linkCheck($data);
			if(sizeof($test))$lastURL=$test[sizeof($test)-1];					
			$chat_text=substr($data,strpos($data,$channel)+strlen($channel)+2);
			if(strpos(trim($chat_text),"?lookie")===0){
				$urls=linkCheck($chat_text);
				if(sizeof($urls)){
					for($i=0;$i<1 /*sizeof($urls)*/ ;++$i){
						$artist="";
						$description="";
						if(strpos(strtolower($chat_text),"artist=")){
							$artist=substr($chat_text,strpos(strtolower($chat_text),"artist=")+7);
							if(strpos(strtolower($artist),"description"))$artist=substr($artist,0,strpos(strtolower($artist),"description"));
							$artist=trim(str_replace("\"","",$artist));
						}
						if(strpos(strtolower($chat_text),"artist =")){
							$artist=substr($chat_text,strpos(strtolower($chat_text),"artist =")+8);									
							if(strpos(strtolower($artist),"description"))$artist=substr($artist,0,strpos(strtolower($artist),"description"));
							$artist=trim(str_replace("\"","",$artist));
						}
						if(strpos(strtolower($chat_text),"description=")){
							$description=substr($chat_text,strpos(strtolower($chat_text),"description=")+12);
							if(strpos(strtolower($description),"artist"))$description=substr($description,0,strpos(strtolower($description),"artist"));
							$description=trim(str_replace("\"","",$description));
						}
						if(strpos(strtolower($chat_text),"description =")){
							$description=substr($chat_text,strpos(strtolower($chat_text),"description =")+13);							
							if(strpos(strtolower($description),"artist"))$description=substr($description,0,strpos(strtolower($description),"artist"));
							$description=trim(str_replace("\"","",$description));
						}
						echo "\n\n\n$chat_text\n";
						echo "artist = ".$artist."\ndescription = ".$description."\n";
						processURL($urls[$i],$artist,$description);
					}
				}else{
					if(strpos($chat_text," last")){
						if(strlen($lastURL)){
							$artist="";
							$description="";
							if(strpos(strtolower($chat_text),"artist=")){
								$artist=substr($chat_text,strpos(strtolower($chat_text),"artist=")+7);
								if(strpos(strtolower($artist),"description"))$artist=substr($artist,0,strpos(strtolower($artist),"description"));
								$artist=trim(str_replace("\"","",$artist));
							}
							if(strpos(strtolower($chat_text),"artist =")){
								$artist=substr($chat_text,strpos(strtolower($chat_text),"artist =")+8);									
								if(strpos(strtolower($artist),"description"))$artist=substr($artist,0,strpos(strtolower($artist),"description"));
								$artist=trim(str_replace("\"","",$artist));
							}
							if(strpos(strtolower($chat_text),"description=")){
								$description=substr($chat_text,strpos(strtolower($chat_text),"description=")+12);
								if(strpos(strtolower($description),"artist"))$description=substr($description,0,strpos(strtolower($description),"artist"));
								$description=trim(str_replace("\"","",$description));
							}
							if(strpos(strtolower($chat_text),"description =")){
								$description=substr($chat_text,strpos(strtolower($chat_text),"description =")+13);							
								if(strpos(strtolower($description),"artist"))$description=substr($description,0,strpos(strtolower($description),"artist"));
								$description=trim(str_replace("\"","",$description));
							}
							processURL($lastURL,$artist,$description);
						}else{
							$send="No URL was seen.";
							fputs($socket, "PRIVMSG " . $channel . " :$send\n");
						}
					}elseif(strpos($chat_text," help")){
						$send="Usage: ?lookie URL|last artist=\"Someone Special\" description=\"Short Description\"";
						fputs($socket, "PRIVMSG " . $channel . " :$send\n");
					}else{
						$send="Missing URL. Try ?lookie help";
						fputs($socket, "PRIVMSG " . $channel . " :$send\n");
					}
				}
			}
		}
	}
?>
