<?php 
//		  1		    2		  3		    4		  5		    6		  7		    7	      8		
//2345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345
//==============================================================================================
//	
//	ooooo	oooo	ooooo	o		o	ooooo	ooooo	oooo	oooo	ooooo	oooo	ooooo
//	o   o	o	o	o		oo		o	o	o	  o		o	o	o	o	o	o	o	o	  o
//	o	o	o	o	o		o o		o	o	o	  o		o	o	o	o	o	o	o	o	  o
//	o	o	oooo	oooo	o 	o	o	ooooo	  o		oooo	oooo	o	o	oooo	  o	
//	o	o	o		o		o  	 o	o	o	o	  o		o  o	o		o	o	o  o	  o
//	o	o	o		o		o	  o	o	o	o	  o		o	o	o		o	o	o   o	  o
//	00000	0		ooooo	o		o	o	o	ooooo	o	o	o		ooooo	o	o     o
//
//	The premium quality open source software soultion for airport record keeping requirements
//
//	Designed, Coded, and Supported by Erick Dahl
//
//	Copywrite 2002 - Whatever the current year is
//
//	~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~
//	
//	Name of Document		:	part139327_discrepancy_report_summary.php
//
//	Purpose of Page			:	Enter new Part 139.327 Discrepancy
//
//	Special Notes			:	Change the information here for your airport.
//
//==============================================================================================
//2345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345
//		  1		    2		  3		    4		  5		    6		  7		    7	      8	

// Load Global Include Files
	
		include("includes/_template_header.php");												// This include 'header.php' is the main include file which has the page layout, css, and functions all defined.
		include("includes/POSTs.php");															// This include pulls information from the $_POST['']; variable array for use on this page

// Load Page Specific Includes

		include("includes/_modules/part139339/part139339.list.php");
		include("includes/_template_enter.php");
		include("includes/_template/template.list.php");
		
// Define Variables	
		
		$tblname		= "Condition Report Summary Report";
		$tblsubname		= "(summary of information)";
		
// Define Variables...
//						for Auto Entry Function {Beginning of Page}
		
		// Navigation Page ID
		//		Enter the ID of the Navigation Module this page belongs to.
		//		Check the AutoEntry function for more details...
		$navigation_page 			= 40;
		// Page Type ID
		//		Enter the ID of the Event type for this page.
		//		Check the AutoEntry function for more details...
		$type_page 					= 2;							// Page is Type ID, see function for notes!
		// Other Settings for AutoEntry
		//		You should not need to change these values.
		$date_to_display_new		= AmerDate2SqlDateTime(date('m/d/Y'));
		$time_to_display_new		= date("H:i:s");

// Build the BreadCrum trail... 
//		which shows the user their current location and how to navigate to other sections.
	
		//buildbreadcrumtrail($strmenuitemid,$frmstartdate,$frmenddate);
	
// Start Procedures...
//		Main Page Procedures and Functions
		
		?>
		<form style="margin-top:-3px;" action="<?php echo $_SERVER["PHP_SELF"];?>" method="post" name="edittable" id="edittable">
			<input type="hidden" name="formsubmit" 		ID="formsubmit"		value="1">
			<input type="hidden" NAME="recordid" 		ID="recordid" 		value="<?=$_POST['recordid'];?>">
		<table border="0" width="100%" id="tblbrowseformtable" cellspacing="0" cellpadding="0">
			<tr>
				<td width="10" class="tableheaderleft">&nbsp;</td>
				<td class="tableheadercenter">
					<?php echo $tblname;?>
					</td>
				<td class="tableheaderright">
					(<?php echo $tblsubname;?>)
					</td>
				</tr>
			<tr>
				<td colspan="3" class="tablesubcontent">
					<table border="0" width="100%" cellspacing="3" cellpadding="5" id="table2" height="10">
						<tr>
							<td colspan="3">
								<?php
								_339_c_display_report_summary($_POST['recordid'],2,0);
								?>
								</td>
							</tr>		
						</table>
					</td>
				</tr>
			</table>
<?php
			
// Define Variables...
//						for Auto Entry Function {End of Page}

		// Last Main ID
		//		This is the ID of the main record of this page, not a sub routine.
		//		If no ID is used or possible to obtain such a browse page or a form loader enter '-'
		$last_main_id	= $_POST['recordid'];
		
		//	AutoEntry Function Array
		//		This array controls the values sent to the auto entry function.
		//		No changes should be needed to it.
		$auto_array		= array($navigation_page, $_SESSION["user_id"], $_POST["formsubmit"], $date_to_display_new, $time_to_display_new, $type_page,$last_main_id); 

		ae_completepackage($auto_array);	
	
// Load End of page includes
//	This page closes the HTML tag, nothing can come after it.

		include("includes/_userinterface/_ui_footer.inc.php");							// Include file providing for Tool Tips			
?>	