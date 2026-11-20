$(function(){
	

	/**do not allow F12 or right click***/
	//window.oncontextmenu = function () { return false; } 
	document.onkeydown = function (e) {	
		if (e.keyCode == 123 || e.button==2)
			return false; 

		if(window.event.ctrlKey && window.event.shiftKey )
	    {
	       // alert('Entered ctrl+shift+i')
	        return false;  //Prevent from ctrl+shift+i
	    }

	    if(event.ctrlKey && event.shiftKey )
	    {
	        //alert('Entered ctrl+shift+i')
	        return false;  //Prevent from ctrl+shift+i
	    }
	} 

});