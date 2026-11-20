/**Separate JS File***with functions***********/  
$(function(){
	
	$("#txt_pullout,#delWin_date").keypress(function(e){
		e.preventDefault();
	});
	$("#txt_pullout,#delWin_date").bind('cut copy paste',function(e){
		e.preventDefault();
		//alert("Asdf");	 
	});
	$("#ApprovReason").attr("disabled","disabled");	
	//alert("Asdf");
});
 
/**print preview ***/
function printTrf(isTotalPullout){ 
	
	var txt_pullout=$("#txt_pullout").val();
	//alert(flag);
	//alert(isTotalPullout);
	if(txt_pullout!=''){
		//$(".dialog-lookup-print #dr-rpt").load("generate_sale/dr_rpt.php")  ;
		$(".dr-rpt").attr({'src': 'generate_sale/pullout_rpt.php?pullout='+txt_pullout+'&isTotalPullout='+isTotalPullout+'&param='+gId+'&param2='+gCanApprove+'&param3='+gIsCoor});

		$(".dialog-lookup-print").ready(function() {
			setTimeout(function(){
				$(".dialog-lookup-print").modal("show");	
			},100);
			
		});		
	}else{		 
		$(".mySku_msg").text('Please Select Pullout # !');
		showMiniDialog();
	}

	
}

function closeAlert(){
	$(".dialog-alert").modal("hide");
}

function setpickDate(btn_calendar){
	/* Set DATE  GREATHER THAN CURRENT DATE*/    
	$(btn_calendar).datepicker({
		todayHighlight: true,
		clearBtn: true,
		autoclose: true,
		autoopen: false,
		startDate:new Date()
	}).on('changeDate', function(selected){
	
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
		$("#to").datepicker('setStartDate', startDate);	
        
		endDate = new Date(selected.date.valueOf());
		endDate.setMonth($(this).datepicker("getDate").getMonth() + 1);
		endDate.setDate(1);
		endDate.setDate(endDate.getDate() - 1);
		//$("#to").datepicker('setEndDate', endDate);
		 
    }).on('show', function(){
		/*if($("#to").val() == "") {
			$(this).datepicker('setStartDate', new Date("12/01/2014"));
			$(this).datepicker('setEndDate', '');
		}*/
    }).off('focus');
	

}

