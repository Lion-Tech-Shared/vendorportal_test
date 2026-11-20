/*if(typeof(EventSource) !== "undefined") {
    var source = new EventSource("demo_sse.php");
    source.onmessage = function(event) {
        document.getElementById("result").innerHTML = event.data + "<br>";
       // $("html").html("");
    };
} else {
    document.getElementById("result").innerHTML = "Sorry, your browser does not support server-sent events...";
}*/
 
// JavaScript Document
$(document).ready(function(){
	//alert("Asdf");
	$("#modal_forgot_pwd").on("show.bs.modal",function(){
		$("#login-form-group").removeClass("has-error");			
		$("#myuserid").val(' ');
		$("#myuserid").focus();
	});
	
	$("#forgot_pwd").click(function(){			 
		$("#modal_forgot_pwd").modal("show");
		setTimeout( 
		 	function(){
				$("#myuserid").focus();	 
		},500);
	});	
	$("#btn_send_link").click(function(){
		var userid=$.trim($("#myuserid").val());
		$("#myuserid").prop("disabled",true);
		$("#btn_send_link").prop("disabled",true);
		 
		if(userid!=''){
			$("#loading").html("<img height='30' width='80' src='css-main/images/blue-load.gif' />");
			$.ajax({type:'POST',url:'panel/control/send.php',data:({userid:userid}),
				success:function(data){		
					//alert(data);								 				
			 				  
					if(data.split("-")[0]==1){
						alert("Message Sent to your email,  Link will expire in "+data.split("-")[1]+" minutes");	
					}
					else if(data==-2){
						alert("Email not sent.");	
					}
					else{
						alert("Userid Invalid");	
					}
				 	$("#loading").html(" ");
					$("#modal_forgot_pwd").modal("hide");
					$("#myuserid").prop("disabled",false);
					$("#btn_send_link").prop("disabled",false);
				}
			});
		}else{
			$("#login-form-group").addClass("has-error");
			$("#myuserid").prop("disabled",false);
			$("#btn_send_link").prop("disabled",false);			
		}				
	});
});