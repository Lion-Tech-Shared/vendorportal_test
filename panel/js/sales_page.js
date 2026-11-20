$(function() {
	var format = "d", per = "s";
    flag=0;
	var cells=[];
	
	$("#from").datepicker({
		todayHighlight: true,
		clearBtn: true,
		autoclose: true,
		autoopen: false
	}).on('changeDate', function(selected){
	
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
		$("#to").datepicker('setStartDate', startDate);	
        
		endDate = new Date(selected.date.valueOf());
		endDate.setMonth($(this).datepicker("getDate").getMonth() + 1);
		endDate.setDate(1);
		endDate.setDate(endDate.getDate() - 1);
		$("#to").datepicker('setEndDate', endDate);
		
		if($("#vcode").is(":visible")) {
			$("#vcode").val("");
		}
    }).on('show', function(){
		if($("#to").val() == "") {
			$(this).datepicker('setStartDate', new Date("12/01/2014"));
			$(this).datepicker('setEndDate', '');
		}
    }).off('focus');
	
	$("#to").datepicker({
		todayHighlight: true,
		clearBtn: true,
		autoclose: true,
		autoopen: false
	}).on('changeDate', function(selected){
	
		startDate = new Date(selected.date.valueOf());
		startDate.setDate(1);
		$("#from").datepicker('setStartDate', startDate);
	
        endDate = new Date(selected.date.valueOf());
        endDate.setDate(endDate.getDate(new Date(selected.date.valueOf())));
        $("#from").datepicker('setEndDate', endDate);
		
		if($("#vcode").is(":visible")) {
			$("#vcode").val("");
		}
    }).on('show', function(){
		if($("#from").val() == "") {
			$(this).datepicker('setStartDate', new Date("12/01/2014"));
			$(this).datepicker('setEndDate', '');
		}
    }).off('focus');
	
	$(".optradio").change(function() {
		if($("#vcode").is(":visible")) {
			$("#vcode").val("");
			$("#luplbl").html($(this).attr("id")+' Code:<font style="color:red;"> *</font>');
		}
	})
	
	$(".optradio_format_type").change(function() {
		format = $(this).attr("id");
		if(format == "t") {
			$(".optradio_format").prop("disabled", true);
		}
		else {
			$(".optradio_format").prop("disabled", false);
		}
	});
	
	$(".optradio_format").change(function() {
		per = $(this).attr("id");
	});
	
	$("#sbu").change(function() {
		if($("#vcode").is(":visible")) {
			$("#vcode").val("");
		}
	});
	
	$("#vcode").change(function() {
		if($("#store").is(":visible")) {
			$("#store").val("");
		}
	});
	
	$("#btn-from").click(function () {
		$("#from").datepicker("show");
	});
	
	$("#btn-to").click(function () {
		$("#to").datepicker("show");
	});
	
	$("#btnlu").click(function () {
		if($("#from").val() != "" && $("#to").val() != "") {
			lookupDtable("#dataTables-lookup");
		}
		else {
			alert("Please select date range first.");
		}
	});
	
	$("#btnlu_2").click(function () {
		//$("#dataTables-lookup_filter input:text").val('');
		//$("div.dataTables_filter input").val('');
		if($("#from").val() != "" && $("#to").val() != "") {
			if($("#vcode").val() != "") {
				//$("#dataTables-lookup_filter input").val('');
				lookupDtableS("#dataTables-lookup");
				
			}
			else {
				alert("Please input vendor code first.");		
			}
		}
		else {
			alert("Please select date range first.");
		}
	});
	
	$(".dialog-lookup-3").on("hidden.bs.modal", function () {
		$(".siframe").attr({"src": "generate_sale/loading_pdf.html"});
	});
	
	$(".dialog-lookup").on("shown.bs.modal", function () {
		var rows=$("#dataTables-lookup").dataTable().fnGetNodes();			
		//$("div.dataTables_filter input").val("_");
		//$("div.dataTables_filter input").val("");
		$("div.dataTables_filter input").focus();
		//uncheck checkbox of multiple store selection
		$("#dataTables-lookup input").prop("checked",false);	
		//clear array of stores
		for(var i=0;i<rows.length;i++){		
		  //$(rows[i]).find("td:eq(2) input").prop("checked",false);
		  cells.pop();
		}
						
	});
	$(".dialog-lookup").on("hidden.bs.modal", function () {
		var table_id=$("#dataTables-lookup");
		//$("div.dataTables_filter input").val('');
		//alert(flag);
		if(flag==1){
			//table_id.
		}
		//$("#dataTables-lookup").datatables({"bFilter":false});
				
	});
	
	$("#btnvclear").click(function() {
		$("#vcode").val("");
	});
	
	$("#btnsclear").click(function() {
		$("#store").val("");
	});
	
	$("#vsales").click(function () {
		console.log("sbu:"+ $("#sbu").val());
		if((!$("#vcode").is(":visible") || ($("#vcode").is(":visible") && $("#vcode").val() != "")) &&
			$("#from").val() != "" && $("#to").val() != ""  && $("#sbu").val() != "0")  {
			var vcode = ($("#vcode").val()).split(" : ");
			//var store = ($("#store").val()).split(" : ");
			//$("#sbu").val('DS');
			$(".siframe").attr({'src': 'generate_sale/sales_review.php'
									+'?vcode='+vcode[0]
									+'&store='+$("#store").val()
									+'&from='+$("#from").val()
									+'&to='+$("#to").val()
									+'&sbu='+$("#sbu").val()
									+'&format='+format
									+'&per='+per
									+'&isv='+$("#Vendor").is(":checked")
								});
			/*$(".siframe").ready(function() {
				$(".dialog-lookup-3").modal("show");
			});*/
			$(".dialog-lookup-3").modal("show");
		}
		else {
			alert("Fill-up required fields.");
		}
	}); 
	
	$("#exportbtn").click(function () {
		console.log("sbu:"+ $("#sbu").val());
		if((!$("#vcode").is(":visible") || ($("#vcode").is(":visible") && $("#vcode").val() != "")) &&
			$("#from").val() != "" && $("#to").val() != ""  && $("#sbu").val() != "0")  {
			var x = ($("#vcode").val()).split(" : ");
			var vcode = x[0]
			var store = $("#store").val();
			var from = $("#from").val();
			var to = $("#to").val();
			var sbu = $("#sbu").val();
			var isv = $("#Vendor").is(":checked");
		$.ajax({
			type : 'post',
			url : 'generate_sale/export2excel.php',
			data : {
				'vcode':vcode,
				'store' : store,
				'from' : from,
				'to' : to,
				'sbu' : sbu,
				'format' : format,
				'per' : per,
				'isv' : isv
			},
			success : function(response){
				var jsonData = JSON.parse(response);

				var host = jsonData.host;

				var vcode = jsonData.vcode;
				var store = jsonData.store;
				var from = jsonData.from;
				var to = jsonData.to;
				var sbu = jsonData.sbu;
				var thisformat = jsonData.format;
				var thisper = jsonData.per;
				var thisisv = jsonData.isv;

				var url = host +'panel/generate_sale/xls.php?vcode='+vcode+'&&store='+store+'&&from='+from+'&&to='+to+'&&sbu='+sbu+'&&format='+thisformat+'&&per='+thisper+'&&isv='+thisisv+'';
				//var url = host +'panel/generate_sale/xls.php?vcode='+vcode+'';

				window.open(url,'_blank' );
			}
		});
		} else {
			alert("Fill-up required fields.");
		}
	});	
 
	$("#checks input:checkbox").click(function(){
		if($(this).prop("checked")){
			alert("chck");
		}else{
			alert("not");
		}
	});
	 
	$("#dataTables-lookup tbody").on("click","td input:checkbox",function(e){		
		 
		if(($(this).prop("checked"))){					  
			  cells.push($(this).attr("class"));			
		}else{			
			 //cells.pop($(this).attr("class"));
			 for(var i=0;i<cells.length;i++){			  
			     if(cells[i]==$(this).attr("class")){
					cells.splice(i,1); 
					break;
				 }
			 }
		}			
		//for(var i=0;i<cells.length;i++){			  
			 // alert(cells[i]);
		//}			 
	});
 
	$("#dataTables-lookup .check_all").on("click",function(e){
		//alert($(this).attr("class"));	\		
		var rows=$("#dataTables-lookup").dataTable().fnGetNodes();
		//if($(this).prop("checked")))		
		//alert($("#dataTables-lookup input").prop("checked"));
		if(($("#dataTables-lookup input").prop("checked"))){
			for(var i=0;i<rows.length;i++){			 
			 cells.pop($(rows[i]).find("td:eq(2) input").attr("class"));
			}
			for(var i=0;i<rows.length;i++){			  
			  $(rows[i]).find("td:eq(2) input").prop("checked",true);  			 	  
			  cells.push($(rows[i]).find("td:eq(2) input").attr("class"));
			}
		}else{
			for(var i=0;i<rows.length;i++){
			 //alert($(rows[i]).find("td:eq(2) input").prop("checked",false));
			 $(rows[i]).find("td:eq(2) input").prop("checked",false);
			 //cells.pop($(rows[i]).find("td:eq(2) input").attr("class"));
			 cells.pop();
			}
		}
		
		for(var i=0;i<cells.length;i++){
			  //$(rows[i]).find("td:eq(2) input").prop("checked",true);
			  //alert(cells[i]);
		}
	});
	$("#btn_multiple").click(function(){
		var rows=$("#dataTables-lookup").dataTable().fnGetNodes();
		var loc=0;
		var desc='';
		var store_code="";
		
		if(rows.length>0)
			$("#store").val("");
			
		for(var i=0;i<rows.length;i++){
			 //alert($(rows[i]).find("td:eq(2) input").prop("checked",false));
			 loc=$(rows[i]).find("td:eq(0)").text();
			 desc=$(rows[i]).find("td:eq(1)").text();
			 store_code= loc +" : "+ desc;
			 if($(rows[i]).find("td:eq(2) input").prop("checked")){
				//if($.trim($("#store").val())!='')
					//$("#store").val(" , ");				
				$("#store").val(""+ $("#store").val()+ " ");
				if($.trim($("#store").val())!='')
				  $("#store").val($("#store").val()+ " , ");					  
				  $("#store").val(""+ $("#store").val()+ " " + store_code);				
			 }			 
			 //$(rows[i]).find("td:eq(2) input").prop("checked",false);
			 //cells.pop($(rows[i]).find("td:eq(2) input").attr("class"));
			 //cells.pop();
		}
		$(".dialog-lookup").modal("hide");
	});
	
});

