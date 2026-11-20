$(function() {
	$("#btnlu").click(function () {
		if($("#sbu").val().trim() != "0"){
			lookupDtable("#dataTables-lookup",$("#sbu").val().trim());
		}else{
			alert("Please Select Business Unit!")
		}
	});
	
	$(".dialog-lookup").on("shown.bs.modal", function() {
		$("div.dataTables_filter input").focus();
	});


	$("#sbu").change(function(){
		$("#vcode").val('')
	});
});

function lookupDtable(dtid,sbu) {
	$(dtid).DataTable().destroy()
	if (!$.fn.DataTable.isDataTable(dtid)) {
		
		$("html").mask("Loading...");
		$.ajax ({
			url: 'control/dt_vendor.php?lctr=1&sbu='+ sbu,
			dataType: 'json', 
			//async: false,
			success: function(out) {  
				var dtdata = '';
				for(var i = 0; i < out.length; i++ ){
					var strId = "'"+out[i][0]+" : "+out[i][1]+"'";
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
					"sPaginationType": "bs_two_button"
				});
				$("html").unmask();
			}
		});
	}
	$(".dialog-lookup").modal("show");
};

function idCliked(id) {
	$("#vcode").val(id);
};