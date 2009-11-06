//0 means disabled; 1 means enabled;
var popupStatus = 0;

//loading popup
function loadPopup(){
	//
	//loads popup only if it is disabled
	if(popupStatus==0){
		$("#backgroundPopup").css({"opacity": "0.7"});
		$("#backgroundPopup").show();
		$("#centerPopup").show();
		popupStatus = 1;
		
		
		var windowWidth = document.documentElement.clientWidth;
		var windowHeight = document.documentElement.clientHeight;
		var popupHeight = $("#centerPopup").height();
		var popupWidth = $("#centerPopup").width();
		//centering
		$("#centerPopup").css({	
			"position": "absolute",
			"top": windowHeight/2-popupHeight/2,
			"left": windowWidth/2-popupWidth/2
		});
		//only need force for IE6

		$("#backgroundPopup").css({
			"height": windowHeight
		});
	}
}

//centering popup
function centerPopup(){
	//
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#centerPopup").height();
	var popupWidth = $("#centerPopup").width();
	//centering
	$("#centerPopup").css({	
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6

	$("#backgroundPopup").css({
		"height": windowHeight
	});

}


//disabling popup
function disablePopup(){
	//
	//disables popup only if it is enabled
	if(popupStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		$("#centerPopup").fadeOut("slow");
		popupStatus = 0;
	}
}


//
//CONTROLLING EVENTS IN jQuery
//$(document).ready(function(){
//	//
//	//LOADING POPUP
//	//Click the button event!
//	$(".myButton").click(function(){
//		//centering with css
//		centerPopup();
//		//load popup
//		loadPopup();
//	});
//
//
//});

