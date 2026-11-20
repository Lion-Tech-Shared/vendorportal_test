$(function() {   
	var txt_vendor;
	var txt_from;
	var txt_to;
	var txt_sku;
	var ccn_mf;
	var txt_dept;
	var txt_qty;
	var getTrf='';
	var vView='';
	var sd = 0;
	myFlagPrt = 0;
    myCart=[];
	globalVendor=0;
	globalIdept=0;
	globalClas=0;
	forTrfQty=[];
	getUrl = 0;
	multArr=[];
	multArrSku=[];
	heirarchy=[];
 	ccnMf=0;
    
 	vndr=0,frm=0,to=0,skeu=0;

 	gGroupIdSplit =getGid();		 
	 splitData=gGroupIdSplit.split("-");
	 gId = splitData[0];
	 gCanApprove = splitData[1];
	 gCanReceive = splitData[2];
	 gIsCoor = splitData[3];
	 gCanVwOrdrNoti = splitData[4];
	 gCanVwRcvNoti = splitData[5];
	 gIsPa = splitData[6];
	/********/
	//alert("asdf");
	var name='trf';
	var vViewNam='vView';
	var trigger = 'trigger';
	var results=new RegExp('[\?&]' + name +'=([^&#]*)').exec(window.location.href);	
	var vViewResult=new RegExp('[\?&]' + vViewNam +'=([^&#]*)').exec(window.location.href);	
	var triggerArr=new RegExp('[\?&]' + trigger +'=([^&#]*)').exec(window.location.href);	
	if(results && results!=''){
		getTrf=results[1];
	}	
	 
	if(vViewResult && vViewResult!=''){
		vView=vViewResult[1];
		 
	}	

	if(triggerArr && triggerArr!=''){
		getUrl = triggerArr[1];
		 
	}
	name = 'sd';
	if(results && results!=''){
		sd=results[1];
	}	
	/* search trf attached to user */
	$("#btn_find_trf").click(function(){
		var grpId=getGid();

	 

		//var splitStr=grpId.split("-");
		/*var groupId=splitStr[0];
		var canApprove=splitStr[1];
		var canReceive=splitStr[2]
		var isCoor=splitStr[3];*/
		//alert(splitStr);
		var groupId=gId;
		var canApprove=gCanApprove;
		var canReceive=gCanReceive
		var isCoor=gIsCoor;

		var dtid="#dataTables-lookup-trf";
		var flag=1;
		if(isCoor == 1 || groupId == 2 || canApprove == 1){
			flag=2;
			//updCoorNoti();
		}		

		_selectTrf(dtid,flag); 		
	});

	$("#btn_print_trf").click(function(){
		printTrf(vView);
	});
	$("#btn_update").click(function(){
		updateInventory();
	});
	/*disable remarks*/
	$("#ApprovReason").attr("disabled","disabled");	 
	//alert("asdf");
	/* search vendor attached to user */
	$("#btn_search_store").click(function(){
		var dtid="#dataTables-lookup-branchdvndr";
		_selectVendor(dtid); 		
	});
	/* search from location */
	$("#btn_search_from").click(function(){
		var dtid="#dataTables-lookup-fromLoc";
		txt_vendor=$.trim($("#str_store").val());
		var vendor_num=_getVendor();
		if(txt_vendor!=''){
			_selectFrom(dtid,vendor_num); 		
		}else{
			//alert("Please Select Vendor!");
			clear_timeout();
			$(".mySku_msg").text("Please Select Vendor!");
			showMiniDialog();
	 	}
	});
	/* search To location   */ 
	$("#btn_search_to").click(function(){
		var dtid="#dataTables-lookup-toLoc";		
		txt_from=$.trim($("#idFrom").val());
		if(txt_from!=''){
			txt_vendor=$.trim($("#str_store").val());
			var txtSplit=txt_vendor.split(":");
			var vendor_num=$.trim(txtSplit[0]);		

			var txtStoreSplit=txt_from.split(":");
			var store_num=$.trim(txtStoreSplit[0]);
			_selectTo(dtid,vendor_num,store_num); 	
		}else{
			clear_timeout();
			$(".mySku_msg").text("Please Select From Location!");
			showMiniDialog();			 
		}			
	});
	/* search Dept */
	$("#btn_search_dept").click(function(){
		var dtid="#dataTables-lookup-dept";		
		txt_to=$.trim($("#txtToLoc").val());
		if(txt_to!=''){
			 _selectDept(dtid);
		}else{			 
			clear_timeout();
			$(".mySku_msg").text("Please Select To Location!");
			showMiniDialog();	
		}			
	});
	/* search SKu */
	$("#btn_search_sku2").click(function(){
		var dtid="#dataTables-lookup-sku";
		var ccn_mf=$.trim($("#ccn_mf").val());
		txt_dept=$.trim($("#txtDept").val());
		if(txt_dept!='' && ccn_mf!=''){
			if(ccnMf!=0 && ccnMf!=ccn_mf){
				//alert("ccnMF:"+ccnMf+"  ccn_mf:"+ccn_mf);
				clear_timeout();
				$(".mySku_msg").text("CCN MF not the same");
				showMiniDialog();	
			}else{
				_selectSku(dtid); 	
			}
		}else{
			clear_timeout();
			$(".mySku_msg").text(txt_dept==''?"Please Select Department!":"Please Input CCN MF!");
			showMiniDialog();			
		}		
	});
	/*****************Add to Cart******************************/
	$("#btnAddToCart").click(function(){		    
		txt_vendor=$.trim($("#str_store").val());
		txt_from=$.trim($("#idFrom").val());
		txt_to=$.trim($("#txtToLoc").val());
		txt_sku=$.trim($("#txt_sku").val());
		ccn_mf=$.trim($("#ccn_mf").val());
		txt_dept=$.trim($("#txtDept").val());
		txt_qty=$.trim($("#txt_qty").val());
		 
		/**split data*/ 
		var vendorSplit=txt_vendor.split(":");
		var myVendor=$.trim(vendorSplit[0]);

		var storeSplit=txt_from.split(":");
		var myStore=$.trim(storeSplit[0]);

		var skuSplit=txt_sku.split(":");
		var mySku=$.trim(skuSplit[0]);

		if(isComplete(txt_vendor,txt_from,txt_to,txt_sku,ccn_mf,txt_dept,txt_qty) && parseInt(txt_qty) > 0){
			 //var bool=confirm("Are you sure? Click cancel if not");
			 var on_hand=checkInventory(myVendor,myStore,mySku);
			 var dtQty=checkTrfArrQty(myVendor,myStore,mySku,txt_qty); //datatable total qty
			// alert(dtQty+parseInt(txt_qty));
			 if(on_hand > 0){
			 	if( (dtQty+parseInt(txt_qty)) > on_hand ){
			 		//alert("");
			 		clear_timeout();
			 		$(".mySku_msg").text('Total Qty is over on hand !');
			 		showMiniDialog();
			 	}else{
			 		addToCart(txt_vendor,txt_from,txt_to,txt_sku,ccn_mf,txt_dept,txt_qty);
			 		$("#ApprovReason").removeAttr("disabled");
			 	}			 	
			 }else{
			 	//alert("  ");
			 	clear_timeout();
			 	$(".mySku_msg").text('Zero Inventory For your Entry!');
				showMiniDialog();
			 }			   
		}else{
			//alert("");
			clear_timeout();
			$(".mySku_msg").text('Please Fill-up All Fields!');
			showMiniDialog();			    
		}		
	}); 
	/*******************CANCEL CART***************************/
	$("#btn_cancel").click(function(){		 
		 		 		
		 var bool=confirm("Are you sure? Click cancel if not");
		 if(bool){
		 	 cancelCart();
		 }			 		 
	});  
	/*******************CONFIRM***************************/
	$("#btn_accept").click(function(){		 		 		 	
		if($.trim($("#ApprovReason").val()) == ''){
			alert("Please Fill-up remarks");
			return 1;
		}
		var bool=confirm("Are you sure? Click cancel if not");
		var myTrf;
	 	if(bool){
	 		 var tblLoopRtn=loopDatatable();
	 		 if(tblLoopRtn>0){
	 		 	 var rtrn=sendToServer();
		 		 if(rtrn==1){
		 		 	clearUpperFields();
		 		 	cancelCart();
		 		 	$("#txt_qty").attr("disabled","disabled");
		 		 	myTrf=_getTrfSession(1);	 		 	 
		 		 	//alert("!");	
		 		 	clear_timeout();
		 		 	$(".mySku_msg").text('Success! Please Update Inventory');
					showMiniDialog(); 	
					setTimeout(function(){
						window.location.href="transfer.php?trf="+myTrf+"&sd=1";	
					},3000);
		 		 	
		 		 }else{
		 		 	//alert("Error : "+rtrn);
		 		 	clear_timeout();
		 		 	$(".mySku_msg").text(rtrn);
					showMiniDialog(); 
		 		 }
	 		 }else{
	 		 	//alert("");
	 		 	clear_timeout();
	 		 	$(".mySku_msg").text('Total of Zero Qty Requested!');
				showMiniDialog(); 	
	 		 }
	 		myCart=[]; 
	 	}
	 	//alert(myCart.length);			 		 
	}); 

	/* Clear vendor,from,to,sku ccn mf # */
	$("#btn_clear").click(function(){	
		//alert(globalVendor);
		clearUpperFields();
		//alert(_getTrfSession());
	}); 

	/* Clear vendor,from,to,sku ccn mf # */
	$("#btn_clear_qty").click(function(){		 
		clearQty();
	}); 
	/********** Clear ccn mf************ */
	$("#btn_clear_ccnmf").click(function(){		 
		clearCcnMf();
	}); 
	


	/******************Key Event******************************/
	$("#str_store,#idFrom,#txtToLoc,#txt_sku,#txtDept,#txt_trf").keydown(function(event){
		event.preventDefault();		 
	});
	$("#str_store,#idFrom,#txtToLoc,#txt_sku").bind('cut copy paste',function(e){
		e.preventDefault();
		//alert("Asdf");	 
	});
	$("#str_store,#idFrom,#txtToLoc,#txt_sku").change(function(e){
		e.preventDefault();
		//alert("Asdf2");
	});
	$("#txt_qty").on("keydown",function(e){				 	
		if(e.which==39||e.which==37||e.which==9||e.which==13||e.which==8||e.which>=48&&e.which<=57 ||e.which>=96&&e.which<=105){

		}	 
		else{				  			 			  
			  e.preventDefault();	
		}	
		 
	});

	/*****************CLICK OF DATATABLES*****************************************/
	$("#dataTables-cart-transfer tbody").on("click","tr",function(event){		
		var dtid="#dataTables-cart-transfer";
		var nodeNam=(event.target.nodeName).toLowerCase();		
		 
		var tbl=$(dtid).DataTable();
		var cel=$(this).index();
		var rw=$(this).closest("tr").get(0);
		
		//var rows=$("#dataTables-lookup-branch5").dataTable().fnGetNodes(); 
		//var stockNum=$(rows[i]).find("td:eq(4) input").val();
		var i=tbl.row(this).index(); 
		if(nodeNam=="button" || nodeNam=='i'){	
			//$(this).remove();
			//$("tr:eq("+i+")").remove();
			tbl.fnDeleteRow(tbl.fnGetPosition(i));
		}
	});
	$(".dialog-lookup-trf tbody").delegate("tr","click",function(event){		
		
		var dtid="#dataTables-lookup-trf";
		var nodeNam=(event.target.nodeName).toLowerCase();		
		 
		var tbl=$(dtid).DataTable();
		 
		
		var rows=$(dtid).dataTable().fnGetNodes(); 
		var i=tbl.row(this).index(); 
		var rowNum=tbl.rows({order:'applied'}).nodes().indexOf(this);
		var data=tbl.cells(rowNum,'').render('display');
		var myTrfNum=$(rows[rowNum]).find("td:eq(0)").text();		
		
		//alert(rowNum); 
		//alert(data.row(this).index());
		$("#txt_trf").val(myTrfNum);
		
		$(this).css({"background-color":"#1E90FF"});
	 	$(".dialog-lookup-trf").modal("hide");
	});
	var trfTbl="#dataTables-cart-transfer";
	$(trfTbl+" tbody").on("keydown","td input:text",function(e){				 
		if(e.which==39||e.which==37||e.which==9||e.which==13||e.which==8||e.which>=48&&e.which<=57 ||e.which>=96&&e.which<=105){		 
		}	 
		else{				  			 			  
			  e.preventDefault();	
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

			var rowNum=tbl.rows({order:'applied'}).nodes().indexOf(this);
			//var rowNum=i;
			var qty=$(rows[rowNum]).find("td:eq(5) input").val();
			$(rows[rowNum]).find("td:eq(5) input").val(Math.abs(qty));			 		
			//alert("i:"+i +" rown:"+rowNum);
			//myCart.push([mySku,myVendor,myFrom,myTo,myQty,ccn_mf]);
		}
	});

	if($.trim(getTrf)!=''){
		$("#txt_trf").val(getTrf);
	 	$("#btn_print_trf").trigger("click");
	}
	if(vView==1){		 
		var dtid="#dataTables-lookup-trf";
		_selectTrf(dtid,2);
	}
	var skuDtid='#dataTables-lookup-sku';
	tr_sku_click(skuDtid);
	digitsOnlyForQty(skuDtid);
	validateInputQty(skuDtid);
	selSku();
	_submit();

	$(".dialog-lookup-sku2").on("hidden.bs.modal",function(){  	
  			multArr=[];
  			multArrSku=[];	 
  			$(".mySelectedSku").text("("+multArrSku.length+")");  			
			}).on("shown.bs.modal",function(){
		  
	});

	if(getUrl){
		$("#btn_find_trf").trigger('click');
	}
}); 
/* END END END END END END END END END END END END END END END END END END END END END END END ENDEND END END  */

