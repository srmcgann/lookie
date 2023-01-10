init=0;
nexting=false;
force=0;
function resize(){
	if(assetType == 'undefined')return;
	asset = $("#asset" )[0];
	if(assetType=="flash" || $('#asset').data('loaded')) {
		switch(assetType){
			case "pic":
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
			w2 = $(window).width()/1.45;
			h2 = Math.floor(h * (w2 / w));
		}else{
			h2 = $(window).height()/1.65;
			w2 = Math.floor(w * (h2 / h));
		}
		$("#asset").width(w2+"px");
		$("#asset").height(h2+"px");
		$("#assetFrame").width(w2+"px");
		$("#assetFrame").height(h2+"px");
		$("#fileInfoDiv").width($("#assetFrame").width()+29);
	}
	$("#featuredItemContainer").show();
	$("#clickToExit").show();
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
function rgb(col){

        col+=.000001;
        var r = parseInt((.25+Math.sin(col)*.25)*16);
        var b = parseInt((.25+Math.cos(col)*.25)*16);
        var g = 0;//parseInt((.5-Math.sin(col)*.5)*16);
        return "#"+r.toString(16)+r.toString(16)+g.toString(16)+g.toString(16)+b.toString(16)+b.toString(16);
}
function closeAsset(){
	
	$("#featuredItemContainer").hide();
	$("#clickToExit").hide();
	if(assetType=="vid")asset.pause();
	clearInterval(resizeTimer);
	init=0;
	window.history.pushState("", "", galleryDir+"/?sort="+sort);
	assetType=undefined;
}
function lastSibling(node){
    var tempObj=node.parentNode.lastChild; 
    while(tempObj.nodeType!=1 && tempObj.previousSibling!=null){  
    tempObj=tempObj.previousSibling;   
    }  
    return (tempObj.nodeType==1)?tempObj:false; 
}
function previous(){

	if(assetType=="vid"){
		asset.pause();
		init=0;
	}
	clearInterval(resizeTimer);
	if($("#"+shortName).prev().prop("tagName") != undefined){
		$("#"+shortName).prev().click();
	}else{
		$("div").last().click();
	}
}
function next(){

	if(assetType=="vid"){
		asset.pause();
		init=0;
	}
	clearInterval(resizeTimer);
	if($("#"+shortName).next().prop("tagName") != undefined){
		nexting=false;
		$("#"+shortName).next().click();
	}else{
		force=1;
		fetchMore();
		if(fetchComplete){
			nexting=false;
			if($("#"+shortName).next().prop("tagName") != undefined){
				$("#"+shortName).next().click();
			}else{
				a=$("#"+shortName);
				while(a.prev().prop("tagName")!=undefined)a=a.prev();
				a.click();
			}
		}else{
			nexting=true;
		}
	}
}

function applyZoom(){
	viewer = ImageViewer('.pannable-image',{ maxZoom : 1000, snapView : false, });
}

function view(asset,type,sn){
	file=asset.substring(0,asset.length-4);
	s='<div id="clickToExit" onclick="closeAsset()"></div>';
	s+='<img src="'+galleryDir+'/left.png" id="leftArrow" onclick="previous()">';
	s+='<img src="'+galleryDir+'/right.png" id="rightArrow" onclick="next()">';
	s+='<div id="assetFrame">';
	s+='<img src="'+galleryDir+'/closeIcon.png" id="closeIcon" onclick="closeAsset()">';
	switch(type){
		case "pic":
			s+='<img onload="$(this).data(\'loaded\', \'loaded\');resize();applyZoom();" src="'+file+'" data-high-res-src="'+file+'" class=\"pannable-image\" id="asset">';
			url=sn;
		break;
		case "vid":
			s+='<video onloadedmetadata="$(this).data(\'loaded\', \'loaded\');" controls loop src="'+file+'" id="asset"/></video>';
		break;
		case "flash":
			s+= "<object id=\"asset\">";
			s+= "<param name=\"movie\" value=\""+file+"\">";
			s+= "<param name=\"allowFullScreen\" value=\"true\">";
			s+= "<embed src=\""+file+"\"></embed>";
			s+= "</object>";
		break;
	}

	$.post( galleryDir+"/incrementCount.php", { i: sn } , function(data){
		s+=data;
		s+='</div>';
		assetType=type;
		shortName=sn;
		$("#featuredItemContainer").html(s);
		resize();
		resizeTimer=setInterval(resize,100);
	});
	
	window.history.pushState("", "", "../"+(type=="pic"?"i-":"v-")+sn);
}
$(document).ready(function(){
	scroll=0;
	$(window).bind('scroll',fetchMore);
	fetchMore();
});

$("sort").change(function() {
    $('input[name="' + this.name + '"]').removeClass("hidden");

    $(this).addClass("hidden");
});

function resort(){
	if($('[name=sort]:checked').val()!=sort){
		window.location="?sort="+$('[name=sort]:checked').val();
	}
}

function fetchMore(){

	if(force||$(window).scrollTop()+$(window).height() > $(document).height()-900){
		force=0;
		$(window).unbind('scroll',fetchMore);
		fetchComplete=false;
		$.post(galleryDir+'/fetch.php',{'scroll': scroll, 'sort': $('[name=sort]:checked').val()},
		function(data) {
			if(data.length>10){
				scroll++;
				$("#gallery").append(data);
				if($(window).scrollTop()+$(window).height() > $(document).height()-900){
					fetchMore();
				}else{
					$(window).bind('scroll',fetchMore);
				}
			}
			fetchComplete=true;
			if(nexting){
				next();
			}
		});
	}
}
$(document).keyup(function(e) {
     if (e.keyCode == 27) {
        closeAsset();
    }
});
