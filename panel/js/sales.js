function addLoadEvent(func) {
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func;
	}
	else {
		window.onload = function() {
			if (oldonload) {
				oldonload();
			}
			func();
		}
	}
}

function setMonthWeeks() {
	var opt = "";
	for(var i = 1; i <= weekCount($("#cmb_year").val(), $("#cmb_month").val()); i++) {
		opt += '<option class="'+i+'" value="'+i+'">Week '+i+'</option>';
	}
	$("#cmb_month_week").html(opt);
}

function setWeek() {
	var date = new Date();
	var year = date.getFullYear();
	var month = date.getMonth() + 1;
	var weeks = weekCount(year, month);
	for(var i = 1; i <= weeks; i++) {
		var days = getDaysFromWeek(year, month, i);
		var day = [];
		for(var j = 0; j < days.length; j++) {
			var temp = days[j].split("||");
			if(temp[0] == date.getDate()) {
				$("#cmb_month_week").val(i);
				return i;
			}
		}
	}
}

function getCurrentMonth() {
	var date = new Date();
	$("#cmb_month").val((date.getMonth()+1));
}

function getCurrentYear() {
	var date = new Date();
	$("#cmb_year").val((date.getFullYear()));
}

function weekCount(year, month) {
    var firstOfMonth = new Date(year, month-1, 1);
	if(month == 12) {
		var lastOfMonth = new Date((year + 1), 0, 0);
	}
	else {
		var lastOfMonth = new Date(year, month, 0);
	}
    //var lastOfMonth = new Date(year, month, 0);
    var used = firstOfMonth.getDay() + lastOfMonth.getDate();
    return Math.ceil( used / 7);
}

function getDaysFromWeek(year, month, week) {
	var firstDateOfWeek = new Date(year, month - 1, 1);		// copy firstDateOfMonth (month is determinded as array index)
															// 0 = January to 11 = December
	if(month == 12) {
		var lastDateOfMonth = new Date((year + 1), 0 /*<- 0 = january*/, 0); 		// get the last day of the month
	}																				// by getting the day before the next month
	else {																			// if month is december (12) add 1 on year
		var lastDateOfMonth = new Date(year, month /*<- month = next month*/, 0);	// to capture next month (january)
	}																				// 
	
	if(week > 1) {
		firstDateOfWeek.setDate(                       		// move the Date object
			firstDateOfWeek.getDate() +                		// forward by the number of
			(7 * (week - 1)) - firstDateOfWeek.getDay()		// weeks required (week - 1)
		);													// if week is not the first week
	}
	
	var firstDayOfWeek = firstDateOfWeek.getDay();
	var weekDet = [];
	
	for (var i = 0; i < 7; i++) {
		if(firstDayOfWeek <= i) {
			weekDet.push(firstDateOfWeek.getDate()+"||"+firstDateOfWeek.getDay());
			if(firstDateOfWeek.getDate() == lastDateOfMonth.getDate()) break;
			firstDateOfWeek.setDate(firstDateOfWeek.getDate() + 1);
		}
	}
	
	return weekDet;
}

function selectSales() {
	populateWeekSales($("cmd_year").val(), $("cmd_month").val(), $("cmd_month_week").val());
}

function populateWeekSales(year, month, week) {
	var date = new Date();
	var days = getDaysFromWeek(year, month, week);
	console.log(days.length);
	var dayName = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
	$("#sales-body").html("");
	for(i = 0; i < days.length; i++) {
		console.log(days[i]);
		var day = days[i].split("||");
		$("#sales-body").append('<div class="row">'
								+	'<div class="col-lg-4">'
								+		'<div class="panel '+(month+'/'+day[0]+'/'+year == (date.getMonth() + 1)+'/'+date.getDate()+'/'+date.getFullYear() ? 'panel-yellow' : 'panel-default')+'">'
								+			'<div class="panel-heading">'
								+				'<i class="fa fa-math"></i>'
								+				'Day '+(i + 1)
								+			'</div>'
								+			'<div class="panel-body">'
								+				'<i class="fa fa-calendar fa-fw"></i>'
								+				'Date: '+month+'/'+day[0]+'/'+year+' ('+dayName[day[1]]+')'
								+			'</div>'
								+		'</div>'
								+	'</div>'
								+	'<div class="col-lg-8">'
								+		'<div class="panel panel-default">'
								+			'<div class="panel-body">'
								+				'<i class="fa fa-math"></i>'
								+				'<small><a href="#" target="_blank">See complete details.</a></small>'
								+			'</div>'
								+		'</div>'
								+	'</div>'
								+'</div>');
	}
}

