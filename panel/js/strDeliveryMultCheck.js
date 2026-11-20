
$(function(){
	var tbl_sku="#dataTables-lookup-sku";	
	

	$("#miniDialog").hide();
	$(".mySelectedSku").text("("+multArr.length+")");
    $(".msg-dialog").on("hidden.bs.modal", function () {
		 $(".msg-dialog .my_msg").text('');
	});

	tr_sku_click(tbl_sku);
	digitsOnlyForQty(tbl_sku);
	selSku();
	_submit();
 	//alert($("#miniDialog").width());
 	var winWidHalf=$(window).width()/2;
 	var miniWidSize=$("#miniDialog").width()/2;

 	//var winHeightHalf=($(window).height()/2)/2;
 	var winHeightHalf=((screen.availHeight)/2)/2;
 	var miniHeightSize=$("#miniDialog").height()/2;

 	//alert($(".dialog-lookup-sku").offset().top);
 	//alert(screen.availHeight);
 	//alert(miniSize);
  	$("#miniDialog").css({"margin-left":(winWidHalf-miniWidSize)+"px"});
  	$("#miniDialog").css({"margin-top":(winHeightHalf-miniHeightSize)+"px"});

  	$("miniDialog").on("shown.bs.modal",function(){
  		//$(".dialog-lookup-sku2,body").css({"overflow-y":"auto"});
  	});

  	$(".dialog-lookup-sku2").on("hidden.bs.modal",function(){  	
  			multArr=[];
  			multArrSku=[];	 
  			$(".mySelectedSku").text("("+multArrSku.length+")");  			
	}).on("shown.bs.modal",function(){
		  
	});
});

