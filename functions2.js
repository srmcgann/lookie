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

function applyZoom(){
	viewer = ImageViewer('.pannable-image',{ maxZoom : 1000, snapView : false});
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
				$.post( "receive_local2.php", { file: data, origin: url, autodelete: autodelete } , function(data){
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
					$.post( "receive_local2.php", { file: data, origin: url, artist: artist, description: description, autodelete:autodelete } , function(data){
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
		xhr.open('post', 'receive2.php', true);
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
		xhr.addEventListener('load', function(e) {
			$("#prog").html("File Received!");
			window.location.href=this.responseText;
		});
		xhr.open('post', 'receive2.php', true);
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
