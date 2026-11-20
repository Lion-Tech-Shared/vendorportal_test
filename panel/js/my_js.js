// JavaScript Document     d 
$(function(){ 
	var current=$("#num_mon").val();
	notifications=0;
	//on load 
	
    my_index=0;
    flagRegularPullout=0;
    flagTrf=0;
    flagTotalPullout=0;
    firstFlag=0;
    cells=[];
    orderedCount=0;
    pickupCount=0;
    pickupCount2=0;
    trfCount=0;
    adjCount=0;
    regPulloutNoti=0; /* for PA notification. assigned if regular pullout status is approved only*/
    totPulloutNoti=0; /* for PA notification. assigned if total pullout status is approved only*/
    trfRcvNoti=0;     /*for Vendor notification. assigned if trf is received*/
    trfCoorNoti=0;    /*for trf*/
    trfCoorNoti2=0;   /*for pullout*/
    trfCoorNoti3=0;   /*for store delivery*/
    trfCoorNoti4=0;   /*for adjustment */
    //delApproval=0;
    isCoordinator=0;
    //var groupIdSplit=getGid();
  	//var splitData=groupIdSplit.split("-");
    //var groupId=splitData[0];
    //var cana=splitData[1];
    //var canr=splitData[2];
    //isCoordinator=splitData[3];
	 gGroupIdSplit =getGid();		 
	 splitData=gGroupIdSplit.split("-");
	 gId = splitData[0];
	 gCanApprove = splitData[1];
	 gCanReceive = splitData[2];
	 gIsCoor = splitData[3];
	 gCanVwOrdrNoti = splitData[4];
	 gCanVwRcvNoti = splitData[5];
	 gIsPa = splitData[6];
    //alert(isCoordinator);
    isCoordinator = gIsCoor;
	$.ajax({type:'get',url:'effect_date.php',async:false,
		success:function(data){
			//alert(data);
			load_mon_val(data);
			current=data;
		}
	});

	 var name='trigger';
	 var results=new RegExp('[\?&]' + name +'=([^&#]*)').exec(window.location.href);	
	 //getUrl = 0;
	 if(results){
	 	 getUrl=results[1];
	 }	
	 //alert(getUrl);

	//	change
	$("#num_mon").change(function(){
		//alert($("#num_mon").val());
	 	//alert(current);
		
		if($("#num_mon").val()!=""){
			
			current=$("#num_mon").val();
			$.ajax({type:'GET',url:'updateUserEffect.php',data:({months:current}),
				success:function(){
					
				}
			});
		}else{
			$("#logo").html('');
			$("#num_mon").val(current);	
		}
		$("#logo").html('<i class="fa fa-check fa-fw"></i>');
		setTimeout(function(){
			$("#logo").html('');
		},3000);
		//$("#logo").html(''); 
		
		//$("#logo").show("slow"); 
	});	

	$("#save_pwd").click(function(){
		var cpass=$.trim($("#cpass").val());	
		var npass=$.trim($("#npass").val());
		var rnpass=$.trim($("#rnpass").val());
		var email=$.trim($("#email").val());
		 
		if(cpass != '' && npass != '' && rnpass !='' && email != ''){
			//$("html").mask("");		
			$("#loadingGif").show();
			$("#save_pwd").submit();
				
		}
	});
	
	$("#btn_save").click(function(){
		$("#hide_me").hide();
		if($.trim($("#npass").val()) != '')
			//$("html").mask("");
		$("#loadingGif").show();
	});

	
	$("#a-deactivate-user").click(function(){
		 //$(".deactivate-user").modal("show");	
		lookupDataTable("#dataTables-lookups")
	});
	//keypress
	$(".add-user .store").on("keydown",function(e){		
		if(e.which==9||e.which==13||e.which==8||e.which>=48&&e.which<=57 ||e.which>=96&&e.which<=105){
				 
		}	 
		else{				  			 			  
			  e.preventDefault();	
		}	
	});	

	$("#add_user").click(function(){
		 $(".add-user").modal("show");		
		 setTimeout( 
		 	function(){
				$(".add-user .userid").focus();	 
		 },500);
	});		
	$(".add-user #btn_add_user").click(function(){
		var userid=$.trim($(".add-user .userid").val());	
		var fn=$.trim($(".add-user .fn").val());	
		var department=$.trim($(".add-user .department").val());	
		var store=$.trim($(".add-user .store").val());	
		var msg='';
		if( userid=='' || fn=='' || department=='' || store=='' ){
				msg='Please Fill-up All Fields';
				$(".add-user .userid").focus();	 
				alert(msg);
		}else{
			var bol=confirm("Submit?");
			if(bol){
				 $.ajax({type:'POST',url:'control/add_user.php',data:({userid:userid,fn:fn,dep:department,store:store}),
				 	success:function(data){
						
						switch(data){
							case 'exist':
								alert('UserId exist');
								$(".add-user .userid").val('');	
								$(".add-user .fn").val('');	
								$(".add-user .department").val('');		
								$(".add-user .userid").focus();		
								break;									
							case 'failed':
								alert('Failed Inserting Record');							
								 $(".add-user").modal("hide");	
								break;								
							case 'success':
								alert('Record Added. Activate the UserId.');							 							 	
							    $(".add-user").modal("hide");	
								break;
							case 'notstore':
								alert('Store Not Found!');							 							 	
							    $(".add-user .store").focus();		
								break;	
						}
						
					}
				 });
			}
		}
		
	});
	
	$("#delete_user").click(function(){
		 $(".delete-user").modal("show");		
		 setTimeout( 
		 	function(){
				$(".delete-user .userid").focus();	 
		 },500);
	});
	$(".delete-user #btn_delete_user").click(function(){
		 
		var userid=$.trim($(".delete-user .userid").val());		
		 
		if(userid==''){
			
		}else{
			$.ajax({type:'POST',url:'control/delete_user.php',data:({userid:userid}),
				success:function(data){
					switch(data){
						case 'exist':
							 var confirms=confirm("Are you sure you want to Totally Remove this User Account?");
							 if(confirms){
							 	$.ajax({type:'POST',url:'control/confirm_delete.php',data:({gate_pass:"confirm",userid:userid}),
									success:function(data){
										switch(data){
											case 'failed1':
												alert("Failed Delete from user account");
											break;
											case 'failed2':
												alert("Failed Delete from vendor info");
											break;
											case 'failed3':
												alert("Failed Delete from employee master");
											break;
											case 'success':
												$(".delete-user").modal("hide");
												alert("UserId Remove");
											break;
										}
									}
								});
							 }							 
						break;
							
						case 'absent':							
							$(".delete-user .userid").val('');
							$(".delete-user .userid").focus();
							alert("Absent");
						break;
						
					}
				}
			});
		}
	});
	
	$(".deactivate-user #dataTables-lookups tr td input:checkbox").click(function(){
		//alert("nox");	
	});
	$("#dataTables-lookups tbody").on("click","tr ",function(e){		
		//alert($(this).parent('tr').children('td').eq(1).val());
		
		var tbl=$("#dataTables-lookups").DataTable();
	    var i=tbl.row(this).index();
		var rows=$("#dataTables-lookups").dataTable().fnGetNodes(); 
		//$(rows[i]).find("td:eq(0)").text();
		var userid=$(rows[i]).find("td:eq(0)").text();
		var status=$(rows[i]).find("td:eq(1)").text();
		if($(rows[i]).find("td:eq(2) input").prop("checked")){
			$.ajax({url:'control/enable_disable.php',type:'POST',data:({pass:"gate_pass",userid:userid,status:1,flag:1}),
				success:function(data){
					if(data==1){
						$(rows[i]).find("td:eq(1)").text('Enabled');
						$(rows[i]).find("td:eq(1)").css({'color':'BLUE'});
					}
				}
			});
		}else{
			$.ajax({url:'control/enable_disable.php',type:'POST',data:({pass:"gate_pass",userid:userid,status:0,flag:1}),
				success:function(data){
					if(data==1){
						$(rows[i]).find("td:eq(1)").text('Disabled');
						$(rows[i]).find("td:eq(1)").css({'color':'RED'});
					}
				}
			});	
		}			
		
	});
	$("#dataTables-lookups tbody").delegate("td input:checkbox","click",function(){
		//alert($(this).val());
	});
	$("#dis_check_all").click(function(){
		var rows=$("#dataTables-lookups").dataTable().fnGetNodes(); 
		var bool=$(this).prop('checked');
		var status='';
		var color='';
		if(bool)
			bool=1;
		else
			bool=0;
				 
		$.ajax({url:'control/enable_disable.php',type:'POST',data:({pass:"gate_pass",flag:2,bool:bool}),
			success:function(data){
				 
				if(data==1){
					if(bool){
						status='Enabled';
						color='BLUE';
					}
					else{
						status='Disabled';	
						color='RED';
					}
					for(var i=0;i<rows.length;i++){
						$(rows[i]).find('td:eq(1)').text(status);
						$(rows[i]).find("td:eq(1)").css({'color':''+color+''});
						$(rows[i]).find("td:eq(2) input").prop('checked',bool);
					}
				}
			}
		});
	});
	//change report-------------------------------------------------------------------------------
	$("#a_rpt").click(function(){
		var splt;
		 
		 //$(".change_rpt_mdl .notice").focus();
		 $.ajax({
		 		type:'GET',
		 		url:'control/cm.php',
		 		data:({flag:"1"}),
		 		dataType:'text',
		 		success:function(out){
		 			//setTimeout( 
		 				//alert(out);
		 				splt=out.split("--");
		 			//function(){
				//$(".change_rpt_mdl .userid").focus();	 		
			 			//alert(splt[0]);
			 			$(".change_rpt_mdl .notice").val(splt[0]);
			 			$(".change_rpt_mdl .for_counter").val(splt[1]);
						$(".change_rpt_mdl .not_for_counter").val(splt[2]);						
						//},5000);
		 				$(".change_rpt_mdl").modal('show');
		 		}
		 });
	});
	$(".change_rpt_mdl #btn_submit").click(function(){
		var notice=$(".change_rpt_mdl .notice").val();
		var for_counter=$(".change_rpt_mdl .for_counter").val();
		var not_for_counter=$(".change_rpt_mdl .not_for_counter").val();

		$.ajax({
			type:'GET',
			url:'control/cm.php',
			data:{notice:notice,forcounter:for_counter,notFor:not_for_counter},
			success:function(out){
				if(out=='')
					$(".change_rpt_mdl").modal("hide");			
			}
		});

	});

	//------------------------load request---------------------------------------------
	clck=0;
	$("#requestAdj").click(function(){	

	});
	/*remove adjustemnt functionlity----**/
	//_clickReq();
	_clickId();
	//_loadMsg();
	//_hideBadge();
	/**removed***************************/
	//_notify();
	//_headToDelivery();
	//_getNotification();
	//getNoti();
	$("#check_all_adj").click(function(e){
		var bool=$(this).prop("checked");
		if(bool){
			var cnfrm=confirm("This will check all the items here, Continue?");
			 
			if(cnfrm){
				_toggleCheck(bool);				 
			}else{
				$(this).prop("checked",false);
			}
		}
		else{
			_toggleCheck(bool);
		}
		//alert("asdf");
	});
	//submit check item for adjustment
	$("#submit_adj").click(function(){
		 _submitAdj();
	});
	_uncheckSelectAll();

   //reset user
   $("#a_resetUser").click(function(){
   		$(".mdl-reset #pSmall").html('&nbsp;');   
   		setTimeout(function(){
   			$(".mdl-reset #userid").focus();   			
   		},500);
   		
   		$(".mdl-reset #userid").val('');
   		$(".mdl-reset").modal("show");
   });
   //reset user
   $("#a_privileges").click(function(){
   		$(".mdl-reset #pSmall").html('&nbsp;');   
   		setTimeout(function(){
   			$(".mdl-reset #userid").focus();   			
   		},500);
   		
   		//$(".mdl-reset #userid").val('');
   		$(".mdl-privilege").modal("show");
   });
   $(".mdl-reset #btn_submit").click(function(){
   		var userid=$(".mdl-reset #userid").val();
   		if(userid!=""){
   			var cnfrm=confirm("Are you sure?");
   			if(cnfrm){
   				$.ajax({
	   				type:'POST',
	   				url:'control/reset_userid.php',
	   				data:{userid:userid,securityFlag:1,flagProcess:'submit'},
	   				success:function(out){
	   					if(out==1){
	   						alert("USERID RESET");
	   						$(".mdl-reset").modal("hide");
	   					}else if(out==0){   											
	   						alert("USERID NOT FOUND!");
	   						$(".mdl-reset #userid").val('');   
	   						$(".mdl-reset #userid").focus();
	   					}
	   				}
   				});
   			}
   			
   		}
   		$(".mdl-reset #userid").focus();
   });
  $(".add-user .store").on("keydown",function(e){

  });		
  $(".mdl-reset #userid").keydown(function(e){
  	 if(e.which==13){
  	 	 var userid=$(".mdl-reset #userid").val();
  	 	 $.ajax({
   				type:'POST',
   				url:'control/reset_userid.php',
   				data:{userid:userid,securityFlag:1,flagProcess:'inquiry'},
   				success:function(out){
   					if(out==0){
   						//$(".mdl-reset #pSmall").text('');  
   						$(".mdl-reset #userid").val('');  
   						$(".mdl-reset #pSmall").text('NOT FOUND!');   
   					}else{
   						$(".mdl-reset #pSmall").text(out);  
   					}
   					 
   				}
   		});
  	 }
  });
  //view download logs------
  $("#a_viewDwnloadLogs").click(function(){
  		_loadLogs();  		
  });
  _privilige();
  _clickTblPriv();  

  /*-------notification dropdown-------------*/
  $("#myNoti li:eq(0)").click(function(){
  		//alert($(this).attr("id"));
  });

  if(getUrl){
	 	$("#btn_find_adj").trigger("click");
  }
 
});//END---------------END-----------END-----------------END-----------------END------

