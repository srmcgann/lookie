<?
	set_time_limit(0);
	$nick="lookiebot";
	$channel="#trollface";
	$server="irc.rizon.net";
	
	function postData($key,$data,$url){

		$fields = array( $key => $data);
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
		preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $text, $match);
		return $match[0]; 
	}

	function processURL($url){
		global $channel, $socket;
		$response=postData('url',$url,"http://lookie.ml/download.php");
		switch($response){
			case "too big":
				$send="File is too big! File must be smaller than 100MB.";
				break;
			case "not found":
				$send="Sorry. Could not download from this link: ( $url )";
				break;
			case "wrong type":
				$send="Incorrect file type. Supported file types: jpg, png, gif, bmp, mp4, webm, ogv, or Youtube links.";
				break;
			default:
				$send="http://lookie.ml/".postData('file',$response,"http://lookie.ml/receive_local.php");
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
		  flush();
		  $ex = explode(' ', $data);
		  if($ex[0] == "PING") fputs($socket, "PONG ".$ex[1]."\n");
		  $search_string = "/^:([A-Za-z0-9_\-]+)[@!~a-zA-Z0-9@\.\-]+\s*([A-Z]+)\s*[:]*([\#a-zA-Z0-9\-]+)*\s*[:]*([!\#\-\.A-Za-z0-9 ]+)*/";
		  $do = preg_match($search_string, $data, $matches);

		  if(isset($matches['2'])) {
			 switch($matches['2']) {
				case "PRIVMSG":
					$user = $matches['1'];
					$channel = $matches['3'];
					$chat_text = isset($matches['4']) ? $matches['4'] : "";
					$test=linkCheck($data);
					if(sizeof($test))$lastURL=$test[sizeof($test)-1];
					if(strpos($data,"?lookie")){
						$urls=linkCheck($data);
						if(sizeof($urls)){
							for($i=0;$i<sizeof($urls);++$i){
								processURL($urls[$i]);
							}
						}else{
							if(strpos($data," last")){
								if(strlen($lastURL)){
									processURL($lastURL);
								}else{
									$send="No URL was seen.";
									fputs($socket, "PRIVMSG " . $channel . " :$send\n");
								}
							}else{
								$send="Missing URL.";
								fputs($socket, "PRIVMSG " . $channel . " :$send\n");
							}
						}
					}
				break;
			 }
		  }
	   }
	}
?>
