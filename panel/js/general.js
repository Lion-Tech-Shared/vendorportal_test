$(function(){
	var tbl_sku="#dataTables-lookup-sku";	
	

	$("#miniDialog").hide();
	//$(".mySelectedSku").text("("+multArr.length+")");
    $(".msg-dialog").on("hidden.bs.modal", function () {
		 $(".msg-dialog .my_msg").text('');
	});
	 
	//_submit();
 	//alert($("#miniDialog").width());
 	var winWidHalf=$(window).width()/2;
 	var miniWidSize=$("#miniDialog").width()/2;

 	//var winHeightHalf=($(window).height()/2)/2;
 	var winHeightHalf=((screen.availHeight)/2)/2;
 	var miniHeightSize=$("#miniDialog").height()/2;

 	//alert($(".dialog-lookup-sku").offset().top);
 	//alert(screen.availHeight);
 	//alert(miniSize);
  	//$("#miniDialog").css({"margin-left":(winWidHalf-miniWidSize)+"px"});
  	//$("#miniDialog").css({"margin-top":(winHeightHalf-miniHeightSize)+"px"});
});

 

 
function showMiniDialog(){
	//$(".mySku_msg").text('Please Fill up fields!');
	//clear_timeout();
	myTimeId=setTimeout(function(){
			//$(".msg-dialog").modal("show");	
			//$("#miniDialog").fadeIn("slow");
			$("#miniDialog").modal("show");
			setTimeout(function(){
			//$(".msg-dialog").modal("show");	
				$("#miniDialog").modal("hide");
				return true;
			},4000);
	},200);


}
 
function showLoadingDialog(){
	//$(".mySku_msg").text('Please Fill up fields!');
	//clear_timeout();	 
	$("#loadingDialog").modal("show");			 
}


function delay(){
		setTimeout(function(){
			//$(".msg-dialog").modal("show");	
			//$("#miniDialog").fadeIn("slow");
			setTimeout(function(){
			//$(".msg-dialog").modal("show");	
				//$("#miniDialog").fadeOut("slow");
			},2000);
	},200);	
}

function clear_timeout(){
	//alert(myTimeId);
	clearTimeout(myTimeId);
}
function closeImmedMiniD(){
	//setTimeout(function(){
		$("#miniDialog").modal("hide");
	//},2000);
	
}