function updCoorNoti(flag){
	//var rtn=0;
	//flag=2 trf; flag=3 pullout
	$.ajax({
		type:'GET',
		dataType:'text',
		async:false,
		url:'control/updCoorNoti.php', 
		data:{flag:2,txn:flag},
		success:function(out){
			//alert(out);

		}
	});
	//trfCount=rtn;
	//trfCoorNoti=rtn;
	//return rtn;
}
function updVendorNoti(){
	//var rtn=0;
	//flag=2 trf; flag=3 pullout
	$.ajax({
		type:'GET',
		dataType:'text',
		async:false,
		url:'control/updVendorNoti.php', 
		data:{flag:2},
		success:function(out){
			//alert(out);

		}
	});
	//trfCount=rtn;
	//trfCoorNoti=rtn;
	//return rtn;
}
function _getTrfNot(){
	var rtn=0;
	$.ajax({
		type:'GET',
		async:false,
		url:'control/getTrfNotif.php',
		dataType:'json',
		success:function(out){
			if(out.length>0){
				rtn=1;
				
			}
			trfRcvNoti=out.length;
			//rtn=out.length;
			//trfRcvNoti=rtn;
		}
	});
	//trfRcvNoti=rtn;
	return rtn;
	//return 1;
}

function _loadLogs(){
	var dtid="#tbl-dwnloadLogs";
		//$("html").mask();	
		$("#loadingGif").show();
		$.ajax({
   				type:'POST',
   				url:'control/dwnloadLogs.php',   
   				dataType:'json',				
   				success:function(out){
   					$(dtid).dataTable().fnDestroy();	
					$(dtid +" tbody").empty();  	
					if (!$.fn.DataTable.isDataTable(dtid)) {				 
						var dtdata = '';
						var checked='';
						var color='';
						for(var i = 0; i < out.length; i++ ){
							//var strId = "'"+out[i]['user_id']+" : "+out[i]["status"]+"'";							
							dtdata += '<tr>'
									+	'<td width="10" align="right">'+out[i][1]+'</td>'
									+	'<td width="120" align="right">'+out[i][0]+'</td>'		
									+	'<td width="120" align="right">'+(parseInt(out[i][0])-parseInt(out[i][1]))+'</td>'		
									+   '<td width="120" align="center">'+ out[i][2] +' </td>'					
									+   '<td width="120" align="center">'+ out[i][3] +' </td>'	
									+   '<td width="100" align="center">'+ out[i][5] +' </td>'	
									+   '<td width="100" align="center">'+ out[i][4] +' </td>'	
									+'</tr>';
						}				
						$(dtid+" tbody").html(dtdata);
						
						$(dtid).dataTable({
							"columnDefs": [{ "width": "150px", "targets": 0 }],
							"bLengthChange": false,
							"iDisplayLength": 10,
							"sPaginationType": "bs_two_button",
							"destroy": true,
							"oLanguage":{
								"sSearch":"Search"
							}		
						});
						$(dtid).dataTable().fnFilter("");
					}												
					$(dtid).dataTable().fnFilter("");
					//$("html").unmask();										
					$("#loadingGif").hide();
   					$(".mdl-dwnloadLogs").modal("show");	 
   				}
   		});
}
function _submitAdj(){

	if(cells.length > 0){
		var cnfrm=confirm("Are you sure?");
		if(cnfrm){
			//alert(cells.length);
			$.ajax({
				type:'POST', 
				url:'control/batchAdj.php',
				data:{arr:cells},
				success:function(data){
					//alert(data);
					var rows=$("#tbl-view-request").dataTable().$('tr',{'filter':'applied'});
					for(var i=0;i<rows.length;i++){					 					
						$(rows[i]).find("td:eq(10)").text('Approved');		
						$(rows[i]).find("td:eq(10) input").remove();	
					}	
					$("#check_all_adj").prop("checked",false);		
					cells=[];
				}
			});
		}else{
			//alert(cells.length);
		}
		
	}else{
		$("#check_all_adj").prop("checked",false);	
		alert("You have to Select All");
	}
}
function _uncheckSelectAll(){
	$(".dialog-lookup-viewReq").on("shown.bs.modal", function () {
		$("#check_all_adj").prop("checked",false);	
		cells=[];
	});
}
function _toggleCheck(bool){
	var rows=$("#tbl-view-request").dataTable().$('tr',{'filter':'applied'});//fnGetNodes(); 
	
	var status='';
	var color='';			 
	 
	if(bool)
			bool=1;
		else
			bool=0;
	cells=[];	
	for(var i=0;i<rows.length;i++){					 
		if(bool){
			if($(rows[i]).find("td:eq(11) input").attr("id"))
				cells.push($(rows[i]).find("td:eq(11) input").attr("id"));
		}else{
			cells.pop();
		}
		$(rows[i]).find("td:eq(11) input").prop('checked',bool);		
		//$("input", $("#tbl-view-request").rows({ search: 'applied' }).nodes()).attr('checked', checked);
	}		
}
function lookupDataTable(dtid) {
		 
	url = 'control/select_accounts.php';
	//alert("me lookup");
	//$("html").mask("Loading...");
	$("#loadingGif").show();
	//$(dtid).dataTable().fnFilter("");
	$.ajax ({
		url: url,
	 	type:'POST',
		dataType: 'json',
		//async: false,
		data:({pass:'gate_pass'}),
		success: function(out) {
			if (!$.fn.DataTable.isDataTable(dtid)) {
				//alert(out[0][1]);
				var dtdata = '';
				var checked='';
				var color='';
				for(var i = 0; i < out.length; i++ ){
					var strId = "'"+out[i]['user_id']+" : "+out[i]["status"]+"'";
					//alert(strId);
					if(out[i]["status"]=='Enabled'){
						checked="Checked='Checked'"	;
						color='BLUE';
					}else{
						checked="";
						color='RED';
					}
					dtdata += '<tr>'
							+	'<td><center>'+out[i]["user_id"]+'</center></td>'
							+	'<td ><font color="'+color+'">'+out[i]["status"]+'</font></td>'		
							+   '<td><input id="checkbox" class="'+ out[i]["status"] +'" type="CheckBox" '+ checked +' />';					
							+'</tr>';
				}				
				$(dtid+" tbody").html(dtdata);
				
				$(dtid).dataTable({
					"columnDefs": [{ "width": "150px", "targets": 0 }],
					"bLengthChange": false,
					"iDisplayLength": 10,
					"sPaginationType": "bs_two_button",
					"destroy": true,
					"oLanguage":{
						"sSearch":"Search"
					}		
				});
				$(dtid).dataTable().fnFilter("");
			}
			else {
				$(dtid).dataTable().fnClearTable();
				
				for(var i = 0; i < out.length; i++ ){
					var strId = "'"+out[i][0]+" : "+out[i][1]+"'";		
					
					if(out[i]["status"]=='Enabled'){
						checked="Checked='Checked'"	;
						color='BLUE';
					}else{
						checked="";
						color='RED';
					}			
					$(dtid).dataTable().fnAddData([
						'<center>'+out[i]["user_id"]+'</center>',
						"<font color="+color+">"+out[i]["status"]+"</font>",'<input id="checkbox" class="'+ out[i]["user_id"] +'" type="CheckBox" '+checked+' />'
					])
				}
			}
			$(dtid).dataTable().fnFilter("");
			//$("html").unmask();
			$("#loadingGif").hide();
			
			$(".deactivate-user").modal("show");
		},
		error: function(out) {
			console.log(out);
		}
	});	
};

