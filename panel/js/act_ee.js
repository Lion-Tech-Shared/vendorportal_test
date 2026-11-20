$(function() {
	$("#btnlu").click(function () {
		lookupDtable("#dataTables-lookup");
	});
	$("#btn_activate").click(function(){
		var bool;
		var uid=$.trim($("#uid").val());
		var gid=$.trim($("#group").val());
		if(uid!='' && gid!=''){
			bool=confirm("Are you sure? Click cancel if not");
			if(bool){
				var bool2;
				bool2=confirm("Is the User ID member of User Group? Click Ok if yes,cancel if not");
				if(bool2){
					$("#frm_actvEmp").trigger("submit");
				} 				
			}
		}else{
			alert("Please Select User ID & User group!");
		}
		
	});
	$(".dialog-lookup").on("shown.bs.modal", function() {
		$("div.dataTables_filter input").focus();
	});
});

function lookupDtable(dtid) {
	if (!$.fn.DataTable.isDataTable(dtid)) {
		$("html").mask("Loading...");
		$.ajax ({
			url: 'control/dt_employee.php?lctr=1',
			dataType: 'json',
			async: false,
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
			},
			error:function(out){
				alert(out.length);
			}
		});
	}
	$(".dialog-lookup").modal("show");
};

function idCliked(id) {
	//alert(id);
	$("#uid").val(id);
};