function tr_sku_click(tbl_sku){
	$(tbl_sku).delegate("tr","click",function(event){

		var nodeNam=(event.target.nodeName).toLowerCase();		
		 
		var tbl=$(tbl_sku).DataTable();
		var cel=$(this).index();		

		var rows=$(tbl_sku).dataTable().fnGetNodes(); 
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
					$(rows[i]).find("td:eq(3),td:eq(4)").append("<input type='text' style='text-align:right;width:60%;' tabIndex='6' class='form-control' maxlength='5' />");
					$(rows[i]).find("td:eq(3) input").focus();
					multArr.push([i," ",0]);	 	
				}
				
			}else{
				$(rows[i]).find("td:eq(3),td:eq(4)").children("input").remove();
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
			$(tbl_sku).dataTable().fnFilter("");
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

				if(colIndex==4){
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

function preventCopyPaste(tbl_sku){
	$(tbl_sku+" tbody td input:text").bind('cut copy paste',function(event){
		event.preventDefault();		 
	});	 
}

function loopArr(){
	for(var x=0;x<multArr.length;x++){
			//alert(multArr[x][0]+"-"+multArr[x][1]+"-"+multArr[x][2]);
	}
}
function selSku(){
	var dtid='#dataTables-lookup-skuSel';
	$(".dialog-lookup-sku2 a").click(function(){
		if(multArr.length>0){
			var tbl=$("#dataTables-lookup-sku").DataTable();
			var rows=$("#dataTables-lookup-sku").dataTable().fnGetNodes(); 						 
			var i=tbl.rows({order:'applied'}).nodes().indexOf(this);

			var dtdata = '';		

			$(dtid).dataTable().fnDestroy();	
			$(dtid +" tbody").empty();

			for(var x=0;x<multArr.length;x++){
				//alert("sku:"+$(rows[multArr[x][0]]).find("td:eq(1)").text()+" description:"+$(rows[multArr[x][0]]).find("td:eq(2)").text()+" stockNum:"+$(rows[multArr[x][0]]).find("td:eq(3)").text()+"  qty:"+$(rows[multArr[x][0]]).find("td:eq(4)").text());
				var mySku=$(rows[multArr[x][0]]).find("td:eq(1)").text();
				var mySkuDesc=$(rows[multArr[x][0]]).find("td:eq(2)").text();
				var mySkuStock=$(rows[multArr[x][0]]).find("td:eq(3) input:text").val();
				var mySkuQty=$(rows[multArr[x][0]]).find("td:eq(4) input:text").val();
				//alert(mySku+mySkuDesc+mySkuStock+mySkuQty);
				dtdata  += '<tr>'							
						//+	'<td width="600" align="Center" style="font-size:12px; font-family:arial;"><input type="checkbox"/></td>'
						+	'<td width="600" align="Center" style="font-size:12px; font-family:arial;">'+mySku+'</td>'
						+	'<td width="600" align="left" style="font-size:12px; font-family:arial;">'+mySkuDesc+'</td>'
						+	'<td width="600" align="left" style="font-size:12px; font-family:arial;">'+mySkuStock+'</td>'
						+	'<td width="600" align="left" style="font-size:12px; font-family:arial;">'+mySkuQty+'</td>'
						 
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

function _submit(){
	$(".dialog-lookup-sku2 #btn_submit_sku").click(function(){
		 if(multArr.length>0){
		 	var price;
			var priceWithNoComma;
			var sku_qty;
			multArrSku=[];
			//var dtid='#dataTables-lookup-branch5';
			var dtid='#dataTables-lookup-sku';
			//var store=$.trim($("#str_store").val());
			//var dpt=$.trim($("#str_dept").val());

		 	var tbl=$("#dataTables-lookup-sku").DataTable();
			var rows=$("#dataTables-lookup-sku").dataTable().fnGetNodes(); 						 
			var i=tbl.rows({order:'applied'}).nodes().indexOf(this);		
			
			for(var x=0;x<multArr.length;x++){				
				var mySku=$(rows[multArr[x][0]]).find("td:eq(1)").text();
				var mySkuDesc=$(rows[multArr[x][0]]).find("td:eq(2)").text();
				var mySkuStock=$.trim($(rows[multArr[x][0]]).find("td:eq(3) input:text").val());
				var mySkuQty=$.trim($(rows[multArr[x][0]]).find("td:eq(4) input:text").val());
				if(mySkuStock=='' || mySkuQty==''){
					$(dtid).dataTable().fnFilter(mySku);
					$(".msg-dialog .my_msg").text('Please Fill up fields!');
					$(".mySku_msg").text('Please Fill up fields!');
					 showMiniDialog();
					//break 0;
					return 0;
				}	
				//alert("0");			 
			}
			$(".dialog-lookup-sku2").modal("hide");
			if(multArr.length > 0){

				$(".mySku_msg").text('Your Selection was added to Cart!');
		 		showMiniDialog();
		 		//$("body").css({"overflow":"auto"});	
			}
			//alert("outer");
			
				//multArr=[];
				//multArrSku.push([mySku,mySkuDesc,mySkuStock,mySkuQty]);		
				dtid='#dataTables-lookup-branch5';	
						if (!$.fn.DataTable.isDataTable(dtid)) {	
							var dtdata = '';		
						   for(var x=0;x<multArr.length;x++){				
								var mySku=$(rows[multArr[x][0]]).find("td:eq(1)").text();
								var mySkuDesc=$(rows[multArr[x][0]]).find("td:eq(2)").text();
								var mySkuStock=$.trim($(rows[multArr[x][0]]).find("td:eq(3) input:text").val());
								var mySkuQty=$.trim($(rows[multArr[x][0]]).find("td:eq(4) input:text").val());
								multArrSku.push([mySku,mySkuDesc,mySkuStock,mySkuQty]);	
						   		price=0;
								priceWithNoComma=0;					 
								//sku_qty=parseInt(sku_qty);														
									dtdata += '<tr >'
											+	'<td width="20">'+mySku+'</td>'
											+	'<td width="120">'+mySkuDesc+'</td>'
											+ 	'<td width="20"></td>'												
											+ 	'<td width="20"><input type="text" style="text-align:right;" class="form-control  col-sm-1 input-sm " value="'+mySkuStock+'"/></td>'
											+ 	'<td width="20"><input type="text" style="text-align:right;" class="form-control col-sm-1 input-sm" value="'+mySkuQty+'"/></td>'
											+ 	'<td width="20"><button id="'+globalsDept+'-'+globalClas+'" type="button" class="btn btn-default btn-circle"><i class="fa fa-times fa-f5"></i></button></td>'
											+'</tr>';	
								total_amt+=(parseFloat(0) * parseInt(mySkuQty));
								total_qty+=parseInt(mySkuQty);	
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
								
								//alert("! price:"+(price*1));
								//alert("! sku qty:"+sku_qty);
								//alert("! (parseFloat(price)*sku_qty):"+(parseFloat(price)*sku_qty));
								//sku_qty=parseInt(sku_qty);
								//var p=2 * 2;	
								//alert("100,100,100".replace(",",""));
									

								/*alert((parseFloat(priceWithNoComma) * sku_qty));
								alert(sku_qty);
							    alert(p);
			d				    alert("! total_amt:"+total_amt) ;
							    alert("! total_qty:"+total_qty);*/
													
						}
						else {
							//$(dtid).dataTable().fnClearTable();		
							 for(var x=0;x<multArr.length;x++){				
								var mySku=$(rows[multArr[x][0]]).find("td:eq(1)").text();
								var mySkuDesc=$(rows[multArr[x][0]]).find("td:eq(2)").text();
								var mySkuStock=$.trim($(rows[multArr[x][0]]).find("td:eq(3) input:text").val());
								var mySkuQty=$.trim($(rows[multArr[x][0]]).find("td:eq(4) input:text").val());	
								multArrSku.push([mySku,mySkuDesc,mySkuStock,mySkuQty]);	
						   		price=0;
								priceWithNoComma=0;					 
								
								$(dtid).dataTable().fnAddData([
									''+mySku+'',mySkuDesc,price,'<input type="text" style="text-align:right;" class="form-control input-sm" value="'+mySkuStock+'"/>','<input type="text" style="text-align:right;" 	class="form-control input-sm " value="'+mySkuQty+'"/>','<button id="'+globalsDept+'-'+globalClas+'" type="button" class="btn btn-default btn-circle"><i class="fa fa-times fa-f5"></i></button>'
									//out[i]["descr"],'<input class="form-control" id='+dta+' type="text" style="height:25px;width:50%;"/>'									
								])							 					 		 
								total_amt+=(parseFloat(priceWithNoComma) * parseInt(mySkuQty));
								total_qty+=parseInt(mySkuQty);	
							}									 
								
								$(dtid).dataTable().fnFilter("");
						}

						arrOrgQty.push([mySku,mySkuQty,mySkuQty,priceWithNoComma]);	
						if(multArr.length > 0){
							myClearFunc();
						}
						var rtn_total_amt=numFormat(total_amt);
						//alert(rtn_total_amt);
					    $("#btn_accept,#btn_cancel").removeClass("disabled");	
					    $("#grand_total .grnd_my_qty").text(total_qty.toFixed(2));
						$("#grand_total .grnd_my_amt").text((rtn_total_amt));	
			 
			//alert("multArrSku length : "+multArrSku.length);
			$(dtid).dataTable().fnFilter("");
			 
				 

		 }
		 else{	
		 	// $(".msg-dialog .my_msg").text('No Records Selected!');
		 	$(".mySku_msg").text('No Records Selected!');
		 	showMiniDialog();
		 }
		 $(".dialog-lookup-sku2 #files").val('');
	});	  
	
}
function myClearFunc(){
	multArr=[];
	multArrSku=[];		
	heirarchy=[];
	var dtid='#dataTables-lookup-sku';
	$(dtid).dataTable().fnDestroy();	
	$(dtid +" tbody").empty();	

	var dtid='#dataTables-lookup-skuSel';
	$(dtid).dataTable().fnDestroy();	
	$(dtid +" tbody").empty();					 
	$(".mySelectedSku").text("("+multArr.length+")");
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