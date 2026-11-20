
$(function(){
    cells=[];
	var dtid='#dataTables-lookup-branch5'; 

	$("#btn_find_vendor").click(function(){	
		 loadVendor();		
	});
	$("#btn_print").click(function(){	
		 print();		
	}); 
	$("#btn_find_store").click(function(){		
		var bool=$(this).hasClass('vendor');
		choose_store(bool);
	});
	$(".dialog-lookup-vendor #btn_submit").click(function(){
		//$("#btn_label_store").text("asdf");
		//alert(cells.length);
		if(cells.length > 0){
			
			if((cells.length)==1){
				$("#btn_label_store").text(cells[0]);
			}else if(cells.length < 6){
				$("#btn_label_store").text(cells[0]+","+(cells[1]?cells[1]:"")+","+(cells[2]?cells[2]+",":"")+""+(cells[3]?cells[3]+",":"")+""+(cells[4]?cells[4]+",":"")+"..");
			}
			$(".dialog-lookup-vendor").modal("hide");	
		}
	});
	
	check_all();
	clearArr();
	clickRow();
	setVendor();

});

//User Defined functions--------------------------------------------------------------------------------------

function clickRow(){
	$("#dataTables-lookup-branchdvndr tbody").on("click","td input:checkbox",function(e){		
		
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
}

function clearArr(){

	//$(".dialog-lookup-vendor").on("shown.bs.modal", function () {
	$(".dialog-lookup-vendor").on("shown.bs.modal", function () {
		cells=[];		
	});
}

function check_all(){
	var dtid='#dataTables-lookup-branchdvndr';
	$(".dialog-lookup-vendor table thead").on("click","th input:checkbox",function(e){
		//alert($(this).attr("class"));		
		var rows=$(dtid).dataTable().fnGetNodes();
		//if($(this).prop("checked")))		
		//alert($("#dataTables-lookup input").prop("checked"));
		if(($(this).prop("checked"))){
			//alert("sdf");
			for(var i=0;i<rows.length;i++){			 
			 cells.pop($(rows[i]).find("td:eq(2) input").attr("class"));
			}
			for(var i=0;i<rows.length;i++){			  
			  $(rows[i]).find("td:eq(2) input").prop("checked",true);  			 	  
			  cells.push($(rows[i]).find("td:eq(2) input").attr("class"));
			  if((i+1) % 2==0){
			  	//$("#dr_num").val($("#dr_num").val() +',');
			  }
			 // $("#dr_num").val($("#dr_num").val() + cells[i]);
			}
		}else{
			for(var i=0;i<rows.length;i++){
			 //alert($(rows[i]).find("td:eq(2) input").prop("checked",false));
			 $(rows[i]).find("td:eq(2) input").prop("checked",false);
			 //cells.pop($(rows[i]).find("td:eq(2) input").attr("class"));
			 cells.pop();
			}
		}		
		//for(var i=0;i<cells.length;i++){
			  //$(rows[i]).find("td:eq(2) input").prop("checked",true);  
			  //alert(cells[i]);
		//}
	});
}

function loadVendor(){
	var dtid="#dataTables-lookup-branchdvndr";
	var tr2='<tr>'		
		+ '<th width="50">Vendor #</th>'
		+ '<th width="100">Vendor Name</th>'	 									
		+'</tr>';
	$("#dataTables-lookup-branchdvndr thead").html(tr2);	
	$(".dialog-lookup-vendor #btn_submit").addClass("hidden");
	$.ajax({
		type:'GET',
		url:'control/loadVendor.php',
		dataType:'json',
		success:function(out){
			$(dtid).dataTable().fnDestroy();	
			 	$(dtid +" tbody").empty();				 	 				
					var dtdata = '';
					var dr='';
					for(var i = 0; i < out.length; i++ ){
						dr="'"+out[i]['vendor_num']+"--"+out[i]['vendor_name']+"'";
						dtdata += '<tr>'								
								+	'<td width="100"><a href="#" aria-label="Close" data-dismiss="modal" onclick="clickedVendor('+dr+')">'+out[i]['vendor_num']+'</a></td>'
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

function print(){	 
	var dtid="#dataTables-lookup-branch5";
	var rows=$(dtid).dataTable().fnGetNodes(); 
	var vendor_num=$("#dr_num").val();
	var store=$("#btn_label_store").text();

	//alert(rows.length);
	if(cells.length > 0)
		$(dtid).dataTable().fnDestroy();
	if(rows.length < -1){
		$(dtid).dataTable().fnDestroy();
		alert("No Record to print!");
	}
	else{
		//$("")
		$(".siframe").attr({'src': 'generate_sale/inventory.php'
			+'?vendor='+vendor_num+'&store='+store+'&stores='+cells			 
		});
		$.ajax({
			type:'POST',
			url:'control/setSession.php',
			data:({stores:cells})			
		});
		$(".dialog-lookup-print").modal("show");
	}
}

function clickedVendor(vendor_num){
	var dtid="#dataTables-lookup-branch5";
	var splt=vendor_num.split("--");
	var vendor_num=splt[0];
	var vendor_name=splt[1];

	var tr='<tr  style="font-weight:bolder;text-align:right;">'		
			+ '<th width="50">Store</th>'
			+ '<th width="100">Sku</th>'
			+ '<th width="250">Description</th>'
			+ '<th width="100">On Hand</th>' 										 
			+'</tr>';
			
	$("#btn_label").text(vendor_name);
	$("#btn_label_store").text('');
	$("#dr_num").val(vendor_num);
	
	$(dtid).dataTable().fnDestroy();	
	$(dtid +" tbody").empty();			

	/*$.ajax({
		type:'GET',
		dataType:'json',		 
		url:'control/inventory.php',
		data:({flag:2,vendor_num:vendor_num}),
		success:function(out){
			$(dtid).dataTable().fnDestroy();	
		 	$(dtid +" tbody").empty();	
		 	 
			if (!$.fn.DataTable.isDataTable(dtid)) {
				var dtdata = '';
				var dr='';
				for(var i = 0; i < out.length; i++ ){
					dr="'"+out[i]['dr']+"--"+out[i]['status']+"'";
					dtdata += '<tr>'
							//+	'<td><center><a aria-label="Close" data-dismiss="modal"  onclick="idCliked('+dr+')"  style="cursor:pointer;" >'+out[i]["dr"]+'</a></center></td>'
							//+	'<td>'+out[i]['store']+'</td>'
							+	'<td>'+out[i]['strnam']+'</td>'
							+	'<td>'+out[i]['sku']+'</td>'							
							+	'<td>'+out[i]['descr']+'</td>'
							+	'<td>'+out[i]['total']+'</td>'
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

			}
		}
	});*/
	//$(dtid).children('thead tr').remove(); 
	//$(dtid).children('thead ').html(tr); 
}

function clickedStore(store){
	cells=[];	
	$("#btn_label_store").html(store);
	var dtid="#dataTables-lookup-branch5";
	var vendor_num=$("#dr_num").val();
	cells.push(store);
	/*$.ajax({
		type:'GET',
		dataType:'json',		 
		url:'control/inventory.php',
		data:({flag:2,vendor_num:vendor_num,store:parseInt(store)}),
		success:function(out){
			//alert(out);
			$(dtid).dataTable().fnDestroy();	
		 	$(dtid +" tbody").empty();	
		 	 
			if (!$.fn.DataTable.isDataTable(dtid)) {
				var dtdata = '';
				var dr='';
				for(var i = 0; i < out.length; i++ ){
					//dr="'"+out[i]['dr']+"--"+out[i]['status']+"'";
					dtdata += '<tr>'
							//+	'<td><center><a aria-label="Close" data-dismiss="modal"  onclick="idCliked('+dr+')"  style="cursor:pointer;" >'+out[i]["dr"]+'</a></center></td>'
							//+	'<td>'+out[i]['store']+'</td>'
							+	'<td>'+out[i]['strnam']+'</td>'
							+	'<td>'+out[i]['sku']+'</td>'							
							+	'<td>'+out[i]['DESCR']+'</td>'
							+	'<td>'+out[i]['total']+'</td>'
														//+ 	'<td><input id='+dta+' class="form-control" type="text" style="height:25px;width:50%;"/></td>'
							//+ 	'<td>Not yet</td>'
							+'</tr>';
							//alert(out[1]['strnam']);
				}
				 
				$(dtid+" tbody").html(dtdata);
				$(dtid).dataTable({
					"columnDefs": [{ "width": "150px", "targets": 0 }] ,
					"bLengthChange": false,
					"iDisplayLength":10,					    
					"bDestroy": true,
					"oLanguage":{
						"sSearch":"Search"
					}
				});

			}
		}
	});*/
	$.ajax({
		type:'POST',
		async:false,
		url:'control/setSession.php',
		data:({stores:store})			
	});
}

function choose_store(bool){
	var dtid="#dataTables-lookup-branchdvndr";
	if(bool)
		var vendor_num=" ";
	else
		var vendor_num=$("#dr_num").val();

	var tr='<tr>'		
			+ '<th width="50">Store</th>'
			+ '<th width="100">Store Name</th>'	 									
			+ '<th width="100"><input class="check_all" type="checkbox"/> Check All</th>'	 	
			+'</tr>';
	$(dtid + " thead").html(tr);	
	$("#btn_submit").removeClass("hidden");
	//if(vendor_num != ''){
		//alert(vendor_num);
		$.ajax({
			type:'GET',
			url:'control/loadStore.php',
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
									+	'<td width="100"><a href="#" aria-label="Close" data-dismiss="modal" onclick="clickedStore('+dr+')">'+out[i]['STORE']+'</a></td>'
									+	'<td width="300">'+out[i]['STORE_NAME']+'</td>'
									+ 	'<td   align="center"><input class="'+out[i]['STORE']+'" type="checkbox" style="height:15px;"/></td>'
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
	//}

	//$(".dialog-lookup-vendor").modal("show");
}

function setVendor(){
	$.ajax({
		type:'GET',
		url:'control/loadVendor.php',
		dataType:'json',
		success:function(out){
			 $("#dr_num").val(out[0][0]);
			 $("#btn_label").text(out[0][1]);
		}
	});
}

function _(){
	$.ajax({
		type:'GET',
		dataType:'json',		 
		url:'control/inventory.php',
		data:({flag:1}),
		success:function(out){
			$(dtid).dataTable().fnDestroy();	
		 	$(dtid +" tbody").empty();	
		 	 
			if (!$.fn.DataTable.isDataTable(dtid)) {
				var dtdata = '';
				var dr='';
				for(var i = 0; i < out.length; i++ ){
					dr="'"+out[i]['dr']+"--"+out[i]['status']+"'";
					dtdata += '<tr>'
							//+	'<td><center><a aria-label="Close" data-dismiss="modal"  onclick="idCliked('+dr+')"  style="cursor:pointer;" >'+out[i]["dr"]+'</a></center></td>'
							+	'<td>'+out[i]['store']+'</td>'
							+	'<td>'+out[i]['sku']+'</td>'
							+	'<td>'+out[i]['descr']+'</td>'
							+	'<td>'+out[i]['on_hand']+'</td>'
							+	'<td>'+out[i]['lst_rec_date']+'</td>'
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

			}
		}
	});
}