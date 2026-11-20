$(function(){
	var getUrl=0;
	var results;

	myClas=0; 
	mysDept=0; 
	multArr=[];
	multArrSku=[];

 
	var name='trigger';
	var results=new RegExp('[\?&]' + name +'=([^&#]*)').exec(window.location.href);	
	if(results){
	 	getUrl=results[1];
	}

	$(".mySelectedSku").text("("+multArr.length+")");
    $(".msg-dialog").on("hidden.bs.modal", function () {
		 $(".msg-dialog .my_msg").text('');
	});

	$("#ASku").focus();
	
	//find adjustment----------------
	$("#btn_find_adj").click(function(){
		//alert("Asdf");
		 //$(".dialog-lookup-adj").modal("show");
		 _loadReqToTbl('read-all');
	});
	/******clear adjustment****************/
	$("#btn_clear_adj").click(function(){		 
		 $("#txt_adj").val('');		 
	});
	/******print adjustment****************/
	$("#btn_print_adj").click(function(){		 
		var txt_adj=$("#txt_adj").val();		 
		if(txt_adj==''){
			//alert("Please Select Adjustment!");
			$(".mySku_msg").text('Please Select Adjustment!');
		 	showMiniDialog();
		}else{
			$(".dialog-lookup-print .dr-rpt").attr({ 'src':'generate_sale/adjustment_rpt.php?adj='+txt_adj});
			$(".dialog-lookup-print").ready(function() {
			setTimeout(function(){
				$(".dialog-lookup-print").modal("show");	
			},100);			
		});	
		}
	});
	//cancel---------------_--------
	$("#Acancel").click(function(){
		_clear(0);
	});
	//sku keydown------------------
	$("#ASku").keydown(function(e){
		//_skuKeydown(e);
	});
	//qty keydown------------------
	$("#Aqty").keydown(function(e){
		//_skuKeydown(e);
	});
	//submit-----------------------
	$("#ASubmit").click(function(){		
		if(isInputComplte()){
			$(".generic-alert .generic_txt").text("Are you sure?");
			$(".generic-alert").modal("show");
		} else{
			$(".mySku_msg").text('Please Select Fill-up all fields!');
		 	showMiniDialog();
		}
		
		//_submit();		
		//_myBtnSubmit(2);
	});	 
	//select vendor
	$("#btnlu").click(function(){
		if($.trim($("#idFrom").val())==""){
			$(".mySku_msg").text('Please Select Your Location!');
		 	showMiniDialog();
		 	return 0;
		}
		var myStore=$.trim($("#idFrom").val().split(":")[0]);
		_selectVendor('#dataTables-lookup-branchdvndr',myStore);
	});
	//clear vendor code input
	$("#btnvclear").click(function(){
		$("#vcode").val('');		 
		$("#str_dept").val('');
		mysDept=0;
		myClas=0;
	});
	//select store code
	$("#btnlu_2").click(function(){
		//$("#dataTables-lookup").dataTable().fnClearTable();
		_loadStore();
	});
	//clear store code
	$("#btnsclear").click(function(){
		$("#store").val('');
	});
	//search from store/whse
	$("#btn_search_from").click(function(){			 
		var dtid="#dataTables-lookup-fromLoc";
		_selectFrom(dtid);		 
	});

	/**Click Event in datatables**/
	$("#tbl-view-request tbody").delegate("tr","click",function(event){		
		
		 
	 
	}); 
	/*Select of multiple SKU*/ 
	$("#btn_search_sku2").click(function(){		
		var dtid="#dataTables-lookup-sku3";
		if(mysDept!=0) {			  	
			$("html").mask(" ");
			$.ajax({
				type:'GET',
				dataType:'json',
				async:true,
				url:'control/list_sku.php',
				data:{vendor_num:setVendor(),contract_num:'Not used',globalIdept:mysDept,globalClas:myClas},
				success:function(out){				
				//alert(setVendor()+"-"+mysDept);
						//var obj=jQuery.parseJSON(out);	
						//alert(obj.length);
						$(dtid).dataTable().fnDestroy();	
					 	$(dtid +" tbody").empty();
						//if (!$.fn.DataTable.isDataTable(dtid)) {
							var dtdata = '';
							var sku
							for(var i = 0; i < out.length; i++ ){
								//var strId = "'"+out[i][0]+"'";						 
								sku = "'"+out[i]["sku"]+":"+out[i]['descr']+"'";									
								dtdata += '<tr>'
								 	    +	'<td><input type="checkbox"/></td>'																 
										+	'<td><center>'+out[i]["sku"]+'</center></td>'																 
										+	'<td>'+out[i]['descr']+'</td>'
										+	'<td></td>'
										+	'<td></td>'
										+	'<td></td>'
										+'</tr>';
							}					
							$(dtid+" tbody").html(dtdata);
							$(dtid).dataTable({
								"columnDefs": [{ "width": "150px", "targets": 0 }],
								"bLengthChange": false,
								"iDisplayLength": 10,
								//"sPaginationType": "bs_two_button",
								"destroy": true,
								"oLanguage":{
									"sSearch":"Search"
								}
							});
							$(dtid).dataTable().fnFilter("");
							$(".dialog-lookup-sku3").modal("show");
							//$(dtid).dataTable().fnDestroy();	
					 		//$(dtid +" tbody").empty();	
						//}				 
						$(dtid).dataTable().fnFilter("");
						$("html").unmask();
					//}else{
						/*for(var i = 0; i < out.length; i++ ){
							$(dtid).dataTable().fnAddData([
									'<center>'+mySku+'</center>',myDescr,txt_vendor,txt_from,txt_to,'<input class="form-control" type="text" style="width:80px;text-align:right;" value="'+txt_qty+'"/>',ccn_mf			 				
							])				
						}*/
						
						//<td><button type="button" class="btn btn-default btn-circle"><i class="fa fa-times fa-f5"></i></button></td>
						//$(dtid).dataTable().fnFilter("");
					//}
						
				},
				error:function(out){
					alert(out);
				}	
			}); 	
		}else{
			$(".mySku_msg").text('Please Select Department!');
		 	showMiniDialog();
		}			
	});
	$(".dialog-lookup-sku3 #btn_submit_sku").click(function(){
		 //$(".mySku_msg").text('No Records Selected!');
		 //showMiniDialog();
	});

	//$("#vcode").val(setVendor());
	//setStore($("#vcode").val());		
	getStore();
	tr_sku_click();
	digitsOnlyForQty(); 
	selSku();
	overSelected();
	_submitMult();
	btnUpdate();
	Confirm_submit();
	includeNot();

	$('.dialog-lookup-skuSel').on("shown.bs.modal", function(){
    	$('.dialog-lookup-sku3').css('overflow', 'auto');
	}).on('hidden.bs.modal', function(){
	    $('.dialog-lookup-sku3').css('overflow', 'auto');
	    $('.dialog-lookup-skuSel #btn_update').remove();

	    $("#txt_mySku").val(multArrSku.length+' Sku Selected');
	    $(".mySelectedSkuTotal").text("("+multArrSku.length+")");
	});
	$('.dialog-lookup-sku3').on("shown.bs.modal", function(){
    	//$('body').css('overflow', 'hidden');
    	$(".mySelectedSku").text("("+multArr.length+")");
	}).on('hidden.bs.modal', function(){
	    //$('body').css('overflow', 'auto');
        if(checkSkuIfComplete()==0){
        	destroyTables();
        	multArr=[];        		
        }	   
	});
	$('.dialog-adj-return').on("shown.bs.modal", function(){
    	
	}).on('hidden.bs.modal', function(){
	    $('body').css('overflow', 'hidden');
    	$(".mySelectedSkuTotal").text("("+multArrSku.length+")");   
    	$("#viewTotal").trigger("click");
	});

	$('.dialog-adj-return').on("shown.bs.modal", function(){
    	
	}).on('hidden.bs.modal', function(){
	    $('body').css('overflow', 'hidden');
    	$(".mySelectedSkuTotal").text("("+multArrSku.length+")");   
    	$("#viewTotal").trigger("click");
	});
	
	if(getUrl==1){
	 	//$("#btn_find_adj").trigger("click");
	}

});//END--------END----------END---


