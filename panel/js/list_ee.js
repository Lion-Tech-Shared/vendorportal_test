$(function() {
	$(document).ready(function() {
		$("#dataTables").dataTable({
			"bLengthChange": false,
			"iDisplayLength": 15,
			"fnInitComplete": function(oSettings, json) {
				$.ajax ({
					url: 'control/dt_employee.php?lctr=2',
					dataType: 'json',
					async: false,
					success: function(out) {
						listDtable("#dataTables", out)
					}
				});
			}
		});
		$("div.dataTables_filter input").focus();
	});
});

function listDtable(dtid, data) {
	$(dtid).dataTable().fnClearTable()
	for(var i = 0; i < data.length; i++ ){
		$(dtid).dataTable().fnAddData([
			data[i][0],
			data[i][1],
			data[i][4],
			data[i][5],
			'<a href="mailto:'+data[i][6]+'">'+data[i][6]+'</a>',
			data[i][3],
			data[i][7],
			data[i][8],
			data[i][2]
		])
	}
};