//load current effectivity date
function load_mon_val(data){

	$("#num_mon").val(data);	

}
// load every 5 seconds to show if there is/are new messages
function _loadMsg(){
	$("#me_badge").hide();
	$("#reqViewer").children().hide();
	setInterval(function(){
		//$("#me_badge").text(Math.round(Math.random() * 10));//alert("Asdf");
		_loadBadge();
	},5000);

}
function _notify(){
	//$("#me_badge").hide();
	//$("#reqViewer").children().hide();
	setTimeout(function(){
		//$("#me_badge").text(Math.round(Math.random() * 10));//alert("Asdf");
		//_getNotification();
		getNoti();
		_notify();
	},20000);

}
/*  */
function _getNotification(){
	//$("#reqViewer").show();
	//var gGroupIdSplit=getGid();

	//var splitData=groupIdSplit.split("-");
	/*var groupId=splitData[0];
	var canApprove=splitData[1];
	var canReceive=splitData[2];
	var isCoordinator=splitData[3];
	*/
	/*var gGroupIdSplit =getGid();		 
	var splitData=gGroupIdSplit.split("-");
    var gId = splitData[0];
  	var gCanApprove = splitData[1];
  	var gCanReceive = splitData[2];
  	var gIsCoor = splitData[3];
  	var gCanVwOrdrNoti = splitData[4];
    var gCanVwRcvNoti = splitData[5];
    var gIsPa = splitData[6];*/

    //alert(gId);
    var groupId=gId;
	var canApprove=gCanApprove;
	var canReceive=gCanReceive;
	var isCoordinator=gIsCoor;


	var url='';
	var notify_count=0;
 	//alert(isCoordinator);
	url='control/getNotification.php'; 
	$.ajax({
		type:'GET',
		url:url,
		async:false,
		dataType:'text',
		data:{canApprove:canApprove},
		success:function(data){
			//if(!$("#requestAdj").parent("li").hasClass("open")){  
				//alert("sdf");				 
				orderedCount=data;
				//alert(data);
				if(data >0){
					//alert(data);
					//$("#me_badge").html("<span class='fa fa-bell'></span>");	
					//$("#me_badge").html("1");	
					//$("#me_badge").show();
					//$("#reqViewer").children().show();
					//$("#me_badge").text(data);
					notify_count+=1;				
					firstFlag=1;			
				}else{
					//$("#me_badge").hide();   
					//$("#reqViewer").hide()   ;
				}			
		}
	});
	//alert(orderedCount);
	//if(groupId==2 || canReceive==1){
		notify_count=getPullout(0,notify_count); /**regular**/
		notify_count=getPullout(1,notify_count); /**total***/
		notify_count+=getTrfCount();				     /***Trf***/	
		notifications=notify_count;
		notifications+=_loadBadge();

		if(canReceive==1){
			notifications+=getRegPullPANoti();
			notifications+=getTotPullPANoti();
		}
		else if(isCoordinator==1){
			notifications+=getCoorNoti();
			notifications+=getCoorNoti2();
			notifications+=getCoorNoti3();
			notifications+=getCoorNoti4();
		}	
	//}else if(canApprove==1){
		//notifications=notify_count;
	//}	
	/*if(groupId==2 || canReceive==1)
		notifications+=_loadBadge();*/
		//alert(notifications);
		else if(groupId==2){
			notifications+=_getTrfNot();
		}

	if(notifications > 0){
		$("#me_badge").html(notifications);	
		$("#me_badge").show();
	}
	else{
		$("#me_badge").hide();
		//$("#reqViewer").hide();
	}
} 
function getTrfCount(){
	var rtn=0;
	$.ajax({
		type:'GET',
		dataType:'json',
		async:false,
		url:'control/loadTrfNumbers.php', 
		data:{flag:2}, 
		success:function(out){	
			rtn=out.length;
		}

	});
	trfCount=rtn;
	return rtn;
}
/*get Transfer notification*/
function getCoorNoti(){
	var rtn=0;
	trfCoorNoti=0;
	$.ajax({
		type:'GET',
		dataType:'json',
		async:false,
		url:'control/getCoorNoti.php', 
		data:{flag:2}, 
		success:function(out){	

			if(out.length>0){
				rtn=1;
				
			}
			trfCoorNoti=out.length;
		}

	});
	//trfCount=rtn;
	//trfCoorNoti=rtn;
	return rtn;
}
/*Get PUllout notification*/
function getCoorNoti2(){
	var rtn=0;
	trfCoorNoti2=0;
	$.ajax({
		type:'GET',
		dataType:'json',
		async:false,
		url:'control/getCoorNoti2.php', 
		data:{flag:2}, 
		success:function(out){	
			//alert(out);
			if(out.length>0){
				rtn=1;
				
			}
			trfCoorNoti2=out.length;
		}

	});
	//trfCount=rtn;
	//trfCoorNoti=rtn;
	//alert(rtn);
	return rtn;
}
/*Get Store Delivery notification*/
function getCoorNoti3(){
	var rtn=0;
	trfCoorNoti3=0;
	$.ajax({
		type:'GET',
		dataType:'json',
		async:false,
		url:'control/getCoorNoti3.php', 
		data:{flag:2}, 
		success:function(out){	
			//alert(out);
			if(out.length>0){
				rtn=1;
				
			}
			trfCoorNoti3=out.length;
		}

	});
	//trfCount=rtn;
	//trfCoorNoti=rtn;
	//alert(rtn);
	return rtn;
}
/*Get adjustment notification*/
function getCoorNoti4(){
	var rtn=0;
	trfCoorNoti4=0;
	$.ajax({
		type:'GET',
		dataType:'json',
		async:false,
		url:'control/getCoorNoti4.php', 
		data:{flag:2}, 
		success:function(out){	
			//alert(out);
			if(out.length>0){
				rtn=1;
				
			}
			trfCoorNoti4=out.length;
		}

	});
	//trfCount=rtn;
	//trfCoorNoti=rtn;
	//alert(rtn);
	return rtn;
}
function getPullout(isTotalPullout,notify_count){

	var url=''; 
	url='control/getPullout.php';
	$.ajax({
		type:'GET',
		url:url,
		async:false,
		dataType:'text',
		data:{flag:1,isTotalPullout:isTotalPullout},
		success:function(data){			 
			//alert(data);

			if(isTotalPullout==0)
				pickupCount=parseInt(data);
			else
				pickupCount2=parseInt(data);

			if(parseInt(data) > 0 ){
				notify_count+=1;
				if(isTotalPullout==0)	{
					flagRegularPullout=1;
				}
				else if(isTotalPullout==1) {
					flagTotalPullout=1; 
				}
			}		
		}
	});
	return notify_count;
}
function _loadBadge(){
	var rtn=0;
	$.ajax({
		type:'GET',
		async:false,
		url:'control/msgalert.php', 
		dataType:'json',
		success:function(data){
			//if(!$("#requestAdj").parent("li").hasClass("open")){
				//alert("sdf");
				
				if(data.length>0){
					rtn=1;
					//alert("sdf");
					adjCount=data.length;
					//$("#me_badge").show();
					//$("#reqViewer").children().show();
					//$("#me_badge").text(data.length);						
				}else{
					////$("#me_badge").hide();					
				}

		}
	});
	return rtn;
}