//----------------------FUNCTIONS------------------------
function destroyTables(){
	var dtid='#dataTables-lookup-sku3';
	$(dtid).dataTable().fnDestroy();	
	$(dtid +" tbody").empty();

	var dtid='#dataTables-lookup-skuSel';
	$(dtid).dataTable().fnDestroy();	
	$(dtid +" tbody").empty();
}
function checkSkuIfComplete(){
    var dtid='#dataTables-lookup-sku3';			
 	var tbl=$("#dataTables-lookup-sku3").DataTable();
	var rows=$("#dataTables-lookup-sku3").dataTable().fnGetNodes(); 						 
	var i=tbl.rows({order:'applied'}).nodes().indexOf(this);		

	for(var x=0;x<multArr.length;x++){		
		var mySku=$(rows[multArr[x][0]]).find("td:eq(1)").text();						
		var myReason=$(rows[multArr[x][0]]).find("td:eq(5) textarea").val();
		var myAdjType=$.trim($(rows[multArr[x][0]]).find("td:eq(4) option:selected").text());
		var mySkuQty=$.trim($(rows[multArr[x][0]]).find("td:eq(3) input:text").val());
		if(myReason=='' || myAdjType=='' || mySkuQty==''){
			$(dtid).dataTable().fnFilter(mySku);
			//$(".msg-dialog .my_msg").text('Please Fill up fields!');			 
			return 0;
		}	
		//alert("0");			 
	}
	return 1;
}
function isInputComplte(){
	var fromLoc=$.trim($("#idFrom").val());
	var vcode=$.trim($("#vcode").val());
	var str_dept=$.trim($("#str_dept").val());
	
	if(fromLoc=='' || vcode=='' || str_dept==''){
		return false;
	}
	return true
}
function Confirm_submit(){
	
	$(".generic-alert #btn_yes").click(function(){
		 sendtoServer();		 
	});
	$(".generic-alert #btn_no").click(function(){
		 		 
	});	
}
//function 
function sendtoServer(){
	var fromLoc=$.trim($("#idFrom").val().split(":")[0]);
	var vcode=$.trim($("#vcode").val().split(":")[0]);
	var str_dept=$.trim($("#str_dept").val());

	$.ajax({
		Type:'GET',
		url:'control/addccnMain.php',
		data:{flag:1,fromLoc:fromLoc,vendor:vcode,isdept:mysDept,iclas:myClas},
		success:function(data){
			if(data==1){
				$(".mySku_msg").text('Added!');	 
				_clear(1);			 
			}else{
				$(".mySku_msg").text(data);	 	
				$("#str_dept").val('');		 
			}			 
	 		showMiniDialog();
		}

	});
}
function displayMessage(){
	if(multArrSku.length < 1){
		$(".mySku_msg").text('No Records Selected!');
	 	showMiniDialog();
	 	return false;
	}
	return true;
}
function _myBtnSubmit(flag,dtid){

}
function btnUpdate(){
	 /*$('.dialog-lookup-skuSel').on("click","#btn_update",function(){
	 	var dtid='#dataTables-lookup-skuSel';	
	 	_myBtnSubmit(3,dtid);
	 });*/
}
function _submitMult(){
	$(".dialog-lookup-sku3 #btn_submit_sku").click(function(){
		var dtid='#dataTables-lookup-sku3';		
		 _myBtnSubmit(1,dtid);
	});	  
}

