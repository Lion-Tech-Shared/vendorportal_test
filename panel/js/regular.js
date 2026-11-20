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
	var isForfeited=0;
	myCart=[];
	globalVendor=0;
	globalIdept=0;
	globalClas=0;
	forTrfQty=[];
	heirarchy=[];
	multArr=[];
	multArrSku=[];
	
	gGroupIdSplit = getGid();		 
	splitData=gGroupIdSplit.split("-");
	gId = splitData[0];
	gCanApprove = splitData[1];
	gCanReceive = splitData[2];
	gIsCoor = splitData[3];
	gCanVwOrdrNoti = splitData[4];
	gCanVwRcvNoti = splitData[5];
	gIsPa = splitData[6];

	/**GET url data to trigger click of search*/
	getUrl=getUrlData('trigger');

	var getRegular=getUrlData('regular');

	/* Set DATE  GREATHER THAN CURRENT DATE*/
	setpickDate(txtCalendar);

	 /*********btn datepicker*******************/
	$("#btn_find_pullout").click(function(){
		var dtid="#dataTables-lookup-regular";
		_selectPulloutNum(dtid,0); 
	}); 
	/*********print preview*****************/
	$("#btn_print_pullout").click(function(){
		printTrf(0);
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
			$(".mySku_msg").text('Please Select Vendor!');
			showMiniDialog();
	 	}
	});

	/**********Search Dept********************/
	$("#btn_search_dept").click(function(){
		var dtid="#dataTables-lookup-dept";		
		var mystr=$.trim($("#idFrom").val());
		if(mystr!=''){
			 _selectDept(dtid);
		}else{
			$(".mySku_msg").text('Please Select location!');
			showMiniDialog();
		}			
	});

	/**********Search SKu*********************/
	$("#btn_search_sku2").click(function(){
		var dtid="#dataTables-lookup-sku";
		txt_dept=$.trim($("#txtDept").val());
		if(txt_dept!=''){
			_selectSku(dtid,1); 	
		}else{			
			$(".mySku_msg").text('Please Select Department!');
			showMiniDialog();
		}		
	});

	/**********Clear qty*********************/
	$("#btn_clear_qty").click(function(){		 
		clearQty();
	}); 

	/* Clear vendor,from,to,sku ccn mf # */
	$("#btn_clear").click(function(){		 
		//alert(globalVendor);
		clearUpperFields();
	}); 

	/*****************Add to Cart******************************/
	$("#btnAddToCart").click(function(){		    
		txt_vendor=$.trim($("#str_store").val());
		txt_from=$.trim($("#idFrom").val());
		  
		txt_sku=$.trim($("#txt_sku").val());
		 
		txt_dept=$.trim($("#txtDept").val());
		txt_qty=$.trim($("#txt_qty").val());
		txt_date='___';
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
		if(isComplete(txt_vendor,txt_from,txt_sku,txt_dept,txt_qty,txt_date) && parseInt(txt_qty) > 0){
			 //var bool=confirm("Are you sure? Click cancel if not");
			 
			 var on_hand=checkInventory(myVendor,myStore,mySku);
			 var dtQty=checkTrfArrQty(myVendor,myStore,mySku,txt_qty); //datatable total qty
		 
			 if(on_hand > 0){
			 	if( (dtQty+parseInt(txt_qty)) > on_hand ){
			 		alert("Total Qty is over on hand !");
			 	}else{
			 		addToCart(txt_vendor,txt_from,txt_date,txt_sku,ccn_mf,txt_dept,txt_qty,isTotalPullout);
			 		$("#ApprovReason").removeAttr("disabled");
			 	}			 	
			 }else{
			 	alert(" Zero Inventory For your Entry! ");
			 }			 
		}else{
			alert("Please Fill-up All Fields!");
		}		
	}); 
	/*******************CANCEL CART***************************/
	$("#btn_cancel").click(function(){		 
		 		 		
		 var bool=confirm("Are you sure? Click cancel if not");
		 if(bool){
		 	 cancelCart(0);
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
	 		 var myTotal=loopDatatable(0); 
	 		 /*summarizeSku();
	 		 alert(CheckReqQtyVsInv());
	 		  myCart=[]; 
 
	 		  return 0;*/ 
	 		 if(myTotal > 0){
	 		 	 var myRegular=_getTrfSession(2);
	 		 	 var rtrn=sendToServer(2); //flag=2 for regular pulloutdf
		 		 if(rtrn==1){
		 		 	clearUpperFields();
		 		 	cancelCart(0);
		 		 	$("#txt_qty").attr("disabled","disabled");
		 		 	alert("Successfully submitted!");	 			 		 	
		 		 	window.location.href="regular_pullout.php?regular="+myRegular;	
		 		 }else{
		 		 	alert("Error : "+rtrn);
		 		 }
	 		 }else{
	 		 	 alert("Total of Zero Qty Requested!");
	 		 }
	 		myCart=[]; 
	 	}
	 	//alert(myCart.length);			 		 
	}); 
	/******************Click datatables***********************/
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
		//alert(computePulloutDaysLeft(mypullout));
		$("#txt_pullout").val(mypullout);
		$(".pckDate").html("VP Pullout # :"+vp_pullout+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;From : "+from+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"+"Pick-Up Date : "+pckDate);
		$(this).css({"background-color":"#1E90FF"});
	 	$(".dialog-lookup-regular").modal("hide");

	 	/* var grpId=getGid();
		 var splitStr=grpId.split("-");
		 var groupId=splitStr[0];*/
		 var groupId=gId;
		 /*var canApprove=splitStr[1];
		 var canReceive=splitStr[2]*/
		 if(groupId==2){
		 	$(".mySku_msg").text('Please Confirm Pick-up Schedule');
			showMiniDialog();
		 }
		 var isPa=gIsPa;
		 if(isPa!=1){
		 	return	0;
		 }
	 	/****check if approved already then enable hidden button "btn_picked***/
	 	if(isPulloutNumOpen(mypullout)==1){	 	
	 		/*if days left is within alloted date return 1*/
	 		if(computePulloutDaysLeft(mypullout)==1){	 	 		 
		 		flagBtn=3;		 		 		 
				
 				$("#btn_picked").removeClass("hidden");
 				$(".dialog-alert .span_are_you_sure").text('If you click yes this will set status to Picked-up Status, Are Items Picked-up Already ?');	 		
				$(".dialog-alert").modal("show");			 
			}
			else{
				var rtn=updateInvRegular(mypullout,1);
				//alert(rtn);
				$(".dialog-alert2 .span_are_you_sure").html('"Forfeited" <br> Cannot Receive Items under "Terms and Conditions" already Property of LCC!');
				$(".dialog-alert2").modal("show");
				//alert('Cannot Receive Items under "Terms and Conditions" already Property of LCC!');
			}
	 	}else{
	 		$("#btn_picked").addClass("hidden");
	 	}

	 	$("#txt_dtls").focus();
	});
	var trfTbl="#dataTables-regular-pullout";
	$(trfTbl+" tbody").on("keydown","td input:text",function(event){				 
		if(event.which==39||event.which==37||event.which==9||event.which==13||event.which==8||event.which>=48&&event.which<=57 ||event.which>=96&&event.which<=105){		 
			var nodeNam=(event.target.nodeName).toLowerCase();					
			var tbl=$(trfTbl).DataTable();	
			var cel=$(this).index();		
			var i=tbl.row(this).index(); 
			if(nodeNam=='input'){		
			 					
				var rows=$(trfTbl).dataTable().fnGetNodes(); 
				
				var rowNum=tbl.rows({order:'applied'}).nodes().indexOf(this);				
				var qty=$(rows[rowNum]).find("td:eq(4) input").val();
				//$(rows[rowNum]).find("td:eq(4) input").val(Math.abs(qty));			 						
				var colIndex=$(event.target).closest("td").index();
				 
				if(colIndex==4){													 					
					//$(".grand_qty").text("Qty : "+ parseInt(totalTrfQtyInput(0)));
					//alert(getTotalOnHandArr(i,0));
				}
			}
		}	 
		else{				  			 			  
			  event.preventDefault();	
		}			 
	});
	$(trfTbl+" tbody").on("keyup","tr",function(event){	
		var nodeNam=(event.target.nodeName).toLowerCase();		
		//alert(nodeNam);
		var tbl=$(trfTbl).DataTable();	
		var cel=$(this).index();		

		if(nodeNam=='input'){			
			//var colIndex=$(event.target).closest("td").index();			 
			var rows=$(trfTbl).dataTable().fnGetNodes(); 
			var i=tbl.row(this).index(); 
			//alert("i:"+i);
			var rowNum=tbl.rows({order:'applied'}).nodes().indexOf(this);
			//var rowNum=i;
			var qty=$(rows[rowNum]).find("td:eq(4) input").val();
			$(rows[rowNum]).find("td:eq(4) input").val(Math.abs(qty));			 		
			
			var colIndex=$(event.target).closest("td").index();
			if(colIndex==4){													 
				
				$(".grand_qty").text("Qty : "+ parseInt(totalTrfQtyInput(0)));
			}
		}
	});
	/*******Datatable Event***********/
	$("#txt_qty").keydown(function(e){				     
			if(e.which==39 || e.which==37 || e.which==9 || e.which==13 || e.which==8 || e.which>=48 && e.which<=57 || e.which>=96 && e.which<=105){
				 
			}
			else{
				e.preventDefault();
			}
	});
	 

	/*********************Ternms and conditions************************/
	$("#a_terms_cons").click(function(){
		//$(".dialog-lookup-terms #dialog_terms_and_cond").hide();
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
		//$("#dialog_terms_and_cond").show();
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
				$("#dialog_terms_and_cond").hide();
				$(".dialog-alert .terms_con_cap").hide();
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
				$(".mySku_msg").text('Success');
				showMiniDialog();
				clearAll();
			}else{
				alert(rtn);
			}
			closeAlert();

		}else if(flagBtn==3){
			flagBtn=2;
			$("#btn_yes").trigger('click'); 
			return 0;
		}else if(flagBtn==2){
			var txt_pullout=$("#txt_pullout").val();
			//alert(txt_pullout);
			//checkInv(txt_pullout);
			var rtn=updateInvRegular(txt_pullout,0);

			if(rtn==1){				 
				$(".mySku_msg").text('Success');
				showMiniDialog();
			}else{
				alert(rtn);
			}
			closeAlert();
		}else{
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

	/* Trigger button find **/
	if(getUrl){
	 	$("#btn_find_pullout").trigger("click");
	}
	if(getRegular!=''){
		$("#txt_pullout").val(getRegular);
	 	$("#btn_print_pullout").trigger("click");
	}
	var tbl_sku='#dataTables-lookup-sku';
	digitsOnlyForQty(tbl_sku);
	tr_sku_click(tbl_sku);
	validateInputQty(tbl_sku);
	selSku();
	_submit();
	$(".dialog-lookup-sku").on("hidden.bs.modal",function(){  	
  			multArr=[];
  			multArrSku=[];	 
  			$(".mySelectedSku").text("("+multArrSku.length+")");  			
	}).on("shown.bs.modal",function(){
		  
	});

});
/*****END **********END*****************END***************8END********/
 

function isComplete(txt_vendor,txt_from,txt_date,txt_sku,txt_dept,txt_qty){
	if(txt_vendor=='' || txt_from==''  || txt_sku=='' || txt_dept=='' || txt_qty==''){
		return false;
	}
	return true;
}

function clearAll(){
	//$("#txt_pullout").val('');
	$(".pckDate").html('');
	$("#txt_dtls").val('');
}