function _loadReq(){
	var gid=0;
	$.ajax({
		type:'GET',
		url:'control/loadRequest.php',
		dataType:'json',
		data:({id:'read-all'}),
		success:function(data){
			//if(!$("#requestAdj").parent("li").hasClass("open")){
 				//alert(data.length);
				for(var i=0; i < data.length;i++){					 
					 if(i<3){ 
						 var msg='  <li class="divider"></li>';
			                 msg+='      <li id="'+data[i]['id']+'" class="message-preview">';
			                 msg+='           <a href="#">';
			                 msg+='             <div class="media">';
			                 msg+='             <span class="pull-left">';	                                       
			                 msg+='                   </span>';
			                 msg+='                   <div class="media-body">';
			                 msg+='                       <h5 class="media-heading"><strong>'+data[i]['ee_name']+'  Store:'+data[i]['store']+'</strong>';
			                 msg+='                       </h5>';
			                 msg+='                       <p class="small text-muted"><i class="fa fa-clock-o"></i>'+data[i]['requestDate']+'</p>';
			                 msg+='                       <p>'+data[i]['reason'].substr(0,30)+'....</p>';
			                 msg+='                   </div>';
			                 msg+='               </div>';
			                 msg+='           </a>';
			                 msg+='       </li>';
			                $("#reqViewer").append(msg);
		             }
		             else{		     
		             	$("#me_badge").hide();        	
		             	break;
		             }
		             gid=data[i]['group_id'];   
		              
				} 
				if(data.length>0){
 					//$("#reqViewer").children("li").remove(); 				
					$("#reqViewer").append('<li id="msg_last" class="divider"></li><li id="read-all" class="message-footer"> <a href="#">Read All Request for adjustment</a> </li>');
				}else{
				   if(gid==3){	
					 var msg='  <li class="divider"></li>';
		                 msg+='      <li class="message-previews2">';
		                 msg+='           <a href="#">';
		                 msg+='             <div class="media">';
		                 msg+='             <span class="pull-left">';	                                       
		                 msg+='                   </span>';
		                 msg+='                   <div class="media-body">';
		                 msg+='                       <h5 class="media-heading"><strong></strong>';
		                 msg+='                       </h5>';
		                 msg+='                       <p><i class="fa fa-ban fa-fw"></i> No New Request For adjustment</p>';
		                 msg+='                      ';
		                 msg+='                   </div>';
		                 msg+='               </div>';
		                 msg+='           </a>';
		                 msg+='       </li>';
		                $("#reqViewer").append(msg);
				   }else{
				   	  var msg='  <li class="divider"></li>';
		                 msg+='      <li class="message-previews2">';
		                 msg+='           <a href="#">';
		                 msg+='             <div class="media">';
		                 msg+='             <span class="pull-left">';	                                       
		                 msg+='                   </span>';
		                 msg+='                   <div class="media-body">';
		                 msg+='                       <h5 class="media-heading"><strong></strong>';
		                 msg+='                       </h5>';
		                 msg+='                       <p>&nbsp;&nbsp;&nbsp;<i class="fa fa-ban fa-fw"></i> No Request For adjustment &nbsp;&nbsp;&nbsp;</p>';
		                 msg+='                      ';
		                 msg+='                   </div>';
		                 msg+='               </div>';
		                 msg+='           </a>';
		                 msg+='       </li>';
		                $("#reqViewer").append(msg);
				   }		
	                				  
				}
		}
	});
} 
function _loadNotification(str,flag,count){
	 var msg='   ';
	 
	 if(count>0 && str){
	 		
	 	 //var msg=' <li class="dropdown-header"><span class="fa fa-bell-o"></span> Notification</li> ';
	  		 msg+='<li class="divider"></li>';	

	         msg+='      <li class="message-preview" id="'+flag+'">';
	         msg+='           <a href="#">';
	         msg+='             <div class="media">';
	         msg+='             <span class="pull-left">';	                                       
	         msg+='                   </span>';
	         msg+='                   <div class="media-body">';
	         msg+='                       <h5 class="media-heading"><strong> </strong>';
	         msg+='                       </h5>';
	         msg+='                       <p class="small text-muted"><span class="glyphicon glyphicon-envelope"></span>';
	         msg+='                       '+str+' </p>';
	         msg+='                   </div>';
	         msg+='               </div>';
	         msg+='           </a>';
	         msg+='       </li>';    

	       
     }else if(notifications < 1){ 	
     		 msg+='<li class="divider"></li>';	
	         msg+='      <li class="message-preview">';
	         msg+='           <a href="#">';	      
	         msg+='<p class="fa fa-ban fa-fw" > No notification </p>';	       
	         msg+='           </a>';
	         msg+='       </li>';     
     		
     }
     $("#reqViewer").append(msg);
}
function getGid(){
	var gid;
	 $.ajax({
    	type:'GET', 
    	async:false,      	 
    	url:'control/getGid.php',    	
    	dataType:'text', 
    	success:function(out){
    		gid=out; 
    		//alert(out[0]['gid']);
    	}
    });
	return gid;
}
function _clearMsg(){

	$.ajax({
		type:'GET',
		url:'control/loadRequest.php',
		dataType:'json',
		success:function(data){
			//if(!$("#requestAdj").parent("li").hasClass("open")){
 				if(data.length>0){
 					$("#reqViewer").children("li").remove();
 				}				 			
		}
	});
}

