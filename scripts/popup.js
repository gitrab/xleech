/////////////////////////////
//	shiny shiny shiny popup...
////////////////////////////
function PopUp(url, name, width,height,center,resize,scroll,posleft,postop)
{
	showx = "";
	showy = "";
	
	if (posleft != 0) { X = posleft }
	if (postop  != 0) { Y = postop  }
	
	if (!scroll) { scroll = 1 }
	if (!resize) { resize = 1 }
	
	if ((parseInt (navigator.appVersion) >= 4 ) && (center))
	{
		X = (screen.width  - width ) / 2;
		Y = (screen.height - height) / 2;
	}
	
	if ( X > 0 )
	{
		showx = ',left='+X;
	}
	
	if ( Y > 0 )
	{
		showy = ',top='+Y;
	}
	
	if (scroll != 0) { scroll = 1 }
	
	var Win = window.open( url, name, 'width='+width+',height='+height+ showx + showy + ',resizable='+resize+',scrollbars='+scroll+',location=no,directories=no,status=no,menubar=no,toolbar=no');
}