function lookupDtable(dtid) {
	var url = '';
	flag=0;
	$(dtid+" .check_all,#btn_multiple").hide();
	//alert($("#sbu").val());
	//location.href="check_session.php";
	
	if($("#Vendor").is(":checked")) {
		//alert($("#sbu").val() +"-"+ $("3"));
		 
		url = 'control/dt_vendor.php?lctr=3&from='+$("#from").val()+'&to='+$("#to").val()+'&sbu='+$("#sbu").val();
	}
	else {
		url = 'control/dt_branch.php?lctr=1&from='+$("#from").val()+'&to='+$("#to").val()+'&sbu='+$("#sbu").val();
	}
	
	$("html").mask("Loading...");
	
	$.ajax ({
		url: url,
		dataType: 'json',
		//async: false,
		success: function(out) {
			if (!$.fn.DataTable.isDataTable(dtid)) {
				var dtdata = '';
				for(var i = 0; i < out.length; i++ ){
					var strId = "'"+out[i][0]+" : "+out[i][1]+"'";
					//if(out[i][0]=='670020'){
						//out[i][1]=out[i][0].replace(/~/g,);
					//}	
					dtdata += '<tr>'
							+	'<td><center><a href="#" aria-label="Close" data-dismiss="modal" onclick="idCliked('+strId+')" >'+out[i][0]+'</a></center></td>'
							+	'<td>'+out[i][1]+'</td>'
							+ 	'<td></td>'
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
			}
			else {
				$(dtid).dataTable().fnClearTable();
				
				for(var i = 0; i < out.length; i++ ){
					var strId = "'"+out[i][0]+" : "+out[i][1]+"'";
					
					$(dtid).dataTable().fnAddData([
						'<center><a href="#" aria-label="Close" data-dismiss="modal" onclick="idCliked('+strId+')" >'+out[i][0]+'</a></center>',
						out[i][1],' '
					])
				}
				$(dtid).dataTable().fnFilter("");
			}
				
			$("html").unmask();			
			$(".dialog-lookup").modal("show");
		},error:function(out){
					alert(out);
		}
	});
	
};

function lookupDtableS(dtid) {
	var url = '';
	var vcode = ($("#vcode").val()).split(" : ");
	flag=1;
	$(dtid+" .check_all,#btn_multiple").show();
	//alert($("#sbu").val());
	//$("#sbu").val('DS');
	url = 'control/dt_branch.php?lctr=2&from='+$("#from").val()+'&to='+$("#to").val()+'&sbu='+$("#sbu").val()+'&vcode='+vcode[0];
	//alert("me lookup");
	$("html").mask("Loading...");
	//$(dtid).dataTable().fnFilter("");
	$.ajax ({
		url: url,
		dataType: 'json',
		//async: false,
		success: function(out) {
			if (!$.fn.DataTable.isDataTable(dtid)) {
				
				var dtdata = '';
				for(var i = 0; i < out.length; i++ ){
					var strId = "'"+out[i][0]+" : "+out[i][1]+"'";
					dtdata += '<tr>'
							+	'<td><center><a href="#" aria-label="Close" data-dismiss="modal" onclick="idCliked_2('+strId+')" >'+out[i][0]+'</a></center></td>'
							+	'<td>'+out[i][1]+'</td>'		
							+   '<td><input id="checkbox" class="'+ out[i][0] +'" type="CheckBox" />';					
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
					$(dtid).dataTable().fnAddData([
						'<center><a href="#" aria-label="Close" data-dismiss="modal" onclick="idCliked_2('+strId+')" >'+out[i][0]+'</a></center>',
						out[i][1],'<input id="checkbox" class="'+ out[i][0] +'" type="CheckBox" />'
					])
				}
			}
			$(dtid).dataTable().fnFilter("");
			$("html").unmask();
			
			$(".dialog-lookup").modal("show");
		},
		error: function(out) {
			console.log(out);
		}
	});	
};

function idCliked(id) {
	$("#vcode").val(id);
	$("#store").val('');
	//$("div.dataTables_filter input").val('');
	//alert($("#dataTables-lookup_filter input").val());
};

function idCliked_2(id) {
	var strArr = $("#store").val().split(",");
	if(strArr == "" || strArr.length == 0) {
		$("#store").val(id);
	}
	else {
		var content = $("#store").val();
		$("#store").val(content+","+id);
	}
};