function _hideBadge(){
	$("#requestAdj").click(function(){
		//$("#me_badge").hide();
	});
}
function _clickReq(){
	$("#reqViewer").delegate("li","click",function(e){
		//alert($(this).attr("id"));
		var my_id=$(this).attr("id");
		var my_clas=$(this).attr("class");
		//$("#tbl-view-request").dataTable().fnclearTable();
		if(my_id=='read-all'){
			//$(".dialog-lookup-viewReq").modal("show"); tbl-view-request
			_updateViewed("1",my_id);
			_upViewed(my_id);
			_loadReqToTbl(my_id);
			
		}else if(my_clas=='divider' || my_clas=='message-previews2'){
			//alert('divider');
		}else{
			_upViewed(my_id);
			_loadReqToTbl(my_id);
			_updateViewed("2",my_id);
		}
	});
}
function _headToDelivery(){
	$("#reqViewer").delegate("li","click",function(e){
		//var grpId=getGid();
		/*var gGroupIdSplit =getGid();		 
  		var splitData=gGroupIdSplit.split("-");
	    var gId = splitData[0];
	  	var gCanApprove = splitData[1];
	  	var gCanReceive = splitData[2];
	  	var gIsCoor = splitData[3];
	  	var gCanVwOrdrNoti = splitData[4];
	    var gCanVwRcvNoti = splitData[5];
	    var gIsPa = splitData[6];*/

		
		 var groupId=gId;
		 var canApprove=gCanApprove;
		 var canReceive=gCanReceive;
		 var isCoor=gIsCoor;
		 var myFlag=$(this).attr("id");
		 if(canReceive==1){		 
		 	if(_isForRecLoaded()!=1){
		 		if(notifications>0)
		 			location.href="../panel/store_delivery.php?trigger=1";	
		 		//$("#btn_find_dr").trigger("click");
		 	}else{
		 		//location.href="";
		 		//clickLinkNotification();
		 	}
		 	if(myFlag==4){
		 			location.href="../panel/trf_receiving.php?trigger=1";	
		 	}else if(myFlag==5){
		 			location.href="../panel/regular_pullout.php?trigger=1";	
		 	}	
		 	else  if(myFlag==6){
		 			location.href="../panel/total_pullout.php?trigger=1";	
		 	}	
		 	else  if(myFlag==8){
		 			location.href="../panel/trf_receiving.php?trigger=8";	
		 	} 
		 	else  if(myFlag==13){
		 			location.href="../panel/regular_pullout.php?trigger=13";	
		 	}
		 	else  if(myFlag == 18){
		 			location.href="../panel/total_pullout.php?trigger=18";
		 	}
		 }
		 else if(canApprove==1){
		 	 if(notifications>0){
		 	 	if(myFlag == 2){
		 	 		location.href="../panel/store_delivery.php?trigger=2";	/*trigger =2 merch view*/
		 	 	}else if(myFlag == 3){
		 			location.href="../panel/store_delivery.php?trigger=3";	
		 	 	}else if(myFlag == 6){
		 			location.href="../panel/adjustment.php?trigger=6";	
		 	 	}
		 	 	else  if(myFlag==10){
		 			location.href="../panel/transfer.php?trigger=10";	
		 		}	
		 		else  if(myFlag==15){
		 			location.href="../panel/regular_pullout.php?trigger=15";	
		 		}
		 		else  if(myFlag==19){
		 			location.href="../panel/total_pullout.php?trigger=19";	
		 		}else if(myFlag == 22){
		 	 		location.href="../panel/store_delivery.php?trigger=22";	/*trigger =2 merch view*/
		 	 	}	 		
		 	 }
		 }	
		 else if(groupId==2){
		 	 if(notifications>0){
		 	 	if(myFlag==2){
		 	 		location.href="../panel/regular_pullout.php?trigger=1";
		 	 	}else if(myFlag==3){
		 	 		location.href="../panel/total_pullout.php?trigger=1";
		 	 	}else if(myFlag==4){ /*adjustment*/
		 	 		//_loadReqToTbl('read-all');
		 	 		location.href="../panel/adjustment.php?trigger=5";
		 	 	}else if(myFlag == 5){ /*adjustment*/
		 	 		//_loadReqToTbl('read-all');
		 	 		location.href="../panel/adjustment.php?trigger=5";
		 	 	}else if(myFlag==7){	
		 	 		updVendorNoti();
		 	 		location.href="../panel/transfer.php?trigger=1&vView=1&vl="+(Math.random() * 1000);	
		 	 	}else if(myFlag ==8 ){
		 	 		/*trigger == 3 receive for vendor notification*/
		 	 		location.href="../panel/store_delivery.php?trigger=3&vView=1&vl="+(Math.random() * 1000)+"&rcv=1";	 
		 	 	}else if(myFlag == 1){
		 	 		location.href="../panel/store_delivery.php?trigger=4&vView=1&vl="+(Math.random() * 1000)+"&rcv=1";	 
		 	 	}
		 	 	else  if(myFlag==9){
		 			location.href="../panel/transfer.php?trigger=9";	
		 		}
		 		else  if(myFlag==12){
		 			location.href="../panel/regular_pullout.php?trigger=12";	
		 		}
		 		else  if(myFlag==14){
		 			location.href="../panel/regular_pullout.php?trigger=14";	
		 		}
		 		else  if(myFlag == 17){
		 			location.href="../panel/total_pullout.php?trigger=17";
		 		}
		 		else  if(myFlag == 21){
		 			location.href="../panel/total_pullout.php?trigger=21";
		 		}
		 	 }
		 		
		 }else if(isCoor==1){
		 		if(myFlag==8){
		 			updCoorNoti(2);
		 	 		location.href="../panel/transfer.php?trigger=1&vView=1&vl="+(Math.random() * 1000);	
		 	 	}
		 	 	else if(myFlag==9){
		 			updCoorNoti(3);
		 	 		location.href="../panel/regular_pullout.php?trigger=1&vView=1&vl="+(Math.random() * 1000);	
		 	 	}
		 	 	else if(myFlag==10){
		 			updCoorNoti(4);
		 	 		location.href="../panel/store_delivery.php?trigger=1&vView=1&vl="+(Math.random() * 1000);	
		 	 	}
		 	 	/*else if(myFlag==11){
		 			updCoorNoti(5);
		 	 		location.href="../panel/adjustment.php?trigger=1&vView=1&vl="+(Math.random() * 1000);	
		 	 	}*/else if(myFlag==0){
		 			//updCoorNoti(5);
		 	 		location.href="../panel/store_delivery.php?trigger=0&vView=1&vl="+(Math.random() * 1000);		
		 	 	}else if(myFlag == 7){ /*adjustment*/		 	 		 
		 	 		location.href="../panel/adjustment.php?trigger=7";
		 	 	}
		 	 	else  if(myFlag == 11){
		 			location.href="../panel/transfer.php?trigger=11";	
		 		}
		 		else  if(myFlag == 16){
		 			location.href="../panel/regular_pullout.php?trigger=16";
		 		}
		 		else  if(myFlag == 20){
		 			location.href="../panel/total_pullout.php?trigger=20";
		 		}
		 }	 
	});
		 
}
function _updateViewed(flag,idi){

	//alert(""+flag+"-"+id);
	$.ajax({
		type:'POST',
		url:'control/msgUpdate.php',
		dataType:'text',
		data:({idi:idi,flag:flag}),
		success:function(data){
			//alert(data);
		}
	});

} 

