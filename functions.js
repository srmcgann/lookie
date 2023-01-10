function resize(){
	asset = $("#asset")[0];
	if(1||$('#asset').data('loaded')) {
		switch(assetType){
			case "img":
				w=asset.naturalWidth;
				h=asset.naturalHeight;
				break;
			case "vid":
				w=asset.videoWidth;
				h=asset.videoHeight;
				if(!init){
					asset.play();
					init=1;
				}
				break;
			case "flash":
				w=$("#asset").width();
				h=$("#asset").height();
				break;
		}
		w2 = $(window).width();
		h2 = Math.floor(h * (w2 / w));
		if(h2 < $(window).height()){
			w2 = $(window).width()/1.35;
			h2 = Math.floor(h * (w2 / w));
		}else{
			h2 = $(window).height()/1.5;
			w2 = Math.floor(w * (h2 / h));
		}
		if(assetType=="flash"){
			$("#asset").width(parseInt(w2));
			$("#asset").height(parseInt(h2));
			$("#embed").width(parseInt(w2));
			$("#embed").height(parseInt(h2));
		}else{
			$("#asset").width(parseInt(w2));
			$("#asset").height(parseInt(h2));			
		}
		$("#fileInfoDiv").width($("#asset").width()+2);
	}
}

function mobileCheck() {
 
 var check = false;
  (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
  return check;
}

function applyZoom(){
	if(!mobileCheck())viewer = ImageViewer('.pannable-image',{ maxZoom : 1000, snapView : false});
}


function updateZoom(){
	var x=Math.round(window.x*10000)/10000;
	var y=Math.round(window.y*10000)/10000;
	var zoom=window.zoom;
	var newURL=url+"?x="+x+"&y="+y+"&zoom="+zoom;
	//if(url!=newURL) window.history.pushState("", "", newURL);
}

function formatBytes(bytes,decimals) {
   if(bytes == 0) return '0 Byte';
   var k = 1000;
   var dm = decimals + 1 || 3;
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
   var i = Math.floor(Math.log(bytes) / Math.log(k));
   return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

function handleKeypress(e){
	if(e.keyCode === 13) download();
}

function download(){
	
	$("#uploadInner").hide();
	fileInfo='<div style="color:#aa0;font-size:36px;width:100%;text-align:center;" id="prog">Fetching File...</div>';
	$("#fileInfo1").html(fileInfo);
	url=$("#inputURL").val();
	$.post( "download.php", { url: url } , function(data){
   // console.log("download.php data ", data)
		switch(data){
			case "not found":
				fileInfo='<div class="status">Invalid URL! (File not found)</div>';
				$("#fileInfo1").html(fileInfo);
				$("#uploadInner").show();				
				break;
			case "too big":
				fileInfo='<div class="status">File Size is too Large!</div>';
				$("#fileInfo1").html(fileInfo);
				$("#uploadInner").show();
				break;
			case "wrong type":
				fileInfo='<div class="status">This is not a supported file!</div>';
				$("#fileInfo1").html(fileInfo);
				$("#uploadInner").show();
				break;
			default :
				fileInfo='<div style="color:#ff0">Downloaded File:<br><span style="color:#0f8;">'+
						 (data.length>28?data.substr(0,28)+"...":data)+
						 '</span><br><br>Processing...</div>';
				$("#fileInfo1").html(fileInfo);
				var autodelete = document.querySelector('input[name="autodelete"]:checked').value;
				$.post( "receive_local.php", { file: data, origin: url, autodelete: autodelete } , function(data){
					//console.log('receive_local.php data', data)
          window.location.href=data;
				});
				break;
		}
	});
}

function downloadAdvanced(){
	
	if(file2.size < 100){
		fileInfo='<div style="color:#aa0;font-size:36px;width:100%;text-align:center;" id="prog">Fetching File...</div>';
		$("#fileInfo2").html(fileInfo);
		url=$("#inputURL2").val();
		artist=$("#artist").val();
		description=$("#description").val();
		$.post( "download.php", { url: url } , function(data){
			switch(data){
				case "not found":
					fileInfo='<div class="status">Invalid URL! (File not found)</div>';
					$("#fileInfo2").html(fileInfo);
					$("#uploadInner2").show();				
					break;
				case "too big":
					fileInfo='<div class="status">File Size is too Large!</div>';
					$("#fileInfo2").html(fileInfo);
					$("#uploadInner2").show();
					break;
				case "wrong type":
					fileInfo='<div class="status">This is not a supported file!</div>';
					$("#fileInfo2").html(fileInfo);
					$("#uploadInner2").show();
					break;
				default :
					fileInfo='<div style="color:#ff0">Downloaded File:<br><span style="color:#0f8;">'+
							 (data.length>28?data.substr(0,28)+"...":data)+
							 '</span><br><br>Processing...</div>';
					$("#fileInfo2").html(fileInfo);
					var autodelete = document.querySelector('input[name="autodelete"]:checked').value;
					$.post( "receive_local.php", { file: data, origin: url, artist: artist, description: description, autodelete:autodelete } , function(data){
						window.location.href=data;
					});
					break;
			}
		});
	}else{
		uploadFile(file2);
	}
}

		
function uploadFile(file){
	
	if($("#advancedDiv").is(':visible')){
		fileInfo=$("#fileInfo2").html()+'<br><br><span style="color:#ff0" id="prog">Uploading File...</span>';
		$("#fileInfo2").html(fileInfo);
		$(".goButton").prop("disabled",true);
		$(".cancelButton").prop("disabled",true);
		var xhr = new XMLHttpRequest();
		(xhr.upload || xhr).addEventListener('progress', function(e) {
			var done = e.position || e.loaded
			var total = e.totalSize || e.total;
			var pc=Math.round(done/total*100);
			if(pc<100){
				$("#prog").html('Uploading File...'+pc+ '%');
			}else{
				$("#prog").html('File Received. Processing...');			
			}
		});
		xhr.addEventListener('load', function(e) {
			$("#prog").html("File Received!");
			window.location.href=this.responseText;
		});
		xhr.open('post', 'receive.php', true);
		var fd = new FormData();
		fd.append("file", file);
		artist=$("#artist").val();
		description=$("#description").val();
		fd.append("artist", artist);
		fd.append("description", description);
		fd.append("autodelete", document.querySelector('input[name="autodelete"]:checked').value);
	}else{
		$("#uploadInner").hide();
		fileInfo=$("#fileInfo1").html()+'<br><br><span style="color:#ff0" id="prog">Uploading File...</span>';
		$("#fileInfo1").html(fileInfo);
		
		var xhr = new XMLHttpRequest();
		(xhr.upload || xhr).addEventListener('progress', function(e) {
			var done = e.position || e.loaded
			var total = e.totalSize || e.total;
			var pc=Math.round(done/total*100);
			if(pc<100){
				$("#prog").html('Uploading File...'+pc+ '%');
			}else{
				$("#prog").html('File Received. Processing...');			
			}
		});
xhr.onreadystatechange = function() {
    if (xhr.readyState == XMLHttpRequest.DONE) {
       // alert(xhr.responseText);
    }
}
		xhr.addEventListener('load', function(e) {
			$("#prog").html("File Received!");
      window.location.href=this.responseText;
		});
		xhr.open('post', 'receive.php', true);
		var fd = new FormData();
		fd.append("file", file);
		fd.append("autodelete", document.querySelector('input[name="autodelete"]:checked').value);
	}
	xhr.send(fd);
}

function rgb(col){

        col+=.000001;
        var r = parseInt((.25+Math.sin(col)*.25)*16);
        var b = parseInt((.25+Math.cos(col)*.25)*16);
        var g = 0;//parseInt((.5-Math.sin(col)*.5)*16);
        return "#"+r.toString(16)+r.toString(16)+g.toString(16)+g.toString(16)+b.toString(16)+b.toString(16);

}


function validateFile(file1){
	
	if(file1.size<=100000000){
		type = file1.type;

		fileInfo='<span style="color:#0f0;">File: </span><span style="font-size:18px;">'+
				 (file1.name.length>28?file1.name.substr(0,28)+"...":file1.name)+
				 '</span><br><span style="color:#0f0;">Size: </span>' +
				 formatBytes(file1.size,1);
		$("#fileInfo1").html(fileInfo);
		$("#fileInfo1").show();
		if(file1.type=="image/jpeg" ||
		   file1.type=="image/png" ||
		   file1.type=="image/gif" ||
		   file1.type=="image/bmp" ||
		   file1.type=="video/mp4" ||
		   file1.type=="video/webm" ||
		   file1.type=="application/x-shockwave-flash" ||
		   file1.type=="application/x-shockwave-flash2-preview" ||
		   file1.type=="application/futuresplash" ||
		   file1.type=="image/vnd.rn-realflash" ||
		   file1.type=="application/x-download" ||
		   file1.type=="video/ogg"){
			   uploadFile(file1);
		}else{
			fileInfo='<div class="status">This is not a supported file!</div>';
			$("#fileInfo1").html(fileInfo);				   
		}
	}else{
		$("#fileInfo1").show();
		fileInfo='<div class="status">File Size is too Large!</div>';
		$("#fileInfo1").html(fileInfo);
	}
}


function validateAdvancedFile(file){
	
	file2=file;
	if(file2.size<=100000000){
		type = file2.type;

		fileInfo='<span style="color:#0f0;">File: </span><span style="font-size:18px;">'+
				 (file2.name.length>18?file2.name.substr(0,18)+"...":file2.name)+
				 '</span><br><span style="color:#0f0;">Size: </span>' +
				 formatBytes(file2.size,1);
		$("#fileInfo2").html(fileInfo);
		$("#fileInfo2").show();
		if(file2.type=="image/jpeg" ||
		   file2.type=="image/png" ||
		   file2.type=="image/gif" ||
		   file1.type=="image/bmp" ||
		   file2.type=="video/mp4" ||
		   file2.type=="video/webm" ||
		   file2.type=="application/x-shockwave-flash" ||
		   file2.type=="application/x-shockwave-flash2-preview" ||
		   file2.type=="application/futuresplash" ||
		   file2.type=="image/vnd.rn-realflash" ||
		   file2.type=="application/x-download" ||
		   file2.type=="video/ogg"){
		}else{
		fileInfo='<span style="color:#0f0;">File: </span><span style="font-size:18px;">'+
				 (file2.name.length>28?file2.name.substr(0,28)+"...":file2.name)+
				 '</span><br><span style="color:#0f0;">Size: </span>' +
				 formatBytes(file2.size,1)+"<br>"+
				 '<div class="status">This is not a supported file!</div>';
			$("#fileInfo2").html(fileInfo);				   
		}
	}else{
		$("#fileInfo2").show();
		fileInfo='<div class="status">File Size is too Large!</div>';
		$("#fileInfo2").html(fileInfo);
	}
}


function addListeners(){
	
	document.getElementById('file1').addEventListener('change', function(e) {
		validateFile(this.files[0]);
	});

	document.getElementById('file2').addEventListener('change', function(e) {
		validateAdvancedFile(this.files[0]);
	});
}
