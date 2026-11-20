$(function(){

	globalVendor=[];
	
	var txtCalendar="#delWin_date";
	var txt_vendor;
	var txt_qty;
	var txt_dept;
	var txt_sku	
	var txt_from;
	var txt_date;
	var flagBtn=0;
	//var getUrl=0;
	myCart=[];
	globalVendor=0;
	globalIdept=0;
	forTrfQty=[];
	heirarchy=[];
	 
	/**GET url data to trigger click of search ***/
	getUrl=getUrlData('trigger');

	var getTotal=getUrlData('total');
	 
	/* Set DATE  GREATHER THAN CURRENT DATE*/
	setpickDate(txtCalendar);
	 
	 /*********btn datepicker*****************/
	$("#btn_find_pullout").click(function(){
		var dtid="#dataTables-lookup-regular";
		_selectPulloutNum(dtid,1); 
	}); 
	/*********print preview*****************/
	$("#btn_print_pullout").click(function(){
		printTrf(1);
	});
	/*********is items picked up?****************/
	$("#btn_picked").click(function(){
		 var txt_pullout=$("#txt_pullout").val();
		 flagBtn=2;
		 if(isPulloutNumOpen(txt_pullout)==1){
		 	$(".dialog-alert").modal("show");
		 }else{
		 	alert("Pullout is not approved or already in Pick-Up Status");
		 }
	});
	/*********Clear PUllout ****************/
	$("#btn_clear_pullout").click(function(){
		$("#txt_pullout").val('');
		$("#btn_picked").addClass("hidden");
	});
	
    /*********btn datepicker*****************/
	$("#btn_date_delWin").click(function(){
		$("#delWin_date").datepicker("show");
	}); 

	/********Search Vendor******************/
	$("#btn_search_vendor").click(function(){	 
		var dtid="#dataTables-lookup-branchdvndr";
		_selectVendor(dtid); 			 
	}); 

	/*****Search from location***************/
	$("#btn_search_from").click(function(){
		var dtid="#dataTables-lookup-fromLoc";
		txt_vendor=$.trim($("#str_store").val());
		var vendor_num=_getVendor();
		if(txt_vendor!=''){
			_selectFrom(dtid,vendor_num); 		
		}else{
			alert("Please Select Vendor!");
	 	}
	});

	/**********Search Dept********************/
	$("#btn_search_dept").click(function(){
		var dtid="#dataTables-lookup-dept";		
		txt_vendor=$.trim($("#str_store").val());
		if(txt_vendor!=''){
			 _selectDept(dtid);
		}else{
			alert("Please Select Vendor!");
		}			
	});

	/**********Search SKu*********************/
	$("#btn_search_sku2").click(function(){
		var dtid="#dataTables-lookup-sku";
		txt_dept=$.trim($("#txtDept").val());
		if(txt_dept!=''){
			_selectSku(dtid,2); 	
		}else{
			alert("Please Select Department!");
		}		
	});

	/**********Clear qty*********************/
	$("#btn_clear_qty").click(function(){		 
		clearQty();
	}); 

	/* Clear vendor,from,to,sku ccn mf # */
	$("#btn_clear").click(function(){		 
		clearUpperFields();
	}); 

	/*****************Add to Cart******************************/
	$("#btnAddToCart").click(function(){	
		$("#loadingGif").show();		    
		txt_vendor=$.trim($("#str_store").val());
		txt_from=$.trim($("#idFrom").val());
		 
		txt_sku=$.trim($("#txt_sku").val());
		 
		txt_dept=$.trim($("#txtDept").val());
		txt_qty=$.trim($("#txt_qty").val());
		txt_date='__'; 
		//txt_date=$.trim($("#delWin_date").val()); 

		/**split data*/ 
		var vendorSplit=txt_vendor.split(":");
		var myVendor=$.trim(vendorSplit[0]);

		var storeSplit=txt_from.split(":");
		var myStore=$.trim(storeSplit[0]);

		//var skuSplit=txt_sku.split(":");
		//var mySku=$.trim(skuSplit[0]);
		//var ccn_mf=0;
		//var isTotalPullout=1;
		//if(isComplete(txt_vendor,txt_from,txt_sku,txt_dept,txt_qty,txt_date) && parseInt(txt_qty) > 0){
				
		if(isComplete(txt_vendor,txt_from)){
			 //var bool=confirm("Are you sure? Click cancel if not");
			 
			 /*var on_hand=checkInventory(myVendor,myStore,mySku);
			 var dtQty=checkTrfArrQty(myVendor,myStore,mySku,txt_qty); //datatable total qty
			 //alert(on_hand);
		 	// alert(dtQty);
			 if(on_hand > 0){
			 	if( (dtQty+parseInt(txt_qty)) > on_hand ){
			 		alert("Total Qty is over on hand !");
			 	}else{
			 		addToCart(txt_vendor,txt_from,txt_date,txt_sku,ccn_mf,txt_dept,txt_qty,isTotalPullout);
			 	}			 	
			 }else{
			 	alert(" Zero Inventory For your Entry! ");
			 }		
			 */
			 getInventory(myVendor,myStore);

		}else{
			alert("Please Fill-up All Fields!");
		}	
		$("#loadingGif").fadeOut("slow");	
	}); 
	/*******************CANCEL CART***************************/
	$("#btn_cancel").click(function(){		 
		 		 		
		 var bool=confirm("Are you sure? Click cancel if not");
		 if(bool){
		 	 cancelCart(1);
		 	 $(".grand_qty").text('');
		 }			 		 
	}); 

	/*******************CONFIRM***************************/
	$("#btn_accept").click(function(){		 		 		
		if($.trim($("#ApprovReason").val()) == ''){
			alert('Please Fill-up Remarks!');
			return 1;
		}	 	
		var bool=confirm("Are you sure? Click cancel if not");
	 	if(bool){
	 		var myTotal=loopDatatable(1); 
	 		 if(myTotal > 0){ 
	 		 	//alert("1");
	 		 	 var rtrn=sendToServer(3); //flag=2 for regular pulloutdf
	 		 	// alert(rtrn);
		 		 if(rtrn==1){
		 		 	clearUpperFields();
		 		 	cancelCart(1);
		 		 	$("#txt_qty").attr("disabled","disabled");
		 		 	alert("Success!");	 	
		 		 	var myTotal=_getTrfSession(3);
		 		 	window.location.href="total_pullout.php?total="+myTotal;	
		 		 }else{
		 		 	alert("Error : "+rtrn);
		 		 }
	 		 }else{
	 		 	  alert("Total of Zero Qty Requested!");
	 		 }
	 		
	 	}			 		 
	}); 
	/******************Click datatables**********************/
	$("#dataTables-lookup-regular tbody").delegate("tr","click",function(event){		
		
		var dtid="#dataTables-lookup-regular";
		var nodeNam=(event.target.nodeName).toLowerCase();		
		 
		var tbl=$(dtid).DataTable();
		 
		
		var rows=$(dtid).dataTable().fnGetNodes(); 
		var i=tbl.row(this).index(); 
		var rowNum=tbl.rows({order:'applied'}).nodes().indexOf(this);
		var data=tbl.cells(rowNum,'').render('display');
		var mypullout=$(rows[rowNum]).find("td:eq(0)").text();		
		var pckDate=$(rows[rowNum]).find("td:eq(5)").text();		
		var vp_pullout=$(rows[rowNum]).find("td:eq(0)").text();	
		var from=$(rows[rowNum]).find("td:eq(2)").text();	
		
		//alert(rowNum); 
		//alert(data.row(this).index());
		$("#txt_pullout").val(mypullout);
		$(".pckDate").html("VP Pullout # :"+vp_pullout+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;From : "+from+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+"Pick-Up Date : "+pckDate);
		$(this).css({"background-color":"#1E90FF"});
	 	$(".dialog-lookup-regular").modal("hide");

	 	/****check if approved already then enable hidden button "btn_picked***/
	 	if(isPulloutNumOpen(mypullout)==1){	 
	 		if(computePulloutDaysLeft(mypullout)==1){	 
		 		flagBtn=3;		 
		 		$("#btn_picked").removeClass("hidden");
		 		$(".dialog-alert .span_are_you_sure").text('If you click yes this will set status to Picked-up Status, Are Items Picked-up Already ?');	 		
				$(".dialog-alert").modal("show");
			}else{
				var rtn=updateInvRegular(mypullout,1);
				$(".dialog-alert2 .span_are_you_sure").html('"Forfeited" <br> Cannot Receive Items under "Terms and Conditions" already Property of LCC!');
				$(".dialog-alert2").modal("show");
				//alert('Cannot Receive Items under "Terms and Conditions" already Property of LCC!');
			}
	 	}else{
	 		$("#btn_picked").addClass("hidden");
	 	}
	 	$("#txt_dtls").focus();
	});
	/***************************For vendor********* *******************/
	/*********************Ternms and conditions***********************/
	$("#a_terms_cons").click(function(){
		$(".dialog-lookup-terms").modal("show");
	});

	/*********************Ternms and conditions***********************/
	$("#btn_confirm_pick_up").click(function(){
		flagBtn=1;
		var rtn=computeDate($('#delWin_date').val());		 
		if(rtn==-1){
			alert("Your Set Date is Over!");
			return 0;
		} 
		$("#dialog_terms_and_cond").show();
		var txt_pullout=$("#txt_pullout").val();
		if($.trim(txt_pullout)==''){
			alert("Please Select Pullout #");
		}
		else if($.trim($("#txt_dtls").val())==''){
			alert("Please Fill-up Details!");
		}else if($.trim($("#delWin_date").val())==''){
			alert("Please Select Pick-up Date!");
		}else{
			if(isPulloutNumOpen(txt_pullout)==0){
				$(".span_clarify").removeAttr("hidden");
				$(".dialog-alert").modal("show");
			}else{
				alert("please Select Pullout with Open Status!");
				clearAll();
			}
		}
	});
	
	/*******Click of Yes button** dialog alert****/
	$("#btn_yes").click(function(){	
		/***alert dialog for vendor to approve*****/  
		if(flagBtn==1){
			var rtn=vendordApproval();	 	
			if(rtn==1){
				alert("Success!");
				clearAll();
			}else{
				alert(rtn);
			}
			closeAlert();
			
		}else if(flagBtn==3){
			flagBtn=2;
			$("#btn_yes").trigger('click');
			return 0;
		}else{
			var txt_pullout=$("#txt_pullout").val();
			//alert(txt_pullout);
			//checkInv(txt_pullout);
			var rtn=updateInvRegular(txt_pullout,0);

			if(rtn==1){
				alert("Success!");
			}else{
				alert(rtn);
			}
			closeAlert();
		}
		//$("#btn_clear_pullout").trigger("click");
		$("#btn_print_pullout").trigger("click");
	});

	/*******Click of No button***dialog alert*/
	$("#btn_no").click(function(){		 
		closeAlert();
	});

	/************modal event********************* ***/
	$(".dialog-alert").on("shown.bs.modal", function () {
		
	});
	/************modal event********************* ***/
	$(".dialog-alert").on("hidden.bs.modal", function () {
		$(".dialog-alert .span_are_you_sure").text('Are you sure?');
	});
	/* Trigger button find **/
	if(getUrl){
	 	$("#btn_find_pullout").trigger("click");
	}
	if(getTotal!=''){
		$("#txt_pullout").val(getTotal);
	 	$("#btn_print_pullout").trigger("click");
	}

});
/*****END **********END*****************END***************8END********/


function isComplete(txt_vendor,txt_from){
	if(txt_vendor=='' || txt_from=='' ){
		return false;
	}
	return true;
}

function clearAll(){
	//$("#txt_pullout").val('');
	$(".pckDate").html('');
	$("#txt_dtls").val('');
	$("#delWin_date").val('');
}

function getInventory(vendor,store){
	//alert(vendor+"-"+store);
	var rtrn;
	$.ajax({
		type:'GET',
		async:false,
		dataType:'json',
		url:'control/getAllInventory.php', 
		data:{flag:1,vendor:vendor,store:store},
		success:function(out){
		//alert(out.length);
			if(out.length > 0){
				clearTotalDtaTable(1);
				for(var i=0;i<out.length;i++){
				 	//alert(out[i][0]+"_"+out[i][1]+"_"+out[i][2]);
					addToCartForTotal(out[i][0],out[i][1],vendor,store,out[i][2],out[i]['vendor_name'],out[i]['store_name'],1);						
				}
			}else{
				alert("No Inventory!");
			}	
		},
		error:function(out){
			alert(out);
		}
	});
	return rtrn;
}
function clearTotalDtaTable(isTotalPullout){
	var dtid=_returnDtaTable(isTotalPullout);
 	$(dtid).dataTable().fnDestroy();	

}
function addToCartForTotal(sku,descr,vendor,store,qty,vendor_name,store_name,isTotalPullout){
	//alert(sku+"_"+descr+"_"+vendor+"_"+store+"_"+qty);
	dtid=_returnDtaTable(isTotalPullout);
	//alert(dtid);
	var dtdata='';
	var vendor_w_name=vendor+' : '+ vendor_name;
	var store_w_name=store+' : '+store_name;
		if (!$.fn.DataTable.isDataTable(dtid)) {
			dtdata += '<tr>'
				+	'<td><center>'+sku+'</center></td>'
				+	'<td>'+descr+'</td>'
				+	'<td>'+vendor_w_name+'</td>'
				+	'<td>'+store_w_name+'</td>'				 
				//+	'<td>'+txt_qty+'</td>'
				+ 	'<td><input class="form-control" type="text" style="width:80px;text-align:right;" value="'+qty+'"/></td>'
				//+   '<td><button type="button" class="btn btn-default btn-circle"><i class="fa fa-times fa-f5"></i></button></td>'			 				
				//+	'<td>'+txt_date+'</td>'
				+'</tr>';
		}else{
			//$(dtid).dataTable().fnClearTable();													
			$(dtid).dataTable().fnAddData([
				'<center>'+sku+'</center>',descr,vendor_w_name,store_w_name,'<input class="form-control" type="text" style="width:80px;text-align:right;" value="'+qty+'"/>'//txt_date			 				
			])				
			//<td><button type="button" class="btn btn-default btn-circle"><i class="fa fa-times fa-f5"></i></button></td>
			$(dtid).dataTable().fnFilter("");
		}		
		//setToArrTrfQty(myVendor,myStore,mySku,txt_qty,checkInventory(getVendorNum(),getStorerNum(),getSkuNum()));
		if(dtdata!='')
			$("#ApprovReason").removeAttr("disabled");
		$(dtid+" tbody").html(dtdata);		
		_dataTable(dtid);
		//$(".grand_qty").text("Qty : "+totalTrfQtyInput(0));
		isDataTableHasRows(isTotalPullout);
 
}