function _loadReqToTbl(pass_var){ 
	var dtid="#tbl-view-request";
	//$("html").mask();	 
	//alert(gGroupIdSplit);
	//alert(gCanApprove);
	$("#loadingGif").show();
	$.ajax({
		type:'GET', 
		url:'control/loadRequest.php',
		dataType:'json',
		data:({id:pass_var,isCoordinator:isCoordinator,privDtl:gGroupIdSplit}),  
		success:function(data){
			  	//var dtid='#tbl-view-request';		 
			  	//$(dtid).dataTable().fnFilter(""); 
			$(dtid).dataTable().fnDestroy();	
			$(dtid +" tbody").empty();  	
			if (!$.fn.DataTable.isDataTable(dtid)) { 	
				 var dtdata='';				 
				 var back = '';
				 //alert(gIsCoor);
				for(var i=0; i < data.length;i++){					 
					 back = '';
					//alert(data[i]['group_id']);
					if(gCanApprove == 1){
						if(getUrl == 6 && data[i]['mVwd'] == 0)							
							back='style="background-color:#c6dff8;"';	
					}
					else if(gIsCoor == 1){
						if(getUrl == 7 && data[i]['ctorVwd'] == 0)							
							back='style="background-color:#c6dff8;"';	
					}
					
					 dtdata += '<tr '+back+' id='+(data[i]["primId"]?data[i]["primId"]:data[i]["id"])+' style="font-family:sans-serif">'
					 		+	'<td >'+data[i]['ref_num']+'</td>'	
					 		+	'<td >'+data[i]['type']+'</td>'	
							+	'<td><center>'+data[i]['ee_name']+'</center></td>'
							+	'<td ><center>'+data[i]['sku']+'</center></td>'		
							+	'<td ><center>'+data[i]['descr']+'</center></td>'	
							+	'<td ><center>'+parseFloat(data[i]['cost']).toFixed(2)+'</center></td>'								
							+	'<td ><center>'+data[i]['qty']+'</center></td>'	
							+	'<td ><center>'+parseFloat(data[i]['cost']*data[i]['qty']).toFixed(2)+'</center></td>'							
							+	'<td >'+data[i]['reason']+'</td>'	
							+	'<td >'+data[i]['requestDate']+'</td>';	
							if(data[i]["is_approved"]==1){
					dtdata+=	'<td >Approved</td>'	
								+	'<td > </td>';
							}else{
					dtdata+=	'<td >For Approval</td>';
					        	if(data[i]['group_id']==2){
					dtdata+=   	'<td ><center><input id="'+data[i]["id"]+'" type="checkbox" class="'+i+'" /></center></td>'	;
					        	}else{
					dtdata+=    '<td ></td>';
					        	}	
								
							}
								
							+'</tr>';
				}				
				$(dtid+" tbody").html(dtdata);
				
				$(dtid).dataTable({
					"columnDefs": [{ "width": "150px", "targets": 0 }],
					"bLengthChange": false,
					"iDisplayLength": 10,
					"sPaginationType": "bs_two_button",
					"destroy": true,
					"aaSorting":[[9,"desc"]],    
					"oLanguage":{
						"sSearch":"Search"
					}		
				});		
							
			}
		    else {
				$(dtid).dataTable().fnClearTable();
				
				for(var i = 0; i < data.length; i++ ){
					//var strId = "'"+out[i][0]+" -- "+out[i][1]+"'";
					if(data[i]["is_approved"]==1){

						$(dtid).dataTable().fnAddData([							
								'<center>'+data[i]["ee_name"]+'</center>',data[i]["sku"],data[i]["qty"],
								data[i]["adjType"],data[i]["reason"],data[i]["requestDate"],'Approved','<center></center>'																										
						])
					}else{
						if(data[i]['group_id']==2){
							$(dtid).dataTable().fnAddData([														 							
									'<center>'+data[i]["ee_name"]+'</center>',data[i]["sku"],data[i]["qty"],
								data[i]["adjType"],data[i]["reason"],data[i]["requestDate"],'For Approval','<center><input id="'+data[i]["id"]+'" class="'+i+'"  type="checkbox" /></center>'																			
							])
						}else{
							$(dtid).dataTable().fnAddData([														 							
									'<center>'+data[i]["ee_name"]+'</center>',data[i]["sku"],data[i]["qty"],
								data[i]["adjType"],data[i]["reason"],data[i]["requestDate"],'For Approval',' '																			
							])
						}
					}
				}
				$(dtid).dataTable().fnFilter("");
		    }
		    //$("html").unmask();
		    $("#loadingGif").hide();
		    $(".dialog-lookup-viewReq").modal("show"); 
	   }
	});
}

