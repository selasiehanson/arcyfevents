(function ($){
	$(document).ready(function($){
	    var dateFormat = {
	        dateFormat: "DD ,d MM yy",
	        changeMonth: true,
			changeYear: true
	    };
	    $("#startDate").datepicker(dateFormat);
	    $( "#endDate" ).datepicker(dateFormat);
	});
})(jQuery)