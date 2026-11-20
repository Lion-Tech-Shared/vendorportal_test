$(function() {
	loadAccess($("#group").val());
	
	$("#btnAdd").click(function() {
		$(".dialog-group").modal("show");
	})
	
	$("#btnUpdate").click(function() {
		checkMCboxes();
	})
	
	$("#btnDelete").click(function() {
		/*wrong*/
		//deleteMenu($("#group").val());
	})
	
	$("#group").change(function() {
		loadAccess($(this).val());					
	});
});

function loadAccess(group) {
	$(".cont-access").html("");
	$.ajax ({
		url: 'control/control_user_group.php?lctr=1&group='+group,
		dataType: 'json',
		//async: false,
		success: function(out) {
			$(".cont-access").html(out);
		}
	});
}

function cbAction(id) {
	var sep = id.split("-");
	
	$.ajax ({
		url: 'control/control_user_group.php?lctr=2&mmenu='+sep[1],
		dataType: 'json',
		//async: false,
		success: function(out) {
			var bool = false;
			for(var i = 0; i < out.length; i ++) {
				if($("#"+sep[0]+"-"+sep[1]+"-"+out[i]["menu_id_sub"]).is(":checked")) {
					bool = true;
				}
			}
			
			if(!bool) {
				$("#menu-"+sep[1]).prop("checked", false);
			}
			else {
				$("#menu-"+sep[1]).prop("checked", true);
			}
		}
	});
}

function checkMCboxes() {
	$("html").mask("Loading...");
	$.ajax ({
		url: 'control/control_user_group.php?lctr=3',
		dataType: 'json',
		//async: false,
		success: function(out) {
			var cbSubIDs = out.split("|");
			
			deleteMmenu($("#group").val());
			deleteSmenu($("#group").val());
			
			for(var i = 0; i < cbSubIDs.length; i ++) {
				if($("#menu-" + cbSubIDs[i]).is(":checked")) {
					var splitID = cbSubIDs[i].split("-");
					if(splitID.length == 1) {
						updateMmenuAccess($("#group").val(), splitID[0]);
					}
					else if(splitID.length == 2) {
						updateSmenuAccess($("#group").val(), splitID[1])
					}
				}
			}
			
			$("html").unmask();
		}
	});
}

function deleteMmenu(gid) {
	$.ajax ({
		url: 'control/control_user_group.php?lctr=4&gid='+gid,
		dataType: 'json',
		async: false,
		success: function(out) {
			console.log(out);
		}
	});
}

function deleteSmenu(gid) {
	$.ajax ({
		url: 'control/control_user_group.php?lctr=5&gid='+gid,
		dataType: 'json',
		async: false,
		success: function(out) {
			console.log(out);
		}
	});
}

function updateMmenuAccess(gid, id) {
	$.ajax ({
		url: 'control/control_user_group.php?lctr=6&gid='+gid+'&mmenu='+id,
		dataType: 'json',
		async: false,
		success: function(out) {
			if(out) {
				console.log("mmenu: "+id+" added");
			}
			else {
				console.log("mmenu: "+id+" fails");
			}
		}
	});
}

function updateSmenuAccess(gid, id) {
	$.ajax ({
		url: 'control/control_user_group.php?lctr=7&gid='+gid+'&smenu='+id,
		dataType: 'json',
		async: false,
		success: function(out) {
			if(out) {
				console.log("smenu: "+id+" added");
			}
			else {
				console.log("smenu: "+id+" fails");
			}
		}
	});
}

/*wrong*/
function deleteMenu(id) {
	$.ajax ({
		url: 'control/control_user_group.php?lctr=8&mmenu='+id,
		dataType: 'json',
		async: false,
		success: function(out) {
			console.log(out);
		}
	});
}