function _clickId(){
	
	$("#tbl-view-request tbody").delegate("tr","click",function(event){
		var nodeNam=(event.target.nodeName).toLowerCase();		
		var tbl=$("#tbl-view-request").DataTable();
	    //var i=$(this).attr("class");
		var rows=$("#tbl-view-request").dataTable().fnGetNodes(); 
	 	//var rows=$("#tbl-view-request").dataTable().$('tr',{'filter':'applied'}); 
	 	//var i=tbl.row(this).index(); 
	 	var i=tbl.rows({order:'applied'}).nodes().indexOf(this);
	 	
	 	if(nodeNam=='input'){
  
			if($(rows[i]).find("td:eq(11) input").prop('checked')){
				//var id=$(this).attr('id');
				var id=$(rows[i]).find("td:eq(11) input").attr('id');
				//alert(id);
				//return 0;
				var cnfrm=confirm("This will approve your selected Item, Continue?");
				if(cnfrm){
					$.ajax({
						type:'GET',
						url:'control/reqApproval.php',
						async:false,
						data:({id:id}),
						success:function(data){
							if(data==1){
								alert("Successfully adjusted!");
								$(rows[i]).find("td:eq(10)").text("Approved");
							}else{
								alert(data);
							}														
						}
					});				
				}else{
					$(rows[i]).find("td:eq(11) input").prop('checked',false);
				}
			}else{
				$(rows[i]).find("td:eq(11) input").prop('checked',true);
			}
	    }			
	});

	/*$("#tbl-view-request tbody").delegate("tr","click",function(event){	
		var nodeNam=(event.target.nodeName).toLowerCase();		
		//alert(nodeNam);
		var tbl=$("#tbl-view-request").DataTable();	
		var cel=$(this).index();		
		 
		//alert(event.target.parent());
		//alert(canReceive);
		/*canReceive for merch to approve vendor delivery request
		  function for modifying qty.	*/
		//if(canReceive==1){			 
			/*alert(nodeNam);
			if(nodeNam=='input'){
				
				//var colIndex=$(event.target).closest("td").index();
				//if(colIndex==4){
			}*/

	//});*/
}
function _upViewed(my_id){
	$.ajax({
		type:'GET',
		url:'control/upViewed.php',
		data:({id:my_id}),
		success:function(data){
			//alert(data);
		}
	});
}

