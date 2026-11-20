$(function(){ 
	var paId; 
	/********SEARCH****SEARCH************/
	storeArr=[];
	vendor=0;
	$("#btn_search_pa").click(function(){
		load_pa(); 
	});
	$("#btn_search_store").click(function(){
		if(hasVendor() || $("#txt_vendor").val()!=''){
			var myVendor;
			paId=getPaId();
			if(hasVendor()){
				myVendor=vendor;
			}else{
				myVendor=$("#txt_vendor").val();
			}
			load_store(paId,myVendor);
		}else{
			alert("Please Select Vendor Assistant!");
		}			
	});
	$("#btn_search_vendor").click(function(){
		if($("#txt_pa").val()!=''){
			//paId=getPaId();
			//load_store(paId);
			if(hasVendor()){
				//dialog-lookup-vendordataTables-lookup-vendor
				//$(".dialog-lookup-vendor").modal("show");
				alert("Not Allowed!");
			}else{
				loadVendor();
			}
			//$(".dialog-lookup-vendor").modal("show");
		}else{
			alert("Please Select Promo Assistant!");
		}			
	});
	/*****************clear fields****************************************/	
	$("#btnClearPa").click(function(){		 
		clearPa();
	}); 
	$("#btnClearVendor").click(function(){		 
		clearVendor();
	}); 
	$("#btnClearStore").click(function(){		 
		clearStore();
	}); 
	$("#btn_clear").click(function(){
		clearFields();
	});
	/******Ok in selecting Store modal(OK for checked stores)*************/
	$(".dialog-lookup-store #btn_submit").click(function(){		
		if(storeArr.length >0 && $("#txt_stores").val()!=''){
			$("#txt_stores").val('');
		}
		//_displayArrToTxt();
		hideModal();
	});
	 
	/************Attach Stores to Promo assistant*************************/
	$("#btnSubmit").click(function(){		
		if(isCompleted()){
			var bool=confirm("Are you sure? Click Cancel if not.");
			var myVendor;
			if(bool){
				paId=$("#txt_pa").val();
				if(hasVendor()){
					myVendor=vendor;
				}else{
					myVendor=$("#txt_vendor").val();
				}
				insertToDb(paId,myVendor);
			}			
		}else{
			alert("Please Fill-up Fields!");
		}
		 
	});
	/********************click event of checkbox inside datatables modal**/
	$("#dataTables-lookup-store").delegate("input","click",function(){
		var myStore=$(this).attr("class");
		//alert(val(myStore));
		if($(this).prop("checked")){
			storeArr.push(myStore);
		}else{
			 for(var i=0;i<storeArr.length;i++){			  
				     if(storeArr[i]==myStore){
						storeArr.splice(i,1); 
						break;
		     		  }
		     }
		}

	});
	/*******************click event inside datatables********************}/

	/********Disable Input Keys*****************/
	disableKeys();

	/********ON load of modal Clear Array*******/
	clearArr();
	onCloseOfModal();
	$("#dataTables-lookup-store tbody").on("click","tr",function(){
		var tbl=$("#dataTables-lookup-store").DataTable(); 		 
		var rows=$("#dataTables-lookup-store").dataTable().fnGetNodes(); 
		
			//var stockNum=$(rows[i]).find("td:eq(4) input").val();
		var i=tbl.row(this).index(); 
		 //var y=tbl.col(this).index();
		 //alert(i);
		$(rows[i]).find("td:eq(2) input").prop('checked',true);			 
	});
});
/****************END*********************END*********************END*******************************************************/
function loadVendor(){
	var dtid='#dataTables-lookup-vendor';
	$.ajax({
		type:'GET',
		url:'control/loadVpa.php',
		dataType:'json',
		async:false,
		success:function(out){
			$(dtid).dataTable().fnDestroy();	
			 	$(dtid +" tbody").empty();				 	 				
					var dtdata = '';
					var dr='';
					for(var i = 0; i < out.length; i++ ){
						dr="'"+out[i]['vendor_num']+"'";
						dtdata += '<tr>'								
								+	'<td width="100"><center><a href="#" aria-label="Close" data-dismiss="modal" onclick="clickedVendor('+dr+')">'+out[i]['vendor_num']+'</a></center></td>'
								+	'<td width="300">'+out[i]['vendor_name']+'</td>'
								//+ 	'<td><input id='+dta+' class="form-control" type="text" style="height:25px;width:50%;"/></td>'
								//+ 	'<td>Not yet</td>'
								+'</tr>';
					}
					 
					$(dtid+" tbody").html(dtdata);
					$(dtid).dataTable({
						"columnDefs": [{ "width": "150px", "targets": 0 }],
						"bLengthChange": false,
						"iDisplayLength":10,						    
						"bDestroy": true,
						"oLanguage":{
						"sSearch":"Search"
						}
					});		
					$(".dialog-lookup-vendor").modal("show");
		}
	});
}
function clickedVendor(vendorId){
	 $("#txt_vendor").val(vendorId);
}
function onCloseOfModal(){
	$(".dialog-lookup-store").on("hidden.bs.modal", function () {	
		 $("#txt_stores").val('');
		 _displayArrToTxt();
	});
}
function _clickId(){
	
	$("#dataTables-lookup-store tbody").on("click","tr",function(e){
		alert("asdf");
		 
		var rows=$("#dataTables-lookup-store").dataTable().fnGetNodes(); 
			//var stockNum=$(rows[i]).find("td:eq(4) input").val();
			var i=tbl.row(this).index(); 
			//var y=tbl.col(this).index();
		 
				$(rows[i]).find("td input").prop('checked');
				 
	});
}

