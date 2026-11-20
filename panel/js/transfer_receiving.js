//include('testing.js');

$(function(){
	var trigger=0;
	var name='trigger';
	var results=new RegExp('[\?&]' + name +'=([^&#]*)').exec(window.location.href);	
	var dtid='#dataTables-transfer';
	myArray=[];
	if(results && results!=''){
		trigger=results[1];
	}

	$('html').load('testing.js');	
	/*****Search transfer***************/
	$("#btn_find_trf").click(function(){
		var dtid="#dataTables-lookup-trf";
		_selectTrf(dtid);
	});

	/*****Clear transfer***************/
	$("#btn_clear").click(function(){
		clearTrf();
	});

	/***********Click of row inside datatables*************************/
	$(".dialog-lookup-trf tbody").delegate("tr","click",function(event){		
		
		var dtid="#dataTables-lookup-trf";
		var nodeNam=(event.target.nodeName).toLowerCase();		
		 
		var tbl=$(dtid).DataTable();
		 
		
		var rows=$(dtid).dataTable().fnGetNodes(); 
		var i=tbl.row(this).index(); 
		var rowNum=tbl.rows({order:'applied'}).nodes().indexOf(this);
		var data=tbl.cells(rowNum,'').render('display');
		var myTrfNum=$(rows[rowNum]).find("td:eq(0)").text();		
				 
		$("#txt_trf").val(myTrfNum);
		
		$(this).css({"background-color":"#1E90FF"});
	 	$(".dialog-lookup-trf").modal("hide");
	 	getAndLoadToDtbl(myTrfNum);
	 	
	});

	//getAndLoadToDtbl(myTrfNum);
	$(".dialog-lookup-trf").on("hidden.bs.modal", function () {
		if( getDtblRows() > 0){
	 		enableDtblBtns();
	 	}
		//getAndLoadToDtbl(myTrfNum);
	});	
	/*******Print Preview of Transfer****/
	$("#btn_print_trf").click(function(){
		if(getTrfVal()!=''){
		    printPullout();
		}else{
			//alert("Please Select Transfer # !");
			$(".mySku_msg").text("Please Select Transfer # !");
		 	showMiniDialog();
		}
	
	});

	/*******Click of Accept button****/
	$("#btn_accept").click(function(){
		if($.trim($("#ApprovReason").val()) == ''){
			alert('Please fill-up remarks');
			return 1;
		}
		if(getDtblRows()>0){
			$("#dialog_terms_and_cond").hide();
			if(getTrfVal()!=''){
				$(".dialog-alert").modal("show");
			}else{
				//alert("Please Select Transfer # !");
				$(".mySku_msg").text("Please Select Transfer # !");
		 		showMiniDialog();
			}
		}else{
			$("#btn_cancel").trigger("click");
		}
		
	});

	/*******Click of Cancel button****/
	$("#btn_cancel").click(function(){
		
		destroyDtbl(dtid);
		disableDtblBtns();
	});

	/*******Click of Yes button** dialog alert**/
	$("#btn_yes").click(function(){
		var rtn=checkTrfStatus(getTrfVal());
		if(rtn==1){
			//setTrfToReceivedStatus(getTrfVal());		 
			begin();
			closeAlert();
			//clearTrf();
			$("#btn_print_trf").trigger('click');
			$("#btn_cancel").trigger("click");
		}else{
			//alert("Only Trf with Transfer Status can be received!");
			$(".mySku_msg").text("Only Trf with Transfer Status can be received!");
		 	showMiniDialog();
			closeAlert();
		}		
	});

	/*******Click of No button***dialog alert*/
	$("#btn_no").click(function(){		 
		closeAlert();
	});


	/*******Datatable Event***********/
	$(dtid).on("keydown","tbody td input:text",function(e){				     
			if(e.which==39 || e.which==37 || e.which==9 || e.which==13 || e.which==8 || e.which>=48 && e.which<=57 || e.which>=96 && e.which<=105){
				 
			}
			else{
				e.preventDefault();
			}
	});
	$(dtid+" tbody").on("keyup","tr",function(event){	
		var nodeNam=(event.target.nodeName).toLowerCase();		
		//alert(nodeNam);
		var tbl=$(dtid).DataTable();	
		//var cel=$(this).index();		
	 
		if(nodeNam=='input'){			
			//var colIndex=$(event.target).closest("td").index();		
			var rows=$(dtid).dataTable().fnGetNodes(); 
			var rowNum=tbl.rows({order:'applied'}).nodes().indexOf(this);
			var mySku=$(rows[rowNum]).find("td:eq(0)").text();
			var myTrf=getTrfVal();
			//alert(myTrf);
			//checkIfQtyAllowed(myTrf,mySku);
			//alert(rowNum);		 
		}
	});		
	if(trigger>0){
		$("#btn_find_trf").trigger('click');
	}

});
/*END****END****END***END****END***END***END**END**END**END***END********/