function _privilige(){
	var dtid="#dataTables-privileges";
	//$(dtid).dataTable().fnDestroy();	
	//$(dtid +" tbody").empty();	
	//$(dtid+" tbody").html(dtdata);
	$(dtid).dataTable({
		"columnDefs": [{ "width": "150px", "targets": 0 }],
		"bLengthChange": false,
		"iDisplayLength":10,						    
		"bDestroy": true,
		"oLanguage":{
			"sSearch":"Search"
		}
	}); 
}
function _clickTblPriv(){
	var dtid="#dataTables-privileges";
	var classUpd='';

	$(dtid).delegate("input","click",function(e){	
		classUpd=$(this).attr('class');
		var splt=classUpd.split("-");
		var bool=$(this).prop('checked');
		// alert(classUpd);
		if(bool)
			bool=1;
		else
			bool=0;

		
		//alert("bool:"+bool+"; classupd:"+classUpd+";");
		$.ajax({
			type:'GET',
			async:false,
			url:'control/updPrivileges.php',
			data:({data:classUpd,flag:bool}),
			dataType:'json',
			success:function(out){
				//alert("success:"+out);
			},error:function(out){
				alert("errors:"+out);
			}

		});
		if(splt[1]==4){/***for Islocked****/
			location.href="../panel/user_privilege.php";
		}
	}); 
 
}
function _isForRecLoaded(){
	var returns=0;
	$.ajax({
		type:'GET',
		async:false,
		url:'control/isForRecLoaded.php',
		data:{flag:1},
		success:function(data){
			returns=data;
		}
	});
	return returns;
}
function clickLinkNotification(){
	var returns=0;
	$.ajax({
		type:'GET',
		async:false,
		url:'control/notLoaded.php',
		data:{flag:1},
		success:function(data){
			//returns=data;
		}
	});
	//return returns;
}

function getRegPullPANoti(){
	var returns=0;
	$.ajax({
		type:'GET',
		async:false,
		url:'control/getPulloutPANot.php',
		data:{flag:1,isTotalPullout:0},
		success:function(data){
			returns=data>0?"1":"0";
			regPulloutNoti=data;  
		}
	});
	return parseInt(returns);
}

/*Get Approved pullout status=1/Approved for the PA to Have in Notification*/
function getTotPullPANoti(){
	var returns=0;
	$.ajax({
		type:'GET',
		async:false,
		url:'control/getPulloutPANot.php',
		data:{flag:1,isTotalPullout:1},
		success:function(data){
			returns=data>0?"1":"0";
			totPulloutNoti=data;
		}
	});
	return parseInt(returns);
}

function getNoti(){
	//setGlobal();
	//alert(gGroupIdSplit);
	$.ajax({
		type:'GET',
		url:'control/getNoti.php',
		async:false,
		dataType:'json',
		data:{privDtl:gGroupIdSplit},
		success:function(data){		
			remNoti(); 
			//rows['vUpd'] == 1 && $rows['pUpd'] == 1 && $rows['vVwd']
			//alert(data[0]['vUpd']+":"+data[0]['pUpd']+":"+data[0]['vVwd']+":"+data[0]['vendor_code']);
			notifications = data.length;
			if(data.length > 0){
			 	$("#reqViewer").children('li').remove();
				if($("#myNoti li:eq(0)").attr("class")){
					$("#myNoti li:eq(0)").removeClass("open");
					loadNot(data);					
				}else{
					loadNot(data);	
					//$("#myNoti li:eq(0)").addClass("open");										
						//$("#me_badge").hide();					 
				}
				$("#me_badge").html(data.length);	
				$("#me_badge").show();	
		  	}else{
		  		$("#me_badge").hide();
		  	}			
			//return 0;
		} 
	});
}
function remNoti(){
	$("#reqViewer").children('li').remove();
	$("#myNoti li:eq(0)").removeClass("open");
	_loadNotification(" ",0,0);
}

function loadNot(data){
//function _loadNotification(str,flag,count){
	for(var i = 0; i < data.length; i++){
		if( i < 4){
			_loadNotification(data[i].split("::")[0],$.trim(data[i].split("::")[1]),data.length);
			//_loadNotification(data[i]['msg2'],1,data.length);
		}
		else{
			_loadNotification("&nbsp;&nbsp;More.......",0,data.length);
		}
	}	
}