function _selectVendor(dtid){
	//$("html").mask(" ");
	$("#loadingGif").show();
	$.ajax({
		type:'POST',
		dataType:'json',
		url:'control/loadVtransfer.php',
		success:function(out){
				//alert(out.length);

			//for(var i=0;i<out.length;i++){
				//	alert(out[i][0]);
				$(dtid).dataTable().fnDestroy();	
			 	$(dtid +" tbody").empty();
				if (!$.fn.DataTable.isDataTable(dtid)) {
					var dtdata = '';
					for(var i = 0; i < out.length; i++ ){
						var strId = "'"+out[i][0]+"'";						 
						var strId = "'"+out[i][0]+" : "+out[i][1]+"'";		
						//alert(strId);				 
						dtdata += '<tr>'
								+	'<td><center><a href="#" aria-label="Close" data-dismiss="modal" onclick="vendorClicked('+strId+')" >'+out[i][0]+'</a></center></td>'
								+	'<td>'+out[i][1]+'</td>'
								 
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
					//$(dtid).dataTable().fnDestroy();	
			 	//$(dtid +" tbody").empty();	
				}
				 
				$(dtid).dataTable().fnFilter("");
				//$("html").unmask();
				$("#loadingGif").hide();
			//}
			$(".dialog-lookup-vendor").modal("show");
		}		
	});
}

function _selectFrom(dtid,vendor_num){
	//$("html").mask(" ");
	$("#loadingGif").show();
	$.ajax({
		type:'POST',
		dataType:'json',
		url:'control/loadFrom.php',
		data:{vendor_num:vendor_num},
		success:function(out){
				//alert(out.length);
			//for(var i=0;i<out.length;i++){
				//	alert(out[i][0]);
				$(dtid).dataTable().fnDestroy();	
			 	$(dtid +" tbody").empty();
				if (!$.fn.DataTable.isDataTable(dtid)) {
					var dtdata = '';
					for(var i = 0; i < out.length; i++ ){
						//var strId = "'"+out[i][0]+"'";						 
						var strId = "'"+out[i][0]+" : "+out[i][1]+"'";									
						dtdata += '<tr>'
								+	'<td><center><a href="#" aria-label="Close" data-dismiss="modal" onclick="fromClicked('+strId+')" >'+out[i][0]+'</a></center></td>'
								+	'<td>'+out[i][1]+'</td>'
								 
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
					//$(dtid).dataTable().fnDestroy();	
			 	//$(dtid +" tbody").empty();	
				}
				 
				$(dtid).dataTable().fnFilter("");
				//$("html").unmask();
				$("#loadingGif").hide();
			//}
			$(".dialog-lookup-fromLoc").modal("show");
		}		
	});
}
function _selectDept(dtid){
	//$("html").mask(" ");
	$("#loadingGif").show();
	$.ajax({
		type:'POST',
		dataType:'json',
		url:'control/loadDeptTransfer.php',
		data:{vendor_num:globalVendor},
		success:function(out){			
				 
				//var obj=jQuery.parseJSON(out);	
				//alert(obj.length);
				$(dtid).dataTable().fnDestroy();	
			 	$(dtid +" tbody").empty();
				if (!$.fn.DataTable.isDataTable(dtid)) {
					var dtdata = '';
					for(var i = 0; i < out.length; i++ ){
						//var strId = "'"+out[i][0]+"'";	
						var dept="'"+(out[i]['dept'].replace("'","`").replace("/","`"))+"--"+out[i]['isdept']+"--"+out[i]['iclas']+"'";											 
						//var dept = "'"+out[i]["dept"]+":"+out[i]["isdept"]+"'";									
						dtdata += '<tr>'
								+	'<td><center><a href="#" aria-label="Close" data-dismiss="modal" onclick="deptClick('+dept+')" >'+out[i]["dept"]+'</a></center></td>'																 
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
					//$(dtid).dataTable().fnDestroy();	
			 	//$(dtid +" tbody").empty();	
				}
				 
				$(dtid).dataTable().fnFilter("");
				//$("html").unmask();
				$("#loadingGif").hide();
			//}
			$(".dialog-lookup-dept").modal("show");
		},
		error:function(out){
			alert(out);
		}	
	});
}

function _selectSku(dtid,flag){
	//$("html").mask(" ");
	$("#loadingGif").show();
	$.ajax({
		type:'GET',
		dataType:'json',
		url:'control/list_sku.php',
		data:{vendor_num:globalVendor,contract_num:'Not used',globalIdept:globalIdept,globalClas:globalClas},
		success:function(out){							
				//var obj=jQuery.parseJSON(out);	
				//alert(obj.length);
				$(dtid).dataTable().fnDestroy();	
			 	$(dtid +" tbody").empty();
				if (!$.fn.DataTable.isDataTable(dtid)) {
					var dtdata = '';
					for(var i = 0; i < out.length; i++ ){
						//var strId = "'"+out[i][0]+"'";						 
						var sku = "'"+out[i]["sku"]+":"+out[i]['descr']+"'";									
						dtdata += '<tr>'
						 	    + (flag==1?'<td width="10" align="Center"><input type="checkbox"/></td>':'')
								+	'<td><center><a href="#" aria-label="Close" data-dismiss="modal" onclick="skuClick('+sku+')" >'+out[i]["sku"]+'</a></center></td>'																 
								+	'<td>'+out[i]['descr']+'</td>'
								+ (flag==1?'<td></td>':'')
								+ (flag==1?'<td></td>':'')
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
					$(".dialog-lookup-sku").modal("show");
					//$(dtid).dataTable().fnDestroy();	
					heirarchy.push([globalIdept,globalClas]);
			 		//$(dtid +" tbody").empty();	
				}				 
				$(dtid).dataTable().fnFilter("");
				//$("html").unmask();
				$("#loadingGif").hide();
			//}
			
		},
		error:function(out){
			alert(out);
		}	
	}); 	
}


/* Click Id From Dialog */
function vendorClicked(val){
	var split_data=val.split(":");
	var vendor=$.trim(split_data[0]);
	var vendor_name=$.trim(split_data[1]);
	//clearFieldsBelowVendor();
	$("#str_store").val(val);
	globalVendor=vendor;
}

function deptClick(dept){	 
	/*var splitDept=dept.split(":");
	var myDept=$.trim(splitDept[0]);
	var isDept=$.trim(splitDept[1]);
	*/

	var splt=dept.split("--");
	var desc=$.trim(splt[0]);
	var sdept=splt[1];
	var cls=splt[2];
	
	globalIdept=sdept;
	globalClas=cls;
	//alert(cls);

	dept=desc+"--"+sdept+"--"+cls;
	$("#txtDept").val(dept);


	/*var splitDept=dept.split(":");
	var myDept=$.trim(splitDept[0]);
	var isDept=$.trim(splitDept[1]);
	globalIdept=isDept;
	$("#txtDept").val(myDept);*/
	 
}

function skuClick(sku){	 
	var splitSku=sku.split(":");		 
	$("#txt_sku").val(sku);	 
	$("#txt_qty").removeAttr('disabled');
	$("#txt_qty").val('');
	$("#txt_qty").focus();	
	$("#div_on_hand").text("on hand : "+checkInventory(getVendorNum(),getStorerNum(),getSkuNum()));
}

function clearQty(){
	$("#txt_qty").val('');
}

function _getVendor(){
	var txt_vendor=$.trim($("#str_store").val());
	var txtSplit=txt_vendor.split(":");
	var vendor_num=$.trim(txtSplit[0]);	

	return vendor_num;
}

function fromClicked(val){ 
	var split_data=val.split(":");
	var from_loc=$.trim(split_data[0]);
	var loc_name=$.trim(split_data[1]);
	//clearFieldsBelowFrom();
	$("#idFrom").val(val);	
}

function getVendorNum(){
	var txt_vendor=$.trim($("#str_store").val());
	var vendorSplit=txt_vendor.split(":");
	var myVendor=$.trim(vendorSplit[0]);

	return myVendor;
}
function getStorerNum(){
	var txt_from=$.trim($("#idFrom").val());
	var storeSplit=txt_from.split(":");
	var myStore=$.trim(storeSplit[0]);

	return myStore;
}
function getSkuNum(){
	var txt_sku=$.trim($("#txt_sku").val());
	var skuSplit=txt_sku.split(":");
	var mySku=$.trim(skuSplit[0]);

	return mySku;
} 

function checkInventory(vendor,store,sku){
	var on_hand=0;
	$.ajax({
		type:'GET',
		dataType:'text',
		async:false,
		url:'control/ifHasInventory.php',
		data:{flag:1,vendor:vendor,store:store,sku:sku},
		success:function(out){
			if(out>0){
				on_hand=out;
			}
		},
		error:function(out){
			alert("Error : "+out);
		}
	});
	return on_hand;
}

function checkTrfArrQty(vendor,store,sku,qty){
	var myQty=0;
	var arrVendor;
	var arrStore;
	var arrSku;
	var arrQty;
	var qty=parseInt(qty);
	//var qtySplit=qty.split(":");
	//var myQty=$.trim(qtySplit[0]);

	for(var i=0;i<forTrfQty.length;i++){
		arrVendor=forTrfQty[i][0];
		arrStore=forTrfQty[i][1];
		arrSku=forTrfQty[i][2];
		arrQty=parseInt(forTrfQty[i][3]);

		//alert(arrVendor+"-"+arrStore+"-"+arrSku+"-"+arrQty);
		if(arrVendor==vendor && arrStore==store && arrSku==sku ){
			myQty+=arrQty;
		}
	}
	return myQty;
}


function setToArrTrfQty(vendor,store,sku,qty,on_hand){
	forTrfQty.push([vendor,store,sku,qty,on_hand]);
}

function getTotalOnHandArr(index,isTotalPullout){
	var on_hand=forTrfQty[index][4];
	var vendor=forTrfQty[index][0];
	var store=forTrfQty[index][1];
	var sku=forTrfQty[index][2];
	var myReturn=getTotalOnHandArrBySelectedRow(vendor,store,sku,on_hand,isTotalPullout);

	return myReturn;
} 

function getTotalOnHandArrBySelectedRow(vendor,store,sku,on_hand,isTotalPullout){
	var dtid=(isTotalPullout==0?"#dataTables-regular-pullout":'#dataTables-total-pullout');
	var myQty;	 
	var totalQTy=0;
	//if (!$.fn.DataTable.isDataTable(dtid)) {
	var dtrows=$(dtid).dataTable().fnGetNodes();		

	var mySku;
	var myVendor;
	var myFrom;
	var myTo;
	var myQty;	
	var totalQTy=0; 	

	for(var i=0;i<dtrows.length;i++){				 			 
		mySku=$(dtrows[i]).find("td:eq(0)").text();
		myVendor=$.trim(($(dtrows[i]).find("td:eq(2)").text()).split(":")[0]);
		myFrom=$.trim(($(dtrows[i]).find("td:eq(3)").text()).split(":")[0]);		 
		myQty=$(dtrows[i]).find("td:eq(4)").text();
		if(myVendor==vendor && myFrom==store && mySku==sku){
			totalQty+=myQty;
		}		 			 
	}	
	return totalQty;
}

function totalTrfQty(){
	var grand_qty=0;
	for(var i=0;i<forTrfQty.length;i++){
		 grand_qty+=parseInt(forTrfQty[i][3]);
	}
	return grand_qty;
}

function totalTrfQtyInput(flag){
	var dtid=(flag==0?"#dataTables-regular-pullout":'#dataTables-total-pullout');
	var myQty;	 
	var totalQTy=0;
	//if (!$.fn.DataTable.isDataTable(dtid)) {
	var dtrows=$(dtid).dataTable().fnGetNodes();				
	for(var i=0;i<dtrows.length;i++){				 			 
		myQty=parseInt($(dtrows[i]).find("td:eq(4) input").val());		
		totalQTy+=myQty;		 
	}	
	//}
	return totalQTy;
}

function _dataTable(dtid){
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
}

function isDataTableHasRows(isTotalPullout){
	var dtid=_returnDtaTable(isTotalPullout);		 
	var rw=$(dtid).dataTable().fnGetNodes(); 
	if(rw.length>0){
		enableButtons();
	}
}

function enableButtons(){
	$("#btn_accept,#btn_cancel").removeClass('disabled');
}
function disableButtons(){
	$("#btn_accept,#btn_cancel").addClass('disabled');	
}

function clearUpperFields(){
	//$("html").mask(" ");
	$("#loadingGif").show();
	$("#str_store").val('');
	$("#idFrom").val('');
	//$("#txtToLoc").val('');
	$("#txt_sku").val(''); 
	//$("#ccn_mf").val('');
	$("#txtDept").val('');	
	$("#txt_qty").val('');
	$("#delWin_date").val('');
	//$("html").unmask();
	$("#loadingGif").hide();
	heirarchy=[];
	multArr=[];
}

function addToCart(txt_vendor,txt_from,txt_date,txt_sku,ccn_mf,txt_dept,txt_qty,isTotalPullout){

	var dtid="#dataTables-regular-pullout";
	var splitSku=txt_sku.split(":");
	var mySku=splitSku[0];
	var myDescr=splitSku[1];
	var dtdata='';

	/**split data*/ 
	var vendorSplit=txt_vendor.split(":");
	var myVendor=$.trim(vendorSplit[0]);

	var storeSplit=txt_from.split(":");
	var myStore=$.trim(storeSplit[0]);

	var skuSplit=txt_sku.split(":");
	var mySku=$.trim(skuSplit[0]);
	dtid=_returnDtaTable(isTotalPullout);

		if (!$.fn.DataTable.isDataTable(dtid)) {
			dtdata += '<tr>'
				+	'<td><center>'+mySku+'</center></td>'
				+	'<td>'+myDescr+'</td>'
				+	'<td>'+txt_vendor+'</td>'
				+	'<td>'+txt_from+'</td>'
				 
				//+	'<td>'+txt_qty+'</td>'
				+ 	'<td><input class="form-control" type="text" style="width:80px;text-align:right;" value="'+txt_qty+'"/></td>'
				//+   '<td><button type="button" class="btn btn-default btn-circle"><i class="fa fa-times fa-f5"></i></button></td>'			 				
				//+	'<td>'+txt_date+'</td>'
				+'</tr>';
		}else{
			//$(dtid).dataTable().fnClearTable();													
			$(dtid).dataTable().fnAddData([
				'<center>'+mySku+'</center>',myDescr,txt_vendor,txt_from,'<input class="form-control" type="text" style="width:80px;text-align:right;" value="'+txt_qty+'"/>'//txt_date			 				
			])				
			//<td><button type="button" class="btn btn-default btn-circle"><i class="fa fa-times fa-f5"></i></button></td>
			$(dtid).dataTable().fnFilter("");
		}		
		setToArrTrfQty(myVendor,myStore,mySku,txt_qty,checkInventory(getVendorNum(),getStorerNum(),getSkuNum()));
		
		$(dtid+" tbody").html(dtdata);		
		_dataTable(dtid);
		$(".grand_qty").text("Qty : "+totalTrfQtyInput(0));
		isDataTableHasRows(isTotalPullout);
 
}

function initializetrfQtyArr(){ 
	forTrfQty=[];
}
function cancelCart(isTotalPullout){
	 
	var dtid=_returnDtaTable(isTotalPullout);		 
	$(dtid).dataTable().fnDestroy();		
	$(dtid +" tbody").empty();	
	disableButtons();
	initializetrfQtyArr();
	$("#ApprovReason").attr("disabled","disabled");
	heirarchy=[];
}
function _returnDtaTable(isTotalPullout){
	var dtid;
	if(isTotalPullout==1)
		dtid="#dataTables-total-pullout";
	else
		dtid="#dataTables-regular-pullout";

	return dtid;
}
/***for btn accept***********/    
function loopDatatable(flag){
	var dtid=(flag==0?"#dataTables-regular-pullout":'#dataTables-total-pullout');
	var dtrows=$(dtid).dataTable().fnGetNodes();
	var mySku;
	var myVendor;
	var myFrom;
	var myTo;
	var myQty;
	var ccn_mf;
	var pickDate;
	var totalQTy=0;

	/********FOR regular pull out*******************/
	if(flag==0){
		for(var i=0;i<dtrows.length;i++){		
			mySku=$(dtrows[i]).find("td:eq(0)").text();
			myVendor=($(dtrows[i]).find("td:eq(2)").text()).split(":")[0];
			myFrom=($(dtrows[i]).find("td:eq(3)").text()).split(":")[0];			 
			myQty=$(dtrows[i]).find("td:eq(4) input").val();
			//pickDate=$(dtrows[i]).find("td:eq(5)").text();
			//alert(mySku + "- vendor:"+myVendor+"-From:"+myFrom+"-qty:"+myQty);
			//if(parseInt(qty)>0){
				//my_arr.push([sku,stock_num,qty]);
			//}
			totalQTy+=myQty;
			/**************0******1*******2*****3*****4****5*************/
			myCart.push([mySku,myVendor,myFrom,myQty]);
		}	
	}
	/********FOR total***************************/
	else if(flag==1){
		for(var i=0;i<dtrows.length;i++){		
			mySku=$(dtrows[i]).find("td:eq(0)").text();
			myVendor=($(dtrows[i]).find("td:eq(2)").text()).split(":")[0];
			myFrom=($(dtrows[i]).find("td:eq(3)").text()).split(":")[0];	
			//myTo=$(dtrows[i]).find("td:eq(4)").text();
			myQty=$(dtrows[i]).find("td:eq(4) input").val(); 
			//pickDate=$(dtrows[i]).find("td:eq(5)").text();
			//alert(mySku);
			//if(parseInt(qty)>0){
				//my_arr.push([sku,stock_num,qty]); 
			//}
			totalQTy+=myQty;
			/**************0******1*******2*****3*****4****5*************/
			myCart.push([mySku,myVendor,myFrom,myQty]);
		}			
	}
	/*for(var i=0;i<myCart.length;i++)
	{
		alert(myCart[i][0]+"-"+myCart[i][1]+"-"+myCart[i][2]);
	}*/
	return parseInt(totalQTy);
	//alert(myCart.length);  
}
function sendToServer(flag){
	var rtrn;
	$.ajax({
		type:'POST',
		async:false,
		url:'control/sendCartToServer.php', 
		data:{dataArr:myCart,flag:flag,remarks:$("#ApprovReason").val(),heirarchy:heirarchy},  
		success:function(out){			
			rtrn=out;			
		},
		error:function(out){
			rtrn=out;
		}
	});
	return rtrn;
	
}

function _getTrfSession(flag){
	var myTrf='';
	$.ajax({
		type:'GET',
		async:false,
		url:'control/getTrfSession.php',
		data:{flag:flag},
		success:function(out){		
			myTrf=out;		
		}		 
	});
	return myTrf;
}
    
/***************FOR SEARCH OF regular pullout NUMBER/pullout # clicked******************* ********************/
function _selectPulloutNum(dtid,isTotalPullout){
	//alert(getUrl);
	//$("html").mask(" ");
	$("#loadingGif").show();
	$.ajax({
		type:'GET',
		dataType:'json',
		url:'control/loadPullout.php',  
		data:{flag:1,isTotalPullout:isTotalPullout},
		success:function(out){	
		//alert(out.length);		 
			$(dtid).dataTable().fnDestroy();	
		 	$(dtid +" tbody").empty();
			if (!$.fn.DataTable.isDataTable(dtid)) {
				var dtdata = '';
				var back = '';
				for(var i = 0; i < out.length; i++ ){
					var pullout = "'"+out[i][0]+"'";						 
					//var strId = "'"+out[i][0]+" : "+out[i][1]+"'";  		
					 	if(getUrl == 12 && out[i]['vUpd'] == 0 ){
								back='style="background-color:#c6dff8;"';							 
						}else if( getUrl == 13 && out[i]['pUpd'] == 0 && out[i]['vUpd'] == 1){
								back='style="background-color:#c6dff8;"';
						}
						else if( (getUrl == 14 || getUrl == 21) && out[i]['vVwd'] == 0){
								back='style="background-color:#c6dff8;"';
						}
						else if( (getUrl == 15 || getUrl == 19) && out[i]['mVwd'] == 0){
								back='style="background-color:#c6dff8;"';
						}
						else if( (getUrl == 16 || getUrl == 20) && out[i]['ctorVwd'] == 0){
								back='style="background-color:#c6dff8;"';
						}
						else if( getUrl == 17 && out[i]['pUpd'] == 0){
								back='style="background-color:#c6dff8;"';
						}
						else if( getUrl == 18 && out[i]['pUpd'] == 0 && out[i]['vUpd'] == 1){
								back='style="background-color:#c6dff8;"';
						}
					dtdata += '<tr '+back+'>'
							+	'<td><center><a href="#" aria-label="Close" data-dismiss="modal" onclick="trfClicked('+pullout+')" >'+out[i][0]+'</a></center></td>'
							+	'<td align="center">'+out[i][1]+'</td>'
							//+	'<td>'+out[i][2]+'</td>'
							//+	'<td>'+out[i][3]+'</td>'
							//+	'<td>'+out[i][4]+'</td>'
							+	'<td align="center">'+out[i][2]+" : "+out[i]['strnam']+'</td>'
							+	'<td align="center">'+out[i]['ee_name']+'</td>'
							+	'<td align="center">'+out[i]['date_created']+'</td>'
							+	'<td align="center">'+out[i]['pick_up_date']+'</td>'
							+	'<td align="center">'+out[i]['actualPick_upDate']+'</td>'
							+	'<td align="center">'+out[i]['stat_desc']+'</td>' 
							+'</tr>';
				}					
				$(dtid+" tbody").html(dtdata);		
				//_dataTable(dtid);
				$(dtid).dataTable({
						"columnDefs": [{ "width": "150px", "targets": 0 }],
						"bLengthChange": false,
						"iDisplayLength":10,
						"aaSorting":[[4,"desc"]],    
						"bDestroy": true,
						"oLanguage":{
							"sSearch":"Search"
						}
				});
				$(dtid).dataTable().fnFilter("");
			 
			}			 
			$(dtid).dataTable().fnFilter("");
			//$("html").unmask();		 
			$("#loadingGif").hide();
			$(".dialog-lookup-regular").modal("show");    
		}		
	});

	 
	
}

function trfClicked(trf_num){
	//alert(trf_num);
}


function isPulloutNumOpen(pulloutNum){
	var rtrn;
	$.ajax({
		type:'GET',
		async:false, 
		url:'control/validatePullout.php', 
		data:{flag:1,pulloutNum:pulloutNum},
		success:function(out){			
			rtrn=out;			
		},
		error:function(out){
			rtrn=out;
		}
	});
	return parseInt(rtrn);
}
 

function vendordApproval(){
	var dtls=$.trim($("#txt_dtls").val());
	var pullout=$.trim($("#txt_pullout").val());
	var pick_up=$.trim($("#delWin_date").val());
	var rtn;
	$.ajax({
		type:'GET',
		url:'control/pulloutApproval.php', 
		async:false,
		data:{flag:1,dtls:dtls,pullout:pullout,pick_up:pick_up},
		success:function(out){
			rtn=out;
			//alert("her:"+rtn);
		},
		error:function(out){
			rtn=out;
			// alert("her2:"+rtn);
		}
	});
	return rtn;
}

function checkInv(pullout){
	var rtn;
	$.ajax({
		type:'GET',
		url:'control/checkInv.php',
		async:false,
		data:{flag:1,pullout:pullout},
		success:function(out){
			rtn=out;			 
		},
		error:function(out){
			rtn=out;			 
		}
	});  
	return rtn;
}

function updateInvRegular(pullout,isForfeited){
	var rtn;
	$.ajax({ 
		type:'GET',
		url:'control/updateInvRegular.php', 
		async:false,
		data:{flag:1,pullout:pullout,isForfeited:isForfeited},
		success:function(out){
			rtn=out;			 
		},
		error:function(out){/**/
			rtn=out;			 
		}
	});
	return rtn;
}

function getUrlData(flag){
	 var name=flag;
	 var results=new RegExp('[\?&]' + name +'=([^&#]*)').exec(window.location.href);	
	 if(results && results!=''){
	 	 return results[1];
	 }else{ 
	 	return '';
	 }	
}

function computePulloutDaysLeft(pullout){ 
	var rtn=0;
	$.ajax({
		type:'GET',
		async:false,
		url:'control/getPulloutDaysLeft.php',
		data:{flag:1,pullout:pullout},
		success:function(out){
			rtn=out;
		}

	});
	return rtn;
}
function computeDate(selDate){
	var rtn=0;
	$.ajax({
		type:'GET',
		async:false,
		url:'control/computeDate.php',	
		data:{selDate:selDate},	 
		success:function(out){
			rtn=out;
		}

	});
	return rtn;
}

function CheckReqQtyVsInv(){

	var rtn=0;
	$.ajax({
		type:'GET',
		async:false,
		url:'control/reqQtyVsInv.php',	
		data:{flag:1,myCart:myCart},	
		dataType:'json', 
		success:function(out){
			for(var i=0;i<out.length;i++){
				alert(out[i]['qty'] +"-"+ out[i]['on_hand']);
				if(out[i]['qty'] > out[i]['on_hand']){
					rtn=setCss();
				}
			}
		}

	}); 
	setCss();
	return rtn; 

}
function getGid(){

	var gid;
	 $.ajax({
		type:'GET',
		async:false,
		url:'control/getGid.php',    	 
		success:function(out){
			gid=out; 
		}
	});
 	return gid;
}
		
function setCss(){
	var tbl=$("#dataTables-regular-pullout").DataTable();
	var rowss=tbl.fnGetNodes(); 
  
	var i=tbl.rows({order:'applied'}).nodes().indexOf(this);
	 
	//alert($(rows[i]).find("td:eq(5)").children('input').val());
   	$(rowss[i]).find("td:eq(5)").children("input").css({"border":"5px solid","border-color":"RED"});
   	return $(rows[i]).find("td:eq(4)").children('input').val();
}
/*-----Get total qty per sku-------------*/
function summarizeSku(){
	//myCart.push([mySku,myVendor,myFrom,myQty]);
	var initVal=0;
	var myLArr=[];
	var k=0;
	var flag;
	myLArr=[];
	//alert("Sku:"+myCart[0][0]+"-vendor:"+myCart[0][1]+"-from:"+myCart[0][2]+"-qty:"+myCart[0][3]);
	myLArr.push([myCart[0][0],myCart[0][1],myCart[0][2],myCart[0][3]]);
	for(var i=1;i<myCart.length;i++){

		flag=0;
		for(var j=0;j<myLArr.length;j++){

			if(myCart[i][0]==myLArr[j][0]){
				myLArr[j][3]=parseInt(myLArr[j][3])+parseInt(myCart[i][3]);
				flag=1;
			}
		 
			else if(myLArr.length==(j+1) && flag==0){
				myLArr.push([myCart[i][0],myCart[i][1],myCart[i][2],myCart[i][3]]);
				//alert("last rec ->i: "+i+"  Loop: "+k+"  MY ARR->Sku:"+myLArr[0][0]+"-vendor:"+myLArr[0][1]+"-from:"+myLArr[0][2]+"-qty:"+myLArr[0][3]);
				break;

			} 
			 
		}	
			
	}
	myCart=myLArr;
	/*for(var j=0;j<myCart.length;j++){
		alert("Sku:"+myCart[j][0]+"-vendor:"+myCart[j][1]+"-from:"+myCart[j][2]+"-qty:"+myCart[j][3]);	 
	}*/	

}
function _submit(){
	$(".dialog-lookup-sku #btn_submit_sku").click(function(){
		var txt_vendor=$.trim($("#str_store").val());
		var txt_from=$.trim($("#idFrom").val());
		  
		var txt_sku=$.trim($("#txt_sku").val());
		 
		var txt_dept=$.trim($("#txtDept").val());
		var txt_qty=$.trim($("#txt_qty").val());
		var txt_date='___';
		//txt_date=$.trim($("#delWin_date").val()); 

		/**split data*/ 
		var vendorSplit=txt_vendor.split(":");
		var myVendor=$.trim(vendorSplit[0]);

		var storeSplit=txt_from.split(":");
		var myStore=$.trim(storeSplit[0]);

		var skuSplit=txt_sku.split(":");
		var mySku=$.trim(skuSplit[0]);
		var ccn_mf=0;
		var isTotalPullout=0;

		 if(multArr.length>0){
		 	var price;
			var priceWithNoComma;
			var sku_qty;
			//multArrSku=[];
			
			var dtid='#dataTables-lookup-sku';			 
		 	var tbl=$(dtid).DataTable();
			var rows=$(dtid).dataTable().fnGetNodes(); 						 
			var i=tbl.rows({order:'applied'}).nodes().indexOf(this);		

			for(var x=0;x<multArr.length;x++){				
				var mySku=$(rows[multArr[x][0]]).find("td:eq(1)").text();
				var mySkuDesc=$(rows[multArr[x][0]]).find("td:eq(2)").text();				
				var mySkuQty=$.trim($(rows[multArr[x][0]]).find("td:eq(3) input:text").val());

				//alert(mySku+"-"+mySkuQty+"-"+mySkuDesc);
				if(mySkuQty=='' || mySkuQty < 1){
					$(dtid).dataTable().fnFilter(mySku);
					$(".msg-dialog .my_msg").text('Please Fill up fields!');
					$(".mySku_msg").text('Please Fill up fields!');
					 showMiniDialog();
					//break 0;
					return 0;
				}	
				  
			}
			$(".dialog-lookup-sku").modal("hide");
			if(multArr.length > 0){
				$(".mySku_msg").text('Your Selection was added to Cart!');
		 		showMiniDialog();		 		 
			}
			 
			for(var x=0;x<multArr.length;x++){				
				var mySku=$(rows[multArr[x][0]]).find("td:eq(1)").text();
				var mySkuDesc=$(rows[multArr[x][0]]).find("td:eq(2)").text();				
				var mySkuQty=$.trim($(rows[multArr[x][0]]).find("td:eq(3) input:text").val());				
				//multArrSku.push([mySku,mySkuDesc,mySkuQty]);	
				var myItem=mySku+":"+mySkuDesc;
				addToCart(txt_vendor,txt_from,txt_date,myItem,ccn_mf,txt_dept,mySkuQty,isTotalPullout);	 	
			}

			$(dtid).dataTable().fnFilter("");			 			
			
			//arrOrgQty.push([mySku,mySkuQty,mySkuQty,priceWithNoComma]);	
			if(multArr.length > 0){
				$("#ApprovReason").removeAttr("disabled");
				myClearFunc();
			}
			//var rtn_total_amt=numFormat(total_amt);
			//alert(rtn_total_amt);
		    $("#btn_accept,#btn_cancel").removeClass("disabled");	
		    $("#grand_total .grnd_my_qty").text(total_qty.toFixed(2));
			//$("#grand_total .grnd_my_amt").text((rtn_total_amt));						
				 

		 }
		 else{	
		 	// $(".msg-dialog .my_msg").text('No Records Selected!');
		 	$(".mySku_msg").text('No Records Selected!');
		 	showMiniDialog();
		 }
	});	  
}
function validateInputQty(tbl_sku){

	$(tbl_sku+" tbody").on("keyup","tr",function(event){	
		var nodeNam=(event.target.nodeName).toLowerCase();		
		//alert(nodeNam);
		var tbl=$(tbl_sku).DataTable();	 
		var cel=$(this).index();		
	     
	  
			if(nodeNam=='input'){
				
				var colIndex=$(event.target).closest("td").index();
				var myInput=$(event.target).val();
				var mySku=$(event.target).closest("td").prev('td').prev('td').text();
				
					
				if(colIndex==3){
					//alert("asdf");
					 getParam();
				     var dataTblQty=getOverAllQty(vndr,frm,mySku);				
				     //alert(dataTblQty);
					 var myOnHand=checkInventory(getVendorNum(),getStorerNum(),mySku); 
					 $(event.target).val(Math.abs($(event.target).val()));
					 var myModInut=parseInt($(event.target).val());
					 /*if(myModInut>myOnHand){
						 $(event.target).val('');		
						 $(".mySku_msg").text("Quantity Is greater than on hand!");
		 				 showMiniDialog();				  	 
					 }*/			 
					 if((myModInut+dataTblQty) > myOnHand){
					 	var myTxt=dataTblQty>0?"Your Input plus the current Qty In table":"Quantity";
					 	 $(".mySku_msg").text(myTxt+" Is greater than on hand!");
		 				 showMiniDialog();	
		 				 $(event.target).val('');		
		 				 return 0;
					 }
					 //var rowNum=tbl.rows({order:'applied'}).nodes().indexOf(this);
					 //alert(rowNum);	
				}
			}

	});
	 
}
function myClearFunc(){
	multArr=[];
	multArrSku=[];		
	var dtid='#dataTables-lookup-sku';
	$(dtid).dataTable().fnDestroy();	
	$(dtid +" tbody").empty();	

	var dtid='#dataTables-lookup-skuSel';
	$(dtid).dataTable().fnDestroy();	
	$(dtid +" tbody").empty();					 
	$(".mySelectedSku").text("("+multArr.length+")");
}
function selSku(){
	var dtid='#dataTables-lookup-skuSel';
	$(".dialog-lookup-sku a").click(function(){
		if(multArr.length>0){
			var tbl=$(dtid).DataTable();
			var rows=$("#dataTables-lookup-sku").dataTable().fnGetNodes(); 						 
			var i=tbl.rows({order:'applied'}).nodes().indexOf(this);

			var dtdata = '';		

			$(dtid).dataTable().fnDestroy();	
			$(dtid +" tbody").empty();

			for(var x=0;x<multArr.length;x++){
				//alert("sku:"+$(rows[multArr[x][0]]).find("td:eq(1)").text()+" description:"+$(rows[multArr[x][0]]).find("td:eq(2)").text()+" stockNum:"+$(rows[multArr[x][0]]).find("td:eq(3)").text()+"  qty:"+$(rows[multArr[x][0]]).find("td:eq(4)").text());
				var mySku=$(rows[multArr[x][0]]).find("td:eq(1)").text();
				var mySkuDesc=$(rows[multArr[x][0]]).find("td:eq(2)").text();
				//var mySkuStock=$(rows[multArr[x][0]]).find("td:eq(3) input:text").val();
				var mySkuQty=$(rows[multArr[x][0]]).find("td:eq(3) input:text").val();
				//alert(mySku+mySkuDesc+mySkuStock+mySkuQty);
				dtdata  += '<tr>'							
						//+	'<td width="600" align="Center" style="font-size:12px; font-family:arial;"><input type="checkbox"/></td>'
						+	'<td width="600" align="Center" style="font-size:12px; font-family:arial;">'+mySku+'</td>'
						+	'<td width="600" align="left" style="font-size:12px; font-family:arial;">'+mySkuDesc+'</td>'
						+	'<td width="600" align="left" style="font-size:12px; font-family:arial;">'+mySkuQty+'</td>'
						//+	'<td width="600" align="left" style="font-size:12px; font-family:arial;">'+mySkuQty+'</td>'
						 
						//+ 	'<td   align="center"><input class="'+out[i]['STORE']+'" type="checkbox" style="height:15px;"/></td>'
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

			$(".dialog-lookup-skuSel").modal('show');
		}else{
			$(".mySku_msg").text('No Records Selected!');
		 	showMiniDialog();
		}
	});
}
  
/***Get Qty Even from Your cart*****/
function getOverAllQty(vendor,fromLoc,sku){
	//alert(vendor+"-"+fromLoc+"-"+toLoc+"-"+sku);
	var dtid="#dataTables-regular-pullout";

	if (!$.fn.DataTable.isDataTable(dtid)) {	
		return 0;
	}
	var dtrows=$(dtid).dataTable().fnGetNodes();
	var mySku;
	var myVendor;
	var myFrom;
	var myQty;
	var totalQTy=0;
	for(var i=0;i<dtrows.length;i++){		
		mySku=$(dtrows[i]).find("td:eq(0)").text();
		myVendor=$.trim($(dtrows[i]).find("td:eq(2)").text().split(":")[0]);
		myFrom=$.trim($(dtrows[i]).find("td:eq(3)").text().split(":")[0]);		
		myQty=$(dtrows[i]).find("td:eq(4) input").val();		
		if(vendor==myVendor && mySku==sku && myFrom==fromLoc){ 
			totalQTy+=parseInt(myQty);
			//alert("total:"+totalQTy);
		}		 		 
	}	
	return totalQTy;
	//alert(myCart.length);  
}
function getParam(){
 	 
	/**split data*/ 
	var vendorSplit=$.trim($("#str_store").val()).split(":");
	var myVendor=$.trim(vendorSplit[0]);

	var storeSplit=$.trim($("#idFrom").val()).split(":");
	var fromStr=$.trim(storeSplit[0]);

	//var toLoc=$.trim($("#txtToLoc").val()).split(":");
	//var toStr=$.trim(toLoc[0]);
	
	//var skuSplit=$.trim($("#txt_sku").val()).split(":");
	//var mySku=$.trim(skuSplit[0]);

	//alert(myVendor+"-"+fromStr+"-");
	vndr=myVendor;
	frm=fromStr;
	//to=toStr;
	//skeu=;
}
function selectedSku(){
	$(".dialog-lookup-sku a ").click(function(){
		$(".dialog-lookup-skuSel").modal("show");
	});
}
function digitsOnlyForQty(tbl_sku){

	$(tbl_sku+" tbody").on("keydown","tr",function(event){	
		var nodeNam=(event.target.nodeName).toLowerCase();		
		//alert(nodeNam);
		var tbl=$(tbl_sku).DataTable();	
		var cel=$(this).index();		
	     
	  
			if(nodeNam=='input'){
				
				var colIndex=$(event.target).closest("td").index();
				//var myInput=$(event.target).val();
				//alert(myInput);
				if(colIndex==3){
					//var rows=$("#dataTables-lookup-branch5").dataTable().fnGetNodes(); 
					//var i=tbl.row(this).index(); 
					//var y=tbl.columns(this).index(); 
					if(event.which==39||event.which==37||event.which==9||event.which==13||event.which==8||event.which>=48&&event.which<=57 ||event.which>=96&&event.which<=105){
						   
					}	 
					else{				  			 			  
						  event.preventDefault();	
					}		
				}
			}

	});
	 
}
function tr_sku_click(tbl_sku){
	$(tbl_sku).delegate("tr","click",function(event){

		var nodeNam=(event.target.nodeName).toLowerCase();		
		 
		var tbl=$(tbl_sku).DataTable();
		var cel=$(this).index();		

		var rows=$(tbl_sku).dataTable().fnGetNodes(); 		
		var i=tbl.rows({order:'applied'}).nodes().indexOf(this);	
		var inputType=$(rows[i]).find("td:eq(0) input").prop('type').toLowerCase();
		if( (nodeNam=="input" || nodeNam=='i') && inputType	=='checkbox') {			

			if( $(rows[i]).find("td:eq(0) input").prop('checked')){
				if(!$(rows[i]).find("td:eq(3) input").length){
					var mySku=$(rows[i]).find("td:eq(1)").text();
					var myOnHand=parseInt(checkInventory(getVendorNum,getStorerNum,mySku));
					$(rows[i]).find("td:eq(4)").html('<small class="text-muted">On Hand : '+myOnHand+'</small>');
					if(myOnHand>0){
						$(rows[i]).find("td:eq(3)").append("<input type='text' style='text-align:right;width:90%;' tabIndex='6' class='form-control' maxlength='5' />");
						//$(rows[i]).find("td:eq(4)").html('<small class="text-muted">On Hand : '+myOnHand+'</small>');
						$(rows[i]).find("td:eq(3) input").focus();
						multArr.push([i," ",0]);	 
					}else{

						$(rows[i]).find("td:eq(0)").children("input").remove();
					}
					
					//multArr.push([i," ",0]);	 	
				}
				
			}else{
				$(rows[i]).find("td:eq(3)").children("input").remove();
				$(rows[i]).find("td:eq(4)").text('');
				for(var y=0;y<multArr.length;y++){
					if(multArr[y][0]==i){						
						multArr.splice(y,1);						
					}					
				}
					 	
			}
		    
		}		
		$(".mySelectedSku").text("("+multArr.length+")");
		if(multArr.length < 1){
			$(tbl_sku).dataTable().fnFilter("");
		}
	});
}