/********check if there is inventory*******/
function checkIfQtyAllowed(txt_trf,sku){
	$.ajax({
		type:'GET',
		dataType:'text',
		async:false,
		url:'control/checkTrfRecQtyIfAllowed.php',
		data:{flag:1,txt_trf:txt_trf,sku:sku},
		success:function(out){
			//alert(out);
		}
	});
}
function enableDtblBtns(){
	//$(".panel-body .row #btn_accept,.panel-body .row  #btn_cancel").removeAttr("disabled");
	$("#btn_accept,#btn_cancel").removeAttr("disabled");
	$("#btn_accept,#btn_cancel").removeClass("disabled");	
}
function disableDtblBtns(){
	$("##ApprovReason").val('');
	$("#btn_accept,#btn_cancel,#ApprovReason").attr("disabled","disabled");
}
function destroyDtbl(dtid){
	$(dtid).dataTable().fnDestroy();	 
	$(dtid +" tbody").empty();
}
function getAndLoadToDtbl(txt_trf){
	var rtn=1; 
	var dtid='#dataTables-transfer';
	$.ajax({
		type:'GET',
		dataType:'json',
		async:false,
		url:'control/getTrfDetails.php', 
		data:{flag:1,txt_trf:txt_trf},
		success:function(out){
			//alert(out);
		 	$(dtid).dataTable().fnDestroy();	 
		 	$(dtid +" tbody").empty();
			if (!$.fn.DataTable.isDataTable(dtid)) {
				var dtdata = '';
				for(var i = 0; i < out.length; i++ ){
					//var trf_num = "'"+out[i]['trf_num']+"'";						 
					//var strId = "'"+out[i][0]+" : "+out[i][1]+"'";							 	 
					dtdata += '<tr>'
							//+	'<td><center><a href="#" aria-label="Close" data-dismiss="modal" onclick="trfClicked('+trf_num+')" >'+out[i]['trf_num']+'</a></center></td>'
							+	'<td align="center">'+out[i]['sku']+'</td>'
							//+	'<td>'+out[i][2]+'</td>'
							//+	'<td>'+out[i][3]+'</td>'
							//+	'<td>'+out[i][4]+'</td>'
							+	'<td align="center">'+out[i]['descr']+'</td>'
							+	'<td align="left" >'+out[i]['fromLoc']+"-"+out[i]['fromLocDesc']+'</td>'
							+	'<td align="left" >'+out[i]['toLoc']+"-"+out[i]['toLocDesc']+'</td>'
							+	'<td align="center"><input class="form-control" style="text-align:right;width:100px;" type="text" value="'+out[i]['qty']+'"/></td>'
							//+	'<td align="center">'+out[i]['stat_desc']+'</td>' 
							+'</tr>';
							//alert(dtdata);		
				}	
						
				$(".row "+dtid+" tbody").html(dtdata);		
				//_dataTable(dtid);
				$(dtid).dataTable({
					"columnDefs": [{ "width": "150px", "targets": 0 }],
					"bLengthChange": false,
					"iDisplayLength":5,						    
					"bDestroy": true,
					"oLanguage":{
						"sSearch":"Search"
					}
			 	});
			 	if(out.length>0){
			 		$("#ApprovReason").removeAttr("disabled");
			 	}
				//$(dtid).dataTable().fnFilter("");
			 
			}			 
			//$(dtid).dataTable().fnFilter("");
			//$("#loadingGif").hide();;		 			 
		},
		error:function(out){
			alert("Error : "+out);
			rtn=out;
		}
	});
	return rtn;
}
function getDtblRows(){
	var dtid="#dataTables-transfer";
	var rows=0;
	var rtn=0;
	if ($.fn.DataTable.isDataTable(dtid)) {
		rows=$(dtid).dataTable().fnGetNodes();
		rtn=rows.length;
	}
	return rtn;
}
function checkTrfStatus(txt_trf){
	var rtn=false;
	$.ajax({
		type:'GET',
		dataType:'text',
		async:false,
		url:'control/checkIfTrfOpenStatus.php', 
		data:{flag:1,txt_trf:txt_trf},
		success:function(out){
			if(out==1){
				rtn=true;
			}
		},
		error:function(out){
			//alert("Error : "+out);
			rtn=out;
		}
	});
	return rtn;
}
function printPullout(){
	var txt_trf=$("#txt_trf").val();

	if(txt_trf!=''){
		//$(".dialog-lookup-print #dr-rpt").load("generate_sale/dr_rpt.php");
		$(".dr-rpt").attr({'src': 'generate_sale/trf_rpt.php?trf='+txt_trf});

		$(".dialog-lookup-print").ready(function() {
			setTimeout(function(){
				$(".dialog-lookup-print").modal("show");	
			},100);
			
		});
		
	}
} 
/**FUNCTIONS***FUNCTIONS**FUNCTIONS***FUNCTIONS**FUNCTIONS****FUNCTIONS**/ 
function setTrfToReceivedStatus(txt_trf){
	var rtn=false;
	$.ajax({
		type:'GET',
		dataType:'text',
		async:false,
		url:'control/setTrfToReceivedStatus.php',
		data:{flag:1,txt_trf:txt_trf},
		success:function(out){
			//alert(out);
			if(out==1){
				$(".mySku_msg").text("Success!");
		 		showMiniDialog();
				//alert("Success!");
			}else{
				alert("Error Not Received : "+out);
			}
		},
		error:function(out){
			alert("Error : "+out);
		}
	});
	//return rtn;
}

