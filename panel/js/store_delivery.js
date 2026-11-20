$(function() {   
	//alert('asdf');   
	//var total_qty=0,total_amt=0.0;
	total_qty=0,total_amt=0.0;
	var dr_number;
	var getUrl=0;
	var getVp_dr='';
	var sd = 0;
    arrModQty=[],arrOrgQty=[];
    heirarchy=[];
    /*Use in strDeliverymultCheck.js*/
    multArr=[];
	multArrSku=[];
	globalClas=0;
	globalsDept=0;
    vp_dr=0;

     gGroupIdSplit = getGid();		 
	 splitData=gGroupIdSplit.split("-");
	 gId = splitData[0];
	 gCanApprove = splitData[1];
	 gCanReceive = splitData[2];
	 gIsCoor = splitData[3];
	 gCanVwOrdrNoti = splitData[4];
	 gCanVwRcvNoti = splitData[5];
	 gIsPa = splitData[6];

	var str='';
			str+='<table class="table table-striped table-hover" id="dataTables-lookup-branch" style="font-size:11px;">';
			str+='<thead>';
			str+='			<tr>';
			str+='				<th>Contract #</th>';
			str+='				<th>Concessionaire Name</th>';
			str+='				<th>Store</th>';
			str+='			</tr>';
			str+='		</thead>';
			str+='		<tbody>';
			str+='		</tbody>';
			str+='</table>';

	var str2='<table class="table table-striped table-hover" id="dataTables-lookup-branch" style="font-size:11px;">';
		str2+='<thead>';
		str2+='			<tr>';		
		str2+='				<th><center>Sku</center></th>';
		str2+='				<th>Description</th>';
		str2+='				<th>Qty</th>';
		str2+='			</tr>';
		str2+='		</thead>';
		str2+='		<tbody>';
		str2+='		</tbody>';
		str2+='</table>';	

	var arr=[];
	var arr2=[];
	$("#contract_num").focus();

	/*Set DR # using session */
	//setDR("");
	/*$.ajax ({
		url: 'control/gen_dr_num.php',
		dataType: 'text',		 
		type:'GET',
		//async: false,
		success: function(out) {
			$(".dr_num").text(out);			
		}
	});*/
     /*Get url trigger value to trigger find_dr click*/
	 //alert(window.location.href);
	 var name='trigger';
	 var results=new RegExp('[\?&]' + name +'=([^&#]*)').exec(window.location.href);	
	 if(results){
	 	 getUrl=results[1];
	 }	 
	 name='vp_dr';
	 var results=new RegExp('[\?&]' + name +'=([^&#]*)').exec(window.location.href);	
	 if(results && results!=''){
	 	 getVp_dr=results[1];
	 }		
	 
	 name='sd';
	 var results=new RegExp('[\?&]' + name +'=([^&#]*)').exec(window.location.href);	
	 if(results && results!=''){
	 	 sd=results[1];
	 }	


	//-------------------------------------------------------------------------------------------------------------------	
	$("#btn_find_contract").click(function(){		
		var dtid='#dataTables-lookup-branch';
		var contractnum=$("#contract_num").val();

		//$("html").mask("Loading...");			
		$("#loadingGif").show();
		//$("#dataTables-lookup-branch").children().remove();
		$("#modal-lookup").html(str);				
		$.ajax ({
		url: 'control/list_contract.php',
		dataType: 'text',
		data:{contractNum:contractnum},
		//async: false,
		success: function(out){
			//alert(out);
			if (!$.fn.DataTable.isDataTable(dtid)) {
				var dtdata = '';
				for(var i = 0; i < out.length; i++ ){
					var strId = "'"+out[i][0]+" -- "+out[i][1]+"'";
					//var strId = out[i][0];
					//if(out[i][0]=='670020'){
						//out[i][1]=out[i][0].replace(/~/g,);
					//}	
					dtdata += '<tr>'
							+	'<td><center><a href="#" aria-label="Close" data-dismiss="modal" onclick="contractCliked('+strId+')" >'+out[i][0]+'</a></center></td>'
							+	'<td>'+out[i][1]+'</td>'
							+ 	'<td>'+out[i][2]+'</td>'
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
					var strId = "'"+out[i][0]+" -- "+out[i][1]+"'";
					
					$(dtid).dataTable().fnAddData([
						'<center><a href="#" aria-label="Close" data-dismiss="modal" onclick="contractCliked('+strId+')" >'+out[i][0]+'</a></center>',
						out[i][1],' '
					])
				}
				$(dtid).dataTable().fnFilter("");
			}
			
			//$("html").unmask();			
			$("#loadingGif").hide();
			$(".dialog-lookup-2").modal("show");
		}
	    });
		//$(".dialog-lookup-2").modal("show");
	});   
	
	//-----------------------------------------------------------------------------------------------------------------
	$("#btn_search_sku").click(function(){
		//var contractnum=(($("#contract_num").val().toString()).split(" - "));

		var contractnum=($("#contract_num").val()).split(' -- ');		 
		////var contractnum=$("#contract_num").val();
		var dta='';
		if(contractnum==''){
			alert("Please Select Contract Number!");
			$("#contract_num").focus();
		}
		else{
			//alert(""+contractnum[0]);
			//$("html").mask("Loading...");	

			//$("#modal-lookup").html(str2);
			var dtid='#dataTables-lookup-branch3';
			$("html").mask("Loading...");			
			//$(".dialog-lookup-2 .modal-body .modal-lookup table").remove();
			 	
			$.ajax ({
			url: 'control/list_sku.php',
			dataType: 'json',
			data:({contract_num:contractnum[0]}),
			type:'GET',
			//async: false,
			success: function(out) {
			 	$(dtid).dataTable().fnDestroy();	
			 	$(dtid +" tbody").empty();	
				if (!$.fn.DataTable.isDataTable(dtid)) {
					var dtdata = '';
					
					for(var i = 0; i < out.length; i++ ){
						//var strId = "'"+out[i][0]+" : "+out[i][1]+"'";
						//var strId = out[i][0];
						//if(out[i][0]=='670020'){
							//out[i][1]=out[i][0].replace(/~/g,);
						//}	
						dta=(i+1)+"-"+out[i]['sku'];
						dtdata += '<tr>'
								+	'<td><center><a aria-label="Close" data-dismiss="modal" onClick=order('+out[i]['sku']+') style="cursor:pointer;" >'+out[i]["sku"]+'</a></center></td>'
								+	'<td>'+out[i]["descr"]+'</td>'
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
				else {
					$(dtid).dataTable().fnClearTable();					

					for(var i = 0; i < out.length; i++ ){
						//var strId = "'"+out[i][0]+" : "+out[i][1]+"'";
						dta=(i+1)+"-"+out[i]['sku'];
						//alert(dta+"-"+out[i]["descr"]);
						$(dtid).dataTable().fnAddData([
							'<center>'+out[i]["sku"]+'</center>',
							//out[i]["descr"],'<input class="form-control" id='+dta+' type="text" style="height:25px;width:50%;"/>'
							out[i]["descr"]
						])
					}
					$(dtid).dataTable().fnFilter("");
				}
				//$(dtid).dataTable().fnClearTable();	
				$("html").unmask();			
				if(out.length > 0){
					$("#contract_num").attr("disabled","disabled");			
					$.ajax ({
						url: 'control/get_contract_info.php',
						dataType: 'text',
						data:({contract_num:contractnum[0]}),
						type:'GET',
						//async: false,
						success: function(out) {
							 //$("#my_label").text(out);
							  
							 $("#my_label").html("<i class='glyphicon glyphicon-star text-primary '></i> <span class='text-primary'>"+out+"</span><span class='glyphicon glyphicon-star text-primary'> </span>");
							 $("#btn_post,#btn_clear").removeClass('disabled');
						}
					});

				}
				$(".dialog-lookup-3").modal("show"); 
			},
			error:function(out){
				alert(out);
			}
		    });
			
		}

	});	

	$("#dataTables-lookup-branch2 tbody").on("keydown","td input:text",function(e){		
		var id=$(this).attr("id");
		var splt_str=id.split("-");
		var qty=$(this).val();		
		var thisvallen=($(this).val()).length;
		var thisval=$(this).val();
		//arr.push(id);
		//alert(arr.length);
		//if($.trim($(this).val())==''){
		//alert(id);
		//alert(splt_str[0]);
		//alert(id);
		if(e.which==9||e.which==13||e.which==8||e.which>=48&&e.which<=57 ||e.which>=96&&e.which<=105){
			for(var i=0;i<arr.length;i++){
				//alert(arr[i]);
			}
			//alert(splt_str[0]);
			//alert($("#dataTables-lookup-branch2 tbody #"+id+"").val());
			//arr2.push([arr2[]];]
			if($.trim(thisval)=='' && e.which==8){
				//alert("blank");
			}else{
				if(thisvallen < 1){
					arr2.push(id);
				}else if(thisvallen==1 && e.which==8){					
					for(var i=0;i<arr2.length;i++){						 
						if(arr2[i]==id){							
							arr2.splice(i,1);							
						}						
					}
				}
			}			 		
		}	 
		else{				  			 			  
			  e.preventDefault();	
		}	
		 
	});
	$("#sku_qty,#dataTables-lookup-branch5").on("keydown",function(e){		
		 
	 
		if(e.which==39||e.which==37||e.which==9||e.which==13||e.which==8||e.which>=48&&e.which<=57 ||e.which>=96&&e.which<=105){
			  //if($(this).attr("id")=="in_rec_qty"){
			  	//alert($(this).attr("id"));
			 // }		
		}	 
		else{				  			 			  
			  e.preventDefault();	
		}	
		 
	});
	$("#dataTables-lookup-branch5").on("keyup","tbody td input:text",function(e){		
		     
			if(e.which==39||e.which==37||e.which==9||e.which==13||e.which==8||e.which>=48&&e.which<=57 ||e.which>=96&&e.which<=105){

				  /*var my_split=($(this).attr("id")).split("-");
				  var id=my_split[0];
				  var my_qty_here=parseInt(my_split[1]);
				  var int_qty=$(this).val();*/
				  //setTimeout(function(e){

					  /*if(id=="in_rec_qty"){
					  	if(int_qty>my_qty_here && int_qty!=''){
					  		 
					  		var cnfrm=confirm("Your input is over, Continue?");
					  		if(!cnfrm){
					  		   $(this).val('');		
					  		}
					  	}
					  	else if(int_qty<my_qty_here && int_qty!=''){
					  		 
					  		var cnfrm=confirm("Your input is less than expected, Continue?");
					  		if(!cnfrm){
					  		   $(this).val('');		
					  		}
					  	}
					  }*/
				  //},1000);
			   // alert($(this).val());
				if($(this).val()==''){
				 //arrOrgQty[0][2]=0;
				}else{
				 //arrOrgQty[0][2]=$(this).val(); 	
				 //alert(arrOrgQty[0][2] + "-"+arrOrgQty[0][3]);
				} 		
			}	 
			else{				  			 			  
				  e.preventDefault();	
			}	
	
			 
	});

	$("#btn_submit").click(function(){
		var rows=$("#dataTables-lookup-branch2").dataTable().fnGetNodes();
		 		 	
		if(arr2.length < 1){
			alert("Please enter qty");
		}else{
			var bool=confirm("Are you sure you want to submit?");
			//alert(bool);
			if(bool){
				for(var i=0;i<arr2.length;i++){
					var index=arr2[i].split("-");
					var id=arr2[i];

					//alert(index[0]);
					//arr.push([arr2[i],$("#dataTables-lookup-branch2 tbody #"+arr2[i]+"").val()]);
					//alert("index:"+id);
					//alert("s:"+$(rows[index[0]-1]).find("td:eq(2) input:text").val());
					//alert($("#dataTables-lookup-branch2 tbody #"+arr2[i]+"").val());
					//arr.push([arr2[i],$("#dataTables-lookup-branch2 tbody #"+arr2[i]+"").val()]);
					arr.push([arr2[i],$(rows[index[0]-1]).find("td:eq(2) input:text").val()]);
				}
				for(var i=0;i<arr.length;i++){
					//alert(arr[i]);
					//var index=arr[i].split("-");
					//arr.push([arr2[i],$("#dataTables-lookup-branch2 tbody #"+arr2[i]+"").val()]);
					  //alert("s:"+$(rows[index[0]]).find("td input:text").val());			 	  
					  //cells.push($(rows[i]).find("td:eq(2) input").attr("class"));			 
				}
				$.ajax({ 
					type:'POST',
					url:'control/vendor_qty.php',
					data:{data:arr},
					success:function(out){
						alert(out);
					},
					error:function(out){
						alert(out);
					}
				});
			}
		}
		arr=[];
	});

	$(".dialog-lookup-3").on("shown.bs.modal", function () {
		arr2=[];		
	});
	$(".dialog-lookup-sku2").on("shown.bs.modal", function () {
		 //$('body').css('padding-right','0');
		//$(".modal-open").css({"overflow":"auto"})
		//$("body").css({"overflow":"auto"});;
		//$(this).css({"overflow-y":"scroll"});
		 
		/*setTimeout(function(){
		alert($(".dialog-lookup-sku").offset().left);	
		alert($(".dialog-lookup-sku").offset().top);

		},1000);*/
		

	}).on("hidden.bs.modal",function(){
		//$("body").css({"overflow":"auto"});		
	});
	$('#miniDialog').on("shown.bs.modal", function(){
		// $('body').css('padding-right','0');
		//$("body").css({"overflow":"auto"});
    	//$(".dialog-lookup-sku2").css({"overflow-y":"scroll"});

    	
	}).on('hidden.bs.modal', function(){
	    //$("body").css({"overflow":"hidden"});
	     //$(".dialog-lookup-sku2").css({"overflow":"scroll"});
	});
	
	$('.dialog-lookup-skuSel').on("shown.bs.modal", function(){
		//$("body").css({"overflow":"hidden"});
    	//$(".dialog-lookup-sku2").css({"overflow-y":"scroll"});
    	
	}).on('hidden.bs.modal', function(){
	    //$("body").css({"overflow":"hidden"});
	     //$(".dialog-lookup-sku2").css({"overflow":"auto"});
	});

	$("#btnvclear2").click(function(){
		var dtid='#dataTables-lookup-branch3';
		$("#contract_num").val('');
		$("#contract_num").removeAttr("disabled");
		$("#contract_num").focus();
		$(dtid).dataTable().fnClearTable();
		$("#dialog-lookup-3").modal("show");
		$("#txt_sku,#sku_qty,#stock_num").val('');		
		$("#my_label,#my_descr").text('');		
		$("#sku_qty,#stock_num").attr("disabled","disabled");
		$("#btn_post,#btn_clear").addClass('disabled');
		$("#btn_cancel").trigger('click');
	}); 
	$("#dataTables-lookup-branch3 tbody").on("click","tr",function(e){		
		var sku=$(this).find("td:eq(0)").text();
		var descr=$(this).find("td:eq(1)").text();
		$("#txt_sku").val(sku);
		$("#my_descr").html("<i class='glyphicon glyphicon-star text-primary '></i> <span class='text-primary'>"+descr+"</span><span class='glyphicon glyphicon-star text-primary'> </span>");
		$("#sku_qty,#stock_num").removeAttr("disabled");

		setTimeout(function(){
			$("#stock_num").val('');
			$("#stock_num").focus();
		},500);
		
	});
	$("#btn_post").click(function(){
		//alert(multArr.length);
		var stock_num=$("#stock_num").val();
		var sku_qty=$("#sku_qty").val();
		var sku=$("#txt_sku").val();
		var descr=$("#my_descr").children('span').text();		
		var price;
		var priceWithNoComma;
		var dtid='#dataTables-lookup-branch5';
		var store=$.trim($("#str_store").val());
		var dpt=$.trim($("#str_dept").val());
		var sku=$.trim($("#txt_sku").val());
		 
		if(store=='' || dpt=='' || sku==''){
			//alert('Please fill-up all fields!');
			var flag=(store==''?'Store': (dpt==''?'Department':(sku==''?'Sku':'not')));
			alert("Please fill-up "+flag);
		}
		//else if(stock_num !='' && sku_qty!='' ){
		else if(stock_num !='' && sku_qty!='' ){
			var str='';
			   str+='<tr>'
				  +'<td>sku</td>'
				  +'<td>stock</td>'
				  +'<td>descr</td>'
				  +'<td>amount</td>'
				  +'<td>qty</td>'
			  	  +'</tr>';
			//$("#dataTables-lookup-branch5 tbody").append(str);

			$("#btn_clear").trigger("click");
				//$(dtid).dataTable().fnDestroy();	
			 	//$(dtid +" tbody").empty();	
			 	$.ajax({
				type:'GET',
				async:false,
				url:'control/getPrice.php',
				data:({sku:sku}),
				dataType:'json',
				success:function(out){					 
					price=out[0]['cost'];
					priceWithNoComma=out[0]['costWithNoComma'];					 
					sku_qty=parseInt(sku_qty);
						if (!$.fn.DataTable.isDataTable(dtid)) {																			
								var dtdata = '';										
									dtdata += '<tr disabled>'
											+	'<td width="20">'+sku+'</td>'
											+	'<td width="120">'+descr+'</td>'
											+ 	'<td width="20">'+price+'</td>'												
											+ 	'<td width="20"><input type="text" style="text-align:right;" class="form-control  col-sm-1 input-sm " value="'+stock_num+'"/></td>'
											+ 	'<td width="20"><input type="text" style="text-align:right;" class="form-control col-sm-1 input-sm" value="'+sku_qty+'"/></td>'
											+ 	'<td width="20"><button type="button" class="btn btn-default btn-circle"><i class="fa fa-times fa-f5"></i></button></td>'
											+'</tr>';	
											 
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
								
								//alert("! price:"+(price*1));
								//alert("! sku qty:"+sku_qty);
								//alert("! (parseFloat(price)*sku_qty):"+(parseFloat(price)*sku_qty));
								//sku_qty=parseInt(sku_qty);
								//var p=2 * 2;	
								//alert("100,100,100".replace(",",""));
								total_amt+=(parseFloat(priceWithNoComma) * sku_qty);
								total_qty+=sku_qty;		

								/*alert((parseFloat(priceWithNoComma) * sku_qty));
								alert(sku_qty);
							    alert(p);
							    alert("! total_amt:"+total_amt) ;
							    alert("! total_qty:"+total_qty);*/
														
						}
						else {
							//$(dtid).dataTable().fnClearTable();										 
								$(dtid).dataTable().fnAddData([
									''+sku+'',descr,price,'<input type="text" style="text-align:right;" class="form-control input-sm" value="'+stock_num+'"/>','<input type="text" style="text-align:right;" 	class="form-control input-sm " value="'+sku_qty+'"/>','<button type="button" class="btn btn-default btn-circle"><i class="fa fa-times fa-f5"></i></button>'
									//out[i]["descr"],'<input class="form-control" id='+dta+' type="text" style="height:25px;width:50%;"/>'
									 
								])							 
					 			total_amt+=(parseFloat(priceWithNoComma) * sku_qty);
								total_qty+=sku_qty;
								$(dtid).dataTable().fnFilter("");
						}
						arrOrgQty.push([sku,sku_qty,sku_qty,priceWithNoComma]);	
						
						var rtn_total_amt=numFormat(total_amt);
						//alert(rtn_total_amt);
					    $("#btn_accept,#btn_cancel").removeClass("disabled");	
					    $("#grand_total .grnd_my_qty").text(total_qty.toFixed(2));
						$("#grand_total .grnd_my_amt").text((rtn_total_amt));						
				   }
				});	
				//alert(multArr.length);
				//if(multArr.length > 0){
					$(".mySku_msg").text('Added to Cart!');
			 		showMiniDialog();
				//}	
			//$("#btn_accept,#btn_cancel").text("disabled");	
		}
		//alert(total_amt);
		//alert(total_qty);
		
		
	});
	$("#btn_clear").click(function(){
		 //$("#btnvclear2").trigger("click");
		 $("#stock_num,#sku_qty").val('');
		 $("#stock_num").focus();
		 $("#str_store,#str_dept").val('');

		myClearFunc();

	});
	$("#btn_cancel").click(function(){
		var dtid='#dataTables-lookup-branch5';
		$(dtid).dataTable().fnDestroy();	
		$(dtid +" tbody").empty();	
		$("#grand_total .grnd_my_qty").text('');
		$("#grand_total .grnd_my_amt").text('');
		$("#btn_accept,#btn_cancel").addClass("disabled");	
		$("#btn_clear").trigger('click');		
		arrOrgQty=[];
		arrModQty=[];
		total_qty=0;
		total_amt=0;
	});
	$("#btn_accept").click(function(){
		var dtid='#dataTables-lookup-branch5';
		var tbl5_nodes=$(dtid).dataTable().fnGetNodes();

	 	var my_arr=[];

	 	var dr_num=$(".dr_num").text();
	 	var sku,stock_num,qty,myHeir;
	 	var contractnum=$("#contract_num").val()?"":"";
	 	//var splt1=($("#my_label").text()).split(":");
	 	//var splt2=splt1[1].split("--");
	 	//var store=$.trim(splt2[0]);
	 	//var vendor_num=$.trim(splt2[1]);	 //	

	 	var splt1="";
	 	var splt2="";
	 	var store="";
	 	var vendor_num="";	 //	

	 	var total_qty=$("#grand_total .grnd_my_qty").text();
		var total_amt=$("#grand_total .grnd_my_amt").text();//
 
	 	var confrm=confirm("Are you sure?");
	 	var str=$.trim(($("#str_store").val()).split(":")[0]);
	 	 
	 	//heirarchy=jQuery.unique( heirarchy );	
	 	
	 	if(confrm){
	 		//heirarchy=[];
			for(var i=0;i<tbl5_nodes.length;i++){
				//alert(dr_num);
				sku=$(tbl5_nodes[i]).find("td:eq(0)").text();
				stock_num=$(tbl5_nodes[i]).find("td:eq(3) input:text").val();
				qty=$(tbl5_nodes[i]).find("td:eq(4) input:text").val();
				myHeir="'"+$(tbl5_nodes[i]).find("td:eq(5) button").attr('id')+"'";
				
				if(parseInt(qty)>0){
					my_arr.push([sku,stock_num,qty,"'"+myHeir+"'"]);
					if(jQuery.inArray(myHeir, heirarchy)!=0)
							heirarchy.push($.trim(myHeir));
					
				}
			}  
			/*alert(heirarchy.length);
			for(var i=0;i<heirarchy.length;i++){
					alert(heirarchy[i]);
			}*/
			/*heirarchy=jQuery.unique(heirarchy);
			alert("after unique:"+heirarchy.length);
			for(var i=0;i<heirarchy.length;i++){
					alert(heirarchy[i]);
			} 
	 		return 0;*/
			if(my_arr.length > 0){ 
				 $.ajax({
				 	type:'POST',
				 	url:'control/delivery_request.php',
				 	data:({arr:my_arr,con_num:contractnum,store:str,vndr_num:vendor_num,qty:total_qty,amt:total_amt,heirarchy:heirarchy}),
				 	success:function(out){
				 		if(out.split('-')[0]==1){
				 			$("#contract_num").val('');
				 			$("#btn_cancel").trigger('click');
				 			$("#btnvclear2").trigger('click');				 			
				 			//alert("Accepted!");	
				 			//alert(out);
				 			$(".mySku_msg").text("Successfully Submitted!");
                       		var rtn = showMiniDialog();			 			
				 		}else{
				 			//alert(out);
				 			$(".mySku_msg").text("Error : "+out);
                       		var rtn = showMiniDialog();	
				 		}		 		
				 		//if warehouse
				 		if(out.split('-')[1]==1 || out.split('-')[1] == '1')
				 			location.href="../panel/whse_delivery.php?vp_dr="+dr_num+"&sd=1";
				 		else //if store
				 			location.href="../panel/store_delivery.php?vp_dr="+dr_num+"&sd=1";
				 	}
				 });
				}else{
					alert('No data to submit');
				}
		}

	});
	$("#btn_cancel2").click(function(){
		//var my_cnfrm=confirm("This will clear your cart, Are you sure?");
		//if(my_cnfrm){
			var dtid='#dataTables-lookup-branch5';
			$(dtid).dataTable().fnDestroy();	
			$(dtid +" tbody").empty();	
			$("#grand_total .grnd_my_qty2").text('');
			$("#grand_total .grnd_my_amt2").text('');
			$("#dr_num").val('');
			$("#btn_accept2,#btn_cancel2").addClass("disabled");	
		    $(".contract_number2,.dr_num2,.vendor2,.store2").text('');	
		//}
	});
	$("#btn_accept2").click(function(){
		var dtid='#dataTables-lookup-branch5';
		var tbl5_nodes=$(dtid).dataTable().fnGetNodes();
	 	var my_arr=[];
	 	var dr_num=$(".dr_num2").text();
	 	var sku,stock_num,qty,rec_qty;
	 	var contractnum=$(".contract_number2").val();
	 	var splt1=($(".vendor2").text()).split(":");
	 	//var splt2=splt1[1].split("--");
	 	var store=$(".store2").text();

	 	var vendor_num=$.trim(splt1[0]);

	 	var total_qty=$("#grand_total .grnd_my_qty2").text();
		var total_amt=$("#grand_total .grnd_my_amt2").text();
		var remarks=$.trim($("#ApprovReason").val());
		if(remarks==""){
			alert("Please Fill-up Remarks!")
			return 1;
		}

	 	var confrm=confirm("Are you sure?");
		//dr_num:dr_num,arr:my_arr,con_num:contractnum,store:store,vndr_num:vendor_num,qty:total_qty,amt:total_amt
		var total_rec=0;
	 	if(confrm){
			for(var i=0;i<tbl5_nodes.length;i++){
				//alert(dr_num);				
				sku=$(tbl5_nodes[i]).find("td:eq(0)").text();				
				stock_num=$(tbl5_nodes[i]).find("td:eq(3)").text();				
				qty=$(tbl5_nodes[i]).find("td:eq(4) input:text").val();				
				rec_qty=$(tbl5_nodes[i]).find("td:eq(5) input:text").val();				
				//if(parseInt(rec_qty)>0){
				if(rec_qty==''){
				   rec_qty=0;						
				}	
				total_rec+=rec_qty;
				my_arr.push([sku,stock_num,qty,rec_qty]);
				//alert(my_arr[i]);
				//alert("6");
				//alert(tbl5_nodes.length);
			}	
			  
			if(total_rec < 1){
				alert('Nothing to Receive!');
			}
			else if(my_arr.length > 0){
				 $.ajax({
				 	type:'POST',
				 	url:'control/accept_rec.php',
				 	data:({dr_num:dr_num,arr:my_arr,store:store,vndr_num:vendor_num,qty:total_qty,amt:total_amt,remarks:remarks}),
				 	success:function(out){
				 		//alert(out);
				 		if(out==1){
				 			//$("#btn_cancel").trigger('click');
				 			//$("#btnvclear2").trigger('click');
				 			alert("Accepted!");
				 			$("#btn_cancel2").trigger('click');
				 			window.location.href="store_delivery.php?vp_dr="+dr_num;
				 		}else if(out==1){
				 			//$("#btn_cancel").trigger('click');
				 			//$("#btnvclear2").trigger('click');
				 			alert("DR Status Not open");
				 			$("#btn_cancel2").trigger('click');
				 		}			 		
				 	}
				 }); 
			}else{
				alert('No data to submit');
			}
		}

	});
	$("#btn_find_dr").click(function(){ 
		var dtid='#dataTables-lookup-branchdr'; 
		//var getID=getGid();

		/*gGroupIdSplit =getGid();		 
		splitData=gGroupIdSplit.split("-");
		gId = splitData[0];
		gCanApprove = splitData[1];
		gCanReceive = splitData[2];
		gIsCoor = splitData[3];
		gCanVwOrdrNoti = splitData[4];
		gCanVwRcvNoti = splitData[5];
		gIsPa = splitData[6];*/
		//if($.trim($("#dr_num").val()) == '')
				//getUrl = 0;

		//var gSplit=getID.split("-"); 
		//var gId=gSplit[0]; 
		var canReceive = gCanReceive;
		var isCoor = gIsCoor;
 

		//$("html").mask("loading");		
		$("#loadingGif").show();
		$.ajax ({
			type:'POST',
			url: 'control/list_dr.php',
			dataType: 'json',
			data:{flag:1,isCoor:isCoor},			       			
			success:function(out){
				//alert(out);
				$(dtid).dataTable().fnDestroy();	
			 	$(dtid +" tbody").empty();	
			 	 
				if (!$.fn.DataTable.isDataTable(dtid)) {
					var dtdata = '';
					var dr='';
					var back;
					var badge=''; 
					var styles='';
					var approvalStyle='';
					var approvalStyleForTrf='';
					for(var i = 0; i < out.length; i++ ){
		 				dr="'"+out[i]['dr']+"--"+out[i]['status']+"--"+out[i]['isApproved']+"'";
						approvalStyle='';
						back = '';
						if( gId == 2){
							if(getUrl == 4 && out[i]['isApproved'] == 1 && out[i]['status'] == 'Open'){
								back='style="background-color:#c6dff8;"';	
							}else if(getUrl == 3 && out[i]['status'] == 'Received' && out[i]['vVwd'] == 0){
								back='style="background-color:#c6dff8;"';	
							}
						}else if(canReceive == 1){

						}else if(gCanApprove == 1){
							if(getUrl == 3 && out[i]['status'] == 'Open' && out[i]['isApproved'] ==0){
								back='style="background-color:#c6dff8;"';	
							}else if(getUrl == 2 && out[i]['status'] == 'Received' && out[i]['mVwd'] == 0 ){
								back='style="background-color:#c6dff8;"';	
							}else if(getUrl == 22 && out[i]['pUpd'] == 0 && out[i]['vUpd'] == 1  ){
								back='style="background-color:#c6dff8;"';	
							}
						}else if(isCoor == 1){
							if(getUrl == 0 && out[i]['status'] == 'Received' && out[i]['ctorVwd'] == 0){
								back='style="background-color:#c6dff8;"';	
							}
						}else if(gIsPa == 1){

						}
						if(out[i]['isApproved']==0){
							//back='style="background-color:lightblue;"';
							approvalStyle='style="font-family:cambria;font-weight:bolder;font-size:15px;color:RED;"';
						}
						else if(gCanApprove == 1 && getUrl == 2 && out[i]['mVwd'] == 0){
							back='style="background-color:#97def5;"';
						}else if(gCanApprove == 1 && getUrl == 1 && out[i]['mVwd'] == 0){
							back='style="background-color:#97def5;"';
							//badge='<span class="label label-info" style="background:blue;"> New</span>';								
							//styles='style="font-family:cambria;font-weight:bolder;font-size:15px;color:'+(out[i]['isApproved']==-1?"RED":"BLUE")+';"';																										
						} else if(getUrl == 4){
							badge='';		
								styles='';
								/* For Vendor View */
								if(out[i]['isViewedV']==7){
									//badge='<span class="label label-info" style="background:blue;"> New</span>';								
									//styles='style="font-family:cambria;font-weight:bolder;font-size:15px;color:'+(out[i]['isApproved']==-1?"RED":"BLUE")+';"';																		
								}	
						}
						//if(canReceive == 1 && getUrl == 1)
						//	approvalStyleForTrf='style="font-family:cambria;font-weight:bolder;font-size:15px;color:RED;"';
						

						if(gId==2 && out[i]['status']=='Open' && out[i]['isApproved']==1){
							//back='style="background-color:#97def5;"';
						}
						dtdata += '<tr '+back+' >';
								//if(out[i]['isApproved']==1){						
						dtdata +=	  '<td><center><a aria-label="Close" data-dismiss="modal"  onclick="idCliked('+dr+')"  style="cursor:pointer;" >'+out[i]["dr"]+'</a></center></td>';
						dtdata +=	  '<td><center>'+out[i]['docDr']+'</center></td>';
						dtdata +=	  '<td><center>'+out[i]['vname']+' </center></td>';
								//}else{
								//dtdata +=	  '<td><center>'+out[i]["dr"]+'</center></td> ';								
								//}		
											
						dtdata	+=	  '<td>'+out[i]['date']+'</td>'
								+	  '<td '+(out[i]['status']=='Ordered'?approvalStyleForTrf:"")+'><center>'+out[i]['status']+'</center></td>'
								+     '<td '+(gId==2?styles:"")+' '+(gId!=2?approvalStyle:"")+'>'+out[i]['isApprovedDesc']+' '+(gId==2?badge:"")+'</td>'
								//+	  '<td>'+(out[i]['isWhse']==1?"Warehouse":"Store")+'</td>'
								+	  '<td style="font-size:10px;">'+(out[i]['loc']+"-"+out[i]["str_desc"])+'</td>'
								+	  '<td style="color:red;">'+out[i]['remarks']+' </td>'
								//5th col+	  '<td>'+(out[i]['isApproved']==1?"Approved":(out[i]['isApproved'])==0?"For Approval":"DisApprove")+'</td>'
								//+ 	'<td><input id='+dta+' class="form-control" type="text" style="height:25px;width:50%;"/></td>'
								//+ 	'<td>Not yet</td>'
								+'</tr>';
					}
					 
					$(dtid+" tbody").html(dtdata);
					$(dtid).dataTable({
						"columnDefs": [{ "width": "150px", "targets": 0 }],
						"bLengthChange": false,
						"iDisplayLength":10,
						"aaSorting":[[2,"desc"]],    
						"bDestroy": true,
						"oLanguage":{
							"sSearch":"Search"
						}
					});

				} 
						
			}
		});	
		//$("html").unmask();		
		$("#loadingGif").hide();
		$(".dialog-lookup-dr").modal("show");
	}); 
	$("#btn_print").click(function(){
		var dr=$("#dr_num").val();

		if(dr!=''){
			//$(".dialog-lookup-print #dr-rpt").load("generate_sale/dr_rpt.php");
			$(".dr-rpt").attr({'src': 'generate_sale/dr_rpt.php?dr='+dr+'&param='+gId+'&param2='+gCanApprove+'&param3='+gIsCoor+'&sd='+(sd==1?1:0)});

			/*commented because report is not showing but before it was working*/
			//$(".dialog-lookup-print").ready(function() {
				//setTimeout(function(){
					$(".dialog-lookup-print").modal("show");	
				//},100);
				
			//});
			
		}
		
	});
	$("#btn_cancel_dr").click(function(){
		var dr=$("#dr_num").val();
		var confrm=confirm("Are you sure you want to cancel the this DR");
		if(confrm){
			$.ajax({
				type:'GET',
				url:'control/cancel_dr.php',   
				data:({dr:dr}),
				success:function(out){
					if(out==1){					 			
						alert("DR Cancelled");
					}else{					
						alert("DR is not open/Approved by Merch Already/Ordered Status");
					}
					$("#dr_num").val('');
					$("#btn_cancel_dr").attr("disabled","disabled");
				}
			});
		}	
	});
	$("#btn_search_store").click(function(){
		//$("#loadingGif").show();
		var dtid="#dataTables-lookup-store";
		$.ajax({
			type:'GET',
			url:'control/loadStoreDr.php',  
			dataType:'json',
			//data:({vendor:vendor_num}),
			success:function(out){
				//alert(out);
				$(dtid).dataTable().fnDestroy();	
				 	$(dtid +" tbody").empty();				 	 				
						var dtdata = '';
						var dr='';
						for(var i = 0; i < out.length; i++ ){
							dr="'"+out[i]['STORE']+" : "+out[i]['STORE_NAME']+"'";
							//dr="'"+out[i]['STORE']+"'";
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
							},dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
						});		
						//$(".dialog-lookup-vendor").modal("show");
			}
		});
		$(".dialog-lookup-store").modal("show");
	});
	$("#btn_search_dept").click(function(){
		var str=$.trim($("#str_store").val());
		if(str!=''){
				var dtid="#dataTables-lookup-dept";
				$.ajax({
					type:'GET',
					url:'control/loadDept.php',  
					dataType:'json',
					//data:({vendor:vendor_num}),
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
			 $(".mySku_msg").text("Please Select Location!");
			 showMiniDialog();
		} 
	}); 
	$("#btn_search_sku2").click(function(){ 

		
		//alert("asdf");
		var dept=$("#str_dept").val();
		var splt=dept.split("--");
		var descs=splt[0];
		var sdept=splt[1];
		var clas=splt[2];
		//alert("sdf");
		var dpt=$.trim($("#str_dept").val());
		var str=$.trim($("#str_store").val());
		var dtid="#dataTables-lookup-sku";
		//alert(sdept+""+cls);
		
		//$('html').mask("loading...");
		//$(".masked").css({"overflow-y": "scroll !important"});
		
		if(dpt!='' && str!=''){
			$("#loadingGif").show();
			//$("html").mask("Loading..."); 
			//alert(cls);
			$.ajax({
				type:'GET',
				//url:'control/loadSku.php',
				async:true,
				url:'control/list_sku.php',
				data:({globalIdept:sdept,globalClas:clas}),
				dataType:'json',		    		      	 
				success:function(out){
					//alert(out.length);
					$(dtid).dataTable().fnDestroy();	
				 	$(dtid +" tbody").empty();				 	 				
				 
					var dtdata = '';
					var heir='';
					//alert(out.length);
					for(var i = 0; i < out.length; i++ ){
						//dr="'"+out[i]['STORE']+"--"+out[i]['STORE_NAME']+"'";
						//dr="'"+out[i]['STORE']+"'";
						//heir="'"+(out[i]['dept'].replace("'","`").replace("/","`"))+"--"+out[i]['isdept']+"--"+out[i]['iclas']+"'";
						heir="'"+out[i]['sku']+":"+out[i]['descr']+"'";
						dtdata += '<tr id="'+i+'">'							
								+	'<td width="600" align="Center" style="font-size:12px; font-family:arial;"><input type="checkbox"/></td>'
								//+	'<td width="600" align="Center" style="font-size:12px; font-family:arial;"><a href="#" aria-label="Close" data-dismiss="modal" onclick="clickedSku('+heir+')">'+out[i]['sku']+'</a></td>'
								+	'<td width="600" align="Center" style="font-size:12px; font-family:arial;">'+out[i]['sku']+'</td>'
								+	'<td width="600" align="left" style="font-size:12px; font-family:arial;">'+out[i]['descr']+'</td>'
								+	'<td width="600" align="left" style="font-size:12px; font-family:arial;"><a href="#" aria-label="Close" data-dismiss="modal" onclick=" "></a></td>'
								+	'<td width="600" align="left" style="font-size:12px; font-family:arial;"><a href="#" aria-label="Close" data-dismiss="modal" onclick=" "></a></td>'
								 
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
						"deferRender": true,
						 "bDestroy": true,               
                "sPaginationType": "full_numbers",
                "bDeferRender": true,   
                "bProcessing":true,
						"oLanguage":{
						"sSearch":"Search"
						}
					});		
					if(out.length > 0){
						//$("#contract_num").attr("disabled","disabled");			
						//$.ajax ({
						//	url: 'control/get_contract_info.php',
						//	dataType: 'text',
						//	data:({contract_num:contractnum[0]}),
						//	type:'GET',
							//async: false,
						//	success: function(out) {
								 //$("#my_label").text(out);
								  
						//		 $("#my_label").html("<i class='glyphicon glyphicon-star text-primary '></i> <span class='text-primary'>"+out+"</span><span class='glyphicon glyphicon-star text-primary'> </span>");
								 $("#btn_post,#btn_clear").removeClass('disabled');
						//	}
						//});

					}	
				 	$("#loadingGif").fadeOut("slow");
					$(".dialog-lookup-sku2").modal("show");

				   
					 
				}
			});
		}else{
			 $(".mySku_msg").text("Please Select Department!");
			 showMiniDialog();
		}
		//$("html").unmask();
	});
	//APPROVE DELIVERY
	$("#btn_merchApproval").click(function(){

		if($.trim($("#ApprovReason").val())==''){
			alert("Please put Remarks");
			return 0;
		}
		/*Check if any qty modified*/
		if(hasAqtyChange()){
		/* Approve of Delivery Request */
			alert("You modify qty of SKU!");
			if($.trim($("#ApprovReason").val())==''){
				alert("Please put Remarks why you change qty!");
			}else{
				delApproval(1);
			}
			
		}else{
			delApproval(1);


		}
		
	});
	//DISAPPROVE DELIVERY
	$("#btn_merchDisapproval").click(function(){
		//alert(arrOrgQty.length);
		for(var i=0;i<arrOrgQty.length;i++){
			//alert(arrOrgQty[i][0] +"-"+arrOrgQty[i][1]+"-"+arrOrgQty[i][2]);
		}
		disApproval();
	});
	$("#ApprovReason").attr("disabled","disabled");	

	$("#dataTables-lookup-branch5 tbody").on("click","tr",function(event){		
		var nodeNam=(event.target.nodeName).toLowerCase();		
		 
		var tbl=$("#dataTables-lookup-branch5").DataTable();
		var cel=$(this).index();
		//var getID=getGid();
		//var gSplit=getID.split("-");

		/*splitData=gGroupIdSplit.split("-");
		gId = splitData[0];
		gCanApprove = splitData[1];
		gCanReceive = splitData[2];
		gIsCoor = splitData[3];
		gCanVwOrdrNoti = splitData[4];
		gCanVwRcvNoti = splitData[5];
		gIsPa = splitData[6];*/


		var gId = gId;
		var canApprove = gCanApprove;
		var canReceive = gCanReceive;

		/*canReceive for merch to approve vendor delivery request
		  function for modifying qty.	*/
		//alert("Canreceeve:"+canReceive);
		if(canReceive!=1){
			//alert(event.target.nodeName);                                                                                                                                                         
			//alert(cel);
		//}else{
			var rows=$("#dataTables-lookup-branch5").dataTable().fnGetNodes(); 
			var stockNum=$(rows[i]).find("td:eq(4) input").val();
			//var i=tbl.row(this).index(); 
			var i=tbl.rows({order:'applied'}).nodes().indexOf(this);
			//var rowNum=tbl.rows({order:'applied'}).nodes().indexOf(this);
			//var y=tbl.col(this).index();
			//alert(cel);
			if(nodeNam=="button" || nodeNam=='i'){		
				//alert(nodeNam);				
				$(rows[i]).find("td:eq(4) input").val('0');
				arrOrgQty[i][2]=0;								
 				getFromArrTOtal(gId);

 				var qty=$(rows[i]).find("td:eq(4) input").val();
				var orgQty=arrOrgQty[i][1];
				/* get the column index of input . For canApporve only or Merch only.*/
			    if(canApprove==1){
				    /****color red the row if there is change in qty****/
				    if(qty!=orgQty){
				    	//$(rows[i]).find("td:eq(4)").css("background-color","RED");				    	
				    	$(rows[i]).find("td:eq(4)").children("input").css({"border":"5px solid","border-color":"RED"});
				    }else{
				    	//$(rows[i]).find("td:eq(4)").css("background-color","");
				    	$(rows[i]).find("td:eq(4)").children("input").css({"border":"1px solid","border-color":""});
				    }				  
				}

			}else if(nodeNam=='input'){

			}			
		}
		//alert(cel);
		//alert(event.target.);
		//var tr=$(this).parents('tr');
		//var rows=tbl.row(tr);			 
	    //var i=tbl.row(this).parent("tr");//.index();
	    //var i=tbl.row(this).index(); 		  
	    //alert(rows.children('td:eq(4) input').val());
		
		//$(rows[i]).find("td:eq(0)").text();
		//alert($(rows[i]).find("td:eq(0)").text());
		//alert(stockNum);
		//var userid=$(rows[i]).find("td:eq(0)").text();
		//var status=$(rows[i]).find("td:eq(1)").text();
		//if($(rows[i]).find("td:eq(2) input").prop("checked")){

		//}
	});
	$("#dataTables-lookup-branch5 tbody").on("keyup","tr",function(event){	
		var nodeNam=(event.target.nodeName).toLowerCase();		
		//alert(nodeNam);
		var tbl=$("#dataTables-lookup-branch5").DataTable();	
		var cel=$(this).index();		
		//var getID=getGid();
		/*gId = splitData[0];
		gCanApprove = splitData[1];
		gCanReceive = splitData[2];
		gIsCoor = splitData[3];
		gCanVwOrdrNoti = splitData[4];
		gCanVwRcvNoti = splitData[5];
		gIsPa = splitData[6];*/


		//var gSplit=getID.split("-");
		var gId=gId;
		var canApprove = gCanApprove;
		var canReceive = gCanReceive;
		//alert(event.target.parent());
		//alert(canReceive);
		/*canReceive for merch to approve vendor delivery request
		  function for modifying qty.	*/
		//if(canReceive==1){			 
			if(nodeNam=='input'){
				
				var colIndex=$(event.target).closest("td").index();
				if(colIndex==4){
					var rows=$("#dataTables-lookup-branch5").dataTable().fnGetNodes(); 
					var i=tbl.row(this).index(); 
					//var y=tbl.columns(this).index(); 
					var qty=$(rows[i]).find("td:eq(4) input").val();
					var orgQty=arrOrgQty[i][1];
					var newQty=arrOrgQty[i][2];
					var skuCost=arrOrgQty[i][3];
					//alert("y:"+y);
					$(rows[i]).find("td:eq(4) input").val(Math.abs(qty));
					qty=Math.abs(qty);
					if(qty==''){

						qty=0;
						$(rows[i]).find("td:eq(4) input").val(qty);
					}

					arrOrgQty[i][2]=qty;					
					//alert("origr:"+orgQty+"  new:"+qty+"  cost:"+skuCost);
				    if(qty != newQty){
				    	 getFromArrTOtal(gId);
				    	//=alert("asf");			    	
				    }
				    /* get the column index of input . For canApporve only or Merch only.*/
				    if(canApprove==1){
					    /****color red the row if there is change in qty****/
					    if(qty!=orgQty){
					    	//$(rows[i]).find("td:eq(4)").css("background-color","RED");
					    	$(rows[i]).find("td:eq(4)").children("input").css({"border":"5px solid","border-color":"RED"});
					    	//$("td:eq(4)").css("backgroud-color","RED");
					    	//alert(qty);
					    	//alert("234");
					    	//$(rows[i]).find("td:eq(4)").css("background-color","none");
					    }else{
					    	$(rows[i]).find("td:eq(4)").children("input").css({"border":"1px solid","border-color":""});
					    	//$(rows[i]).find("td:eq(4)").css("background-color","");
					    }
					    //alert($(rows[i])..parent("td").index());
					}

				}				
			}	
			
		//}
	});
	/*ON load of delivery windows dialog */
	$(".dialog-lookup-deliveryWindow").on("shown.bs.modal", function () {
		 $("#delWin_dr_num").focus();
		 
	})
	$(".dialog-lookup-deliveryWindow").on("hidden.bs.modal", function () {
		 $("#delWin_dr_num").val('');
		 $("#delWin_date").val('');	
	});	
	/* Set DATE  GREATHER THAN CURRENT DATE*/
	$("#delWin_date").datepicker({
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
	/* set date in delivery window */
	$("#btn_date_delWin").click(function(){
		$("#delWin_date").datepicker("show");
	}); 
	/* submit delivery window */
	$("#btn_submit_delWin").click(function(){
		 
		 var delWin_dr_num=$.trim($("#delWin_dr_num").val());
		 var delWin_date=$.trim($("#delWin_date").val());
		 var delWin_remarks=$.trim($("#delWin_remarks").val());
		 if(delWin_dr_num=="" || delWin_date =="" || delWin_remarks==""){
		 	alert("Please Fill-up Fields!");
		 }else{		 	
		 	var cnfrm=confirm("Are you sure?, Click Cancel if not");
		 	if(cnfrm){
		 		var returns=checkDrIfExist(delWin_dr_num);
		 		if(returns>0){
		 			alert("DR# already exist!");
		 		}else{
		 			 //inserst
		 			 var returns=insertDelWin(delWin_dr_num,vp_dr,delWin_date,delWin_remarks);
		 			 //alert(returns);
		 			 if(returns>0){
		 			 	alert("Success!");		 			 	
					   	window.location.href="store_delivery.php?vp_dr="+vp_dr+'&sd=1';	

		 			 }else{
		 			 	alert("Not Inserted");
		 			 }
		 			$(".dialog-lookup-deliveryWindow").modal("hide");
		 		}
		 		
		 	}
		 }
		
	}); 
	/*key up in delWin_date field*/
	$("#delWin_date").keydown(function(e){
		e.preventDefault();
	});
	//if(getUrl==1 || getUrl == 2){
	if(getUrl){
	 	$("#btn_find_dr").trigger("click");
	}

	if($.trim(getVp_dr)!=''){
		$("#dr_num").val(getVp_dr);
	 	$("#btn_print").trigger("click");
	}

	//$('#files').change(handleFile);
   // upExcel();
   excelUpload();
   
		
}); // END 					END 					END 							END

 function excelUpload(){

 		$(".dialog-lookup-sku2 #files").on("change", function (e) { 
 			// $("body").css({"overflow":"scroll"});
 			// $("html").mask("Loading...");	
 			$("#loadingGif").show(); 	
 			setTimeout(function(){
 					// $("body").css({"overflow":"scroll"});
 					
			},500); 					
                var excelFile,
                    fileReader = new FileReader();
                 var counter=0;
                 var updat=0;
                 var mymsg='';
               // $("#result").hide();
               
                fileReader.onload = function (e) {
                    var buffer = new Uint8Array(fileReader.result);

                    $.ig.excel.Workbook.load(buffer, function (workbook) {
                        var column, row, newRow, cellValue, columnIndex, i,
                            worksheet = workbook.worksheets(0),
                            columnsNumber = 0,
                            gridColumns = [],
                            data = [],
                            worksheetRowsCount;

                        // Both the columns and rows in the worksheet are lazily created and because of this most of the time worksheet.columns().count() will return 0
                        // So to get the number of columns we read the values in the first row and count. When value is null we stop counting columns:
                        while (worksheet.rows(0).getCellValue(columnsNumber)) {
                            columnsNumber++;
                        }

                        // Iterating through cells in first row and use the cell text as key and header text for the grid columns
                        for (columnIndex = 0; columnIndex < columnsNumber; columnIndex++) {
                            column = worksheet.rows(0).getCellText(columnIndex);
                            //gridColumns.push({ headerText: column, key: column });
                        }

                        // We start iterating from 1, because we already read the first row to build the gridColumns array above
                        // We use each cell value and add it to json array, which will be used as dataSource for the grid
                       
                        var dtid='#dataTables-lookup-sku';		 
					 	var tbl=$(dtid).DataTable();					 
						var rows=$("#dataTables-lookup-sku").dataTable().$('tr',{'filter':'applied'}); 		 
						
						var stock='';
						var qty=0;
						var sku=0;
						var myId;
						updat=0;
						counter=0;
                        for (i = 1, worksheetRowsCount = worksheet.rows().count() ; i < worksheetRowsCount; i++) {
                            newRow = {};
                            row = worksheet.rows(i);

                            /*for (columnIndex = 0; columnIndex < columnsNumber; columnIndex++) {
                                cellValue = row.getCellText(columnIndex);
                                newRow[i] = cellValue;
                               // alert(cellValue);
                            }*/

                            $(dtid).dataTable().fnFilter(row.getCellText(0));
                            //var u=tbl.rows({filter:'applied'}).nodes().indexOf(this);	
                            rows=$("#dataTables-lookup-sku").dataTable().$('tr',{'filter':'applied'}); 	
                            //var u=tbl.rows( {order:'index', search:'applied'} ).nodes();
                            sku=row.getCellText(0);
                            stock=row.getCellText(1);
                            qty=parseInt(row.getCellText(2));
                            //table.rows( {order:'index', search:'applied'} ).nodes();
                            //alert("u:"+tbl.row( 0 ).index());
                            myId=$(rows).attr("id");
                           // alert("txt:"+$(rows[0]).find("td:eq(1)").text());
                            //$(rows[0]).find("td:eq(2)").text(row.getCellText(0));
                            //alert(tbl.row( 0 ).index());
                            //alert(tbl.fnGetPosition( $(rows[]).closest('tr')[0] ));
                            //alert(table.row(rows).index());
                            //alert($(tbl+" tr").row(index));
                           // var anNodes = $(dtid+" tbody tr");
                         // var x=tbl.rows({order:'applied'}).nodes().indexOf(rows);
                          //var y= tbl.fnGetPosition( $(rows[0]) );
                          //  alert(y);
                          //alert($(dtid+' tr:visible').index());
                          //alert(tbl.rows({order:'applied'}).nodes().indexOf($(dtid+' tr:visible')));
							//for (var i = 0; i < anNodes.length; ++i)
							//{
							//var rowData = oTable.fnGetData( anNodes[i] );
							//alert(i);
							//some stuff with the obtained data
							//...
							//}

                       	 	//alert(myId);

                           // alert(row.getCellText(0)+"-"+row.getCellText(1));
                           if(sku==parseInt($.trim($(rows[0]).find("td:eq(1)").text()))){
                           	//alert("asdf");
	                            if(!$(rows[0]).find("td:eq(0) input:checkbox").prop("checked") ){
	                            	$(rows[0]).find("td:eq(0) input:checkbox").prop("checked",true);
									if(!$(rows[0]).find("td:eq(3) input,td:eq(4) input").length){
									 
												$(rows[0]).find("td:eq(3)").append("<input type='text' style='text-align:right;width:60%;' value='"+stock+"' tabIndex='6' class='form-control' maxlength='5' />");
												$(rows[0]).find("td:eq(4)").append("<input type='text' style='text-align:right;width:60%;' value='"+qty+"' tabIndex='6' class='form-control' maxlength='5' />");
												$(rows[0]).find("td:eq(3) input").focus();
												multArr.push([myId," ",0]);	 	
										 
									}
									counter++;
									
								}else{
								 	 $(rows[0]).find("td:eq(4) input:text ").val(parseInt( $(rows[0]).find("td:eq(4) input:text ").val())+qty);
								 	 updat++;
								}
								
						   }

                            //data.push(newRow[0],"'"+newRow[1]+"'",newRow[2]);
                           // alert(data);
                        }
                        //$("html").unmask();	
                        mymsg=counter>0?"Done Uploading "+counter+"  Records":updat>0?"Done Updating Records":"No Changes/Invalid Data Template";
                        $("#loadingGif").hide();
                        $(dtid).dataTable().fnFilter("");
                        $(".mySelectedSku").text("("+multArr.length+")");

                        $(".mySku_msg").text(mymsg);
                        showMiniDialog();
                        //if(multArr.length>0 &&)

                        // we can also skip passing the gridColumns use autoGenerateColumns = true, or modify the gridColumns array
                       // createGrid(data, gridColumns);
                      // alert(data);
                    }, function (error) {
                        //$("#result").text("The excel file is corrupted.");
                        //$("#result").show(1000);
                       $("#loadingGif").hide();
                        $(".mySku_msg").text('The excel file is corrupted!');
			 		 	showMiniDialog();

                    });
                }                               			 		
                if (this.files.length > 0) {

                    excelFile = this.files[0];
                    if (excelFile.type === "application/vnd.ms-excel" || excelFile.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || (excelFile.type === "" && (excelFile.name.endsWith("xls") || excelFile.name.endsWith("xlsx")))) {
                        fileReader.readAsArrayBuffer(excelFile);
                   }else{
                   		$("#loadingGif").hide();
                   		 $(".mySku_msg").text('Excel files only!(.xlsx)');
			 		 	showMiniDialog();
                   }

               }
               $(".dialog-lookup-sku2 #files").val('');
 			
 			
           });

 }


 function upExcel(){
 	$("#uploadExcel").click(function(){
 		 // var form_date = new FormData();
		  var file_data = $("#files").prop("files")[0]; // Getting the properties of file from file field
		  var form_data = new FormData(); // Creating object of FormData class
		  form_data.append("file", file_data) // Appending parameter named file with properties of file_field to form_data
		  form_data.append("user_id", 123) // Adding extra parameters to form_data
		  $.ajax({
		    url: "control/excel.php", // Upload Script
		    dataType: 'json',
		    cache: false,
		    contentType: false,
		    processData: false,
		    data: form_data, // Setting the data attribute of ajax with file_data
		    type: 'post',
		    success: function(data) {
		      // Do something after Ajax completes 
		      alert(data);
		    }
		  });
 	});
 }
 function handleFile(e) {
     //Get the files from Upload control
        var files = e.target.files;
        var i, f;
        var arr=[];
     //Loop through files
        for (i = 0, f = files[i]; i != files.length; ++i) {
            var reader = new FileReader();
            var name = f.name;
            reader.onload = function (e) {
                var data = e.target.result;

                var result;
                var workbook = XLSX.read(data, { type: 'binary' });
                
                var sheet_name_list = workbook.SheetNames;
                sheet_name_list.forEach(function (y) { /* iterate through sheets */
                    //Convert the cell value to Json
                    var roa = XLSX.utils.sheet_to_json(workbook.Sheets[y]);
                    if (roa.length > 0) {
                        result = roa;
                          $.each(result, function (index, value) {
				                 arr.push(value);     // PUSH THE VALUES INSIDE THE ARRAY.
				            });
                       // arr.push(result);
                       for(var i=0;i<arr.length;i++){
                       		alert(arr[i]);
                       }
                    }
                });
               //Get the first column first cell value
               // alert(result[0].Column1);
            };
            reader.readAsArrayBuffer(f);
        }
        alert(arr.length);
 }

function getFromArrTOtal(gid){
	var totalAmt=0;
	var totalQty=0;
	for(var i=0;i<arrOrgQty.length;i++){
		totalAmt+=parseFloat(arrOrgQty[i][2])*arrOrgQty[i][3];
		totalQty+=parseFloat(arrOrgQty[i][2]);
		//alert(totalQty);
	}	 
	 
	/*gid=2 for vendor else for merch/ops*/
	var rtn_total_amt=numFormat(totalAmt);
	if(gid==2){
		showGrandTotal2(totalQty,rtn_total_amt);
	}else{
		showGrandTotal(totalQty,rtn_total_amt);	
	}
	
	//alert("sdf");
}
function selectInput(){

}
function clickedSku(sku){
	//alert(sku);
	$("#txt_sku").val($.trim(sku.split(":")[0]));
	//var sku=$(this).find("td:eq(0)").text();
		var descr=$.trim(sku.split(":")[1]);
		//$("#txt_sku").val(sku);
		$("#my_descr").html("<i class='glyphicon glyphicon-star text-primary '></i> <span class='text-primary'>"+descr+"</span><span class='glyphicon glyphicon-star text-primary'> </span>");
		$("#sku_qty,#stock_num").removeAttr("disabled");

		setTimeout(function(){
			$("#stock_num").val('');
			$("#stock_num").focus();
		},500);
}

function clickedDept(dept){
	var splt=dept.split("--");
	var desc=$.trim(splt[0]);
	var sdept=splt[1];
	var cls=splt[2];
	//alert(desc+"-"+sdept+"-"+cls);
	globalsDept = sdept;
	globalClas = cls;

	dept = desc+"--"+sdept+"--"+cls;
	$("#str_dept").val(dept);
	$("#txt_sku").val('');
}

function clickedStore(store){
	$("#str_store").val(store);
	$("#str_dept").val('');
	$("#txt_sku").val('');
}

function contractCliked(contract_num){
	//alert(contract_num);
	$("#contract_num").val(contract_num);

}
function order(sku){
	
	//$(".dialog-lookup-4 #sku_label").text(sku); 
	//$(".dialog-lookup-4").modal("show");
	//$(".dialog-lookup-4 tbody").append("<tr><td><input  class='form-control'></td><td><input class='form-control'></td></tr>");	 
}
function checkDr(dr){
	globalRet='';
	var outs='';
	 $.ajax({
			type:'GET',
			url:'control/checkDr.php',
			async:false,
			data:({dr:dr}),
			dataType:'text',
	    	success:function(out){
	    		if(out==0){
	    			outs='-1';
	    		}else{
	    			outs=out;
	    		}

	    		
	    	}
     });
     //alert(outs);

     return(outs);
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
function idCliked(dr){
    	
	var splt=(dr).split("--");
	var dr=$.trim(splt[0]);
	var status_desc=$.trim(splt[1]);
	var isMerchApproved=$.trim(splt[2]);
	var dtid='#dataTables-lookup-branch5';

	var drSplit=checkDr(dr);                   
	//alert(drSplit);
	var spltReturn=(drSplit).split("--");
	var status_desc2=$.trim(spltReturn[1]);
	var isMerchApproved2=$.trim(spltReturn[2]);
	//var gidReturn=getGid();

	/*gCanApprove = splitData[1];
	gCanReceive = splitData[2];
	gIsCoor = splitData[3];
	gCanVwOrdrNoti = splitData[4];
	gCanVwRcvNoti = splitData[5];
	gIsPa = splitData[6];*/

	//var gidSplit=gidReturn.split("-"); 
	var gid = gId;
	var canApprove = gCanApprove;
	var canReceive = gCanReceive;
	var isCoor = gIsCoor;
	vp_dr=dr;
	//alert(status_desc);
	//alert(status_desc2);
	//alert(isMerchApproved2);
	//$("html").mask(" ");	
	$("#loadingGif").show();
	//alert("gid:"+gid+"-canApprove:"+canApprove+"-canReceive:"+canReceive+" isMerchApproved2:"+isMerchApproved2);
	$("#dr_num").val(dr);
	$(".dr_num2").text(dr);
    total_amt=0
    total_qty=0;
 
    $(dtid).dataTable().fnDestroy();	
	$(dtid +" tbody").empty();	
	$("#grand_total .grnd_my_qty2").text('');
	$("#grand_total .grnd_my_amt2").text('');
	//for vendor only-------------------------------------
    $.ajax({
    	type:'GET',
    	url:'control/check_status.php',
    	data:({dr:dr}),
    	async:false,
    	success:function(out){
    		if(out==1){
    			$("#btn_cancel_dr").removeAttr("disabled");
    		}
    	}
    });
    //------------------------------------------------- ---
    dr_number=0;
    //alert(status_desc2);
    //alert(isMerchApproved2);

    /*status_desc2 ; 1=open, 0=received*/
    $("#btn_accept2,#btn_cancel2,#btn_merchApproval,#btn_merchDisapproval").addClass("disabled");	    
    /* for merch only gid==3 */
    if(gid==3){
    	if(drSplit==0) {
    		//alert("DR# not found with your userid");
    		$(".mySku_msg").text('DR# not found with your userid!');
			showMiniDialog();
	    }
	    else if(status_desc2==1 && isMerchApproved2==0){	   
	    	//approve==0 for merch to approved
			ajaxLoadSku(dr,canApprove,canReceive);	
			//fromNotificationSetSession();/* Set Session when For Receiving is loaded */			
		}	
	}/* for vendor only gid==2 */
	else if(gid==2){		
		if(status_desc2==1 && isMerchApproved2==1){
			/* ordered status */
			showDialogDeliveryWindow(dr);
		}else if(status_desc2==2){
			//alert("Status : Ordered");
		}
	}else{
		//alert(status_desc2);
		//alert(isMerchApproved2);

		if(drSplit==0) {
    		$(".mySku_msg").text('DR# not found with your userid!');
			showMiniDialog();
	    }/* show skus with approval from merch and ordered status*/
	    else if(status_desc2==2 && isMerchApproved2==1){
	    	//arrOrgQty=[];
	    	//alert("a");
			ajaxLoadSku(dr,canApprove,canReceive);
			//fromNotificationSetSession();/* Set Session when For Receiving is loaded */	
		}else if(isMerchApproved2==0 && isCoor!=1){
			//alert("DR not yet approved"); 
			$(".mySku_msg").text('Not yours!');
			showMiniDialog();
		}	
	}   
	/*if canApprove set DR session */
	if(canApprove==1){
		setDR(dr);
	}
	//$("html").unmask();
	$("#loadingGif").hide();
}
//load
function ajaxLoadSku(dr,canApprove,canReceive){ 
	var dtid='#dataTables-lookup-branch5'; 
	$.ajax({
		type:'GET',
		url:'control/rec_list.php',
		//async:false,
		data:({dr:dr}),
		dataType:'json',
		success:function(out){	
		  //alert(out);
		  $(dtid).dataTable().fnDestroy();	
		  $(dtid +" tbody").empty();	
		  
		  	 //alert(out.length);
			if (!$.fn.DataTable.isDataTable(dtid)) {
				var dtdata = '';		
				for(var i=0;i<out.length;i++){
															
						dtdata += '<tr>'
								+	'<td width="20">'+out[i]["sku"]+'</td>'
								+	'<td width="120">'+out[i]["descr"]+'</td>'
								+ 	'<td width="20" align="right">'+numFormat(parseFloat(out[i]["cost"]))+'</td>'
								+ 	'<td width="40" style="text-align:center;">'+out[i]["stock_num"]+'</td>'
								if(canApprove==1){//merch can modify qty requested
						dtdata +=	'<td width="50" style="text-align:center;"><input style="text-align:right;width:100px;" class="form-control col-xs-1" type="text" value="'+out[i]["qty"]+'" /></td>';		
								}else{            //non merch, str ops  
						dtdata +=   '<td width="50" style="text-align:right;">'+numFormat(parseInt(out[i]["qty"]).toFixed(2))+'</td>';			
								}								
								//if(out[0]['gid']!=3){
								if(canReceive==1){
						dtdata +=   '<td width="20"><input id="in_rec_qty-'+parseInt(out[i]["qty"])+'" type="text" style="text-align:right;width:100px;" class="form-control col-xs-1   " value=""/></td>'
								}else{
									if(canApprove==1){
						dtdata +=     '<td><button type="button" class="btn btn-default btn-circle">'+out[i]["qty"]+'&nbsp;&nbsp;<i class="fa fa-times fa-f5"></i></button></td>'	
									}else{
						dtdata +=     '<td width="20"></td>'		 	
								    }
								}
						dtdata+='</tr>';					
					arrOrgQty.push([out[i]["sku"],out[i]["qty"],out[i]["qty"],out[i]["cost"],out[i]['stock_num']]);	
					total_amt+=out[i]["cost"]*out[i]["qty"];
					total_qty+=parseInt(out[i]["qty"]);	 
			    }	
		   }	
			$(dtid+" tbody").html(dtdata);
			$(dtid).dataTable({
				"columnDefs": [{ "width": "150px", "targets": 0 }],
				"bLengthChange": false,
				"iDisplayLength":5,						    
				"bDestroy": true,
				"oLanguage":{
					"sSearch":"Search"
				}
		 	});

		 dr_number=dr;			  
		 $(".contract_number2").text(out[0]["contract_num"]);
		 $(".vendor2").text(out[0]["vendor_code"]+ "    "); //$(".vendor2").text(out[0]["vendor_code"]+ "  :  "+out[0]['ccmnam']);
		 $(".store2").text(out[0]["store"]);
		 if(out.length > 0){
		 	$("#btn_accept2,#btn_cancel2").removeClass("disabled");	
		 	$("#ApprovReason").removeAttr("disabled");
		 }
		 if(canApprove==1){
		 	$("#btn_merchApproval,#btn_merchDisapproval").removeClass("disabled");	
		 	$("#ApprovReason").removeAttr("disabled");
		 	//$("#btn_merchApproval").remove();
		 }
		 //show grandtotal of qty & cost  of skus loaded	
		 var rtn_total_amt=numFormat(total_amt);	 	
		// alert(rtn_total_amt);		 		
		 showGrandTotal(total_qty,rtn_total_amt);
		}
	});
}
function showGrandTotal(totalqty,totalamt){
	 $.trim($("#grand_total .grnd_my_qty2").text(totalqty.toFixed(2)));
	 $.trim($("#grand_total .grnd_my_amt2").text(totalamt));	
}
function showGrandTotal2(totalqty,totalamt){
	 $.trim($("#grand_total .grnd_my_qty").text(totalqty.toFixed(2)));
	 $.trim($("#grand_total .grnd_my_amt").text(totalamt));	
}
function disApproval(){
	var dtid='#dataTables-lookup-branch5';
	var rw=$(dtid).dataTable().fnGetNodes();
	 
	if(rw.length>0 && dr_number!=0){
	 	var reson=$.trim($("#ApprovReason").val());	
	 	if(reson!=''){
	 		 //var cnfrm=confirm("Are you sure you want to DisApprove?");
			 //if(cnfrm){
			 	delApproval(-1);
			 //}
		}else{
			alert('Please FIll-up Reason!');
			$("#ApprovReason").focus();
		}
    
	}
}
function delApproval(flag){
	//alert(flag);
	/*flag=1 approve ; flag=-1 disapprove*/
	var dtid='#dataTables-lookup-branch5';
	var rw=$(dtid).dataTable().fnGetNodes();
	var ifQtyModified=0;
	if(rw.length>0 && dr_number!=0){
		//alert(dr_number);
		var drSplit=checkDr(dr_number);
		var spltReturn=(drSplit).split("--");
		var isOpen=$.trim(spltReturn[1]);
		var isMerchApproved=$.trim(spltReturn[2]);
		var remarks=$("#ApprovReason").val();
		/** dr not associated with the userid **/
		if(drSplit==-1){   
			alert("Dr Not associated with userid!"); 
		}else{

			var cnfrm=confirm("Are you sure you want to "+(flag==-1?"Disa":"A")+"pprove?");
			if(cnfrm){
				/* if qty modified set flag for php flag hasQqtyChange() return boolean*/
				if(hasAqtyChange()){
					ifQtyModified=1;
					setArrForQtyMod();
				}	
				arrOrgQty=[];
				$.ajax({
					type:'POST',
					url:'control/approveDr.php',  
					async:false,
					data:({isApprov:flag,dr:dr_number,remarks:remarks,ifQtyModified:ifQtyModified,dataArr:arrModQty}),
					dataType:'text',
					success:function(out){	
						//alert(out);
					   if(out==1){
					   		_clearForm();
					   		 
					   		//window.location.href="store_delivery.php?vp_dr="+dr_number;	
					   		alert("DR Approved!");
					   		window.location.href="store_delivery.php?vp_dr="+dr_number+"&sd=1";	
					   }else if(out==-1){
					   		_clearForm();
					   		alert("DR Disapproved");
					   }else{
					   		alert("Failed : "+ out);
					   }
					},
					error:function(out){
						alert("Unsuccessful, Please Retry");
					}
				});		
			} 
		}	
	}
}
function approveDr(dr){	
			
}
function _clearForm(){
	var dtid='#dataTables-lookup-branch5';
	$(dtid).dataTable().fnDestroy();	
	$(dtid +" tbody").empty();	
	$("#grand_total .grnd_my_qty2").text('');
	$("#grand_total .grnd_my_amt2").text('');
	$("#btn_merchApproval,#btn_merchDisapproval,#AReason").addClass("disabled");
	$("#ApprovReason").val('');
	$("#ApprovReason").attr("disabled","disabled");		
	$("#btn_clear").trigger('click');
}

function numFormat(total_amt){
	//alert(total_amt); - ng alert when tested  
	var rtn=0;
	$.ajax({
		type:'GET',
		url:'control/numFormat.php',
		async:false,
		data:{total_amt:total_amt},
		success:function(out){
			rtn=out;
		}
	});
	return rtn;
}
/* Approving of Delivery Request . If canApprove, Or Merch modified qty return true */
function hasAqtyChange(){
	for(var i=0;i < arrOrgQty.length;i++){
		//alert("Org: "+ arrOrgQty[i][1]+ "  New:"+arrOrgQty[i][2]);
		if(arrOrgQty[i][1]!=arrOrgQty[i][2]){
			return true;
		}
	}
	return false;
}
function setDR(dr){ 
	//alert(par);
	$.ajax ({
		url: 'control/gen_dr_num.php',
		dataType: 'text',		 
		type:'GET',
		//async: false,
		data:{dr:dr},
		success: function(out) {
			$(".dr_num").text(out);			
		}
	});
}
function setArrForQtyMod(){
	var x=0;
	var mySku;
	var myNewQty;
	var myStockNum;
	for(var i=0;i < arrOrgQty.length;i++){
		//alert("Org: "+ arrOrgQty[i][1]+ "  New:"+arrOrgQty[i][2]);
		if(arrOrgQty[i][1]!=arrOrgQty[i][2]){
			mySku=arrOrgQty[i][0]; /* sku */
			myNewQty=arrOrgQty[i][2]; /* new qty */
			myStockNum=arrOrgQty[i][4]; /* new qty */
			arrModQty.push([mySku,myNewQty,myStockNum]);	
			x++;
		}
	}
	/**for(var i=0;i < arrModQty.length;i++){
		 alert(arrModQty[i][0]+"-"+arrModQty[i][1]);
	}**/
}
function showDialogDeliveryWindow(dr){ 
	vp_dr=dr;
	$(".delWin_drNum").text("VP DR # : "+dr);
	setTimeout(function(){
		$(".dialog-lookup-deliveryWindow").modal("show");
	},500);
	
}
/* Delivery Window functions */
function checkDrIfExist(dr){
	var returns; 
	 $.ajax({
	 	type:'GET',
	 	async:false,
	 	url:'control/checkDrIfExist.php',
	 	data:{dr:dr},
	 	success:function(data){
	 		//alert(data); 
	 		returns=data;
	 	}
	 });
	 return returns; 
}
function insertDelWin(dr,vpdr,delDate,remarks){
	var returns;
	 $.ajax({
	 	type:'GET',
	 	async:false,
	 	url:'control/insertDelWindow.php',
	 	data:{vp_dr:vpdr,dr:dr,delDate:delDate,remarks:remarks},
	 	success:function(data){
	 		//alert(data);
	 		returns=data;
	 	}
	 });
	 return returns;
}
function canSubmitDelWin(){

	 var delWin_dr_num=$.trim($("#delWin_dr_num").val());
	 var delWin_date=$.trim($("#delWin_date").val());
	 var delWin_remarks=$.trim($("#delWin_remarks").val());

	 if(delWin_dr_num!='' && delwin_date!='' && delWin_remarks!=''){
	 	return true;
	 }

	 return false;

}
function showMiniDialog(){
	//$(".mySku_msg").text('Please Fill up fields!');
		setTimeout(function(){
			//$(".msg-dialog").modal("show");	
			$("#miniDialog").fadeIn("slow");
			setTimeout(function(){
			//$(".msg-dialog").modal("show");	
				$("#miniDialog").fadeOut("slow");
			},2000);
	},200);
}
/*function fromNotificationSetSession(){
	$.ajax({
		type:'GET',
		async:false,
		url:'control/fromNotificationSetSession.php',
		success:function(data){

		}

	});
}*/
/*function checkSessionThenLoadForRech(){
	var returns=0;
	$.ajax({
		type:'GET',
		async:false,
		url:'control/checkSessionThenLoadForRech.php',
		data:{flag:1},
		success:function(data){
			returns=data;
		}
	});
	return returns;
}*/