function overSelected(){

	$("#viewTotal").mouseover(function(){
		$(this).css("text-decoration","underline");
	}).mouseout(function(){
		$(this).css("text-decoration","none");
	});

	$("#viewTotal").click(function(){  
		$(this).css("text-decoration","none");
		
		if(multArrSku.length<1){
			$(".mySku_msg").text('No Records Selected!');
		 	showMiniDialog();
			return 0;
		}
		$(".dialog-lookup-skuSel .modal-footer").html('<button id="btn_update" style="display: block;" class="btn btn-primary btn-lg btn-block col-sm-6" type="button">Update</button>');
		//*********continue if records found**************
		var dtid='#dataTables-lookup-skuSel';	  
		var dtdata = '';		

		$(dtid).dataTable().fnDestroy();	
		$(dtid +" tbody").empty();

		for(var x=0;x<multArrSku.length;x++){
			//alert("sku:"+$(rows[multArr[x][0]]).find("td:eq(1)").text()+" description:"+$(rows[multArr[x][0]]).find("td:eq(2)").text()+" stockNum:"+$(rows[multArr[x][0]]).find("td:eq(3)").text()+"  qty:"+$(rows[multArr[x][0]]).find("td:eq(4)").text());
			var mySku=multArrSku[x][0];
			var mySkuDesc=multArrSku[x][1];			
			var mySkuQty=multArrSku[x][2];
			var myAdjType=multArrSku[x][3];
			var myReason=multArrSku[x][4];
			//alert(mySku+mySkuDesc+mySkuStock+mySkuQty);
			var sel="<select id='AType' tabIndex='6' class='form-control' >";
		                    $.ajax({
		                    	Type:'GET',
		                    	url:'control/loadAdjTypes.php',
		                    	async:false,
		                    	data:{myOption:myAdjType},
		                    	success:function(out){
		                    		sel+=out;
		                    	}
		                    });		                        		                        			                        
		                    sel+="</select>";
			dtdata  += '<tr>'							
					//+	'<td width="600" align="Center" style="font-size:12px; font-family:arial;"><input type="checkbox"/></td>'
					+	'<td width="600" align="Center" style="font-size:12px; font-family:arial;">'+mySku+'</td>'
					+	'<td width="600" align="left" style="font-size:12px; font-family:arial;">'+(mySkuDesc.replace(/"/g,"").replace(/'/g,""))+'</td>'
					+	'<td width="600" align="left" style="font-size:12px; font-family:arial;"><input type="text" style="text-align:right;width:100%;" tabIndex="6" class="form-control" maxlength="5" value="'+mySkuQty+'"/></td>'
					+	'<td width="600" align="left" style="font-size:12px; font-family:arial;">'+sel+'</td>'
					+	'<td width="600" align="left" style="font-size:12px; font-family:arial;"><textarea id="AReason" tabIndex="6" class="form-control" rows="1" style="resize:none;"  >'+(myReason.replace(/"/g,"").replace(/'/,""))+'</textarea></td>'
					+	'<td width="600" align="left" style="font-size:12px; font-family:arial;"><input type="checkbox" style="height:20px;" checked="checked"/></td>'

					 	
					
					//$(rows[i]).find("td:eq(4)").append(sel);
					
				 
					//+ 	'<td   align="center"><input class="'+out[i]['STORE']+'" type="checkbox" style="height:15px;"/></td>'
					//+ 	'<td>Not yet</td>'
					+'</tr>';
					$(dtid+" option[value='"+myAdjType+"']").prop("selected",true);
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

	});
}
function selSku(){
	var dtid='#dataTables-lookup-skuSel';
	$(".dialog-lookup-sku3 a").click(function(){
		if(multArr.length>0){
			var tbl=$("#dataTables-lookup-sku3").DataTable();
			var rows=$("#dataTables-lookup-sku3").dataTable().fnGetNodes(); 						 
			var i=tbl.rows({order:'applied'}).nodes().indexOf(this);

			var dtdata = '';		

			$(dtid).dataTable().fnDestroy();	
			$(dtid +" tbody").empty();

			for(var x=0;x<multArr.length;x++){
				//alert("sku:"+$(rows[multArr[x][0]]).find("td:eq(1)").text()+" description:"+$(rows[multArr[x][0]]).find("td:eq(2)").text()+" stockNum:"+$(rows[multArr[x][0]]).find("td:eq(3)").text()+"  qty:"+$(rows[multArr[x][0]]).find("td:eq(4)").text());
				var mySku=$(rows[multArr[x][0]]).find("td:eq(1)").text();
				var mySkuDesc=$(rows[multArr[x][0]]).find("td:eq(2)").text();				
				var mySkuQty=$(rows[multArr[x][0]]).find("td:eq(3) input:text").val();
				var myAdjType=$(rows[multArr[x][0]]).find("td:eq(4) option:selected").text();
				var myReason=$(rows[multArr[x][0]]).find("td:eq(5) textarea").val();
				//alert(mySku+mySkuDesc+mySkuStock+mySkuQty);
				dtdata  += '<tr>'							
						//+	'<td width="600" align="Center" style="font-size:12px; font-family:arial;"><input type="checkbox"/></td>'
						+	'<td width="600" align="Center" style="font-size:12px; font-family:arial;">'+mySku+'</td>'
						+	'<td width="600" align="left" style="font-size:12px; font-family:arial;">'+mySkuDesc.replace(/"/g,"").replace(/'/g,"")+'</td>'
						+	'<td width="600" align="left" style="font-size:12px; font-family:arial;">'+mySkuQty+'</td>'
						+	'<td width="600" align="left" style="font-size:12px; font-family:arial;">'+myAdjType+'</td>'
						+	'<td width="600" align="left" style="font-size:12px; font-family:arial;">'+myReason.replace(/"/g,"").replace(/'/g,"")+'</td>'
						+	'<td width="50" align="left" style="font-size:12px; font-family:arial;"></td>'
						 
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
function tr_sku_click(){
	var dtid="#dataTables-lookup-sku3";
	$(dtid).delegate("tr","click",function(event){

		var nodeNam=(event.target.nodeName).toLowerCase();		
		 
		var tbl=$(dtid).DataTable();
		var cel=$(this).index();		

		var rows=$(dtid).dataTable().fnGetNodes(); 
		//var stockNum=$(rows[i]).find("td:eq(4) input").val();
		//var i=tbl.row(this).index(); 
		var i=tbl.rows({order:'applied'}).nodes().indexOf(this);
		//var rowNum=tbl.rows({order:'applied'}).nodes().indexOf(this);
		//var y=tbl.col(this).index();
		//alert(cel);

		//alert($(this).attr('class'));
		var inputType=$(rows[i]).find("td:eq(0) input").prop('type').toLowerCase();
		if( (nodeNam=="input" || nodeNam=='i') && inputType	=='checkbox') {		
			//alert($(rows[i]).find("td:eq(0) input").prop('checked'));
			//alert($(rows[i]).find("td:eq(3) input") );
				
			if( $(rows[i]).find("td:eq(0) input").prop('checked')){
				if(!$(rows[i]).find("td:eq(3) input,td:eq(4) input").length){
					$(rows[i]).find("td:eq(3)").append("<input type='text' style='text-align:right;width:100%;' tabIndex='6' class='form-control' maxlength='5' />");
					var sel="<select id='AType' tabIndex='6' class='form-control' >";
		                    $.ajax({
		                    	Type:'GET',
		                    	url:'control/loadAdjTypes.php',
		                    	async:false,
		                    	success:function(out){
		                    		sel+=out;
		                    	}
		                    });		                        		                        			                        
		                    sel+="</select>";
					$(rows[i]).find("td:eq(4)").append(sel);
					//$(rows[i]).find("td:eq(5)").append("<input type='text' style='text-align:right;width:100%;' tabIndex='6' class='form-control' maxlength='5' />");
					$(rows[i]).find("td:eq(5)").append('<textarea id="AReason" tabIndex="6" class="form-control" rows="1" style="resize:none;"></textarea>');
					
					$(rows[i]).find("td:eq(3) input").focus();
					multArr.push([i," ",0]);	 	
				}
				
			}else{
				$(rows[i]).find("td:eq(3),td:eq(4),td:eq(5)").children("input,select,textarea").remove();
				for(var y=0;y<multArr.length;y++){
					if(multArr[y][0]==i){
						//multArr.splice(i,0);
						multArr.splice(y,1);
						//multArr.splice(i,2);
						//alert("remove:"+i);
					}					
				}					 
			}		    
		}
		//loopArr();
		$(".mySelectedSku").text("("+multArr.length+")");
		if(multArr.length < 1){
			$(dtid).dataTable().fnFilter("");
		}
	});
}

function fromClicked(val){ 
	var split_data=val.split(":");
	var from_loc=$.trim(split_data[0]);
	var loc_name=$.trim(split_data[1]);	
	$("#idFrom").val(val);	
}

function _selectFrom(dtid){ 
	$("html").mask(" ");
	$.ajax({
		type:'POST',
		dataType:'json',
		async:false,
		url:'control/getYourStore.php',
		//data:{vendor_num:vendor_num,transType:'adj'},
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
					$("html").unmask();
				//}
				$(".dialog-lookup-fromLoc").modal("show");
			}		
    });
}

function getGid(){
	var outs;
	 $.ajax({
    	type:'GET',
    	async:false,
    	url:'control/getGid.php',    	 
    	success:function(out){
    		outs=out; 
    	}
    });
	return outs;
}

function _clear(flag){ // if flag==1 dont clear dept
	if(flag!=1)
		$("#idFrom").val('');

	$("#vcode").val('');
	$("#str_dept").val('');
	
	multArr=[];
	multArrSku=[];

	myClas=0; 
	mysDept=0;
	 
}
function showClearedMsg(msg){
	if(multArrSku.length > 0 || multArr.length > 0){
		closeImmedMiniD();
		setTimeout(function(){
			$(".mySku_msg").text(msg);
			showMiniDialog();
		},1000);
		
	}
}

//---------------------------------
function _submit(){
	 
	 
}
function _submit2(){
 
}
//---------------------------------
function _skuKeydown(e){
 	if(e.which==9||e.which==8||e.which>=48&&e.which<=57 ||e.which>=96&&e.which<=105){
 		 
	}else if(e.which==13){
		var trm=$.trim($("#ASku").val());
		 
		if(!$.isNumeric(trm)){
			$("#ASku").val('');
			$("#small").text('Press enter after SKU input.');
			alert("Invalid SKu!");
		}
		else if($("#vcode").val()!=''){
			//alert("come");
			_skuValidate();
		}else{
			alert("Please select Vendor Code!");
		}
	}
	else{
		//e.preventDefault();
	}
}
function _checkBlank(sku,type,reason,qty){
	
	if(reason==''){
	   $("#AType").focus();			
	}
	if(type==''){
	   $("#Aqty").focus();		
	}
	if(qty==''){
	   $("#AReason").focus();	
	}
	if(sku==''){
	   $("#ASku").focus();	
	}		
} 
function _selectVendor(dtid,myStore){ 
	$("html").mask(" ");
	$.ajax({
		type:'POST',
		dataType:'json',
		url:'control/loadVendorOfCoor.php',
		data:{store:myStore},
		success:function(out){
			//alert(out.length);

			//for(var i=0;i<out.length;i++){
				//	alert(out[i][0]);
				$(dtid).dataTable().fnDestroy();	
			 	$(dtid +" tbody").empty();
				if (!$.fn.DataTable.isDataTable(dtid)) {
					var dtdata = '';
					for(var i = 0; i < out.length; i++ ){
						var strId = "'"+out[i][0]+" : "+out[i][1]+"'";						 
						//var strId = "'"+out[i][0]+" : "+out[i][1]+"'";						 
						dtdata += '<tr>'
								+	'<td><center><a href="#" aria-label="Close" data-dismiss="modal" onclick="idCliked('+strId+')" >'+out[i][0]+'</a></center></td>'
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
				else {
					$(dtid).dataTable().fnClearTable();
					
					for(var i = 0; i < out.length; i++ ){
						var strId = "'"+out[i][0]+" : "+out[i][1]+"'";					
						$(dtid).dataTable().fnAddData([
							'<center><a href="#" aria-label="Close" data-dismiss="modal" onclick="idCliked_2('+strId+')" >'+out[i][0]+'</a></center>',
							out[i][1]
						])
					}
				}
				$(dtid).dataTable().fnFilter("");
				$("html").unmask();
			//}
			$('.dialog-lookup-vendor').modal('show');
		}		
	});
}
function idCliked(vcode){
	$("#str_dept").val('');
	$('#vcode').val(vcode);
}

function _loadStore(){
	var vendor_num=$('#vcode').val();
	if($("#vcode").val()!=''){
		//alert(vendor_num);
		var dtid="#dataTables-lookup-store";
		$.ajax({
			type:'GET',
			url:'control/loadStoreDr.php',
			dataType:'json',
			data:({vendor:vendor_num}),
			success:function(out){
				//alert(out);
				$(dtid).dataTable().fnDestroy();	
				$(dtid +" tbody").empty();				 	 				
						var dtdata = '';
						var dr='';
						for(var i = 0; i < out.length; i++ ){
							//dr="'"+out[i]['STORE']+"--"+out[i]['STORE_NAME']+"'";
							dr="'"+out[i]['STORE']+"'";
							dtdata += '<tr>'								
									+	'<td width="100" align="Center"><a href="#" aria-label="Close" data-dismiss="modal" onclick="clickedStore('+dr+')">'+out[i]['STORE']+'</a></td>'
									+	'<td width="300" align="">'+out[i]['STORE_NAME']+'</td>'
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
						//$(".dialog-lookup-vendor").modal("show");
						$(".dialog-lookup-store").modal("show");
			}
		});
		//$(".dialog-lookup-store").modal("show");
	}else{
		alert("Please Select Vendor");
	}
}

function clickedStore(store){
	$("#store").val(store);
}

function _skuValidate(){ 
	$.ajax({
		type:'GET',
		data:{vendor:$("#vcode").val(),sku:($("#ASku").val())},
		dataType:'json',
		url:'control/checkSku_adj.php',
		success:function(data){
			 //alert("rec:"+data[0]["count"]);
			if(parseInt(data[0]["count"])>0){
				//alert(data[0]["descr"]);
				//alert(data[0]["cost"]);
				//$("#small").text("Description : "+data[0]["descr"] + ";     Cost : "+data[0]["cost"]+ "   ;   On hand : "+data[0]["on_hand"]);
			}else if(parseInt(data[0]["count"])==-1){
				alert("Zero on hand");			
			}else if(parseInt(data[0]["count"])==-2){
				alert("Not in Inventory!");
			}
			else{
				$("#ASku").val('');
				$("#small").text('Press enter after SKU input.');
				alert("Sku Not Found In Selected Vendor Code!");
			}
		}
	});
}

function getOnHand(from){
	var rtn=0;
	$.ajax({
		type:'GET',
		async:false,
		data:{flag:1,vendor:$("#vcode").val(),sku:($("#ASku").val()),from,from},	 
		url:'control/checkOnHandAdj.php',
		success:function(data){
			 //alert("rec:"+data[0]["count"]);
			 rtn=data;
		}
	});
	return rtn;
}
function isSkuInVendor(){
	var rtn=0;
	$.ajax({
		type:'GET',
		async:false,
		data:{flag:1,vendor:$("#vcode").val(),sku:($("#ASku").val())},	 
		url:'control/isSkuInVendorAdj.php',
		success:function(data){
			 //alert("rec:"+data[0]["count"]);
			 rtn=data;
		}
	});
	return rtn;
}

function setStore(vendor_num){
	var rtn='';
	$.ajax({
		type:'POST',
		dataType:'json',
		async:false,
		url:'control/loadFrom.php',
		data:{vendor_num:vendor_num,transType:'adj'},
		success:function(out){			 
				$("#idFrom").val(out[0][0]+" : "+out[0][1]);			 
				rtn=out[0][0];
		}
	});	
	return rtn;
}
function getStore(){
	var rtn='';
	$.ajax({
		type:'POST',
		dataType:'json',
		async:false,
		url:'control/getYourStore.php',
		//data:{vendor_num:vendor_num,transType:'adj'},
		success:function(out){			 				 	
				rtn=out[0][0]+" : "+out[0][1];
		}
	});	
	//alert(rtn);
	$("#idFrom").val(rtn);
	//alert(rtn);
	//return rtn;
}
function setVendor(){
	var rtn=0;
	$.ajax({
		type:'POST', 
		dataType:'json', 
		async:false,
		url:'control/loadVendor_adj.php',
		success:function(out){
			rtn=out[0][0];
		}		 
	});
	return rtn;
} 

$("#btn_search_dept").click(function(){
	var vendor_num=$.trim($("#vcode").val().split(":")[0]);
	var myStr=$("#idFrom").val();
	//var str=$("#str_store").val();
	var split_data=myStr.split(":");

	var from_loc=$.trim(split_data[0]);
	var loc_name=$.trim(split_data[1]);	
	
	if(vendor_num!='' && from_loc!=''){
			var dtid="#dataTables-lookup-dept";
			$.ajax({
				type:'GET',
				url:'control/loadDept.php', 
				dataType:'json',
				data:({vendor:vendor_num}),
				success:function(out){
					//alert(out);
					$(dtid).dataTable().fnDestroy();	
					 	$(dtid +" tbody").empty();				 	 				
							var dtdata = '';
							var heir='';
							for(var i = 0; i < out.length; i++ ){
								//dr="'"+out[i]['STORE']+"--"+out[i]['STORE_NAME']+"'";
								//dr="'"+out[i]['STORE']+"'";
								heir="'"+(out[i]['dept'].replace("'","`").replace("/","`"))+"--"+out[i]['isdept']+"--"+out[i]['iclas']+"'";
								dtdata += '<tr>'								
										+	'<td width="600" align="Center" style="font-size:15px; font-family:arial;"><a href="#" aria-label="Close" data-dismiss="modal" onclick="clickedDept('+heir+')">'+out[i]['dept']+'</a></td>'
										 
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
							//$(".dialog-lookup-vendor").modal("show")  ;
				}
			});
			$(".dialog-lookup-dept").modal("show");
	}else{
		$(".mySku_msg").text('Please Select Vendor!');
		showMiniDialog();
	}
});

function clickedDept(dept){
	var splt=dept.split("--");
	var desc=$.trim(splt[0]);
	var sdept=splt[1];
	var cls=splt[2];
	mysDept=sdept;
	myClas=cls;
	//alert(desc+"-"+sdept+"-"+cls);
	dept=desc+"--"+sdept+"--"+cls;
	$("#str_dept").val(dept);
	$("#txt_sku").val('');
}

function digitsOnlyForQty(){
	var dtid="#dataTables-lookup-sku3";
	$(dtid+" tbody").on("keydown","tr",function(event){	
		var nodeNam=(event.target.nodeName).toLowerCase();		
		//alert(nodeNam);
		var tbl=$(dtid).DataTable();	
		var cel=$(this).index();		
	 
	  
			if(nodeNam=='input'){
				
				var colIndex=$(event.target).closest("td").index();

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
function includeNot(){
	var dtid='#dataTables-lookup-skuSel';
	$(dtid+" tbody").on("click","input:checkbox",function(event){	
		//alert($(this).closest("tr").attr("class"));
		var bool=$(this).prop("checked");
		if(!bool){
			$(this).closest("tr").css({"background-color":"#e7e4e4"});
			$(this).closest("input:text,textarea,option").attr("disabled","disabled");
			$(".mySku_msg").text('Excluded!');		  
		}else{
			$(this).closest("tr").css({"background-color":""});
			$(this).closest("input:text,textarea,option").removeAttr("disabled");
			$(".mySku_msg").text('Included!');		 	
		}
		showMiniDialog();
	});
	 
}