function getTrfVal(){
	return $("#txt_trf").val();	
}

function clearTrf(){
	$("#txt_trf").val('');		
}

function _selectTrf(dtid){ 

	$("#loadingGif").show();;
	$.ajax({
		type:'GET',
		dataType:'json', 
		async:false,
		url:'control/loadTrfNumbers.php', 
		data:{flag:2},
		success:function(out){	
			//alert(out);		 
			$(dtid).dataTable().fnDestroy();	 
		 	$(dtid +" tbody").empty();
			if (!$.fn.DataTable.isDataTable(dtid)) {
				var dtdata = '';
				for(var i = 0; i < out.length; i++ ){
					var trf_num = "'"+out[i]['trf_num']+"'";						 
					//var strId = "'"+out[i][0]+" : "+out[i][1]+"'";		
					 	 
					dtdata += '<tr>'
							+	'<td><center><a href="#" aria-label="Close" data-dismiss="modal" onclick="trfClicked('+trf_num+')" >'+out[i]['trf_num']+'</a></center></td>'
							+	'<td align="center">'+out[i]['ccmnam']+'</td>'
							//+	'<td>'+out[i][2]+'</td>'
							//+	'<td>'+out[i][3]+'</td>'
							//+	'<td>'+out[i][4]+'</td>'
							+	'<td align="center">'+out[i]['ccn_mf_num']+'</td>'
							+	'<td align="left">'+out[i]['fromLoc']+"-"+out[i]['fromLocDesc']+'</td>'
							+	'<td align="left">'+out[i]['toLoc']+"-"+out[i]['toLocDesc']+'</td>'
							+	'<td align="center">'+out[i]['date_created']+'</td>'
							+	'<td align="center">'+out[i]['stat_desc']+'</td>' 
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
		},error:function(out){
			alert(out);
		}	
	});		
}

function begin(){

	loopDtbl();
	sendToDb();
}
function loopDtbl(){
	var dtid="#dataTables-transfer";
	var rows=0;
	var rtn=0; 
	var mySku;
	var myQty;
	if($.fn.DataTable.isDataTable(dtid)) {
		rows=$(dtid).dataTable().fnGetNodes();
		for(var i=0;i<rows.length;i++){
			myQty=$(rows[i]).find("td:eq(4) input").val();
			mySku=$(rows[i]).find("td:eq(0)").text();
			//alert(mySku+"-"+myQty);
			myArray.push([mySku,myQty]);
		}
	}
	//return rtn;
}
function sendToDb(){ 
	var rtn=0;
	if(myArray.length > 0){
		$.ajax({
			type:'GET',
			dataType:'json',
			async:false,
			url:'control/receiveTrf.php',  
			data:{flag:1,dataArr:myArray,trf:getTrfVal(),remarks:$.trim($("#ApprovReason").val())},
			success:function(out){	
				alert(out);
				rtn=out;
			}
		});
	}
	return rtn; 
}