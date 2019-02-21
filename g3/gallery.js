init=0;
nexting=false;
force=0;
function resize(){
	if(assetType==undefined)return;
	asset = $("#asset" )[0];
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
	}
	w2 = $(window).width();
	h2 = Math.floor(h * (w2 / w));
	if(h2 < $(window).height()){
		w2 = $(window).width()/1.45;
		h2 = Math.floor(h * (w2 / w));
	}else{
		h2 = $(window).height()/1.35;
		w2 = Math.floor(w * (h2 / h));
	}
	$("#assetFrame").width(w2+"px");
	$("#assetFrame").height(h2+"px");
	$("#featuredItemContainer").show();
	$("#clickToExit").show();
	$("#fileInfoDiv").width($("#assetFrame").width()+29);
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
function view(asset,type,sn){
	file=asset.substring(0,asset.length-4);
	s='<div id="clickToExit" onclick="closeAsset()"></div>';
	s+='<img src="'+galleryDir+'/left.png" id="leftArrow" onclick="previous()">';
	s+='<img src="'+galleryDir+'/right.png" id="rightArrow" onclick="next()">';
	s+='<div id="assetFrame">';
	s+='<img src="'+galleryDir+'/closeIcon.png" id="closeIcon" onclick="closeAsset()">';
	switch(type){
		case "pic":
			s+='<img onload="resize()" src="'+file+'" id="asset">';
		break;
		case "vid":
			s+='<video onload="resize()" controls loop src="'+file+'" id="asset"/></video>';
		break;
	}

	$.post( galleryDir+"/incrementCount.php", { i: sn } , function(data){
		s+=data;
		s+='</div>';
		$("#featuredItemContainer").html(s);
		assetType=type;
		shortName=sn;
		resize();
		resizeTimer=setInterval(resize,100);
	});
	
	window.history.pushState("", "", "../?i="+sn);
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

	if(force||$(window).scrollTop()+$(window).height() > $(document).height()-300){
		force=0;
		$(window).unbind('scroll',fetchMore);
		fetchComplete=false;
		$.post(galleryDir+'/fetch.php',{'scroll': scroll, 'sort': $('[name=sort]:checked').val()},
		function(data) {
			if(data.length>10){
				scroll++;
				$("#gallery").append(data);
				if($(window).scrollTop()+$(window).height() > $(document).height()-300){
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