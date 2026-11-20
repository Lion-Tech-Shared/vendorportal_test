$(function() {
	loadSub($("#mmenu").val());
	
	$("#btnEdit").click(function() {
		$(".dialog-menu").modal("show");			  
	});
	
	$("#mmenu").change(function() {
		loadSub($(this).val())					
	});
});

function loadSub(mmenu) {
	$.ajax ({
		url: 'control/control_menu.php?mmenu='+mmenu,
		dataType: 'json',
		//async: false,
		success: function(out) {
			$(".cont-smenu").html(out);
		}
	});
}

function btnAddSubClicked() {
	$(".dialog-menu-sub").modal("show");
}