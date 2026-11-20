// JavaScript Document
$(document).ready(function(){
 	$("#new_pwd").focus();
	
	
	$("#btn_reset").click(function(){
		 var new_pwd=$.trim($("#new_pwd").val());
		 var confirm_pwd=$.trim($("#confirm").val());
	
		 $.ajax({type:'POST',url:'panel/control/reset_password.php',data:({new_pwd:new_pwd,confirm_pwd:confirm_pwd}),
		 	success:function(data){
				//alert(data);
				//split(data);
			 	if(data==0){
					alert("Fill-up All Input Fields");	
				}else if(data==2){
					alert("Minumum of 6 Characters is required");		
				}else if(data==3){
					alert("Password Mismatch");				 
				}else if(data==4){
					alert("Session Destroyed");
					location.href="home.php";	
				}else if(data==1){
					alert("Please Login Your Account.");
					location.href="home.php";	
				}
				$("#new_pwd").val('');					 
				$("#confirm").val('');
				$("#new_pwd").focus();					 
					
			}
		});
	});	
});