function populateWeekSales_load() {
	var date = new Date();
	var year = date.getFullYear();
	var month = date.getMonth() + 1;
	var week = setWeek();
	var days = getDaysFromWeek(year, month, week);
	var dayName = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
	$("#sales-body").html("");
	for(i = 0; i < days.length; i++) {
		var day = days[i].split("||");
		$("#sales-body").append('<div class="row">'
								+	'<div class="col-lg-4">'
								+		'<div class="panel '+(month+'/'+day[0]+'/'+year == (date.getMonth() + 1)+'/'+date.getDate()+'/'+date.getFullYear() ? 'panel-yellow' : 'panel-default')+'">'
								+			'<div class="panel-heading">'
								+				'<i class="fa fa-sun-o fa-fw"></i>'
								+				'Day '+(i + 1)
								+			'</div>'
								+			'<div class="panel-body">'
								+				'<i class="fa fa-calendar fa-fw"></i>'
								+				'Date: '+month+'/'+day[0]+'/'+year+' ('+dayName[day[1]]+')'
								+			'</div>'
								+		'</div>'
								+	'</div>'
								+	'<div class="col-lg-8">'
								+		'<div class="panel panel-default">'
								+			'<div class="panel-body">'
								+				'<i class=""></i>'
								+				'<small><a href="#" target="_blank">See complete details.</a></small>'
								+			'</div>'
								+		'</div>'
								+	'</div>'
								+'</div>');
	}
}

function viewSales() {
	populateWeekSales($("#cmb_year").val(), $("#cmb_month").val(), $("#cmb_month_week").val());
}

function onPageLoad() {
	addLoadEvent(getCurrentMonth);
	addLoadEvent(getCurrentYear);
	addLoadEvent(setMonthWeeks);
	addLoadEvent(setWeek);
	addLoadEvent(populateWeekSales_load);
	
	loadDtables();
	
	/*$('#uid').keyup(function(e){
		var input = $(this).val();
		$.ajax({
			type: "get",
			url: "control/autocomplete.php",
			data: {word: input},
			async: true,
			success: function(data){
				var outWords = $.parseJSON(data);
				$('#auto').html('');

				for(x = 0; x < outWords.length; x++){
					$('#auto').prepend('<div>'+outWords[x]+'</div>'); //Fills the #auto div with the options
				}
			}
		})
	})*/
}

function loadDtables() {
	$("#dataTables-employee").dataTable({
		"bLengthChange": false,
		"iDisplayLength": 30,
		"fnInitComplete": function(oSettings, json) {
			employeeListDtable();
		}
	});
	
	$("#dataTables-vendor").dataTable({
		"bLengthChange": false,
		"iDisplayLength": 30,
		"fnInitComplete": function(oSettings, json) {
			vendorListDtable();
		}
	});
	
	$("#dataTables-employee-lookup").dataTable({
		"bLengthChange": false,
		"iDisplayLength": 10,
		"fnInitComplete": function(oSettings, json) {
			$.ajax ({
				url: 'control/dt_employee.php',
				dataType: 'json',
				async: false,
				success: function(out) {
					lookupDtable('#dataTables-employee-lookup', out, 1)
				},
				error: function(out) {
					
				}
			});
		}
	});
	
	/*$("#dataTables-vendor-lookup").dataTable({
		"bLengthChange": false,
		"iDisplayLength": 10,
		"fnInitComplete": function(oSettings, json) {
			$.ajax ({
				url: 'control/dt_vendor.php',
				dataType: 'json',
				async: false,
				success: function(out) {
					lookupDtable('#dataTables-vendor-lookup', out, 2)
				},
				error: function(out) {
					
				}
			});
		}
	});*/
}

function employeeListDtable() {
	$("#dataTables-employee").dataTable().fnClearTable()
	$("#dataTables-employee").dataTable().fnAddData([
		'<center><a href="" ><em>[Process]</em></a></center>',
		'',
		'',
		'',
		'',
		'',
		'',
		'',
		''
	])
}

function vendorListDtable() {
	$("#dataTables-vendor").dataTable().fnClearTable()
	$("#dataTables-vendor").dataTable().fnAddData([
		'<center><a href="" ><em>[Process]</em></a></center>',
		'',
		'',
		'',
		'',
		'',
		'',
		'',
		''
	])
}

function lookupDtable(dtid, data, lctr) {
	$(dtid).dataTable().fnClearTable()
	for(var i = 0; i < data.length; i++ ){
		$(dtid).dataTable().fnAddData([
			'<center><a href="control/functions.php?lctr='+lctr+'&id='+data[i][0]+'" >'+data[i][0]+'</a></center>',
			data[i][1]
		])
	}
}