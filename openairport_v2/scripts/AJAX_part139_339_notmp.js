//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
	var Firequest = creat_Object_ficon_notmp();	
		
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
function creat_Object_ficon_notmp()
{

var xmlhttp;
	// This if condition for  Firefox and  Opera  Browsers	
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') 
  {
		try 
		{
		  xmlhttp = new XMLHttpRequest();
		} 
		catch (e) 
		{
			alert("Your browser is not supporting XMLHTTPRequest");
			xmlhttp = false;
		}
	}
	// else condition for  ie
	else
	{
	xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
  return xmlhttp;
}

function sever_interaction_ficon_notmp()
	{
	if(Firequest.readyState == 4)
		{
		var answer = Firequest.responseText.split("|");
		document.getElementById("CheckListData").innerHTML = answer;
		
		var iframeids=["layouttableiframecontent"]		
		if (window.addEventListener)
			window.addEventListener("load", resizeCaller, false)
		else if (window.attachEvent)
			window.attachEvent("onload", resizeCaller)
		else
			window.onload=resizeCaller
		}
	}
function call_server_ficon_notmp(id,fullorshort)
		{
			var InspCheckList = document.getElementById("InspCheckList").value;
			var url = "ajax_part139339_data_entry_request_notmp.php?InspCheckList=" + escape(InspCheckList) + "&Employee=" + escape(id) + "&fullorshort=" + escape(fullorshort);
			Firequest.open("GET", url); 
			Firequest.onreadystatechange = sever_interaction_ficon_notmp;
			Firequest.send('');
		}