/* functions functions functions functions functions functions functions functions functions functions functions */ 

function _submit(){
	$(".dialog-lookup-sku2 #btn_submit_sku").click(function(){
		 if(multArr.length>0){
		 	var price;
			var priceWithNoComma;
			var sku_qty;
			multArrSku=[];
			
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
			$(".dialog-lookup-sku2").modal("hide");
			if(multArr.length > 0){
				$(".mySku_msg").text('Your Selection was added to Cart!');
		 		showMiniDialog();		 		 
			}
			 
			for(var x=0;x<multArr.length;x++){				
				var mySku=$(rows[multArr[x][0]]).find("td:eq(1)").text();
				var mySkuDesc=$(rows[multArr[x][0]]).find("td:eq(2)").text();				
				var mySkuQty=$.trim($(rows[multArr[x][0]]).find("td:eq(3) input:text").val());				
				multArrSku.push([mySku,mySkuDesc,mySkuQty]);		
			}

			$(dtid).dataTable().fnFilter("");			 			
			dtid='#dataTables-cart-transfer';	
			if (!$.fn.DataTable.isDataTable(dtid)) {	
				var dtdata = '';		
			    for(var i=0;i<multArrSku.length;i++){					   		 											
						dtdata += '<tr >'
								+	'<td width="20">'+multArrSku[i][0]+'</td>'
								+	'<td width="120">'+multArrSku[i][1]+'</td>'
								+ 	'<td width="20">'+$("#str_store").val()+'</td>'												
								+ 	'<td width="20">'+$("#idFrom").val()+'</td>'
								+ 	'<td width="20">'+$("#txtToLoc").val()+'</td>'
								+ 	'<td><input class="form-control" type="text" style="width:80px;text-align:right;" value="'+multArrSku[i][2]+'"/></td>'
								+ 	'<td width="20">'+$("#ccn_mf").val()+'</td>'
								+'</tr>';	
					//total_amt+=(parseFloat(priceWithNoComma) * parseInt(out[i]['skuQty']));
					//total_qty+=parseInt(out[i]['skuQty']);	
					//alert(total_amt);
				}			 
					$(dtid+" tbody").html(dtdata);
					$(dtid).dataTable({
						"columnDefs": [{ "width": "150px", "targets": 0 }],
						"bLengthChange": false,
						"iDisplayLength":3,									    
						"bDestroy": true,
						"oLanguage":{
							"sSearch":"Search"
						}		
					});																			
					 					
			}
			else {
				 
				 for(var i=0;i<multArrSku.length;i++){		
			   		//price=out[i]['cost'];
					//priceWithNoComma=out[i]['costWithNoComma'];					 
					
					$(dtid).dataTable().fnAddData([
						''+multArrSku[i][0]+'',multArrSku[i][1],$("#str_store").val(),$("#idFrom").val(),$("#txtToLoc").val()
						,'<input class="form-control" type="text" style="width:80px;text-align:right;" value="'+multArrSku[i][2]+'"/>',$("#ccn_mf").val()
						//out[i]["descr"],'<input class="form-control" id='+dta+' type="text" style="height:25px;width:50%;"/>'						 
					])							 					 		 
					//total_amt+=(parseFloat(priceWithNoComma) * parseInt(out[i]['skuQty']));
					//total_qty+=parseInt(out[i]['skuQty']);	
				}									 
					
					$(dtid).dataTable().fnFilter("");
			}
			heirarchy.push([globalIdept,globalClas]);
			ccnMf=$("#ccn_mf").val();
	 
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
function tr_sku_click(tbl_sku){
	$(tbl_sku).delegate("tr","click",function(event){

		
		//alert(vndr+"-"+frm+"-"+to);
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
					
					//alert(dataTblQty);
					$(rows[i]).find("td:eq(4)").html('<small class="text-muted">On Hand : '+myOnHand+'</small>');
					if(myOnHand>0){
						$(rows[i]).find("td:eq(3)").append("<input type='text' style='text-align:right;width:100%;' tabIndex='6' class='form-control' maxlength='5' />");
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

function selSku(){
	var dtid='#dataTables-lookup-skuSel';
	$(".dialog-lookup-sku2 a").click(function(){
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
					 //re assign some global variables
				     getParam();
					 var dataTblQty=getOverAllQty(vndr,frm,to,mySku);

					 var myOnHand=checkInventory(getVendorNum(),getStorerNum(),mySku); 
					 $(event.target).val(Math.abs($(event.target).val()));
					 var myModInut=parseInt($(event.target).val());
					 if((myModInut+dataTblQty) > myOnHand){
					 	var myTxt=dataTblQty>0?"Your Input plus the current Qty In table":"Quantity";
					 	 $(".mySku_msg").text(myTxt+" Is greater than on hand!");
		 				 showMiniDialog();	
		 				 $(event.target).val('');		
		 				 return 0;
					 }
					 /*if(myModInut>myOnHand){
						 $(event.target).val('');		
						 $(".mySku_msg").text("Quantity Is greater than on hand!");
		 				 showMiniDialog();				  	 
					 }*/					 
					 //var rowNum=tbl.rows({order:'applied'}).nodes().indexOf(this);
					 //alert(rowNum);	
				}
			}

	});
	 
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
			//alert("Error : "+out);
			$(".mySku_msg").text("Error : "+out);
		 	showMiniDialog();
		}
	});
	return on_hand;
}

function printTrf(flag){
	var txt_trf=$("#txt_trf").val();

	if(txt_trf!=''){
		//alert(gId+":"+gCanApprove+":"+gIsCoor);
		//$(".dialog-lookup-print #dr-rpt").load("generate_sale/dr_rpt.php");
		$(".dr-rpt").attr({'src': 'generate_sale/trf_rpt.php?trf='+txt_trf+'&param='+gId+'&param2='+gCanApprove+'&param3='+gIsCoor+"&sd="+myFlagPrt});

		$(".dialog-lookup-print").ready(function() {
			setTimeout(function(){
				$(".dialog-lookup-print").modal("show");	
			},100);
			
		}); 
		
	}
}
function updateInventory(){
	var txt_trf=$("#txt_trf").val();

	if(txt_trf!=''){
		if(ifOpenStatus(txt_trf)==0){
			var bool=confirm("Are you sure?Click cancel if not");
			if(bool){
				//alert("after bool");
				if(setToTransferStatus(txt_trf)==1){

					alert("Updated to Transfer Status");
					myFlagPrt = 1;
					$("#btn_print_trf").trigger('click');
				}
			}else{
				alert("not true");
			}	
		}else{
			//alert("Cannot Update If Status is not Open!");			
			clear_timeout();
			$(".mySku_msg").text("Cannot Update If Status is not Open!");
		 	showMiniDialog();
		}
			
	}else{
		//alert("Please Select VP Trf #!");
		clear_timeout();
		$(".mySku_msg").text("Please Select VP Trf #!");
		showMiniDialog();
	}
}
function ifOpenStatus(txt_trf){
	var rtn=-1;
	$.ajax({
		type:'GET',
		dataType:'text',
		async:false,
		url:'control/checkIfTrfOpenStatus.php',
		data:{flag:1,txt_trf:txt_trf},
		success:function(out){
			if(out==0){
				rtn=out;
				//alert(rtn);
			}
		},
		error:function(out){
			alert("Error : "+out);
		}
	});
	return rtn;
}
function setToTransferStatus(txt_trf){ 
	var rtn=0;
	$.ajax({
		type:'GET', 
		dataType:'text',
		async:false,
		url:'control/setToTransferStatus.php',
		data:{flag:1,txt_trf:txt_trf},
		success:function(out){
			//alert("setTOTrf:"+out);
			if(out==0){
				rtn=1;
			}
		},
		error:function(out){
			alert("Error : "+out);
		}
	});
	return rtn;
}
function clearQty(){
	$("#txt_qty").val('');
}
function clearCcnMf(){
	$("#ccn_mf").val('');
}
/***for btn accept************/
function loopDatatable(){
	var dtid="#dataTables-cart-transfer";
	var dtrows=$(dtid).dataTable().fnGetNodes();
	var mySku;
	var myVendor;
	var myFrom;
	var myTo; 
	var myQty;
	var ccn_mf;
	var totalQTy=0;
	for(var i=0;i<dtrows.length;i++){		
		mySku=$(dtrows[i]).find("td:eq(0)").text();
		myVendor=$(dtrows[i]).find("td:eq(2)").text();
		myFrom=$(dtrows[i]).find("td:eq(3)").text();
		myTo=$(dtrows[i]).find("td:eq(4)").text();
		myQty=$(dtrows[i]).find("td:eq(5) input").val();
		ccn_mf=$(dtrows[i]).find("td:eq(6)").text();
		//alert(mySku);
		//if(parseInt(qty)>0){
			//my_arr.push([sku,stock_num,qty]);
		//}
		totalQTy+=myQty;
		/**************0******1*******2*****3*****4****5***************/
		myCart.push([mySku,myVendor,myFrom,myTo,myQty,ccn_mf]);
	}	
	return totalQTy;
	//alert(myCart.length);  
}
/***Get Qty Even from Your cart*****/
function getOverAllQty(vendor,fromLoc,toLoc,sku){
	//alert(vendor+"-"+fromLoc+"-"+toLoc+"-"+sku);
	var dtid="#dataTables-cart-transfer";

	if (!$.fn.DataTable.isDataTable(dtid)) {	
		return 0;
	}
	var dtrows=$(dtid).dataTable().fnGetNodes();
	var mySku;
	var myVendor;
	var myFrom;
	var myTo; 
	var myQty;
	var ccn_mf;
	var totalQTy=0;
	for(var i=0;i<dtrows.length;i++){		
		mySku=$(dtrows[i]).find("td:eq(0)").text();
		myVendor=$.trim($(dtrows[i]).find("td:eq(2)").text().split(":")[0]);
		myFrom=$.trim($(dtrows[i]).find("td:eq(3)").text().split(":")[0]);
		myTo=$.trim($(dtrows[i]).find("td:eq(4)").text().split(":")[0]);
		myQty=$(dtrows[i]).find("td:eq(5) input").val();
		//ccn_mf=$(dtrows[i]).find("td:eq(6)").text();
		//alert(myVendor+"-"+myFrom+"-"+myTo+"-"+mySku);
		//alert(vendor+"-"+fromLoc+"-"+toLoc+"-"+sku);
		if(vendor==myVendor && mySku==sku && myFrom==fromLoc && toLoc==myTo){ 
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

	var toLoc=$.trim($("#txtToLoc").val()).split(":");
	var toStr=$.trim(toLoc[0]);
	
	var skuSplit=$.trim($("#txt_sku").val()).split(":");
	var mySku=$.trim(skuSplit[0]);

	//alert(myVendor+"-"+from+"-"+to+"-");
	vndr=myVendor;
	frm=fromStr;
	to=toStr;
	//skeu=;
}
function sendToServer(){ 
	var rtrn;
	$.ajax({
		type:'POST',
		async:false,
		url:'control/sendCartToServer.php',
		data:{dataArr:myCart,flag:1,remarks:$("#ApprovReason").val(),heirarchy:heirarchy},
		success:function(out){

			if(out==1){
				rtrn=out;
			}else{
				rtrn=out;
			}
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
function initializetrfQtyArr(){
	forTrfQty=[];
}
function cancelCart(){
	var dtid="#dataTables-cart-transfer";
	$(dtid).dataTable().fnDestroy();		
	$(dtid +" tbody").empty();	
	disableButtons();
	initializetrfQtyArr();
	$("#ApprovReason").attr("disabled","disabled");
	heirarchy=[];
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
function addToCart(txt_vendor,txt_from,txt_to,txt_sku,ccn_mf,txt_dept,txt_qty){
	var dtid="#dataTables-cart-transfer";
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
	var counter=0;
		if (!$.fn.DataTable.isDataTable(dtid)) {
			dtdata += '<tr>'
				+	'<td><center>'+mySku+'</center></td>'
				+	'<td>'+myDescr+'</td>'
				+	'<td>'+txt_vendor+'</td>'
				+	'<td>'+txt_from+'</td>'
				+	'<td>'+txt_to+'</td>' 	
				//+	'<td>'+txt_qty+'</td>'
				+ 	'<td><input class="form-control" type="text" style="width:80px;text-align:right;" value="'+txt_qty+'"/></td>'
				//+   '<td><button type="button" class="btn btn-default btn-circle"><i class="fa fa-times fa-f5"></i></button></td>'			 				
				+	'<td>'+ccn_mf+'</td>'
				+'</tr>';
				counter++;
		}else{
			//$(dtid).dataTable().fnClearTable();													
			$(dtid).dataTable().fnAddData([
				'<center>'+mySku+'</center>',myDescr,txt_vendor,txt_from,txt_to,'<input class="form-control" type="text" style="width:80px;text-align:right;" value="'+txt_qty+'"/>',ccn_mf			 				
			])				
			//<td><button type="button" class="btn btn-default btn-circle"><i class="fa fa-times fa-f5"></i></button></td>
			$(dtid).dataTable().fnFilter("");
			counter++;
		}		
		if(counter>0){
			clear_timeout();
			$(".mySku_msg").text("Added to Cart");
			showMiniDialog();
			$("#txt_sku").val(''); 	 
			$("#txt_qty").val('');
			$("#div_on_hand").text("");
		}
		setToArrTrfQty(myVendor,myStore,mySku,txt_qty);
		$(".grand_qty").text("Qty : "+totalTrfQty());
		$(dtid+" tbody").html(dtdata);		
		_dataTable(dtid);
		isDataTableHasRows();
 
}
function setToArrTrfQty(vendor,store,sku,qty){
	forTrfQty.push([vendor,store,sku,qty]);
}
function totalTrfQty(){
	var grand_qty=0;
	for(var i=0;i<forTrfQty.length;i++){
		 grand_qty+=parseInt(forTrfQty[i][3]);
	}
	return grand_qty;
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
function isDataTableHasRows(){
	var dtid="#dataTables-cart-transfer";	
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

function _getVendor(){
	var txt_vendor=$.trim($("#str_store").val());
	var txtSplit=txt_vendor.split(":");
	var vendor_num=$.trim(txtSplit[0]);	

	return vendor_num;
}
function _selectVendor(dtid){
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
				$("#loadingGif").hide();;
			//}
			$(".dialog-lookup-vendor").modal("show");
		}		
	});
}
function _selectFrom(dtid,vendor_num){ 
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
				$("#loadingGif").hide();; 
			//}
			$(".dialog-lookup-fromLoc").modal("show");
		}		
	});
}
function _selectTo(dtid,vendor_num,store_num){
	$("#loadingGif").show();
	$.ajax({
		type:'POST',
		dataType:'json',
		async:false,
		url:'control/loadTo.php',
		data:{vendor_num:globalVendor,store_num:store_num},
		success:function(out){			
				//alert(out.length);
				//var obj=jQuery.parseJSON(out);	
				//alert(obj.length);
				$(dtid).dataTable().fnDestroy();	
			 	$(dtid +" tbody").empty();
				if (!$.fn.DataTable.isDataTable(dtid)) {
					var dtdata = '';
					for(var i = 0; i < out.length; i++ ){
						//var strId = "'"+out[i][0]+"'";						 
						var strId = "'"+out[i]['store']+" : "+out[i]['storeName']+"'";									
						dtdata += '<tr>'
								+	'<td><center><a href="#" aria-label="Close" data-dismiss="modal" onclick="toClicked('+strId+')" >'+out[i]['store']+'</a></center></td>'
								+	'<td>'+out[i]['storeName']+'</td>'
								 
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
				$("#loadingGif").hide();;
			//}
			$(".dialog-lookup-toLoc").modal("show");
		},
		error:function(out){
			//alert(out);
		}	
	});
}
function _selectDept(dtid){
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
						//var dept = "'"+out[i]["dept"]+":"+out[i]["isdept"]+"'";			
						var dept="'"+(out[i]['dept'].replace("'","`").replace("/","`"))+"--"+out[i]['isdept']+"--"+out[i]['iclas']+"'";						
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
				$("#loadingGif").hide();; 
			//}
			$(".dialog-lookup-dept").modal("show");
		},
		error:function(out){
			alert(out);
		}	
	});
}
function _selectSku(dtid){
	$("#loadingGif").show();
  	var dept=$("#txtDept").val();
	var splt=dept.split("--");
	var descs=splt[0];
	var sdept=splt[1];
	var clas=splt[2];
   // alert(clas);
	$.ajax({
		type:'GET',
		dataType:'json',
		url:'control/list_sku.php', 
		data:{vendor_num:globalVendor,contract_num:'Not used',globalIdept:sdept,globalClas:clas},
		success:function(out){							
				//var obj=jQuery.parseJSON(out);	
				//alert(obj.length);
				//alert($("#dataTables-lookup-sku").html());
				$(dtid).dataTable().fnDestroy();	
			 	$(dtid +" tbody").empty();
				if (!$.fn.DataTable.isDataTable(dtid)) {
					var dtdata = '';
					for(var i = 0; i < out.length; i++ ){
						//var strId = "'"+out[i][0]+"'";						 
						var sku = "'"+out[i]["sku"]+":"+out[i]['descr']+"'";									
						dtdata += '<tr>'
								+	'<td width="50" align="Center" style="font-size:12px; font-family:arial;"><input type="checkbox"/></td>'
								//+	'<td><center><a href="#" aria-label="Close" data-dismiss="modal" onclick="skuClick('+sku+')" >'+out[i]["sku"]+'</a></center></td>'																 
								+	'<td><center>'+out[i]["sku"]+'</center></td>'																 
								+	'<td>'+out[i]['descr']+'</td>'
								+	'<td></td>'
								+	'<td></td>'
								+'</tr>';
					}			
					//alert(dtdata);		
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
					$(".dialog-lookup-sku2").modal("show");
					//globalIdept=sdept;
					//globalClas=clas;
					//heirarchy.push([sdept,clas]);
					//$(dtid).dataTable().fnDestroy();	
			 		//$(dtid +" tbody").empty();	
				}				 
				$(dtid).dataTable().fnFilter("");
				$("#loadingGif").hide();;
			//}
			
		},
		error:function(out){
			alert(out);
		}	
	}); 	
}
function selectedSku(){
	$(".dialog-lookup-sku2 a ").click(function(){
		$(".dialog-lookup-skuSel").modal("show");
	});
}
function isComplete(txt_vendor,txt_from,txt_to,txt_sku,ccn_mf,txt_dept,txt_qty){
	if(txt_vendor=='' || txt_from=='' || txt_to=='' || txt_sku=='' || ccn_mf=='' || txt_dept=='' || txt_qty==''){
		return false;
	}
	return true;
}
function clearUpperFields(){
	$("#loadingGif").show();
	$("#str_store").val('');
	$("#idFrom").val('');
	$("#txtToLoc").val('');
	$("#txt_sku").val(''); 
	$("#ccn_mf").val('');
	$("#txtDept").val('');	
	$("#txt_qty").val('');
	$("#loadingGif").hide();
	ccnMf=0;//re initialize
	heirarchy=[];
} 
function clearFieldsBelowVendor(){	
	$("#idFrom").val('');
	$("#txtToLoc").val('');
	$("#txt_sku").val(''); 
	$("#ccn_mf").val('');
	$("#txtDept").val('');	
}
function clearFieldsBelowFrom(){	
	$("#txtToLoc").val('');
	$("#txt_sku").val(''); 
	$("#ccn_mf").val('');
	$("#txtDept").val('');	
}
/* Click Id From Dialog */
function vendorClicked(val){
	var split_data=val.split(":");
	var vendor=$.trim(split_data[0]);
	var vendor_name=$.trim(split_data[1]);
	clearFieldsBelowVendor();
	$("#str_store").val(val);
	globalVendor=vendor;
}
function fromClicked(val){ 
	var split_data=val.split(":");
	var from_loc=$.trim(split_data[0]);
	var loc_name=$.trim(split_data[1]);
	clearFieldsBelowFrom();
	$("#idFrom").val(val);	
}
function toClicked(val){
	var split_data=val.split(":");
	var from_loc=$.trim(split_data[0]);
	var loc_name=$.trim(split_data[1]);
	$("#txtToLoc").val(val);	
}
function deptClick(dept){	 
	var splitDept=dept.split(":");
	var myDept=$.trim(splitDept[0]);
	var isDept=$.trim(splitDept[1]);
	globalIdept=isDept;

	

	var splt=dept.split("--");
	var desc=$.trim(splt[0]);
	var sdept=splt[1];
	var cls=splt[2];
	globalIdept=sdept;
	globalClas=cls; 
	dept=desc+"--"+sdept+"--"+cls;
	$("#txtDept").val(dept);
	 
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
function skuClick(sku){	 
	var splitSku=sku.split(":");		 
	$("#txt_sku").val(sku);	 
	$("#txt_qty").removeAttr('disabled');
	$("#txt_qty").val('');
	$("#txt_qty").focus();	
	$("#div_on_hand").text("on hand : "+checkInventory(getVendorNum(),getStorerNum(),getSkuNum()));
}

/***************FOR SEARCH OF TRF NUMBER/trf # clicked**********************************/
function _trfForVViewing(){
	$("#loadingGif").show();
	$.ajax({
		type:'GET',
		dataType:'json',
		url:'control/trfForVViewing.php', 
		data:{flag:1},
		success:function(out){	
		//alert(out.length);		 
			$(dtid).dataTable().fnDestroy();	
		 	$(dtid +" tbody").empty(); 
			if (!$.fn.DataTable.isDataTable(dtid)) {
				var dtdata = '';
				for(var i = 0; i < out.length; i++ ){
					var trf_num = "'"+out[i][0]+"'";						 
					//var strId = "'"+out[i][0]+" : "+out[i][1]+"'";		
					 	 
					dtdata += '<tr>'
							+	'<td><center><a href="#" aria-label="Close" data-dismiss="modal" onclick="trfClicked('+trf_num+')" >'+out[i][0]+'</a></center></td>'
							+	'<td align="center">'+out[i][1]+'</td>'
							//+	'<td>'+out[i][2]+'</td>'
							//+	'<td>'+out[i][3]+'</td>'
							//+	'<td>'+out[i][4]+'</td>'
							+	'<td align="center">'+out[i][2]+'</td>'
							+	'<td align="left">'+out[i][3]+'-'+out[i][8]+'</td>'
							+	'<td align="left">'+out[i][4]+'-'+out[i][7]+'</td>'
							+	'<td align="center">'+out[i][5]+'</td>'
							+	'<td align="center">'+out[i][6]+'</td>' 
							+'</tr>';
				}					
				$(dtid+" tbody").html(dtdata);		
				//_dataTable(dtid);
				$(dtid).dataTable({
						"columnDefs": [{ "width": "150px", "targets": 0 }],
						"bLengthChange": false,
						"iDisplayLength":10,
						"aaSorting":[[5,"desc"]],    
						"bDestroy": true,
						"oLanguage":{
							"sSearch":"Search"
						}
				});
				$(dtid).dataTable().fnFilter("");
			 
			}			 
			$(dtid).dataTable().fnFilter("");
			$("#loadingGif").hide();;		 
			$(".dialog-lookup-trf").modal("show");
		}		
	});
}
function _selectTrf(dtid,flag){
    var url;
    
   	url='control/loadTrfNumbers.php';
   	if(flag==2)
   		url='control/trfForVViewing.php';
   
	$("#loadingGif").show();
	$.ajax({
		type:'GET',  
		dataType:'json',
		url:url, 
		data:{flag:1},
		success:function(out){	
		//alert(out.length);		 
			$(dtid).dataTable().fnDestroy();	
		 	$(dtid +" tbody").empty(); 
			if (!$.fn.DataTable.isDataTable(dtid)) {
				var dtdata = '';
				var back = '';
				for(var i = 0; i < out.length; i++ ){
					var trf_num = "'"+out[i][0]+"'";	
					back = '';					 
					//var strId = "'"+out[i][0]+" : "+out[i][1]+"'";		
					if(getUrl == 9 && out[i]['vVwd'] == 0){
						back='style="background-color:#c6dff8;"';
					}else if(getUrl == 10 && out[i]['mVwd'] == 0){
						back='style="background-color:#c6dff8;"';
					}
					else if(getUrl == 11 && out[i]['ctorVwd'] == 0){
						back='style="background-color:#c6dff8;"';
					}
					dtdata += '<tr '+back+'>'
							+	'<td><center><a href="#" aria-label="Close" data-dismiss="modal" onclick="trfClicked('+trf_num+')" >'+out[i][0]+'</a></center></td>'
							+	'<td align="center">'+out[i][1]+'</td>'
							//+	'<td>'+out[i][2]+'</td>'
							//+	'<td>'+out[i][3]+'</td>'
							//+	'<td>'+out[i][4]+'</td>'
							+	'<td align="center">'+out[i][2]+'</td>'
							+	'<td align="left">'+out[i][3]+'-'+out[i][8]+'</td>'
							+	'<td align="left">'+out[i][4]+'-'+out[i][7]+'</td>'
							+	'<td align="center">'+out[i][5]+'</td>'
							+	'<td align="center">'+out[i][6]+'</td>' 
							+'</tr>';
				}					
				$(dtid+" tbody").html(dtdata);		
				//_dataTable(dtid);
				$(dtid).dataTable({
						"columnDefs": [{ "width": "150px", "targets": 0 }],
						"bLengthChange": false,
						"iDisplayLength":10,
						"aaSorting":[[5,"desc"]],    
						"bDestroy": true,
						"oLanguage":{
							"sSearch":"Search"
						}
				});
				$(dtid).dataTable().fnFilter("");
			 
			}			 
			$(dtid).dataTable().fnFilter("");
			$("#loadingGif").hide();;		 
			$(".dialog-lookup-trf").modal("show");
		}		
	});

	 
	
}

function trfClicked(trf_num){
	//alert(trf_num); 
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

/***************END SEARCH OF TRF #*****************************/