function hideModal(){
	$(".dialog-lookup-store").modal("hide");
}
function _displayArrToTxt(){
	var value=$("#txt_stores").val();

	for(var i=0;i<storeArr.length;i++){
		//comma(i);
		//alert("sdf");
		value=value +''+comma(i)+''+storeArr[i];

		//if((i%2==0))
	}
	$("#txt_stores").val(value);
}
function comma(param){
	var value=$("#txt_stores").val();
	if(param>0){
		return ",";
	}else if(value!=''){
		return ",";
	}
	return "";
}
function clearArr(){
	//if($("#txt_stores").val()==''){
		$(".dialog-lookup-store").on("shown.bs.modal", function () {		 
				storeArr=[];
		});
	//}
}
 
function getPaId(){
	return $("#txt_pa").val();
}
function clearFields(){
	$("#txt_pa").val('');
	$("#txt_stores").val('');
	$("#txt_vendor").val('');
	storeArr=[];
}
function clearPa(){
	$("#txt_pa").val('');	
}
function clearVendor(){ 
	if(hasVendor()){
		//dialog-lookup-vendordataTables-lookup-vendor
		//$(".dialog-lookup-vendor").modal("show");
		alert("Not Allowed!");
	}else{
		$("#txt_vendor").val('');
	}
	
}
function clearStore(){
	$("#txt_stores").val('');
	storeArr=[];
}
function isCompleted(){
	var txtPa=$("#txt_pa").val();
	var txtStore=$.trim($("#txt_stores").val());

	if(txtPa=='' || txtStore==''){
		return false;
	}else{
		return true;
	}
}
function disableKeys(){
	$("#txt_pa,#txt_stores,#txt_vendor").keypress(function(e){
		e.preventDefault();
	});
}
function load_pa(){
	var dtid="#dataTables-lookup-pa";
	$("html").mask("");	
		$.ajax({   			
				type:'GET',
   				url:'control/loadPa.php',   
   				dataType:'json',
   				data:{flag:1},				
   				success:function(out){
   					
   					$(dtid).dataTable().fnDestroy();	
					$(dtid +" tbody").empty();  	
					if (!$.fn.DataTable.isDataTable(dtid)) {				 
						var dtdata = '';
						var checked='';
						var color='';
						var paId;
						for(var i = 0; i < out.length; i++ ){
							//var strId = "'"+out[i]['user_id']+" : "+out[i]["status"]+"'";				
							paId="'"+out[i][0]+"'";			
							dtdata += '<tr>'
									+	'<td width="10" align="center"><a aria-label="Close" data-dismiss="modal" onClick="idClicked('+paId+');" style="cursor:pointer;">'+out[i][0]+'</a></td>'
									+	'<td width="120" align="center">'+out[i][1]+'</td>'											 
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
					$("html").unmask();										 
   					$(".dialog-lookup-pa").modal("show");
   				}
   		});
}

function load_store(paId,myVendor){
	var dtid="#dataTables-lookup-store";
	$("html").mask("");	
		$.ajax({   			
				type:'GET',
   				url:'control/loadStorePa.php',   
   				dataType:'json',
   				data:{flag:1,paId:paId,myVendor:myVendor},				
   				success:function(out){
   					 
   					$(dtid).dataTable().fnDestroy();	
					$(dtid +" tbody").empty();  	
					if (!$.fn.DataTable.isDataTable(dtid)) {				 
						var dtdata = '';
						var checked='';
						var color='';
						var store;
						for(var i = 0; i < out.length; i++ ){
							//var strId = "'"+out[i]['user_id']+" : "+out[i]["status"]+"'";				
							store="'"+out[i][0]+"'";			
							dtdata += '<tr>'
									+	'<td width="10" align="center"><center><input id="dis_check_all" type="checkbox" class="'+out[i][0]+'" /></center></td>'		
									+	'<td width="100" align="center">'+out[i][0]+'</td>'
									+	'<td width="200" align="left">'+out[i][1]+'</td>'											
																			 
									+'</tr>';
							//storeArr.push(out[i][0]); use class instead
							//+	'<td width="10" align="center"><a aria-label="Close" data-dismiss="modal" onClick="storeClicked('+store+');" style="cursor:pointer;">'+out[i][0]+'</a></td>'
						}	
						 
						$(dtid+" tbody").html(dtdata);
						
						$(dtid).dataTable({
							"columnDefs": [{ "width": "150px", "targets": 0 }],
							"bLengthChange": false,
							"iDisplayLength": 10,
							"aaSorting":[[1,"asc"]], 
							"sPaginationType": "bs_two_button",
							"destroy": true,
							"oLanguage":{
								"sSearch":"Search"
							}		
						});
						$(dtid).dataTable().fnFilter("");
					}												
					$(dtid).dataTable().fnFilter("");
					$("html").unmask();										
   					$(".dialog-lookup-store").modal("show");
   				}
   		});	
}

function idClicked(id){
	$("#txt_pa").val(id);
	$("#txt_vendor").val('');
	storeArr=[];
	getvendor(id);
	setVendor();	 
}
function getvendor(id){
	$.ajax({
		type:'GET',
		dataType:'text',
		async:false,
		url:'control/getVendor.php',
		data:{flag:1,paId:id},
		success:function(out){							
			vendor=out;							
		},
		error:function(out){
			alert("Error : "+out+"!");
		}
	});
}
function hasVendor(){
	if(vendor!=0){
		return true
	}	
	return false;
}
function setVendor(){
	if(hasVendor()){
		$("#txt_vendor").val(vendor);
	}
}
function storeClicked(store){
	$("#txt_stores").val(store);
	storeArr.push(store);
}

function insertToDb(paId,myVendor){ 
	$.ajax({
		type:'GET',
		url:'control/insertPa.php',   
   		dataType:'text',	
   		data:{flag:1,storeArr:storeArr,paId:paId,myVendor:myVendor},
		success:function(out){
			//alert(out);
			if(out==1){
				alert("Done");
				clearFields();
			}
		},
		error:function(out){
			alert("Insert Error : "+out+"!");
		}
	});
}