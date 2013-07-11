<?
/*	= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = 
	
	Page Name						Purpose :
	Quickinfo Functions.php			The purpose of this file is to allow for functions that can be used multiple times in many different files, cleaning up the apperence of the pages as well as increasing their usability.
	
								Usage:
								Just include this page in with your other page and the functions will be avilable to be used in that page.
								
								
								
								
	NOTE: THERE SHOULD BE NO NEED TO CHANGE ANY OF THE CODE ON THIS PAGE
	
	= = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = = 
*/	

function quickinfocombobox($menu_id, $archived, $nameofinput, $showcombobox, $default) {
	// $menu_id		, is the number of the menu item to do the search for ;
	// $archived		, do you want to list all menu items, or just the archived ones;
	// $nameofinout		, what is the name of the select box that 'could' be ceated by this function;
	// $showcombobox	, Do you want to show the combo box select input style or just the text without the input box;
	// $default			, What is the default menu item to display in the combobox when it is displayed;

	// Examples
	
	//	$adataselect[$i]($objarray[$adatafieldid[$i]], "all", $adatafield[$i], "hide", "");
	// This example will only show one record, and it will not be in a combobox input box, but rather be displayed as text.
	
	
	$sql	= "";																					// Define the sql variable, just in case
	$nsql 	= "";																				// Define the nsql variable, just in case
	
	$sql = "SELECT * FROM tbl_quickinfo_control ";															// start the SQL Statement with the common syntax

	if ($menu_id=="all") {																			// if supplied 'all' for the menu_id so the following
			// do not add any employee ID information to the SQL String
			$tmp_flagger = 0;																		// important to tell the procedures below this happened
		}
		else {
			$nsql = "WHERE `menu_item_id` = ".$menu_id." ";												// if supplied a menu_id, then add it to the SQL Statement
			$sql = $sql.$nsql;																		// combine the nsql and sql strings
			$tmp_flagger = 1;																		// important to tell the procedures below this happened
		}

	if ($archived == "all") {																		// if supplied 'all' for the archived variable do the following
																								// Do not add any systemuser archived information to the SQL string
		}
		else {
			if ($archived=="yes") {																	// If archived is 'yes' then
					if ($tmp_flagger==0) {
							$nsql = "WHERE tbl_quickinfo_control.menu_item_archived_yn = -1 ";
							$sql = $sql.$nsql;
							$tmp_flagger = 1;
						}
						else {
							$nsql = "AND tbl_quickinfo_control.menu_item_archived_yn = -1 ";
							$sql = $sql.$nsql;
						}
				}
				else {
					if ($tmp_flagger==0) {
							$nsql = "WHERE tbl_quickinfo_control.menu_item_archived_yn = 0 ";
							$sql = $sql.$nsql;
							$tmp_flagger = 1;
						}
						else {
							$nsql = "AND tbl_quickinfo_control.menu_item_archived_yn = 0 ";
							$sql = $sql.$nsql;
						}
				}
		}
	
	$nsql = " ORDER BY tbl_quickinfo_control.menu_item_name_long ";
	$sql = $sql.$nsql;
	//echo $sql;
	
	$mysqli = mysqli_connect("localhost", "webuser", "limitaces", "openairport");
	
	if (mysqli_connect_errno()) {
			printf("connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		else {
			$res = mysqli_query($mysqli, $sql);
			if ($res) {
					$number_of_rows = mysqli_num_rows($res);
					//printf("result set has %d rows. \n", $number_of_rows);
					if ($showcombobox=="show") {
							?>
	<SELECT class="Commonfieldbox" name="<?=$nameofinput?>">
					<?
						}
					while ($newarray = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
							$tmpmenuid 		= $newarray['menu_item_id'];
							$tmpmenuurl 	= $newarray['menu_item_location'];
							$tmpmenulocl	= $newarray['menu_item_name_long'];
							$tmpmenulocs	= $newarray['menu_item_name_short'];
							$tmpmenupurp	= $newarray['menu_item_purpose'];
							
						if ($showcombobox=="show") {
								?>
		<option 
								<?
							}
							if ($menu_id = "all") {
									$intmenuid	= (double) $default;
									if ($tmpmenuid == $intmenuid) {
											if ($showcombobox=="show") {
													?>
				SELECTED
													<?
												}
										}
										else {
											// There is no user specified so we dont need to set a defualt value
										}
								}
								else {
								
								}
								if ($showcombobox=="show") {
										?>
				value="<?=$tmpmenuid;?>"><?=$tmpmenulocl;?>&nbsp;&nbsp;(<?=$tmpmenulocs;?>)</option>
										<?
									}
									else {
										?>
				<?=$tmpmenulocl?>&nbsp;&nbsp;(<?=$tmpmenulocs;?>)
										<?
									}
								}	// End of while loop
								mysqli_free_result($res);
								mysqli_close($mysqli);
								if ($showcombobox=="show") {
										?>
		</SELECT>
										<?
									}
						}	// end of Res Record Object						
				}
	}

// ---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===
// FUNCTION QUICK EQUIPMENT FIX
// ---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===---===
	
function quickequipmentfix($tmp) {
		// Display a listing of quick things that can be fixed with just replacing bulbs
		$tmp = "Quick Equipment Repair";
		
		?>
<table border="0" id="tblbrowseformtable" cellspacing="0" cellpadding="0" class="layoutquickinfocontent" width="100%">
	<tr>
		<td colspan="3">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td class="tableheadercenter">
						<b><?=$tmp;?></b>
						</td>
					</tr>
				</table>
			</td>
			<?
		
		
		
		// Listing 1).
				?>
	<tr>
		<form style="margin-bottom:0;" action="maintenance_event_quick_data_entry.php" method="POST" name="printform" id="printform" target="PrinterFriendlyReport" onsubmit="window.open('', 'PrinterFriendlyReport', 'width=750,height=962,status=no,resizable=no,scrollbars=yes')">
		<td class="tabledatarow">
			<table border="0" width="100%" id="table1" cellpadding="0" cellspacing="1" style="border-collapse: collapse">
				<tr>
					<td class="formheaders">
						PAPI
						</td>
					<td class="formheaders">
						Light Out
						</td>	
					<td class="formheaders">
						Solution
						</td>	
					<td class="formheaders">
						Submit
						</td>
					</tr>
				<tr>
					<td class="formresults">
						<SELECT class="Commonfieldbox" name="PAPIselection"> 
							<?
				// List all PAPI's, search tbl_inventory_sub_e.equipment_serialnumber_a = "PAPI".
				// Build a form for each result, with a radio button for the actual lights.
				$sql = "SELECT * FROM tbl_inventory_sub_e WHERE tbl_inventory_sub_e.equipment_serialnumber_a = 'PAPI' ORDER BY tbl_inventory_sub_e.equipment_name";
				//echo $sql;
				
				$mysqli = mysqli_connect("localhost", "webuser", "limitaces", "openairport");
				
				if (mysqli_connect_errno()) {
						printf("connect failed: %s\n", mysqli_connect_error());
						exit();
					}
					else {
						$res = mysqli_query($mysqli, $sql);
						if ($res) {
								$number_of_rows = mysqli_num_rows($res);
								//printf("result set has %d rows. \n", $number_of_rows);
								while ($newarray = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
										$tmp_id 		= $newarray['equipment_id'];
										$tmp_name 		= $newarray['equipment_name'];
										?>
										<option value="<?=$tmp_id;?>"><?=$tmp_name;?></option>
										<?
										}	// End of while loop
									mysqli_free_result($res);
									mysqli_close($mysqli);	
									?>
										</SELECT>
									</td>
								<td class="formresults" align="center">
									<input type="radio" name="group1" value="1">
									<input type="radio" name="group1" value="2">
									<input type="radio" name="group1" value="3"> 
									</td>	
								<td class="formresults" align="center">
									<input type="text" name="problem" size="15" value="Replaced Bulb"> 
									</td>
								<td class="formresults" align="center">
									<input type="hidden" name="recordid" 			value="<?=$tmp_id;?>"					>
									<input type="submit" name="b1" 					value="Submit"			class="formsubmit">
									</td>
									<?
							}		
					}
					?>
									</form>
								</tr>
					</table>
				</tr>
			</td>
		</tr>
	<tr>
		<td colspan="3" align="right" valign="middle">
			Always use Proper Reporting Methods;<br>
			Discrepancies from inspections MUST be reported on inspection reports!
			</td>
		</tr>	
	</table>
	<?			
		}
	
	
	
function quickinfoinventoryfueltanks($tmp) {
	// will the form use any of the following toggle switches	
		// should the form have the ability to sort the data by date ?
		// 1 : yes ; 0 : no;
		$tbldatesort 			= 0;															// Display Date Sorting Options Toggle Switch
		$tbltextsort 			= 0;															// Display Text Sorting Options Toggle Switch
		$tblheadersort			= 0;															// Display Heading Sort Options Toggle Switch
		$tbldisplaytotal			= 1;
		
	// this information is needed to program the datafields and sql statements on the fly without any user interuption
		// what is the primary key field (id field) of the table
		$tblkeyfield			= "inventory_tanks_id";											// What is the Auto Increment Field for this table ?
		$tbldatesortfield		= "inventory_tanks_dateinstalled";								// What is the name of field use in date sorting ?
		$tbldatesorttable		= "tbl_inventory_sub_tanks";									// What table  is that field part of ?
		$tbltextsortfield		= "inventory_tanks_modelnumber";								// What is the name of the field used in text sorting ?
		$tbltextsorttable		= "tbl_inventory_sub_tanks";									// What is the name of the table used for text sorting ?
		$tblarchivedfield		= "inventory_tanks_archived_yn";								// What is the name of the field used to mark the record archived ?
		$tblname				= "Fuel Tank Summary Report";									// What is the name of the table ? (used on edit/summary/printer report pages)
		$tblsubname				= "here is the information you selected";						// What is the subname of the table ? (used on edit/summary/printer report pages)

	// What php pages are used to control the summary, printer reports, and edit functions?
		// by default these pages should be the following
		// $functioneditpage 			= "edit_record_general.php";
		// $functionsummarypage 		= "summary_report_general.php";
		// $functionprinterpage 		= "printer_report_general.php";
		$functioneditpage 		= "edit_record_general.php";									// Name of page used to edit the record
		$functionsummarypage 	= "summary_report_general.php";									// Name of page used to display a summary of the record
		$functionprinterpage 	= "printer_report_general.php";									// Name of page used to display a printer friendly report

	// what columns should the datagrid display?
		// this is an array of information, from [0] to [...], you may have an unlimited number of columns
		$adatafield[0]			= "inventory_tanks_type_cb_int";
		$adatafield[1]			= "inventory_tanks_totalcapacity";
		$adatafield[2]			= "inventory_tanks_currentcapacity";
	// in each array specified above, what table does that field come from?
		$adatafieldtable[0]		= "tbl_inventory_sub_tanks";
		$adatafieldtable[1]		= "tbl_inventory_sub_tanks";
		$adatafieldtable[2]		= "tbl_inventory_sub_tanks";			
	// do you want the user ot be able to click on the information and have something happen?
		// "notjoined" will cause nothing to happen, only the data will be displayed
		// the name of any field in any of the selected tables will cause only records that have that information to be displayed.
		$adatafieldid[0]		= "inventory_tanks_type_cb_int";
		$adatafieldid[1]		= "notjoined";
		$adatafieldid[2]		= "notjoined";
	// in some cases you may want the information displayed to be prefecned with a $ sign or followed by a % sign. here you can tell the program what to display if anything
		// 0 : nothing ; 2 : @ ; 4 : $ ; 5 : %
		$adataspecial[0]		= 0;
		$adataspecial[1]		= 0;
		$adataspecial[2]		= 0;
	// should this column be added to create a totals column at the end of the recods
		$adataputarray[0]		= 0;
		$adataputarray[1]		= 1;
		$adataputarray[2]		= 1;
	// what do you want to name the columns
		$aheadername[0]			= "Type";
		$aheadername[1]			= "Total Capacity";
		$aheadername[2]			= "Current Capacity";
	// Any special comments to make after the input box?
		$ainputcomment[0]		= "( mm/dd/yyyy )";
		$ainputcomment[1]		= "( select from the list )";
		$ainputcomment[2]		= "( select from the list )";
	// what type of input is this field?
		// this can be any type of input
		// text, textarea, select, etc.
		$ainputtype[0]			= "SELECT";
		$ainputtype[1]			= "TEXT";
		$ainputtype[2]			= "TEXT";
	// if the input type is a SELECT type you should define the name of the function you want to call to load into that select statement
		$adataselect[0]			= "inventoryfueltanktypescombobox";
		$adataselect[1]			= "";
		$adataselect[2]			= "";
	// in the event of an error when a user enteres a wrong date, what should the datagrid say to the user?
		$tmpDateEventErrorMessage	= "Error, reset to default";
		
	// Build SQL Statement
	// Since we have all of the information from the arrays and strings we can assemble the nessary SQL Statement without actually having to supply it verbatum.
	// The Limiting clauses will be left off, all we want to create at this point is the basic SELECT *,*,* FROM syntax
	
		$sql = "SELECT ".$tblkeyfield.",";														// Start SQL adding on the id field first						
		
		for ($i=0; $i<count($adatafield); $i=$i+1) {											// Loop through any of the arrays 0 to array length - 1
				$nsql = " ".$adatafield[$i]."";													// add each new value in the array to a temporary sql string
				$sql = $sql.$nsql;																// add the temporary sql string to the main sql string.
				if ($i == count($adatafield)-1) {												// test to see where we are in the string, in this case are we at the end or not?
						$nsql = "";																// at the end of the arrat dont add a , to the end of the value
						$sql = $sql.$nsql;														// add 'nothing' to the sql string
					}
					else {																		// not at the end of the array so do soemthing else
						$nsql = ", ";															// for each value in the array that is not at the end, add a , after the value
						$sql = $sql.$nsql;														// add the temporary sql string to the main sql string
					}			
		}
		$sql = $sql.$nsql;																		// field index values have all been added to the sql string (this line is reduntent, but there for space)
		$nsql = " FROM ".$tbldatesorttable." ";													// with all field index values added, add the FROM syntax and the applicable table in the DB
		$sql = $sql.$nsql;																		// make it all one nice sql string for use

	// For debugging purposes print out the SQL Statement
		//echo $sql;																				// When dedugging you can uncomment this echo and see the sql statement

	// Start the Real Fun	

		$i 							= 0;	
		$tblheadersortfirstselected="yes";
		$togfrmjoined = "";														//just in case we dont define it latter, set a default here.


//-------------------- [ there should be no need to change any of the code below this line ] ----------------------------------------------------------------------------------------------------------------------

		
	// store this array into a serialized array
		$stradatafield 			= urlencode(serialize($adatafield));			// dont touch
		$stradatafieldtable 	= urlencode(serialize($adatafieldtable));		// dont touch
		$stradatafieldid 		= urlencode(serialize($adatafieldid));			// dont touch	
		$stradataspecial		= urlencode(serialize($adataspecial));			// dont touch
		$straheadername			= urlencode(serialize($aheadername));			// dont touch
		$strainputtype			= urlencode(serialize($ainputtype));			// dont touch
		$stradataselect			= urlencode(serialize($adataselect));			// dont touch
		$strainputcomment		= urlencode(serialize($ainputcomment));			// dont touch
		
	// store this array into a serialized array
		$sadatafield 			= (serialize($adatafield));						// dont touch
		$sadatafieldtable 		= (serialize($adatafieldtable));				// dont touch
		$sadatafieldid 			= (serialize($adatafieldid));					// dont touch	
		$sadataspecial			= (serialize($adataspecial));					// dont touch
		$saheadername			= (serialize($aheadername));					// dont touch
		$sainputtype			= (serialize($ainputtype));						// dont touch
		$sadataselect			= (serialize($adataselect));					// dont touch
		$sainputcomment			= (serialize($ainputcomment));					// dont touch
		
		$sadatafield  			= str_replace("\"","|",$sadatafield);
		$sadatafieldtable 		= str_replace("\"","|",$sadatafieldtable);
		$sadatafieldid 			= str_replace("\"","|",$sadatafieldid);
		$sadataspecial 			= str_replace("\"","|",$sadataspecial);
		$saheadername 			= str_replace("\"","|",$saheadername);
		$sainputtype 			= str_replace("\"","|",$sainputtype);
		$sadataselect 			= str_replace("\"","|",$sadataselect);
		$sainputcomment			= str_replace("\"","|",$sainputcomment);

		$tmpfrmstartdateerror 	= "";
		$tmpfrmenddateerror 	= "";
		$arowtotal[0] 			= "";
		$arowtotal[1] 			= "";
		$arowtotal[2] 			= "";
		$arowtotal[3] 			= "";
		$arowtotal[4] 			= "";
		$arowtotal[5] 			= "";
		$arowtotal[6] 			= "";
		$arowtotal[7] 			= "";
		$arowtotal[8] 			= "";
		$arowtotal[9] 			= "";
		$arowtotal[10] 			= "";
		$arowtotal[11] 			= "";		
		
		
if (!isset($_POST["frmstartdate"])) {
		//$tbldatesortstart=($current_date-30);		
		// setup startdate for query
		$uifrmstartdate 		= date('m/d/Y');
		$sqlfrmstartdate 		= amerdate2sqldatetime($uifrmstartdate);
	}
	else {
		$uifrmstartdate 		= $frmstartdate;
		$sqlfrmstartdate 		= amerdate2sqldatetime($frmstartdate);
		}

if (!isset($_POST["frmenddate"])) {
		//$tbldatesortstart=($current_date-30);		
		// setup startdate for query
		$uifrmenddate 			= date('m/d/Y');
		$sqlfrmenddate 			= amerdate2sqldatetime($uifrmenddate);
	}
	else {
		$uifrmenddate 			= $frmenddate;
		$sqlfrmenddate 			= amerdate2sqldatetime($frmenddate);
		}

if (!isset($_POST["frmjoined"])) {
		//there is no information in this field, set default values
		
		// check to see if the intfrmjoined field has any value
		$intfrmjoined	= 0;
		$intsqlwhereaddon = 0;
		$strsqlwhereaddon = "none";
		
		$togfrmjoined = 1;
		
		if (!isset($_POST["intfrmjoined"])) {
				//echo "there is no value in intfrmjoined";
				$frmjoined 				= 0; //set value to zero (this causes the checkbox to not be checked
				$intfrmjoined 			= 0; //set value to zero (this causes the checkbox to not be checked
				}
			else {
				//echo "value is ".$intfrmjoined." ";
				if ($intfrmjoined==0) {
						// the value of the box has 'no' value
						$frmjoined				= "0";
						$intfrmjoined			= 0;
					}
					else {
						// the value of the box isn't one so we should do what it says
						$frmjoined				= 1;
						$intfrmjoined			= 1;
					}				
			}
		}
	else {
		// the field does exist, what is its current value
		$frmjoined				= $_POST["frmjoined"];
		$intfrmjoined			= 1;
		}	

if (!isset($_POST["strsqlwhereaddon"])) {
		$strsqlwhereaddon="none";
		$intsqlwhereaddon = 0;
	}
	else {
		if ($togfrmjoined==1) {
				// checkbox is not active, clean out sql statement
				$strsqlwhereaddon		= "";
				$intsqlwhereaddon 		= 1;
			}
			else {
	
		$strsqlwhereaddon		= $_POST["strsqlwhereaddon"];
		$strsqlwhereaddon 		= str_replace("%3d","=",$strsqlwhereaddon );
		//$tblsqlwhereaddon 		= 1;
		}
	} 

if (!isset($_POST["intsqlwhereaddon"])) {
		if ($tbldatesort==1) {
				$intsqlwhereaddon = 1;
			}
		//tblsqlwhereaddon = 1
	}
	else {
		$intsqlwhereaddon=$_POST["intsqlwhereaddon"];
		//tblsqlwhereaddon = 1
	}
	
for ($i=0; $i<count($aheadername); $i++) {
	if (!isset($_POST[$adatafield[$i]])) {
			$aheadersort[$i]="NotSorted";
		}
		else {
			$aheadersort[$i]=$_POST[$adatafield[$i]];
		} 
	}


// add where statement to sql statement
if ($tbldatesort==1) {
		$nsql = " where ".$tbldatesorttable.".".$tbldatesortfield." >= '".$sqlfrmstartdate."' and ".$tbldatesorttable.".".$tbldatesortfield." <= '".$sqlfrmenddate."'";
		$sql = $sql.$nsql;
		?>
		<script>	</script>
		<?
	}
if ($tbltextsort==1) {
		if ($tbldatesort==1) {
				$nsql = " and ".$tbltextsorttable.".".$tbltextsortfield." like '%".$frmtextlike."%' ";
				$sql = $sql.$nsql;
				}
			else {
				$nsql = " where ".$tbltextsorttable.".".$tbltextsortfield." like '%".$frmtextlike."%' ";
				$sql = $sql.$nsql;	
				}
	}
if ($strsqlwhereaddon=="none") {
		//do not add any additional sql to the sql statement as they have not been provided
		}
	else {
		if ($intfrmjoined==0) {
				// user has choosen not to enable column joining, so dont do it
				}
			else {
				// user has enabled column joining, so do it
				$sql = $sql.$strsqlwhereaddon;
			}
	}

//order by sql statement
if ($tblheadersort==1) {
	for ($i=0; $i<count($aheadersort); $i=$i+1) {
			if ($aheadersort[$i]=="NotSorted") {
					//do not add sorting column to sql string
				} 
			if ($aheadersort[$i]=="Assending") {
					if ($tblheadersortfirstselected=="yes") {
							//this is the first time a header has been selected
							$tblheadersortfirstselected="no"; //set selected to no
							$nsql=" order by ".$adatafieldtable[$i].".".$adatafield[$i]." ";
							$sql = $sql.$nsql;
						}
						else {
							$sql = $sql.", ".$adatafieldtable[$i].".".$adatafield[$i]." ";
						} 
				} 
			if ($aheadersort[$i]=="Decending") {
					if ($tblheadersortfirstselected=="yes") {
							//this is the first time a header has been selected
							$tblheadersortfirstselected="no"; //set selected to no
							$nsql=" order by ".$adatafieldtable[$i].".".$adatafield[$i]." desc ";
							$sql = $sql.$nsql;
						}
						else {
							$sql = $sql.", ".$adatafieldtable[$i].".".$adatafield[$i]." desc ";
						} 
				} 
		}
	}
	$sql 		= str_replace("%3D","=",$sql);
	//echo $sql;
?>

<table border="0" id="tblbrowseformtable" cellspacing="0" cellpadding="0" class="layoutquickinfocontent" width="100%">
	<tr>
		<td colspan="3">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td class="tableheadercenter">
						<b><?=$tmp;?></b>
						</td>
					</tr>
				<?
				//make connection to database
				$objconn = mysqli_connect("localhost", "webuser", "limitaces", "openairport");
		
				if (mysqli_connect_errno()) {
						// there was an error trying to connect to the mysql database
						printf("connect failed: %s\n", mysqli_connect_error());
						exit();
					}
					else {
						$objrs = mysqli_query($objconn, $sql);
				
						if ($objrs) {
								$number_of_rows = mysqli_num_rows($objrs);
								if ($number_of_rows==0) {
										//echo "no records found";
										}
										else {
												?>
				<tr>
					<td class="tabledatarow">
						<table border="0" width="100%" id="table1" cellpadding="0" cellspacing="1" style="border-collapse: collapse">
							<tr>
								<td class="formheaders">
									ID
									</td>
								<? 
								for ($i=0; $i<count($aheadername); $i=$i+1) {
										?>
								<td class="formheaders">
										<? 
										if ($tblheadersort==1) {
												?>
									<a href="#" onfocus="javascript:getvaluesortform('<?=$adatafield[$i];?>');" onclick="javascript:updatesortform('<?=$adatafield[$i];?>');"><font color="#ffffff"><?=$aheadername[$i];?></font></a>
									<br>(<input class="inlinehiddenbox" type="text" size="8" id="<?=$adatafield[$i];?>" name="<?=$adatafield[$i];?>" value="<?=$aheadersort[$i];?>">)
												<? 
											}
											else {
												echo $aheadername[$i];
											}	
										?>
									</td>
										<?
									}
								?>
							</form>
								</tr>
										<?
										while ($objarray = mysqli_fetch_array($objrs, MYSQLI_ASSOC)) {
										//$tmpfieldname	= $layer3array['menu_item_name_long'];
										?>
							<tr>
								<td height="32" align="center" class="formresults">
									<?=$objarray[$tblkeyfield];?>
									</td>
								
								<? 
								for ($i=0; $i<count($aheadername); $i=$i+1) {
										if ($adataputarray[$i]==1) {
												//echo $arowtotal;
												$arowtotal[$i] = $arowtotal[$i] + $objarray[$adatafield[$i]];
											}
										?>
								<td align="center" valign="middle" class="formresults">
										<? 
										switch ($adatafieldid[$i]) {
												case "notjoined":
														switch ($adataspecial[$i]) {
																case 2:
																		?>
									@ <?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
																case 4:
																		?>
									$ <?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
																case 5:
																		?>
									<?=$objarray[$adatafield[$i]];?> %
																		<? 
																		break;
																default:
																		?>
									<?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
															}
														break;
												case "notjoined":
														switch ($ainputtype[$i]) {
																case "CHECKBOX":
																		if ($objarray[$adatafield[$i]]==0) {
																				$tmpcbfield = "No";
																			}
																			else {
																				$tmpcbfield = "Yes";
																			}
																			?>
									<a href="javascript:updatewhereform('<?=$adatafieldtable[$i];?>.<?=$adatafield[$i];?>=<?=$objarray[$adatafield[$i]];?>');">
										<font color="#000000">
											<?=$tmpcbfield;?>
											</font>
										</a>				
																			<?
																		break;
																default:
																				?>
									<a href="javascript:updatewhereform('<?=$adatafieldtable[$i];?>.<?=$adatafield[$i];?>=<?=$objarray[$adatafield[$i]];?>');">
										<font color="#000000">
											<?=$objarray[$adatafield[$i]];?>
											</font>
										</a>
														<?
																		break;
																}
														break;
												default:											
														$tmpsqlwhereaddon=$objarray[$adatafieldid[$i]];
														?>
									<a href="javascript:updatewhereform('<?=$adatafieldtable[$i];?>.<?=$adatafieldid[$i];?>=<?=$tmpsqlwhereaddon;?>');">
										<font color="#000000">
														<?
											$adataselect[$i]($objarray[$adatafieldid[$i]], "all", $adatafield[$i], "hide", "");
														?>
											</font>
										</a>
														<? 
														break;
												}
											//} 
											?>
									</td>
											<? 
									}
								?>
								</tr>
											<?									
											}	// end of looped data
											mysqli_free_result($objrs);
											mysqli_close($objconn);
								if ($tbldisplaytotal==1) {
									?>
								<tr>
									<td colspan="2" align="center" valign="middle" class="formresults">
										Total
										</td>
									<?
									for ($i=1; $i<count($aheadername); $i=$i+1) {
											if ($adataputarray[$i]==1) {
													?>
									<td align="center" valign="middle" class="formresults">
													<?
													echo $arowtotal[$i];
													?>
										</td>
													<?
												}
												else {
													?>
									<td align="center" valign="middle" class="formresults">
										&nbsp;
										</td>
													<?
												}
										}
										?>
									</tr>
										<?
									}
									?>
							</table>
									<?
									}	// end of records found statement
								}	// end of sucessfull conection and execution of sql statement
							}	// end of connection established
							?>
						</td>
					</tr>					
				</table>	<!-- end of ajax load table-->
			</td>
		</tr>
	</table>
	<?
	}

function quickinfopart139327inspections($tmp) {

	// will the form use any of the following toggle switches	
		// should the form have the ability to sort the data by date ?
		// 1 : yes ; 0 : no;
		$tbldatesort 			= 1;															// Display Date Sorting Options Toggle Switch
		$tbltextsort 			= 0;															// Display Text Sorting Options Toggle Switch
		$tblheadersort			= 0;															// Display Heading Sort Options Toggle Switch
		
		// Temporaryly set here to inital a value.  The user will be able to define these using the interface as well.
		$tblduplicatesort		= 0;															// Show discrepancies which are duplicates
		$tblarchivedsort		= 0;															// Show discrepancies which are archived
		
	// this information is needed to program the datafields and sql statements on the fly without any user interuption
		// what is the primary key field (id field) of the table
		$tblkeyfield			= "inspection_system_id";										// What is the Auto Increment Field for this table ?
		$tbldatesortfield		= "139327_date";												// What is the name of field use in date sorting ?
		$tbldatesorttable		= "tbl_139_327_main";											// What table  is that field part of ?
		$tbltextsortfield		= "";															// What is the name of the field used in text sorting ?
		$tbltextsorttable		= "tbl_139_327_main";											// What is the name of the table used for text sorting ?
		$tblarchivedfield		= "";															// What is the name of the field used to mark the record archived ?
		$tblname				= "Part 139.327 Inspection Summary Report";						// What is the name of the table ? (used on edit/summary/printer report pages)
		$tblsubname				= "here is the information you selected";						// What is the subname of the table ? (used on edit/summary/printer report pages)
	
	// What php pages are used to control the summary, printer reports, and edit functions?
		// by default these pages should be the following
		// $functioneditpage 			= "edit_record_general.php";
		// $functionsummarypage 		= "summary_report_general.php";
		// $functionprinterpage 		= "printer_report_general.php";
		$functioneditpage 		= "part139327_main_edit.php";									// Name of page used to edit the record
		$functionsummarypage 	= "summary_report_general.php";									// Name of page used to display a summary of the record
		$functionprinterpage 	= "part139327_main_report.php";									// Name of page used to display a printer friendly report

	// what columns should the datagrid display?
		// this is an array of information, from [0] to [...], you may have an unlimited number of columns
		$adatafield[0]			= "139327_date";
		$adatafield[1]			= "139327_time";
		$adatafield[2]			= "type_of_inspection_cb_int";
		$adatafield[3]			= "inspection_completed_by_cb_int";
	// in each array specified above, what table does that field come from?
		$adatafieldtable[0]		= "tbl_139_327_main";
		$adatafieldtable[1]		= "tbl_139_327_main";
		$adatafieldtable[2]		= "tbl_139_327_main";
		$adatafieldtable[3]		= "tbl_139_327_main";		
	// do you want the user ot be able to click on the information and have something happen?
		// "notjoined" will cause nothing to happen, only the data will be displayed
		// the name of any field in any of the selected tables will cause only records that have that information to be displayed.
		$adatafieldid[0]		= "notjoined";
		$adatafieldid[1]		= "notjoined";
		$adatafieldid[2]		= "type_of_inspection_cb_int";
		$adatafieldid[3]		= "inspection_completed_by_cb_int";
	// in some cases you may want the information displayed to be prefecned with a $ sign or followed by a % sign. here you can tell the program what to display if anything
		// 0 : nothing ; 2 : @ ; 4 : $ ; 5 : %
		$adataspecial[0]		= 0;
		$adataspecial[1]		= 0;
		$adataspecial[2]		= 0;
		$adataspecial[3]		= 0;	
	// what do you want to name the columns
		$aheadername[0]			= "Date";
		$aheadername[1]			= "Time";
		$aheadername[2]			= "Type";
		$aheadername[3]			= "Inspector";
	// Any special comments to make after the input box?
		$ainputcomment[0]		= "( mm/dd/yyyy )";
		$ainputcomment[1]		= "( 24 hour )";
		$ainputcomment[2]		= "(select from the list)";
		$ainputcomment[3]		= "(select from the list)";
	// what type of input is this field?
		// this can be any type of input
		// text, textarea, select, etc.
		$ainputtype[0]			= "TEXT";
		$ainputtype[1]			= "TEXT";
		$ainputtype[2]			= "SELECT";
		$ainputtype[3]			= "SELECT";
	// if the input type is a SELECT type you should define the name of the function you want to call to load into that select statement
		$adataselect[0]			= "";
		$adataselect[1]			= "";
		$adataselect[2]			= "part139327typescombobox";
		$adataselect[3]			= "systemusercombobox";
	// in the event of an error when a user enteres a wrong date, what should the datagrid say to the user?
		$tmpDateEventErrorMessage	= "Error, reset to default";
		
	// Build SQL Statement
	// Since we have all of the information from the arrays and strings we can assemble the nessary SQL Statement without actually having to supply it verbatum.
	// The Limiting clauses will be left off, all we want to create at this point is the basic SELECT *,*,* FROM syntax
	
		$sql = "SELECT ".$tblkeyfield.",";														// Start SQL adding on the id field first						
		
		for ($i=0; $i<count($adatafield); $i=$i+1) {											// Loop through any of the arrays 0 to array length - 1
				$nsql = " ".$adatafield[$i]."";													// add each new value in the array to a temporary sql string
				$sql = $sql.$nsql;																// add the temporary sql string to the main sql string.
				if ($i == count($adatafield)-1) {												// test to see where we are in the string, in this case are we at the end or not?
						$nsql = "";																// at the end of the arrat dont add a , to the end of the value
						$sql = $sql.$nsql;														// add 'nothing' to the sql string
					}
					else {																		// not at the end of the array so do soemthing else
						$nsql = ", ";															// for each value in the array that is not at the end, add a , after the value
						$sql = $sql.$nsql;														// add the temporary sql string to the main sql string
					}			
		}
		$sql = $sql.$nsql;																		// field index values have all been added to the sql string (this line is reduntent, but there for space)
		$nsql = " FROM ".$tbldatesorttable." ";													// with all field index values added, add the FROM syntax and the applicable table in the DB
		$sql = $sql.$nsql;																		// make it all one nice sql string for use

	// For debugging purposes print out the SQL Statement
		//echo $sql;																				// When dedugging you can uncomment this echo and see the sql statement

	// Start the Real Fun	

		$i 							= 0;	
		$tblheadersortfirstselected="yes";
		$togfrmjoined = "";																		//just in case we dont define it latter, set a default here.
		$isarchived				= "";
		$displaydatarow			= "";


//-------------------- [ there should be no need to change any of the code below this line ] ----------------------------------------------------------------------------------------------------------------------
		
	// store this array into a serialized array
		$stradatafield 			= urlencode(serialize($adatafield));			// dont touch
		$stradatafieldtable 	= urlencode(serialize($adatafieldtable));		// dont touch
		$stradatafieldid 		= urlencode(serialize($adatafieldid));			// dont touch	
		$stradataspecial		= urlencode(serialize($adataspecial));			// dont touch
		$straheadername			= urlencode(serialize($aheadername));			// dont touch
		$strainputtype			= urlencode(serialize($ainputtype));			// dont touch
		$stradataselect			= urlencode(serialize($adataselect));			// dont touch
		$strainputcomment		= urlencode(serialize($ainputcomment));			// dont touch
		
	// store this array into a serialized array
		$sadatafield 			= (serialize($adatafield));						// dont touch
		$sadatafieldtable 		= (serialize($adatafieldtable));				// dont touch
		$sadatafieldid 			= (serialize($adatafieldid));					// dont touch	
		$sadataspecial			= (serialize($adataspecial));					// dont touch
		$saheadername			= (serialize($aheadername));					// dont touch
		$sainputtype			= (serialize($ainputtype));						// dont touch
		$sadataselect			= (serialize($adataselect));					// dont touch
		$sainputcomment			= (serialize($ainputcomment));					// dont touch
		
		$sadatafield  			= str_replace("\"","|",$sadatafield);
		$sadatafieldtable 		= str_replace("\"","|",$sadatafieldtable);
		$sadatafieldid 			= str_replace("\"","|",$sadatafieldid);
		$sadataspecial 			= str_replace("\"","|",$sadataspecial);
		$saheadername 			= str_replace("\"","|",$saheadername);
		$sainputtype 			= str_replace("\"","|",$sainputtype);
		$sadataselect 			= str_replace("\"","|",$sadataselect);
		$sainputcomment			= str_replace("\"","|",$sainputcomment);

$tmpfrmstartdateerror = "";
$tmpfrmenddateerror = "";

if (!isset($_POST["frmstartdate"])) {
		//$tbldatesortstart=($current_date-30);		
		// setup startdate for query
		$uifrmstartdate 			= date('m/d/Y');
		$sqlfrmstartdate 		= amerdate2sqldatetime($uifrmstartdate);
	}
	else {
		$uifrmstartdate 		= $frmstartdate;
		$sqlfrmstartdate 		= amerdate2sqldatetime($frmstartdate);
		}

if (!isset($_POST["frmenddate"])) {
		//$tbldatesortstart=($current_date-30);		
		// setup startdate for query
		$uifrmenddate 			= date('m/d/Y');
		$sqlfrmenddate 			= amerdate2sqldatetime($uifrmenddate);
	}
	else {
		$uifrmenddate 			= $frmenddate;
		$sqlfrmenddate 			= amerdate2sqldatetime($frmenddate);
		}

if (!isset($_POST["frmjoined"])) {
		//there is no information in this field, set default values
		
		// check to see if the intfrmjoined field has any value
		$intfrmjoined	= 0;
		$intsqlwhereaddon = 0;
		$strsqlwhereaddon = "none";
		
		$togfrmjoined = 1;
		
		if (!isset($_POST["intfrmjoined"])) {
				//echo "there is no value in intfrmjoined";
				$frmjoined 				= 0; //set value to zero (this causes the checkbox to not be checked
				$intfrmjoined 			= 0; //set value to zero (this causes the checkbox to not be checked
				}
			else {
				//echo "value is ".$intfrmjoined." ";
				if ($intfrmjoined==0) {
						// the value of the box has 'no' value
						$frmjoined				= "0";
						$intfrmjoined			= 0;
					}
					else {
						// the value of the box isn't one so we should do what it says
						$frmjoined				= 1;
						$intfrmjoined			= 1;
					}				
			}
		}
	else {
		// the field does exist, what is its current value
		$frmjoined				= $_POST["frmjoined"];
		$intfrmjoined			= 1;
		}	

if (!isset($_POST["strsqlwhereaddon"])) {
		$strsqlwhereaddon="none";
		$intsqlwhereaddon = 0;
	}
	else {
		if ($togfrmjoined==1) {
				// checkbox is not active, clean out sql statement
				$strsqlwhereaddon		= "";
				$intsqlwhereaddon 		= 1;
			}
			else {
	
		$strsqlwhereaddon		= $_POST["strsqlwhereaddon"];
		$strsqlwhereaddon 		= str_replace("%3d","=",$strsqlwhereaddon );
		//$tblsqlwhereaddon 		= 1;
		}
	} 

if (!isset($_POST["intsqlwhereaddon"])) {
		if ($tbldatesort==1) {
				$intsqlwhereaddon = 1;
			}
		//tblsqlwhereaddon = 1
	}
	else {
		$intsqlwhereaddon=$_POST["intsqlwhereaddon"];
		//tblsqlwhereaddon = 1
	}
	
for ($i=0; $i<count($aheadername); $i++) {
	if (!isset($_POST[$adatafield[$i]])) {
			$aheadersort[$i]="NotSorted";
		}
		else {
			$aheadersort[$i]=$_POST[$adatafield[$i]];
		} 
	}


// add where statement to sql statement
if ($tbldatesort==1) {
		$today	= date('Y/m/d');
		//echo $today;
		$nsql = " where ".$tbldatesorttable.".".$tbldatesortfield." >= '".$today."' and ".$tbldatesorttable.".".$tbldatesortfield." <= '".$today."'";
		$sql = $sql.$nsql;
		?>
		<script>	</script>
		<?
	}
if ($tbltextsort==1) {
		if ($tbldatesort==1) {
				$nsql = " and ".$tbltextsorttable.".".$tbltextsortfield." like '%".$frmtextlike."%' ";
				$sql = $sql.$nsql;
				}
			else {
				$nsql = " where ".$tbltextsorttable.".".$tbltextsortfield." like '%".$frmtextlike."%' ";
				$sql = $sql.$nsql;	
				}
	}
if ($strsqlwhereaddon=="none") {
		//do not add any additional sql to the sql statement as they have not been provided
		}
	else {
		if ($intfrmjoined==0) {
				// user has choosen not to enable column joining, so dont do it
				}
			else {
				// user has enabled column joining, so do it
				$sql = $sql.$strsqlwhereaddon;
			}
	}

//order by sql statement
if ($tblheadersort==1) {
	for ($i=0; $i<count($aheadersort); $i=$i+1) {
			if ($aheadersort[$i]=="NotSorted") {
					//do not add sorting column to sql string
				} 
			if ($aheadersort[$i]=="Assending") {
					if ($tblheadersortfirstselected=="yes") {
							//this is the first time a header has been selected
							$tblheadersortfirstselected="no"; //set selected to no
							$nsql=" order by ".$adatafieldtable[$i].".".$adatafield[$i]." ";
							$sql = $sql.$nsql;
						}
						else {
							$sql = $sql.", ".$adatafieldtable[$i].".".$adatafield[$i]." ";
						} 
				} 
			if ($aheadersort[$i]=="Decending") {
					if ($tblheadersortfirstselected=="yes") {
							//this is the first time a header has been selected
							$tblheadersortfirstselected="no"; //set selected to no
							$nsql=" order by ".$adatafieldtable[$i].".".$adatafield[$i]." desc ";
							$sql = $sql.$nsql;
						}
						else {
							$sql = $sql.", ".$adatafieldtable[$i].".".$adatafield[$i]." desc ";
						} 
				} 
		}
	}
	$sql 		= str_replace("%3D","=",$sql);
	//echo $sql;
	
if (!isset($_POST["frmarchives"])) {
		$tblarchivedsort	= "";
	}
	else {
		// the field does exist, what is its current value
		$tblarchivedsort				= $_POST["frmarchives"];
	}
?>
<table border="0" width="100%" id="tblbrowseformtable" cellspacing="0" cellpadding="0">
	<tr>
		<td class="tableheadercenter">
			<b><?=$tmp;?></b>
			</td>
		</tr>
	<tr>
		<td colspan="3" class="tablesubcontent">
			<table border="0" width="100%" cellspacing="4">
				<?
				//make connection to database
				$objconn = mysqli_connect("localhost", "webuser", "limitaces", "openairport");
		
				if (mysqli_connect_errno()) {
						// there was an error trying to connect to the mysql database
						printf("connect failed: %s\n", mysqli_connect_error());
						exit();
					}
					else {
						$objrs = mysqli_query($objconn, $sql);
				
						if ($objrs) {
								$number_of_rows = mysqli_num_rows($objrs);								
								if ($number_of_rows==0) {
										?>
				<tr>
					<td class="formresultscount">
							No Records Founds for today
						</td>
					</tr>
										<?
									}
									else {
										?>
				<tr>
					<td class="formresultscount">
							<?=$number_of_rows;?> records have been found today, may include archived inspections
						</td>
					</tr>
				<tr>
					<td class="tabledatarow">
						<table border="0" width="100%" id="table1" cellpadding="0" cellspacing="1" style="border-collapse: collapse">
							<tr>
								<td class="formheaders">
									ID
									</td>
								<? 
								for ($i=0; $i<count($aheadername); $i=$i+1) {
										?>
								<td class="formheaders">
										<? 
										if ($tblheadersort==1) {
												?>
									<a href="#" onfocus="javascript:getvaluesortform('<?=$adatafield[$i];?>');" onclick="javascript:updatesortform('<?=$adatafield[$i];?>');"><font color="#ffffff"><?=$aheadername[$i];?></font></a>
									<br>(<input class="inlinehiddenbox" type="text" size="8" id="<?=$adatafield[$i];?>" name="<?=$adatafield[$i];?>" value="<?=$aheadersort[$i];?>">)
												<? 
											}
											else {
												echo $aheadername[$i];
											}	
										?>
									</td>
										<?
									}
								?>
							</form>
								</tr>
										<?
										while ($objarray = mysqli_fetch_array($objrs, MYSQLI_ASSOC)) {
										//test 2). Is this inspection archived? to determine if the inspection is archived we look in the archived table for the ID of this inspection.
												//Anything over 0 records means it is archived and we need to display the archived summary report, making a little 'A' box
												
												$sql2 = "SELECT * FROM tbl_139_327_sub_a WHERE archived_inspection_id = '".$objarray[$tblkeyfield]."' ";
												//make connection to database
												$objconn2 = mysqli_connect("localhost", "webuser", "limitaces", "openairport");
												if (mysqli_connect_errno()) {
														// there was an error trying to connect to the mysql database
														printf("connect failed: %s\n", mysqli_connect_error());
														exit();
													}
													else {
														$objrs2 = mysqli_query($objconn2, $sql2);
														if ($objrs2) {
																$number_of_rows = mysqli_num_rows($objrs2);
																while ($objarray2 = mysqli_fetch_array($objrs2, MYSQLI_ASSOC)) {
																		$tmpid 		= $objarray2['archived_id'];
																		$isarchived 	= 1;
																	}
															}
													}
												
												if ($isarchived==1) {
														//echo "Is rchived, do I display it?".$tblarchivedsort;
														if ($tblarchivedsort=="1") {
																//echo "Display Row";
																$displaydatarow=1;
															}
															else {
																// Don't display datarow
																//echo "Dont Display row";
																$displaydatarow=0;
															}
													}
													else {
														// This record is not a duplicate, and not archived, so lets display it anyway
														$displaydatarow=1;
													}
										
										if ($displaydatarow == 1) {
												?>
							<tr>
								<form style="margin-bottom:0;" action="part139327_main_report.php" method="POST" name="printform" id="printform" target="PrinterFriendlyReport" onsubmit="window.open('', 'PrinterFriendlyReport', 'width=750,height=962,status=no,resizable=no,scrollbars=yes')">
									<td height="32" align="center" class="formresults">
										<input type="hidden" name="recordid" 			value="<?=$objarray[$tblkeyfield];?>">
										<input type="submit" name="b1" 					value="<?=$objarray[$tblkeyfield];?>"			class="formsubmit">
										</td>
									</form>
									</td>
								<? 
								for ($i=0; $i<count($aheadername); $i=$i+1) {
										?>
								<td align="center" valign="middle" class="formresults">
										<? 
										switch ($adatafieldid[$i]) {
												case "notjoined":
														switch ($adataspecial[$i]) {
																case 2:
																		?>
									@ <?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
																case 4:
																		?>
									$ <?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
																case 5:
																		?>
									<?=$objarray[$adatafield[$i]];?> %
																		<? 
																		break;
																default:
																		?>
									<?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
															}
														break;
												case "notjoined":
														switch ($ainputtype[$i]) {
																case "CHECKBOX":
																		if ($objarray[$adatafield[$i]]==0) {
																				$tmpcbfield = "No";
																			}
																			else {
																				$tmpcbfield = "Yes";
																			}
																			?>
									<a href="javascript:updatewhereform('<?=$adatafieldtable[$i];?>.<?=$adatafield[$i];?>=<?=$objarray[$adatafield[$i]];?>');">
										<font color="#000000">
											<?=$tmpcbfield;?>
											</font>
										</a>				
																			<?
																		break;
																default:
																				?>
									<a href="javascript:updatewhereform('<?=$adatafieldtable[$i];?>.<?=$adatafield[$i];?>=<?=$objarray[$adatafield[$i]];?>');">
										<font color="#000000">
											<?=$objarray[$adatafield[$i]];?>
											</font>
										</a>
														<?
																		break;
																}
														break;
												default:											
														$tmpsqlwhereaddon=$objarray[$adatafieldid[$i]];
														?>
									<a href="javascript:updatewhereform('<?=$adatafieldtable[$i];?>.<?=$adatafieldid[$i];?>=<?=$tmpsqlwhereaddon;?>');">
										<font color="#000000">
														<?
											$adataselect[$i]($objarray[$adatafieldid[$i]], "all", $adatafield[$i], "hide", "");
														?>
											</font>
										</a>
														<? 
														break;
												}
											//} 
											?>
									</td>
											<? 
									}
								?>
								</tr>
									<?	
									$isduplicate			= "";
									$isarchived			= "";											
											}	// end of looped data
									$isduplicate			= "";
									$isarchived			= "";
									}
									mysqli_free_result($objrs);
									mysqli_close($objconn);
									?>
							</table>
									<?
									}	// end of records found statement
								}	// end of sucessfull conection and execution of sql statement
							}	// end of connection established
							?>
						</td>
					</tr>					
				</table>	<!-- end of ajax load table-->
			</td>
		</tr>
	</table>
	<?
	}
	
function quickinfopart139327discrepancies($tmp) {

		// will the form use any of the following toggle switches	
		// should the form have the ability to sort the data by date ?
		// 1 : yes ; 0 : no;
		$tbldatesort 			= 0;															// Display Date Sorting Options Toggle Switch
		$tbltextsort 			= 0;															// Display Text Sorting Options Toggle Switch
		$tblheadersort			= 0;															// Display Heading Sort Options Toggle Switch
		
		// Temporaryly set here to inital a value.  The user will be able to define these using the interface as well.
		$tblduplicatesort		= 0;															// Show discrepancies which are duplicates
		$tblarchivedsort			= 0;															// Show discrepancies which are archived
		$tblworkorderssort		= 0;
		$tblrepairedsort			= 0;
		
	// this information is needed to program the datafields and sql statements on the fly without any user interuption
		// what is the primary key field (id field) of the table
		$tblkeyfield			= "Discrepancy_id";														// What is the Auto Increment Field for this table ?
		$tbldatesortfield		= "Discrepancy_date";													// What is the name of field use in date sorting ?
		$tbldatesorttable		= "tbl_139_327_sub_d";													// What table  is that field part of ?
		$tbltextsortfield		= "Discrepancy_name";													// What is the name of the field used in text sorting ?
		$tbltextsorttable		= "tbl_139_327_sub_d";													// What is the name of the table used for text sorting ?
		$tblarchivedfield		= "";																// What is the name of the field used to mark the record archived ?
		$tblname				= "Discrepancy Monitor";													// What is the name of the table ? (used on edit/summary/printer report pages)
		$tblsubname				= "here is the information you selected";									// What is the subname of the table ? (used on edit/summary/printer report pages)
	
	// What php pages are used to control the summary, printer reports, and edit functions?
		// by default these pages should be the following
		// $functioneditpage 			= "edit_record_general.php";
		// $functionsummarypage 		= "summary_report_general.php";
		// $functionprinterpage 		= "printer_report_general.php";
		$functioneditpage 		= "part139327_sub_d_edit.php";												// Name of page used to edit the record
		$functionsummarypage 	= "summary_report_general.php";											// Name of page used to display a summary of the record
		$functionprinterpage 	= "printer_report_general.php";											// Name of page used to display a printer friendly report

	// what columns should the datagrid display?
		// this is an array of information, from [0] to [...], you may have an unlimited number of columns
		$adatafield[0]			= "Discrepancy_date";
		$adatafield[1]			= "Discrepancy_time";
		$adatafield[2]			= "discrepancy_priority";
		$adatafield[3]			= "discrepancy_name";
		$adatafield[4]			= "discrepancy_by_cb_int";
	// in each array specified above, what table does that field come from?
		$adatafieldtable[0]		= "tbl_139_327_sub_d";
		$adatafieldtable[1]		= "tbl_139_327_sub_d";
		$adatafieldtable[2]		= "tbl_139_327_sub_d";
		$adatafieldtable[3]		= "tbl_139_327_sub_d";		
		$adatafieldtable[4]		= "tbl_139_327_sub_d";	
	// do you want the user ot be able to click on the information and have something happen?
		// "notjoined" will cause nothing to happen, only the data will be displayed
		// the name of any field in any of the selected tables will cause only records that have that information to be displayed.
		$adatafieldid[0]		= "notjoined";
		$adatafieldid[1]		= "notjoined";
		$adatafieldid[2]		= "discrepancy_priority";
		$adatafieldid[3]		= "notjoined";
		$adatafieldid[4]		= "discrepancy_by_cb_int";
	// in some cases you may want the information displayed to be prefecned with a $ sign or followed by a % sign. here you can tell the program what to display if anything
		// 0 : nothing ; 2 : @ ; 4 : $ ; 5 : %
		$adataspecial[0]		= 0;
		$adataspecial[1]		= 0;
		$adataspecial[2]		= 0;
		$adataspecial[3]		= 0;
		$adataspecial[4]		= 0;
	// what do you want to name the columns
		$aheadername[0]			= "Date";
		$aheadername[1]			= "Time";
		$aheadername[2]			= "Priority";
		$aheadername[3]			= "Name";
		$aheadername[4]			= "Author";
	// Any special comments to make after the input box?
		$ainputcomment[0]		= "( mm/dd/yyyy )";
		$ainputcomment[1]		= "( 24 hour )";
		$ainputcomment[2]		= "(select from the list)";
		$ainputcomment[3]		= "(no special charactors)";
		$ainputcomment[4]		= "(select from the list)";
	// what type of input is this field?
		// this can be any type of input
		// text, textarea, select, etc.
		$ainputtype[0]			= "TEXT";
		$ainputtype[1]			= "TEXT";
		$ainputtype[2]			= "SELECT";
		$ainputtype[3]			= "TEXT";
		$ainputtype[4]			= "SELECT";
	// if the input type is a SELECT type you should define the name of the function you want to call to load into that select statement
		$adataselect[0]			= "";
		$adataselect[1]			= "";
		$adataselect[2]			= "part139327prioritycombobox";
		$adataselect[3]			= "";
		$adataselect[4]			= "systemusercombobox";
	// in the event of an error when a user enteres a wrong date, what should the datagrid say to the user?
		$tmpDateEventErrorMessage	= "Error, reset to default";
		
	// Build SQL Statement
	// Since we have all of the information from the arrays and strings we can assemble the nessary SQL Statement without actually having to supply it verbatum.
	// The Limiting clauses will be left off, all we want to create at this point is the basic SELECT *,*,* FROM syntax
	
		$sql = "SELECT ".$tblkeyfield.",";																// Start SQL adding on the id field first						
		
		for ($i=0; $i<count($adatafield); $i=$i+1) {													// Loop through any of the arrays 0 to array length - 1
				$nsql = " ".$adatafield[$i]."";														// add each new value in the array to a temporary sql string
				$sql = $sql.$nsql;																	// add the temporary sql string to the main sql string.
				if ($i == count($adatafield)-1) {														// test to see where we are in the string, in this case are we at the end or not?
						$nsql = "";																// at the end of the arrat dont add a , to the end of the value
						$sql = $sql.$nsql;															// add 'nothing' to the sql string
					}
					else {																		// not at the end of the array so do soemthing else
						$nsql = ", ";																// for each value in the array that is not at the end, add a , after the value
						$sql = $sql.$nsql;															// add the temporary sql string to the main sql string
					}			
		}
		$sql = $sql.$nsql;																			// field index values have all been added to the sql string (this line is reduntent, but there for space)
		$nsql = " FROM ".$tbldatesorttable." ";															// with all field index values added, add the FROM syntax and the applicable table in the DB
		$sql = $sql.$nsql;																			// make it all one nice sql string for use

	// For debugging purposes print out the SQL Statement
		//echo $sql;																				// When dedugging you can uncomment this echo and see the sql statement

	// Start the Real Fun	

		$i 							= 0;	
		$tblheadersortfirstselected="yes";
		$togfrmjoined = "";																			//just in case we dont define it latter, set a default here.
		$discrepancybouncedid 	= "";
		$discrepancybounceddate = "";
		$discrepancybouncedtime = "";
		$discrepancyrepairid 	= "";
		$discrepancyrepairdate 	= "";
		$discrepancyrepairtime 	= "";
		$isduplicate			= "";
		$isarchived				= "";
		$displaydatarow			= "";
		$tblduplicatesort		= 0;															// Show discrepancies which are duplicates
		$tblarchivedsort		= 0;															// Show discrepancies which are archived
		$tblworkorderssort		= 0;
		$tblrepairedsort		= 0;
		


//-------------------- [ there should be no need to change any of the code below this line ] ----------------------------------------------------------------------------------------------------------------------

	// store this array into a serialized array
		$stradatafield 			= urlencode(serialize($adatafield));			// dont touch
		$stradatafieldtable 		= urlencode(serialize($adatafieldtable));			// dont touch
		$stradatafieldid 		= urlencode(serialize($adatafieldid));			// dont touch	
		$stradataspecial		= urlencode(serialize($adataspecial));			// dont touch
		$straheadername			= urlencode(serialize($aheadername));			// dont touch
		$strainputtype			= urlencode(serialize($ainputtype));			// dont touch
		$stradataselect			= urlencode(serialize($adataselect));			// dont touch
		$strainputcomment		= urlencode(serialize($ainputcomment));			// dont touch
		
	// store this array into a serialized array
		$sadatafield 			= (serialize($adatafield));						// dont touch
		$sadatafieldtable 		= (serialize($adatafieldtable));				// dont touch
		$sadatafieldid 			= (serialize($adatafieldid));					// dont touch	
		$sadataspecial			= (serialize($adataspecial));					// dont touch
		$saheadername			= (serialize($aheadername));					// dont touch
		$sainputtype			= (serialize($ainputtype));						// dont touch
		$sadataselect			= (serialize($adataselect));					// dont touch
		$sainputcomment			= (serialize($ainputcomment));					// dont touch
		
		$sadatafield  			= str_replace("\"","|",$sadatafield);
		$sadatafieldtable 		= str_replace("\"","|",$sadatafieldtable);
		$sadatafieldid 			= str_replace("\"","|",$sadatafieldid);
		$sadataspecial 			= str_replace("\"","|",$sadataspecial);
		$saheadername 			= str_replace("\"","|",$saheadername);
		$sainputtype 			= str_replace("\"","|",$sainputtype);
		$sadataselect 			= str_replace("\"","|",$sadataselect);
		$sainputcomment			= str_replace("\"","|",$sainputcomment);

$tmpfrmstartdateerror = "";
$tmpfrmenddateerror = "";

if (!isset($_POST["frmstartdate"])) {
		//$tbldatesortstart=($current_date-30);		
		// setup startdate for query
		$uifrmstartdate 		= date('m/d/Y');
		$sqlfrmstartdate 		= amerdate2sqldatetime($uifrmstartdate);
	}
	else {
		$uifrmstartdate 		= $frmstartdate;
		$sqlfrmstartdate 		= amerdate2sqldatetime($frmstartdate);
		}

if (!isset($_POST["frmenddate"])) {
		//$tbldatesortstart=($current_date-30);		
		// setup startdate for query
		$uifrmenddate 			= date('m/d/Y');
		$sqlfrmenddate 			= amerdate2sqldatetime($uifrmenddate);
	}
	else {
		$uifrmenddate 			= $frmenddate;
		$sqlfrmenddate 			= amerdate2sqldatetime($frmenddate);
		}

if (!isset($_POST["frmjoined"])) {
		//there is no information in this field, set default values
		
		// check to see if the intfrmjoined field has any value
		$intfrmjoined	= 0;
		$intsqlwhereaddon = 0;
		$strsqlwhereaddon = "none";
		
		$togfrmjoined = 1;
		
		if (!isset($_POST["intfrmjoined"])) {
				//echo "there is no value in intfrmjoined";
				$frmjoined 				= 0; //set value to zero (this causes the checkbox to not be checked
				$intfrmjoined 				= 0; //set value to zero (this causes the checkbox to not be checked
				}
			else {
				//echo "value is ".$intfrmjoined." ";
				if ($intfrmjoined==0) {
						// the value of the box has 'no' value
						$frmjoined				= "0";
						$intfrmjoined			= 0;
					}
					else {
						// the value of the box isn't one so we should do what it says
						$frmjoined				= 1;
						$intfrmjoined			= 1;
					}				
			}
		}
	else {
		// the field does exist, what is its current value
		$frmjoined				= $_POST["frmjoined"];
		$intfrmjoined			= 1;
		}	

if (!isset($_POST["strsqlwhereaddon"])) {
		$strsqlwhereaddon="none";
		$intsqlwhereaddon = 0;
	}
	else {
		if ($togfrmjoined==1) {
				// checkbox is not active, clean out sql statement
				$strsqlwhereaddon		= "";
				$intsqlwhereaddon 		= 1;
			}
			else {
	
		$strsqlwhereaddon		= $_POST["strsqlwhereaddon"];
		$strsqlwhereaddon 		= str_replace("%3d","=",$strsqlwhereaddon );
		//$tblsqlwhereaddon 		= 1;
		}
	} 

if (!isset($_POST["intsqlwhereaddon"])) {
		if ($tbldatesort==1) {
				$intsqlwhereaddon = 1;
			}
		//tblsqlwhereaddon = 1
	}
	else {
		$intsqlwhereaddon=$_POST["intsqlwhereaddon"];
		//tblsqlwhereaddon = 1
	}
	
for ($i=0; $i<count($aheadername); $i++) {
	if (!isset($_POST[$adatafield[$i]])) {
			$aheadersort[$i]="NotSorted";
		}
		else {
			$aheadersort[$i]=$_POST[$adatafield[$i]];
		} 
	}


// add where statement to sql statement
if ($tbldatesort==1) {
		$nsql = " where ".$tbldatesorttable.".".$tbldatesortfield." >= '".$sqlfrmstartdate."' and ".$tbldatesorttable.".".$tbldatesortfield." <= '".$sqlfrmenddate."'";
		$sql = $sql.$nsql;
	}
	
if ($tbltextsort==1) {
		if ($tbldatesort==1) {
				$nsql = " and ".$tbltextsorttable.".".$tbltextsortfield." like '%".$frmtextlike."%' ";
				$sql = $sql.$nsql;
				}
			else {
				$nsql = " where ".$tbltextsorttable.".".$tbltextsortfield." like '%".$frmtextlike."%' ";
				$sql = $sql.$nsql;	
				}
	}
	
if ($strsqlwhereaddon=="none") {
		//do not add any additional sql to the sql statement as they have not been provided
		}
	else {
		if ($intfrmjoined==0) {
				// user has choosen not to enable column joining, so dont do it
				}
			else {
				// user has enabled column joining, so do it
				$sql = $sql.$strsqlwhereaddon;
			}
	}

//order by sql statement
if ($tblheadersort==1) {
	for ($i=0; $i<count($aheadersort); $i=$i+1) {
			if ($aheadersort[$i]=="NotSorted") {
					//do not add sorting column to sql string
				} 
			if ($aheadersort[$i]=="Assending") {
					if ($tblheadersortfirstselected=="yes") {
							//this is the first time a header has been selected
							$tblheadersortfirstselected="no"; //set selected to no
							$nsql=" order by ".$adatafieldtable[$i].".".$adatafield[$i]." ";
							$sql = $sql.$nsql;
						}
						else {
							$sql = $sql.", ".$adatafieldtable[$i].".".$adatafield[$i]." ";
						} 
				} 
			if ($aheadersort[$i]=="Decending") {
					if ($tblheadersortfirstselected=="yes") {
							//this is the first time a header has been selected
							$tblheadersortfirstselected="no"; //set selected to no
							$nsql=" order by ".$adatafieldtable[$i].".".$adatafield[$i]." desc ";
							$sql = $sql.$nsql;
						}
						else {
							$sql = $sql.", ".$adatafieldtable[$i].".".$adatafield[$i]." desc ";
						} 
				} 
		}
	}
	$sql 		= str_replace("%3D","=",$sql);
	//echo $sql;
	
if (!isset($_POST["frmduplicates"])) {
		$tblduplicatesort	= "";
	}
	else {
		// the field does exist, what is its current value
		$tblduplicatesort				= $_POST["frmduplicates"];
	}
if (!isset($_POST["frmarchives"])) {
		$tblarchivedsort	= "";
	}
	else {
		// the field does exist, what is its current value
		$tblarchivedsort				= $_POST["frmarchives"];
	}
if (!isset($_POST["frmworkorders"])) {
		$tblworkorderssort	= "";
	}
	else {
		// the field does exist, what is its current value
		$tblworkorderssort				= $_POST["frmworkorders"];
	}
if (!isset($_POST["frmrepaired"])) {
		$tblrepairedsort	= "";
	}
	else {
		// the field does exist, what is its current value
		$tblrepairedsort				= $_POST["frmrepaired"];
	}
?>

<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" name="sorttable" id="sorttable">
<input type="hidden" name="menuitemid" value="<?=$_POST['menuitemid'];?>">
<table border="0" width="100%" id="tblbrowseformtable" cellspacing="0" cellpadding="0">
	<tr>
		<td class="tableheadercenter">
			<b><?=$tmp;?></b>
			</td>
		</tr>
	<tr>
		<td colspan="3" class="tablesubcontent">
			<table border="0" width="100%" cellspacing="4">
				<?
				//make connection to database
				$objconn = mysqli_connect("localhost", "webuser", "limitaces", "openairport");
		
				if (mysqli_connect_errno()) {
						// there was an error trying to connect to the mysql database
						printf("connect failed: %s\n", mysqli_connect_error());
						exit();
					}
					else {
						$objrs = mysqli_query($objconn, $sql);
				
						if ($objrs) {
								$number_of_rows = mysqli_num_rows($objrs);								
								if ($number_of_rows==0) {
										?>
				<tr>
					<td class="formresultscount">
							No Records Founds for today
						</td>
					</tr>
										<?
									}
									else {
										?>
				<tr>
					<td class="formresultscount">
							<?=$number_of_rows;?> records have been found today, may include archived, closed, or otherwise hidden discrepancies.
						</td>
					</tr>
				<tr>
					<td class="tabledatarow">
						<table border="0" width="100%" id="table1" cellpadding="0" cellspacing="1" style="border-collapse: collapse">
							<tr>
								<td class="formheaders">
									ID
									</td>
								<? 
								for ($i=0; $i<count($aheadername); $i=$i+1) {
										?>
								<td class="formheaders">
										<? 
										if ($tblheadersort==1) {
												?>
									<a href="#" onfocus="javascript:getvaluesortform('<?=$adatafield[$i];?>');" onclick="javascript:updatesortform('<?=$adatafield[$i];?>');"><font color="#ffffff"><?=$aheadername[$i];?></font></a>
									<br>(<input class="inlinehiddenbox" type="text" size="8" id="<?=$adatafield[$i];?>" name="<?=$adatafield[$i];?>" value="<?=$aheadersort[$i];?>">)
												<? 
											}
											else {
												echo $aheadername[$i];
											}	
										?>
									</td>
										<?
									}
									?>
									</form>
								<td class="formheaders">
									Classifications
									</td>
								</tr>
										<?
										while ($objarray = mysqli_fetch_array($objrs, MYSQLI_ASSOC)) {
										
												// We need to determine if this discrepancy is archived or a duplicate before we start to display anything about them. That way we can control how they look or if we even show
												// the discrepancy at all.
										
												// Step 1 is to see if the user wants to display duplicated discrepancies
												//test 1). Is this discrepancy a duplicate? to determine this we need to do a search in the duplcate table for this id and see if we get a record. 
												//Anything over 0 records means it is a duplicate and we need to display the duplicate summary report, making a little 'D' box
								
												$sql2 = "SELECT * FROM tbl_139_327_sub_d_d WHERE discrepancy_duplicate_inspection_id = '".$objarray[$tblkeyfield]."' ";
												//echo $sql2;
												//make connection to database
												$objconn2 = mysqli_connect("localhost", "webuser", "limitaces", "openairport");
												if (mysqli_connect_errno()) {
														// there was an error trying to connect to the mysql database
														printf("connect failed: %s\n", mysqli_connect_error());
														exit();
													}
													else {
														$objrs2 = mysqli_query($objconn2, $sql2);
														if ($objrs2) {
																$number_of_rows = mysqli_num_rows($objrs2);
																//echo $number_of_rows;
																while ($objarray2 = mysqli_fetch_array($objrs2, MYSQLI_ASSOC)) {
																		$tmpid = $objarray2['discrepancy_duplicate_id'];
																		$isduplicate = 1;
																	}
																	mysqli_free_result($objrs2);
																	mysqli_close($objconn2);
															}
													}
												//test 2). Is this discrepancy archived? to determine this we need to do a search in the duplcate table for this id and see if we get a record. 
												//Anything over 0 records means it is archived and we need to display the archived summary report, making a little 'A' box
												$sql2 = "SELECT * FROM tbl_139_327_sub_d_a WHERE discrepancy_archeived_inspection_id = '".$objarray[$tblkeyfield]."' ";
												//make connection to database
												$objconn2 = mysqli_connect("localhost", "webuser", "limitaces", "openairport");
												if (mysqli_connect_errno()) {
														// there was an error trying to connect to the mysql database
														printf("connect failed: %s\n", mysqli_connect_error());
														exit();
													}
													else {
														$objrs2 = mysqli_query($objconn2, $sql2);
														if ($objrs2) {
																$number_of_rows = mysqli_num_rows($objrs2);
																while ($objarray2 = mysqli_fetch_array($objrs2, MYSQLI_ASSOC)) {
																		$tmpid = $objarray2['discrepancy_archeived_id'];
																		$isarchived = 1;
																	}
																	mysqli_free_result($objrs2);
																	mysqli_close($objconn2);
															}
													}
												//test 3). Determine if the Discrepancy is currently outstanding or has been fixed. This involves checking both the repaired and bounced tables for information about the
												//current discrepancy ID. This will be done in three phases. 
												//Phase 1 will be to check the bounced table to see if there is any records about this discrepancy ID there. if so get the date of the latest record and put the ID of the record in a variable
												//phase two will be to check the repaired table and see if there is any information about this discrepancy there. if so get the date of the latest record and put the ID of the record in a variable
												//phase three will be to compare the two dates provided and see which event is most recent.
											
												$sql2 = "SELECT * FROM tbl_139_327_sub_d_b WHERE discrepancy_bounced_inspection_id = '".$objarray[$tblkeyfield]."' ORDER BY discrepancy_bounced_date, discrepancy_bounced_time";
												//echo $sql2;		
												//make connection to database
															
												$objconn2 = mysqli_connect("localhost", "webuser", "limitaces", "openairport");
							
												if (mysqli_connect_errno()) {
														// there was an error trying to connect to the mysql database
														printf("connect failed: %s\n", mysqli_connect_error());
														exit();
													}
													else {
														$objrs2 = mysqli_query($objconn2, $sql2);
									
														if ($objrs2) {
																$number_of_rows = mysqli_num_rows($objrs2);
																//echo "Bouced Rows ".$number_of_rows;
																while ($objarray2 = mysqli_fetch_array($objrs2, MYSQLI_ASSOC)) {
																	$discrepancybouncedid 	= $objarray2['discrepancy_bounced_id'];
																	$discrepancybounceddate 	= $objarray2['discrepancy_bounced_date'];
																	$discrepancybouncedtime 	= $objarray2['discrepancy_bounced_time'];
																	//echo $discrepancybouncedtime;
																	?>
																	<?	
																	}
																	mysqli_free_result($objrs2);
																	mysqli_close($objconn2);
															}
													}
												$sql2 = "SELECT * FROM tbl_139_327_sub_d_r WHERE discrepancy_repaired_inspection_id = '".$objarray[$tblkeyfield]."' ORDER BY discrepancy_repaired_date, discrepancy_repaired_time";
												//make connection to database
															
												$objconn2 = mysqli_connect("localhost", "webuser", "limitaces", "openairport");
							
												if (mysqli_connect_errno()) {
														// there was an error trying to connect to the mysql database
														printf("connect failed: %s\n", mysqli_connect_error());
														exit();
													}
													else {
														$objrs2 = mysqli_query($objconn2, $sql2);
									
														if ($objrs2) {
																$number_of_rows = mysqli_num_rows($objrs2);
																//echo " Repaired Rows ".$number_of_rows."<br>";
																while ($objarray2 = mysqli_fetch_array($objrs2, MYSQLI_ASSOC)) {
																	$discrepancyrepairid 	= $objarray2['discrepancy_repaired_id'];
																	$discrepancyrepairdate 	= $objarray2['discrepancy_repaired_date'];
																	$discrepancyrepairtime 	= $objarray2['discrepancy_repaired_time'];
																	//echo $discrepancyrepairtime;
																	?>
																	<?	
																	}
																	mysqli_free_result($objrs2);
																	mysqli_close($objconn2);
															}
													}
												
												if ($discrepancyrepairid == "") {									// There is no repair history. Without being repaired you by definition cant bounce so we give the user the workorder button
														$displaydatarow	= 1;
													}
													else {													// There is a number in the repair ID
														if ($discrepancybouncedid == "") {							// There is not a number in the bounceid, do display the repaired icon
																$displaydatarow	= 0;
															}
															else {											// There is a number in the bounced field
																											// Now we need to compare the date and time of the each record and get the most recent event
																if ($discrepancybounceddate > $discrepancyrepairdate) {								//Bounce is more recent then repair regardless of time, so display bounce icon
																		$displaydatarow	= 1;
																	}
																	else {									// Bounce date is not greater then repaire date
																		if ($discrepancybounceddate == $discrepancyrepairdate) {						// Is the bounce date the same as the repair date?
																				//echo "bounce date is equal to repair date<br>";			// next we need to see if bounce is more recent timewise then the repair time
																				//echo $discrepancybouncedtime." vs ".$discrepancyrepairtime."<br>";
																				if ($discrepancybouncedtime > $discrepancyrepairtime) {					// is the bounce time more recent then the repair time
																						$displaydatarow	= 1;
																					}
																					else {					// Boune time is not greater then the repair time
																						if ($discrepancybouncedtime == $discrepancyrepairtime) {		// are they equal times?
																								$displaydatarow	= 0;
																							}
																							else {			// repair time is more recent then the bounce time
																								$displaydatarow	= 0;
																							}
																					}
																			}
																	}
															}
													}
												if ($isduplicate==1) {
														if($tblduplicatesort==1) {
																$displaydatarow=1;
															}
															else {
																// Don't display datarow
																$displaydatarow=0;
															}
													}
													else {
														//echo "Not Duplicate, Maybe archived";
														if ($isarchived==1) {
																//echo "Is rchived, do I display it?".$tblarchivedsort;
																if ($tblarchivedsort=="1") {
																		//echo "Display Row";
																		$displaydatarow	= 1;
																	}
																	else {
																		// Don't display datarow
																		//echo "Dont Display row";
																		$displaydatarow=0;
																	}
															}
															else {
																// This record is not a duplicate, and not archived, so lets display it anyway
																//$displaydatarow=1;
															}
													}
												//echo $displaydatarow."<br>";
												
												if ($displaydatarow==1) {
										?>
							<tr>
								<td height="32" align="center" class="formresults">
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<form style="margin-bottom:0;" action="<?=$functionprinterpage;?>" method="POST" name="printform" id="printform" target="PrinterFriendlyReport" onsubmit="window.open('', 'PrinterFriendlyReport', 'width=717,height=500,status=no,resizable=no,scrollbars=yes')">
											<td height="32" align="center" class="formresults">
													<?=$objarray[$tblkeyfield];?>&nbsp;&nbsp;&nbsp;
												</td>
											</form>
											
											<form style="margin-bottom:0;" action="part139327_sub_d_r_entry.php" method="POST" name="printform" id="printform" target="PrinterFriendlyReport" onsubmit="window.open('', 'PrinterFriendlyReport', 'width=700,height=480,status=no,resizable=no,scrollbars=yes')">
											<td height="32" align="center" class="formresults">
												<input type="hidden" name="did" 		value="<?=$objarray[$tblkeyfield];?>">
												<input type="submit" name="b1" 			value="R"			class="formsubmit" alt="Repair Discrepancy">
												</td>
											</form>
											</tr>
										</table>								
									</td>
								<? 
								for ($i=0; $i<count($aheadername); $i=$i+1) {
										?>
								<td align="center" valign="middle" class="formresults">
										<? 
										switch ($adatafieldid[$i]) {
												case "notjoined":
														switch ($adataspecial[$i]) {
																case 2:
																		?>
									@ <?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
																case 4:
																		?>
									$ <?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
																case 5:
																		?>
									<?=$objarray[$adatafield[$i]];?> %
																		<? 
																		break;
																default:
																		?>
									<?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
															}
														break;
												case "notjoined":
														switch ($ainputtype[$i]) {
																case "CHECKBOX":
																		if ($objarray[$adatafield[$i]]==0) {
																				$tmpcbfield = "No";
																			}
																			else {
																				$tmpcbfield = "Yes";
																			}
																			?>
									<a href="javascript:updatewhereform('<?=$adatafieldtable[$i];?>.<?=$adatafield[$i];?>=<?=$objarray[$adatafield[$i]];?>');">
										<font color="#000000">
											<?=$tmpcbfield;?>
											</font>
										</a>				
																			<?
																		break;
																default:
																				?>
									<a href="javascript:updatewhereform('<?=$adatafieldtable[$i];?>.<?=$adatafield[$i];?>=<?=$objarray[$adatafield[$i]];?>');">
										<font color="#000000">
											<?=$objarray[$adatafield[$i]];?>
											</font>
										</a>
														<?
																		break;
																}
														break;
												default:											
														$tmpsqlwhereaddon=$objarray[$adatafieldid[$i]];
														?>
									<a href="#">
										<font color="#000000">
														<?
											$adataselect[$i]($objarray[$adatafieldid[$i]], "all", $adatafield[$i], "hide", "");
														?>
											</font>
										</a>
														<? 
														break;
												}
											//} 
											?>
									</td>
											<?
									}
								?>
								<td align="right" valign="middle" class="formresults">
									<table>
										<tr>
								<?
									//test 1). Is this discrepancy a duplicate? to determine this we need to do a search in the duplcate table for this id and see if we get a record. 
									//Anything over 0 records means it is a duplicate and we need to display the duplicate summary report, making a little 'D' box
								
									$sql2 = "SELECT * FROM tbl_139_327_sub_d_d WHERE discrepancy_duplicate_inspection_id = '".$objarray[$tblkeyfield]."' ";
									//make connection to database
												
									$objconn2 = mysqli_connect("localhost", "webuser", "limitaces", "openairport");
				
									if (mysqli_connect_errno()) {
											// there was an error trying to connect to the mysql database
											printf("connect failed: %s\n", mysqli_connect_error());
											exit();
										}
										else {
											$objrs2 = mysqli_query($objconn2, $sql2);
						
											if ($objrs2) {
													$number_of_rows = mysqli_num_rows($objrs2);
													//echo $number_of_rows;
													while ($objarray2 = mysqli_fetch_array($objrs2, MYSQLI_ASSOC)) {
														$tmpid = $objarray2['discrepancy_duplicate_id'];
														?>
											<form style="margin-bottom:0;" action="part139327_sub_d_d_report.php" method="POST" name="reportform" id="reportform" target="PrinterFriendlyWindow" onsubmit="window.open('', 'PrinterFriendlyWindow', 'width=750,height=550,status=no,resizable=no,scrollbars=yes')">
											<td style="font-family: arial narrow; font-size: 10px; color: #ffffff; border: 1px solid #d8dfea; cursor: hand;" bgcolor="#6d84b4" align="center">
												<input type="hidden" name="recordid" 			value="<?=$tmpid;?>">
												<input type="hidden" name="discrepancyid" 		value="<?=$objarray[$tblkeyfield];?>">
												<input type="submit" value="D" name="b1" class="formsubmit" alt="Discrepancy is a Duplicate" onMouseover="ddrivetip('Discrepancy is a Duplicate')"; onMouseout="hideddrivetip()">
												</td>
											</form>
														<?	
														}
												}
										}
									//test 2). Is this discrepancy archived? to determine this we need to do a search in the duplcate table for this id and see if we get a record. 
									//Anything over 0 records means it is archived and we need to display the archived summary report, making a little 'A' box
								
									$sql2 = "SELECT * FROM tbl_139_327_sub_d_a WHERE discrepancy_archeived_inspection_id = '".$objarray[$tblkeyfield]."' ";
									//make connection to database
												
									$objconn2 = mysqli_connect("localhost", "webuser", "limitaces", "openairport");
				
									if (mysqli_connect_errno()) {
											// there was an error trying to connect to the mysql database
											printf("connect failed: %s\n", mysqli_connect_error());
											exit();
										}
										else {
											$objrs2 = mysqli_query($objconn2, $sql2);
						
											if ($objrs2) {
													$number_of_rows = mysqli_num_rows($objrs2);
													//echo $number_of_rows;
													while ($objarray2 = mysqli_fetch_array($objrs2, MYSQLI_ASSOC)) {
														$tmpid = $objarray2['discrepancy_archeived_id'];
														?>
											<form style="margin-bottom:0;" action="part139327_sub_d_a_report.php" method="POST" name="reportform" id="reportform" target="PrinterFriendlyWindow" onsubmit="window.open('', 'PrinterFriendlyWindow', 'width=750,height=550,status=no,resizable=no,scrollbars=yes')">
											<td style="font-family: arial narrow; font-size: 10px; color: #ffffff; border: 1px solid #d8dfea; cursor: hand;" bgcolor="#6d84b4" align="center">
												<input type="hidden" name="recordid" 			value="<?=$tmpid;?>">
												<input type="hidden" name="discrepancyid" 		value="<?=$objarray[$tblkeyfield];?>">
												<input type="submit" value="A" name="b1" class="formsubmit" alt="Discrepancy is archived" onMouseover="ddrivetip('Discrepancy is Archived')"; onMouseout="hideddrivetip()">
												</td>
											</form>
														<?	
														}
												}
										}
									//test 3). Determine if the Discrepancy is currently outstanding or has been fixed. This involves checking both the repaired and bounced tables for information about the
									//current discrepancy ID. This will be done in three phases. 
									//Phase 1 will be to check the bounced table to see if there is any records about this discrepancy ID there. if so get the date of the latest record and put the ID of the record in a variable
									//phase two will be to check the repaired table and see if there is any information about this discrepancy there. if so get the date of the latest record and put the ID of the record in a variable
									//phase three will be to compare the two dates provided and see which event is most recent.
								
									$sql2 = "SELECT * FROM tbl_139_327_sub_d_b WHERE discrepancy_bounced_inspection_id = '".$objarray[$tblkeyfield]."' ORDER BY discrepancy_bounced_date, discrepancy_bounced_time";
									//echo $sql2;		
									//make connection to database
												
									$objconn2 = mysqli_connect("localhost", "webuser", "limitaces", "openairport");
				
									if (mysqli_connect_errno()) {
											// there was an error trying to connect to the mysql database
											printf("connect failed: %s\n", mysqli_connect_error());
											exit();
										}
										else {
											$objrs2 = mysqli_query($objconn2, $sql2);
						
											if ($objrs2) {
													$number_of_rows = mysqli_num_rows($objrs2);
													//echo "Bouced Rows ".$number_of_rows;
													while ($objarray2 = mysqli_fetch_array($objrs2, MYSQLI_ASSOC)) {
														$discrepancybouncedid 	= $objarray2['discrepancy_bounced_id'];
														$discrepancybounceddate = $objarray2['discrepancy_bounced_date'];
														$discrepancybouncedtime = $objarray2['discrepancy_bounced_time'];
														//echo $discrepancybouncedtime;
														?>
														<?	
														}
														mysqli_free_result($objrs2);
														mysqli_close($objconn2);
												}
										}
									$sql2 = "SELECT * FROM tbl_139_327_sub_d_r WHERE discrepancy_repaired_inspection_id = '".$objarray[$tblkeyfield]."' ORDER BY discrepancy_repaired_date, discrepancy_repaired_time";
									//make connection to database
												
									$objconn2 = mysqli_connect("localhost", "webuser", "limitaces", "openairport");
				
									if (mysqli_connect_errno()) {
											// there was an error trying to connect to the mysql database
											printf("connect failed: %s\n", mysqli_connect_error());
											exit();
										}
										else {
											$objrs2 = mysqli_query($objconn2, $sql2);
						
											if ($objrs2) {
													$number_of_rows = mysqli_num_rows($objrs2);
													//echo $number_of_rows;
													while ($objarray2 = mysqli_fetch_array($objrs2, MYSQLI_ASSOC)) {
														$discrepancyrepairid = $objarray2['discrepancy_repaired_id'];
														$discrepancyrepairdate = $objarray2['discrepancy_repaired_date'];
														$discrepancyrepairtime = $objarray2['discrepancy_repaired_time'];
														//echo $discrepancyrepairtime;
														?>
														<?	
														}
														mysqli_free_result($objrs2);
														mysqli_close($objconn2);
												}
										}
									
									if ($discrepancyrepairid == "") {							// There is no repair history. Without being repaired you by definition cant bounce so we give the user the workorder button
											//echo "WorkOrder";
											?>
											<form style="margin-bottom:0;" action="part139327_sub_d_workorder.php" method="POST" name="reportform" id="reportform" target="PrinterFriendlyWindow" onsubmit="window.open('', 'PrinterFriendlyWindow', 'width=750,height=550,status=no,resizable=no,scrollbars=yes')">
											<td style="font-family: arial narrow; font-size: 10px; color: #ffffff; border: 1px solid #d8dfea; cursor: hand;" bgcolor="#6d84b4" align="center">
												<input type="hidden" name="recordid" 			value="<?=$objarray[$tblkeyfield];?>">
												<input type="submit" value="Work Order" name="b1" class="formsubmit">
												</td>
											</form>
											<?
										}
										else {													// There is a number in the repair ID
											if ($discrepancybouncedid == "") {					// There is not a number in the bounceid, do display the repaired icon
													//echo "There is no value in the bouncedID variable";
													?>
											<form style="margin-bottom:0;" action="part139327_sub_d_r_report.php" method="POST" name="reportform" id="reportform" target="PrinterFriendlyWindow" onsubmit="window.open('', 'PrinterFriendlyWindow', 'width=750,height=550,status=no,resizable=no,scrollbars=yes')">
											<td style="font-family: arial narrow; font-size: 10px; color: #ffffff; border: 1px solid #d8dfea; cursor: hand;" bgcolor="#6d84b4" align="center">
												<input type="hidden" name="recordid" 			value="<?=$discrepancyrepairid;?>">
												<input type="submit" value="R" name="b1" class="formsubmit" alt="Discrepancy is Repaired">
												</td>
											</form>
													<?
												}
												else {											// There is a number in the bounced field
																								// Now we need to compare the date and time of the each record and get the most recent event
													if ($discrepancybounceddate > $discrepancyrepairdate) {								//Bounce is more recent then repair regardless of time, so display bounce icon
															//echo "Bounce Date is greater than Repair Date<br>";
															?>
											<form style="margin-bottom:0;" action="part139327_sub_d_b_report.php" method="POST" name="reportform" id="reportform" target="PrinterFriendlyWindow" onsubmit="window.open('', 'PrinterFriendlyWindow', 'width=750,height=550,status=no,resizable=no,scrollbars=yes')">
											<td style="font-family: arial narrow; font-size: 10px; color: #ffffff; border: 1px solid #d8dfea; cursor: hand;" bgcolor="#6d84b4" align="center">
												<input type="hidden" name="recordid" 			value="<?=$discrepancybouncedid;?>">
												<input type="submit" value="B" name="b1" class="formsubmit" alt="Discrepancy is Bounced">
												</td>
											</form>
											<form style="margin-bottom:0;" action="part139327_sub_d_workorder.php" method="POST" name="reportform" id="reportform" target="PrinterFriendlyWindow" onsubmit="window.open('', 'PrinterFriendlyWindow', 'width=750,height=550,status=no,resizable=no,scrollbars=yes')">
											<td style="font-family: arial narrow; font-size: 10px; color: #ffffff; border: 1px solid #d8dfea; cursor: hand;" bgcolor="#6d84b4" align="center">
												<input type="hidden" name="recordid" 			value="<?=$objarray[$tblkeyfield];?>">
												<input type="submit" value="Work Order" name="b1" class="formsubmit" alt="Discrepancy needs to be repaired click this button to generate a printer friendly workorder for distribution to maintenance crew" >
												</td>
											</form>
															<?
														}
														else {									// Bounce date is not greater then repaire date
															if ($discrepancybounceddate == $discrepancyrepairdate) {						// Is the bounce date the same as the repair date?
																	//echo "bounce date is equal to repair date<br>";			// next we need to see if bounce is more recent timewise then the repair time
																	//echo $discrepancybouncedtime." vs ".$discrepancyrepairtime."<br>";
																	if ($discrepancybouncedtime > $discrepancyrepairtime) {					// is the bounce time more recent then the repair time
																			//echo "Bounce time greater than repair time";			// if so, display bounce icon
																			?>
											<form style="margin-bottom:0;" action="part139327_sub_d_b_report.php" method="POST" name="reportform" id="reportform" target="PrinterFriendlyWindow" onsubmit="window.open('', 'PrinterFriendlyWindow', 'width=750,height=550,status=no,resizable=no,scrollbars=yes')">
											<td style="font-family: arial narrow; font-size: 10px; color: #ffffff; border: 1px solid #d8dfea; cursor: hand;" bgcolor="#6d84b4" align="center">
												<input type="hidden" name="recordid" 			value="<?=$discrepancybouncedid;?>">
												<input type="submit" value="B" name="b1" class="formsubmit" alt="Discrepancy is Bounced">
												</td>
											</form>
											<form style="margin-bottom:0;" action="part139327_sub_d_workorder.php" method="POST" name="reportform" id="reportform" target="PrinterFriendlyWindow" onsubmit="window.open('', 'PrinterFriendlyWindow', 'width=750,height=550,status=no,resizable=no,scrollbars=yes')">
											<td style="font-family: arial narrow; font-size: 10px; color: #ffffff; border: 1px solid #d8dfea; cursor: hand;" bgcolor="#6d84b4" align="center">
												<input type="hidden" name="recordid" 			value="<?=$objarray[$tblkeyfield];?>">
												<input type="submit" value="Work Order" name="b1" class="formsubmit" alt="Discrepancy needs to be repaired click this button to generate a printer friendly workorder for distribution to maintenance crew">
												</td>
											</form>
																			<?
																		}
																		else {					// Boune time is not greater then the repair time
																			if ($discrepancybouncedtime == $discrepancyrepairtime) {		// are they equal times?
																					//echo "How the heck did that happen";
																				}
																				else {			// repair time is more recent then the bounce time
																					//echo "Repair Icon";
																					?>
											<form style="margin-bottom:0;" action="part139327_sub_d_r_report.php" method="POST" name="reportform" id="reportform" target="PrinterFriendlyWindow" onsubmit="window.open('', 'PrinterFriendlyWindow', 'width=750,height=550,status=no,resizable=no,scrollbars=yes')">
											<td style="font-family: arial narrow; font-size: 10px; color: #ffffff; border: 1px solid #d8dfea; cursor: hand;" bgcolor="#6d84b4" align="center">
												<input type="hidden" name="recordid" 			value="<?=$discrepancyrepairid;?>">
												<input type="submit" value="R" name="b1" class="formsubmit" alt="Discrepancy is Repaired">
												</td>
											</form>
																					<?
																				}
																		}
																}
														}
												}
										}
										?>
											</tr>
										</table>
									</td>
								</tr>
											<?
		$discrepancybouncedid 	= "";
		$discrepancybounceddate 	= "";
		$discrepancybouncedtime 	= "";
		$discrepancyrepairid 	= "";
		$discrepancyrepairdate 	= "";
		$discrepancyrepairtime 	= "";
		$isduplicate			= "";
		$isarchived			= "";
		$displaydatarow			= "";
		$tblduplicatesort		= 0;															// Show discrepancies which are duplicates
		$tblarchivedsort		= 0;															// Show discrepancies which are archived
		$tblworkorderssort		= 0;
		$tblrepairedsort		= 0;
												}	// end of displaydatarow test
		$isduplicate			= "";
		$isarchived			= "";
		$displaydatarow			= "";
		$discrepancybouncedid 	= "";
		$discrepancybounceddate 	= "";
		$discrepancybouncedtime 	= "";
		$discrepancyrepairid 	= "";
		$discrepancyrepairdate 	= "";
		$discrepancyrepairtime 	= "";
		$tblduplicatesort		= 0;															// Show discrepancies which are duplicates
		$tblarchivedsort		= 0;															// Show discrepancies which are archived
		$tblworkorderssort		= 0;
		$tblrepairedsort		= 0;
											}	// end of looped data
											mysqli_free_result($objrs);
											mysqli_close($objconn);
									?>
							</table>
									<?
									}	// end of records found statement
								}	// end of sucessfull conection and execution of sql statement
							}	// end of connection established
							?>
						</td>
					</tr>					
				</table>	<!-- end of ajax load table-->
			</td>
		</tr>
	</table>
	<?
}

function quickinfopart139339ficons($tmp) {
	// Display FiCONs on todays date
	
	// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	// will the form use any of the following toggle switches	
		// should the form have the ability to sort the data by date ?
		// 1 : yes ; 0 : no;
		$tbldatesort 			= 1;															// Display Date Sorting Options Toggle Switch
		$tbltextsort 			= 0;															// Display Text Sorting Options Toggle Switch
		$tblheadersort			= 0;															// Display Heading Sort Options Toggle Switch
		
		// Temporaryly set here to inital a value.  The user will be able to define these using the interface as well.
		$tblduplicatesort		= 0;																// Show discrepancies which are duplicates
		$tblarchivedsort			= 0;	

		$displayrow			= 1;
		// Show discrepancies which are archived
		
	// this information is needed to program the datafields and sql statements on the fly without any user interuption
		// what is the primary key field (id field) of the table
		$tblkeyfield			= "139339_main_id";													// What is the Auto Increment Field for this table ?
		$tbldatesortfield		= "139339_date";														// What is the name of field use in date sorting ?
		$tbldatesorttable		= "tbl_139_339_main";													// What table  is that field part of ?
		$tbltextsortfield		= "";																// What is the name of the field used in text sorting ?
		$tbltextsorttable		= "tbl_139_339_main";													// What is the name of the table used for text sorting ?
		$tblarchivedfield		= "";																// What is the name of the field used to mark the record archived ?
		$tblname				= "Part 139.339 Field Condition Report";									// What is the name of the table ? (used on edit/summary/printer report pages)
		$tblsubname			= "here is the information you selected";									// What is the subname of the table ? (used on edit/summary/printer report pages)
	
	// What php pages are used to control the summary, printer reports, and edit functions?
		// by default these pages should be the following
		// $functioneditpage 			= "edit_record_general.php";
		// $functionsummarypage 		= "summary_report_general.php";
		// $functionprinterpage 		= "printer_report_general.php";
		$functioneditpage 		= "part139339_main_edit.php";											// Name of page used to edit the record
		$functionsummarypage 		= "summary_report_general.php";											// Name of page used to display a summary of the record
		$functionprinterpage 		= "part139339_main_report.php";											// Name of page used to display a printer friendly report

	// what columns should the datagrid display?
		// this is an array of information, from [0] to [...], you may have an unlimited number of columns
		$adatafield[0]			= "139339_date";
		$adatafield[1]			= "139339_time";
		$adatafield[2]			= "139339_type_cb_int";
		$adatafield[3]			= "139339_by_cb_int";
	// in each array specified above, what table does that field come from?
		$adatafieldtable[0]		= "tbl_139_339_main";
		$adatafieldtable[1]		= "tbl_139_339_main";
		$adatafieldtable[2]		= "tbl_139_339_main";
		$adatafieldtable[3]		= "tbl_139_339_main";		
	// do you want the user ot be able to click on the information and have something happen?
		// "notjoined" will cause nothing to happen, only the data will be displayed
		// the name of any field in any of the selected tables will cause only records that have that information to be displayed.
		$adatafieldid[0]		= "notjoined";
		$adatafieldid[1]		= "notjoined";
		$adatafieldid[2]		= "139339_type_cb_int";
		$adatafieldid[3]		= "139339_by_cb_int";
	// in some cases you may want the information displayed to be prefecned with a $ sign or followed by a % sign. here you can tell the program what to display if anything
		// 0 : nothing ; 2 : @ ; 4 : $ ; 5 : %
		$adataspecial[0]		= 0;
		$adataspecial[1]		= 0;
		$adataspecial[2]		= 0;
		$adataspecial[3]		= 0;	
	// what do you want to name the columns
		$aheadername[0]			= "Date";
		$aheadername[1]			= "Time";
		$aheadername[2]			= "Type";
		$aheadername[3]			= "Inspector";
	// Any special comments to make after the input box?
		$ainputcomment[0]		= "( mm/dd/yyyy )";
		$ainputcomment[1]		= "( 24 hour )";
		$ainputcomment[2]		= "(select from the list)";
		$ainputcomment[3]		= "(select from the list)";
	// what type of input is this field?
		// this can be any type of input
		// text, textarea, select, etc.
		$ainputtype[0]			= "TEXT";
		$ainputtype[1]			= "TEXT";
		$ainputtype[2]			= "SELECT";
		$ainputtype[3]			= "SELECT";
	// if the input type is a SELECT type you should define the name of the function you want to call to load into that select statement
		$adataselect[0]			= "";
		$adataselect[1]			= "";
		$adataselect[2]			= "part139339typescombobox";
		$adataselect[3]			= "systemusercombobox";
	// in the event of an error when a user enteres a wrong date, what should the datagrid say to the user?
		$tmpDateEventErrorMessage	= "Error, reset to default";
		
	// Build SQL Statement
	// Since we have all of the information from the arrays and strings we can assemble the nessary SQL Statement without actually having to supply it verbatum.
	// The Limiting clauses will be left off, all we want to create at this point is the basic SELECT *,*,* FROM syntax
	
		$sql = "SELECT ".$tblkeyfield.",";														// Start SQL adding on the id field first						
		
		for ($i=0; $i<count($adatafield); $i=$i+1) {											// Loop through any of the arrays 0 to array length - 1
				$nsql = " ".$adatafield[$i]."";													// add each new value in the array to a temporary sql string
				$sql = $sql.$nsql;																// add the temporary sql string to the main sql string.
				if ($i == count($adatafield)-1) {												// test to see where we are in the string, in this case are we at the end or not?
						$nsql = "";																// at the end of the arrat dont add a , to the end of the value
						$sql = $sql.$nsql;														// add 'nothing' to the sql string
					}
					else {																		// not at the end of the array so do soemthing else
						$nsql = ", ";															// for each value in the array that is not at the end, add a , after the value
						$sql = $sql.$nsql;														// add the temporary sql string to the main sql string
					}			
		}
		$sql = $sql.$nsql;																		// field index values have all been added to the sql string (this line is reduntent, but there for space)
		$nsql = " FROM ".$tbldatesorttable." ";													// with all field index values added, add the FROM syntax and the applicable table in the DB
		$sql = $sql.$nsql;																		// make it all one nice sql string for use

	// For debugging purposes print out the SQL Statement
		//echo $sql;																				// When dedugging you can uncomment this echo and see the sql statement

	// Start the Real Fun	

		$i 							= 0;	
		$tblheadersortfirstselected="yes";
		$togfrmjoined = "";																		//just in case we dont define it latter, set a default here.
		$isarchived				= "";
		$displaydatarow			= "";


//-------------------- [ there should be no need to change any of the code below this line ] ----------------------------------------------------------------------------------------------------------------------

		
	// store this array into a serialized array
		$stradatafield 			= urlencode(serialize($adatafield));			// dont touch
		$stradatafieldtable 	= urlencode(serialize($adatafieldtable));		// dont touch
		$stradatafieldid 		= urlencode(serialize($adatafieldid));			// dont touch	
		$stradataspecial		= urlencode(serialize($adataspecial));			// dont touch
		$straheadername			= urlencode(serialize($aheadername));			// dont touch
		$strainputtype			= urlencode(serialize($ainputtype));			// dont touch
		$stradataselect			= urlencode(serialize($adataselect));			// dont touch
		$strainputcomment		= urlencode(serialize($ainputcomment));			// dont touch
		
	// store this array into a serialized array
		$sadatafield 			= (serialize($adatafield));						// dont touch
		$sadatafieldtable 		= (serialize($adatafieldtable));				// dont touch
		$sadatafieldid 			= (serialize($adatafieldid));					// dont touch	
		$sadataspecial			= (serialize($adataspecial));					// dont touch
		$saheadername			= (serialize($aheadername));					// dont touch
		$sainputtype			= (serialize($ainputtype));						// dont touch
		$sadataselect			= (serialize($adataselect));					// dont touch
		$sainputcomment			= (serialize($ainputcomment));					// dont touch
		
		$sadatafield  			= str_replace("\"","|",$sadatafield);
		$sadatafieldtable 		= str_replace("\"","|",$sadatafieldtable);
		$sadatafieldid 			= str_replace("\"","|",$sadatafieldid);
		$sadataspecial 			= str_replace("\"","|",$sadataspecial);
		$saheadername 			= str_replace("\"","|",$saheadername);
		$sainputtype 			= str_replace("\"","|",$sainputtype);
		$sadataselect 			= str_replace("\"","|",$sadataselect);
		$sainputcomment			= str_replace("\"","|",$sainputcomment);

$tmpfrmstartdateerror = "";
$tmpfrmenddateerror = "";

if (!isset($_POST["frmstartdate"])) {
		//$tbldatesortstart=($current_date-30);		
		// setup startdate for query
		$uifrmstartdate 		= date('m/d/Y');
		$sqlfrmstartdate 		= amerdate2sqldatetime($uifrmstartdate);
	}
	else {
		$uifrmstartdate 		= $frmstartdate;
		$sqlfrmstartdate 		= amerdate2sqldatetime($frmstartdate);
		}

if (!isset($_POST["frmenddate"])) {
		//$tbldatesortstart=($current_date-30);		
		// setup startdate for query
		$uifrmenddate 			= date('m/d/Y');
		$sqlfrmenddate 			= amerdate2sqldatetime($uifrmenddate);
	}
	else {
		$uifrmenddate 			= $frmenddate;
		$sqlfrmenddate 			= amerdate2sqldatetime($frmenddate);
		}

if (!isset($_POST["frmjoined"])) {
		//there is no information in this field, set default values
		
		// check to see if the intfrmjoined field has any value
		$intfrmjoined	= 0;
		$intsqlwhereaddon = 0;
		$strsqlwhereaddon = "none";
		
		$togfrmjoined = 1;
		
		if (!isset($_POST["intfrmjoined"])) {
				//echo "there is no value in intfrmjoined";
				$frmjoined 				= 0; //set value to zero (this causes the checkbox to not be checked
				$intfrmjoined 			= 0; //set value to zero (this causes the checkbox to not be checked
				}
			else {
				//echo "value is ".$intfrmjoined." ";
				if ($intfrmjoined==0) {
						// the value of the box has 'no' value
						$frmjoined				= "0";
						$intfrmjoined			= 0;
					}
					else {
						// the value of the box isn't one so we should do what it says
						$frmjoined				= 1;
						$intfrmjoined			= 1;
					}				
			}
		}
	else {
		// the field does exist, what is its current value
		$frmjoined				= $_POST["frmjoined"];
		$intfrmjoined			= 1;
		}	

if (!isset($_POST["strsqlwhereaddon"])) {
		$strsqlwhereaddon="none";
		$intsqlwhereaddon = 0;
	}
	else {
		if ($togfrmjoined==1) {
				// checkbox is not active, clean out sql statement
				$strsqlwhereaddon		= "";
				$intsqlwhereaddon 		= 1;
			}
			else {
	
		$strsqlwhereaddon		= $_POST["strsqlwhereaddon"];
		$strsqlwhereaddon 		= str_replace("%3d","=",$strsqlwhereaddon );
		//$tblsqlwhereaddon 		= 1;
		}
	} 

if (!isset($_POST["intsqlwhereaddon"])) {
		if ($tbldatesort==1) {
				$intsqlwhereaddon = 1;
			}
		//tblsqlwhereaddon = 1
	}
	else {
		$intsqlwhereaddon=$_POST["intsqlwhereaddon"];
		//tblsqlwhereaddon = 1
	}
	
for ($i=0; $i<count($aheadername); $i++) {
	if (!isset($_POST[$adatafield[$i]])) {
			$aheadersort[$i]="NotSorted";
		}
		else {
			$aheadersort[$i]=$_POST[$adatafield[$i]];
		} 
	}


// add where statement to sql statement
if ($tbldatesort==1) {
		$nsql = " where ".$tbldatesorttable.".".$tbldatesortfield." >= '".$sqlfrmstartdate."' and ".$tbldatesorttable.".".$tbldatesortfield." <= '".$sqlfrmenddate."'";
		$sql = $sql.$nsql;
		?>
		<script>	</script>
		<?
	}
if ($tbltextsort==1) {
		if ($tbldatesort==1) {
				$nsql = " and ".$tbltextsorttable.".".$tbltextsortfield." like '%".$frmtextlike."%' ";
				$sql = $sql.$nsql;
				}
			else {
				$nsql = " where ".$tbltextsorttable.".".$tbltextsortfield." like '%".$frmtextlike."%' ";
				$sql = $sql.$nsql;	
				}
	}
if ($strsqlwhereaddon=="none") {
		//do not add any additional sql to the sql statement as they have not been provided
		}
	else {
		if ($intfrmjoined==0) {
				// user has choosen not to enable column joining, so dont do it
				}
			else {
				// user has enabled column joining, so do it
				$sql = $sql.$strsqlwhereaddon;
			}
	}

//order by sql statement
if ($tblheadersort==1) {
	for ($i=0; $i<count($aheadersort); $i=$i+1) {
			if ($aheadersort[$i]=="NotSorted") {
					//do not add sorting column to sql string
				} 
			if ($aheadersort[$i]=="Assending") {
					if ($tblheadersortfirstselected=="yes") {
							//this is the first time a header has been selected
							$tblheadersortfirstselected="no"; //set selected to no
							$nsql=" order by ".$adatafieldtable[$i].".".$adatafield[$i]." ";
							$sql = $sql.$nsql;
						}
						else {
							$sql = $sql.", ".$adatafieldtable[$i].".".$adatafield[$i]." ";
						} 
				} 
			if ($aheadersort[$i]=="Decending") {
					if ($tblheadersortfirstselected=="yes") {
							//this is the first time a header has been selected
							$tblheadersortfirstselected="no"; //set selected to no
							$nsql=" order by ".$adatafieldtable[$i].".".$adatafield[$i]." desc ";
							$sql = $sql.$nsql;
						}
						else {
							$sql = $sql.", ".$adatafieldtable[$i].".".$adatafield[$i]." desc ";
						} 
				} 
		}
	}
	$sql 		= str_replace("%3D","=",$sql);
	//echo $sql;
	
if (!isset($_POST["frmarchives"])) {
		$tblarchivedsort	= "";
	}
	else {
		// the field does exist, what is its current value
		$tblarchivedsort				= $_POST["frmarchives"];
	}
?>


<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" name="sorttable" id="sorttable">
<input type="hidden" name="menuitemid" value="<?=$_POST['menuitemid'];?>">
<table border="0" width="100%" id="tblbrowseformtable" cellspacing="0" cellpadding="0">
	<tr>
		<td class="tableheadercenter">
			<b><?=$tmp;?></b>
			</td>
		</tr>
	<tr>
		<td colspan="3" class="tablesubcontent">
			<table border="0" width="100%" cellspacing="4">
				<?
				//make connection to database
				$objconn = mysqli_connect("localhost", "webuser", "limitaces", "openairport");
		
				if (mysqli_connect_errno()) {
						// there was an error trying to connect to the mysql database
						printf("connect failed: %s\n", mysqli_connect_error());
						exit();
					}
					else {
						$objrs = mysqli_query($objconn, $sql);
				
						if ($objrs) {
								$number_of_rows = mysqli_num_rows($objrs);								
								if ($number_of_rows==0) {
										?>
				<tr>
					<td class="formresultscount">
							No Records Founds for today
						</td>
					</tr>
										<?
									}
									else {
										?>
				<tr>
					<td class="formresultscount">
							<?=$number_of_rows;?> records have been found today, may include archived ficons.
						</td>
					</tr>
				<tr>
					<td class="tabledatarow">
						<table border="0" width="100%" id="table1" cellpadding="0" cellspacing="1"  style="border-collapse: collapse">
							<tr>
								<td class="formheaders">
									ID
									</td>
								<? 
								for ($i=0; $i<count($aheadername); $i=$i+1) {
										?>
								<td class="formheaders">
										<? 
										if ($tblheadersort==1) {
												?>
									<a href="#" onfocus="javascript:getvaluesortform('<?=$adatafield[$i];?>');" onclick="javascript:updatesortform('<?=$adatafield[$i];?>');"><font color="#ffffff"><?=$aheadername[$i];?></font></a>
									<br>(<input class="inlinehiddenbox" type="text" size="8" id="<?=$adatafield[$i];?>" name="<?=$adatafield[$i];?>" value="<?=$aheadersort[$i];?>">)
												<? 
											}
											else {
												echo $aheadername[$i];
											}	
										?>
									</td>
										<?
									}
								?>
							</form>
								</tr>
										<?
										while ($objarray = mysqli_fetch_array($objrs, MYSQLI_ASSOC)) {
												//Check to see if this ficon has been archived, and if so, display it according to the values specified in the display archived toggle.
												
												$sql2 = "SELECT * FROM tbl_139_339_sub_a WHERE 139339_a_inspection_id = ".$objarray['139339_main_id'];
												
												$objconn2 = mysqli_connect("localhost", "webuser", "limitaces", "openairport");						
												if (mysqli_connect_errno()) {
														// there was an error trying to connect to the mysql database
														printf("connect failed: %s\n", mysqli_connect_error());
														exit();
													}
													else {
														$objrs2 = mysqli_query($objconn2, $sql2);
												
														if ($objrs2) {
																$number_of_rows2 = mysqli_num_rows($objrs2);
															}
													}
												if ($number_of_rows2>0) {
														// This FiCON has been archived, should we display it?
														if ($tblarchivedsort=="1") {
																// Display Field condition report even though it is archived
																$displayrow	= 1;
															}
															else {
																$displayrow	= 0;
															}
													}
												if ($displayrow==1) {															
														?>
							<tr>
								<form style="margin-bottom:0;" action="<?=$functionprinterpage;?>" method="POST" name="printform" id="printform" target="PrinterFriendlyReport" onsubmit="window.open('', 'PrinterFriendlyReport', 'width=750,height=962,status=no,resizable=no,scrollbars=yes')">
									<td height="32" align="center" class="formresults">
										<input type="hidden" name="recordid" 			value="<?=$objarray[$tblkeyfield];?>">
										<input type="submit" name="b1" 					value="<?=$objarray[$tblkeyfield];?>"			class="formsubmit">
										</td>
									</form>
								<? 
								for ($i=0; $i<count($aheadername); $i=$i+1) {
										?>
								<td align="center" valign="middle" class="formresults">
										<? 
										switch ($adatafieldid[$i]) {
												case "notjoined":
														switch ($adataspecial[$i]) {
																case 2:
																		?>
									@ <?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
																case 4:
																		?>
									$ <?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
																case 5:
																		?>
									<?=$objarray[$adatafield[$i]];?> %
																		<? 
																		break;
																default:
																		?>
									<?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
															}
														break;
												case "notjoined":
														switch ($ainputtype[$i]) {
																case "CHECKBOX":
																		if ($objarray[$adatafield[$i]]==0) {
																				$tmpcbfield = "No";
																			}
																			else {
																				$tmpcbfield = "Yes";
																			}
																			?>
									<a href="javascript:updatewhereform('<?=$adatafieldtable[$i];?>.<?=$adatafield[$i];?>=<?=$objarray[$adatafield[$i]];?>');">
										<font color="#000000">
											<?=$tmpcbfield;?>
											</font>
										</a>				
																			<?
																		break;
																default:
																				?>
									<a href="javascript:updatewhereform('<?=$adatafieldtable[$i];?>.<?=$adatafield[$i];?>=<?=$objarray[$adatafield[$i]];?>');">
										<font color="#000000">
											<?=$objarray[$adatafield[$i]];?>
											</font>
										</a>
														<?
																		break;
																}
														break;
												default:											
														$tmpsqlwhereaddon=$objarray[$adatafieldid[$i]];
														?>
									<a href="javascript:updatewhereform('<?=$adatafieldtable[$i];?>.<?=$adatafieldid[$i];?>=<?=$tmpsqlwhereaddon;?>');">
										<font color="#000000">
														<?
											$adataselect[$i]($objarray[$adatafieldid[$i]], "all", $adatafield[$i], "hide", "");
														?>
											</font>
										</a>
														<? 
														break;
												}
											//} 
											?>
									</td>
											<? 
									}
								?>
								</tr>
											<?									
											}	// end of looped data
											$displayrow = 1;
											}
									?>
							</table>
									<?
									}	// end of records found statement
								}	// end of sucessfull conection and execution of sql statement
							}	// end of connection established
							?>
						</td>
					</tr>					
				</table>	<!-- end of ajax load table-->
			</td>
		</tr>
	</table>

<?
}

function quickinfocurrentnotams($tmp) {
		// Display Current NOTAMS
			// ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
	
	// will the form use any of the following toggle switches	
		// should the form have the ability to sort the data by date ?
		// 1 : yes ; 0 : no;
		$tbldatesort 			= 0;																// Display Date Sorting Options Toggle Switch
		$tbltextsort 			= 0;																// Display Text Sorting Options Toggle Switch
		$tblheadersort			= 0;																// Display Heading Sort Options Toggle Switch
		
		// Temporaryly set here to inital a value.  The user will be able to define these using the interface as well.
		$tblduplicatesort		= 0;																// Show discrepancies which are duplicates
		$tblarchivedsort		= 0;
		$tblclosedsort			= 0;
		$displayrow				= 1;
		
		$function_archivedsort	= 'preflight_tbl_139339_sub_n_a';
		$function_closedsort	= 'preflight_tbl_139339_sub_n_r';
		
	// this information is needed to program the datafields and sql statements on the fly without any user interuption
		// what is the primary key field (id field) of the table
		$tblkeyfield			= "139339_sub_n_id";													// What is the Auto Increment Field for this table ?
		$tbldatesortfield		= "139339_sub_n_date";													// What is the name of field use in date sorting ?
		$tbldatesorttable		= "tbl_139_339_sub_n";													// What table  is that field part of ?
		$tbltextsortfield		= "139339_sub_n_notes";												// What is the name of the field used in text sorting ?
		$tbltextsorttable		= "tbl_139_339_sub_n";													// What is the name of the table used for text sorting ?
		$tblarchivedfield		= "139339_sub_n_archived_yn";											// What is the name of the field used to mark the record archived ?
		$tblname				= "Part 139.339 NOTAM Report";											// What is the name of the table ? (used on edit/summary/printer report pages)
		$tblsubname				= "Here is the information you selected";									// What is the subname of the table ? (used on edit/summary/printer report pages)
	
	// What php pages are used to control the summary, printer reports, and edit functions?
		// by default these pages should be the following
		// $functioneditpage 			= "edit_record_general.php";
		// $functionsummarypage 		= "summary_report_general.php";
		// $functionprinterpage 		= "printer_report_general.php";
		$functioneditpage 		= "part139339_sub_n_main_edit.php";										// Name of page used to edit the record
		$functionsummarypage 		= "summary_report_general.php";											// Name of page used to display a summary of the record
		$functionprinterpage 		= "part139339_sub_n_main_report.php";									// Name of page used to display a printer friendly report
		$calendarpage			= "part139327_main_calendar.php";										// Name of page used to display a calendar printout of data
		$printerpage			= "browse_report_general.php";											// Name of page used to display a printer friendly page
		
	// what columns should the datagrid display?
		// this is an array of information, from [0] to [...], you may have an unlimited number of columns
		$adatafield[0]			= "139339_sub_n_date";
		$adatafield[1]			= "139339_sub_n_time";
		$adatafield[2]			= "139339_sub_n_notes";
		$adatafield[3]			= "139339_sub_n_by_cb_int";
	// in each array specified above, what table does that field come from?
		$adatafieldtable[0]		= "tbl_139_339_sub_n";
		$adatafieldtable[1]		= "tbl_139_339_sub_n";
		$adatafieldtable[2]		= "tbl_139_339_sub_n";
		$adatafieldtable[3]		= "tbl_139_339_sub_n";	
	// do you want the user ot be able to click on the information and have something happen?
		// "notjoined" will cause nothing to happen, only the data will be displayed
		// the name of any field in any of the selected tables will cause only records that have that information to be displayed.
		$adatafieldid[0]		= "notjoined";
		$adatafieldid[1]		= "notjoined";
		$adatafieldid[2]		= "notjoined";
		$adatafieldid[3]		= "139339_sub_n_by_cb_int";
	// in some cases you may want the information displayed to be prefecned with a $ sign or followed by a % sign. here you can tell the program what to display if anything
		// 0 : nothing ; 2 : @ ; 4 : $ ; 5 : %
		$adataspecial[0]		= 0;
		$adataspecial[1]		= 0;
		$adataspecial[2]		= 0;
		$adataspecial[3]		= 0;
	// what do you want to name the columns
		$aheadername[0]			= "Date";
		$aheadername[1]			= "Time";
		$aheadername[2]			= "Memo";
		$aheadername[3]			= "Inspector";
	// Any special comments to make after the input box?
		$ainputcomment[0]		= "( mm/dd/yyyy )";
		$ainputcomment[1]		= "( 24 hour )";
		$ainputcomment[2]		= "(select from the list)";
		$ainputcomment[3]		= "(select from the list)";
	// what type of input is this field?
		// this can be any type of input
		// text, textarea, select, etc.
		$ainputtype[0]			= "TEXT";
		$ainputtype[1]			= "TEXT";
		$ainputtype[2]			= "TEXT";
		$ainputtype[3]			= "SELECT";
	// if the input type is a SELECT type you should define the name of the function you want to call to load into that select statement
		$adataselect[0]			= "";
		$adataselect[1]			= "";
		$adataselect[2]			= "";
		$adataselect[3]			= "systemusercombobox";
	// in the event of an error when a user enteres a wrong date, what should the datagrid say to the user?
		$tmpDateEventErrorMessage	= "Error, reset to default";
		
	// Build SQL Statement
	// Since we have all of the information from the arrays and strings we can assemble the nessary SQL Statement without actually having to supply it verbatum.
	// The Limiting clauses will be left off, all we want to create at this point is the basic SELECT *,*,* FROM syntax
	
		$sql = "SELECT ".$tblkeyfield.",";														// Start SQL adding on the id field first						
		
		for ($i=0; $i<count($adatafield); $i=$i+1) {											// Loop through any of the arrays 0 to array length - 1
				$nsql = " ".$adatafield[$i]."";													// add each new value in the array to a temporary sql string
				$sql = $sql.$nsql;																// add the temporary sql string to the main sql string.
				if ($i == count($adatafield)-1) {												// test to see where we are in the string, in this case are we at the end or not?
						$nsql = "";																// at the end of the arrat dont add a , to the end of the value
						$sql = $sql.$nsql;														// add 'nothing' to the sql string
					}
					else {																		// not at the end of the array so do soemthing else
						$nsql = ", ";															// for each value in the array that is not at the end, add a , after the value
						$sql = $sql.$nsql;														// add the temporary sql string to the main sql string
					}			
		}
		$sql = $sql.$nsql;																		// field index values have all been added to the sql string (this line is reduntent, but there for space)
		$nsql = " FROM ".$tbldatesorttable." WHERE 139339_sub_n_closed_yn = 0";													// with all field index values added, add the FROM syntax and the applicable table in the DB
		$sql = $sql.$nsql;																		// make it all one nice sql string for use

	// For debugging purposes print out the SQL Statement
		//echo $sql;																				// When dedugging you can uncomment this echo and see the sql statement

	// Start the Real Fun	

		$i 							= 0;	
		$tblheadersortfirstselected="yes";
		$togfrmjoined = "";																		//just in case we dont define it latter, set a default here.
		$isarchived				= "";
		$displaydatarow			= "";


//-------------------- [ there should be no need to change any of the code below this line ] ----------------------------------------------------------------------------------------------------------------------

		
	// store this array into a serialized array
		$stradatafield 			= urlencode(serialize($adatafield));			// dont touch
		$stradatafieldtable 	= urlencode(serialize($adatafieldtable));		// dont touch
		$stradatafieldid 		= urlencode(serialize($adatafieldid));			// dont touch	
		$stradataspecial		= urlencode(serialize($adataspecial));			// dont touch
		$straheadername			= urlencode(serialize($aheadername));			// dont touch
		$strainputtype			= urlencode(serialize($ainputtype));			// dont touch
		$stradataselect			= urlencode(serialize($adataselect));			// dont touch
		$strainputcomment		= urlencode(serialize($ainputcomment));			// dont touch
		
	// store this array into a serialized array
		$sadatafield 			= (serialize($adatafield));						// dont touch
		$sadatafieldtable 		= (serialize($adatafieldtable));				// dont touch
		$sadatafieldid 			= (serialize($adatafieldid));					// dont touch	
		$sadataspecial			= (serialize($adataspecial));					// dont touch
		$saheadername			= (serialize($aheadername));					// dont touch
		$sainputtype			= (serialize($ainputtype));						// dont touch
		$sadataselect			= (serialize($adataselect));					// dont touch
		$sainputcomment			= (serialize($ainputcomment));					// dont touch
		
		$sadatafield  			= str_replace("\"","|",$sadatafield);
		$sadatafieldtable 		= str_replace("\"","|",$sadatafieldtable);
		$sadatafieldid 			= str_replace("\"","|",$sadatafieldid);
		$sadataspecial 			= str_replace("\"","|",$sadataspecial);
		$saheadername 			= str_replace("\"","|",$saheadername);
		$sainputtype 			= str_replace("\"","|",$sainputtype);
		$sadataselect 			= str_replace("\"","|",$sadataselect);
		$sainputcomment			= str_replace("\"","|",$sainputcomment);

$tmpfrmstartdateerror = "";
$tmpfrmenddateerror = "";

if (!isset($_POST["frmstartdate"])) {
		//$tbldatesortstart=($current_date-30);		
		// setup startdate for query
		$uifrmstartdate 		= date('m/d/Y');
		$sqlfrmstartdate 		= amerdate2sqldatetime($uifrmstartdate);
	}
	else {
		$uifrmstartdate 		= $frmstartdate;
		$sqlfrmstartdate 		= amerdate2sqldatetime($frmstartdate);
		}

if (!isset($_POST["frmenddate"])) {
		//$tbldatesortstart=($current_date-30);		
		// setup startdate for query
		$uifrmenddate 			= date('m/d/Y');
		$sqlfrmenddate 			= amerdate2sqldatetime($uifrmenddate);
	}
	else {
		$uifrmenddate 			= $frmenddate;
		$sqlfrmenddate 			= amerdate2sqldatetime($frmenddate);
		}

if (!isset($_POST["frmjoined"])) {
		//there is no information in this field, set default values
		
		// check to see if the intfrmjoined field has any value
		$intfrmjoined	= 0;
		$intsqlwhereaddon = 0;
		$strsqlwhereaddon = "none";
		
		$togfrmjoined = 1;
		
		if (!isset($_POST["intfrmjoined"])) {
				//echo "there is no value in intfrmjoined";
				$frmjoined 				= 0; //set value to zero (this causes the checkbox to not be checked
				$intfrmjoined 			= 0; //set value to zero (this causes the checkbox to not be checked
				}
			else {
				//echo "value is ".$intfrmjoined." ";
				if ($intfrmjoined==0) {
						// the value of the box has 'no' value
						$frmjoined				= "0";
						$intfrmjoined			= 0;
					}
					else {
						// the value of the box isn't one so we should do what it says
						$frmjoined				= 1;
						$intfrmjoined			= 1;
					}				
			}
		}
	else {
		// the field does exist, what is its current value
		$frmjoined				= $_POST["frmjoined"];
		$intfrmjoined			= 1;
		}	

if (!isset($_POST["strsqlwhereaddon"])) {
		$strsqlwhereaddon="none";
		$intsqlwhereaddon = 0;
	}
	else {
		if ($togfrmjoined==1) {
				// checkbox is not active, clean out sql statement
				$strsqlwhereaddon		= "";
				$intsqlwhereaddon 		= 1;
			}
			else {
	
		$strsqlwhereaddon		= $_POST["strsqlwhereaddon"];
		$strsqlwhereaddon 		= str_replace("%3d","=",$strsqlwhereaddon );
		//$tblsqlwhereaddon 		= 1;
		}
	} 

if (!isset($_POST["intsqlwhereaddon"])) {
		if ($tbldatesort==1) {
				$intsqlwhereaddon = 1;
			}
		//tblsqlwhereaddon = 1
	}
	else {
		$intsqlwhereaddon=$_POST["intsqlwhereaddon"];
		//tblsqlwhereaddon = 1
	}
	
for ($i=0; $i<count($aheadername); $i++) {
	if (!isset($_POST[$adatafield[$i]])) {
			$aheadersort[$i]="NotSorted";
		}
		else {
			$aheadersort[$i]=$_POST[$adatafield[$i]];
		} 
	}


// add where statement to sql statement
if ($tbldatesort==1) {
		$nsql = " where ".$tbldatesorttable.".".$tbldatesortfield." >= '".$sqlfrmstartdate."' and ".$tbldatesorttable.".".$tbldatesortfield." <= '".$sqlfrmenddate."'";
		$sql = $sql.$nsql;
		?>
		<script>	</script>
		<?
	}
if ($tbltextsort==1) {
		if ($tbldatesort==1) {
				$nsql = " and ".$tbltextsorttable.".".$tbltextsortfield." like '%".$frmtextlike."%' ";
				$sql = $sql.$nsql;
				}
			else {
				$nsql = " where ".$tbltextsorttable.".".$tbltextsortfield." like '%".$frmtextlike."%' ";
				$sql = $sql.$nsql;	
				}
	}
if ($strsqlwhereaddon=="none") {
		//do not add any additional sql to the sql statement as they have not been provided
		}
	else {
		if ($intfrmjoined==0) {
				// user has choosen not to enable column joining, so dont do it
				}
			else {
				// user has enabled column joining, so do it
				$sql = $sql.$strsqlwhereaddon;
			}
	}

//order by sql statement
if ($tblheadersort==1) {
	for ($i=0; $i<count($aheadersort); $i=$i+1) {
			if ($aheadersort[$i]=="NotSorted") {
					//do not add sorting column to sql string
				} 
			if ($aheadersort[$i]=="Assending") {
					if ($tblheadersortfirstselected=="yes") {
							//this is the first time a header has been selected
							$tblheadersortfirstselected="no"; //set selected to no
							$nsql=" order by ".$adatafieldtable[$i].".".$adatafield[$i]." ";
							$sql = $sql.$nsql;
						}
						else {
							$sql = $sql.", ".$adatafieldtable[$i].".".$adatafield[$i]." ";
						} 
				} 
			if ($aheadersort[$i]=="Decending") {
					if ($tblheadersortfirstselected=="yes") {
							//this is the first time a header has been selected
							$tblheadersortfirstselected="no"; //set selected to no
							$nsql=" order by ".$adatafieldtable[$i].".".$adatafield[$i]." desc ";
							$sql = $sql.$nsql;
						}
						else {
							$sql = $sql.", ".$adatafieldtable[$i].".".$adatafield[$i]." desc ";
						} 
				} 
		}
	}
	$sql 		= str_replace("%3D","=",$sql);
	//echo $sql;
	
if (!isset($_POST["frmarchives"])) {
		$tblarchivedsort	= $tblarchivedsort;
	}
	else {
		// the field does exist, what is its current value
		$tblarchivedsort	= $_POST["frmarchives"];
	}

if (!isset($_POST["frmclosed"])) {
		$tblclosedsort		= $tblclosedsort;
	}
	else {
		// the field does exist, what is its current value
		$tblclosedsort		= $_POST["frmclosed"];
	}
	$sql = "SELECT * FROM tbl_139_339_sub_n"
?>


<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" name="sorttable" id="sorttable">
<input type="hidden" name="menuitemid" value="<?=$_POST['menuitemid'];?>">
<table border="0" width="100%" id="tblbrowseformtable" cellspacing="0" cellpadding="0">
	<tr>
		<td class="tableheadercenter">
			<b><?=$tmp;?></b>
			</td>
		</tr>
	<tr>
		<td colspan="3" class="tablesubcontent">
			<table border="0" width="100%" cellspacing="4">
				<?
				//make connection to database
				$objconn = mysqli_connect("localhost", "webuser", "limitaces", "openairport");
		
				if (mysqli_connect_errno()) {
						// there was an error trying to connect to the mysql database
						printf("connect failed: %s\n", mysqli_connect_error());
						exit();
					}
					else {
						$objrs = mysqli_query($objconn, $sql);
				
						if ($objrs) {
								$number_of_rows = mysqli_num_rows($objrs);								
								if ($number_of_rows==0) {
										?>
				<tr>
					<td class="formresultscount">
							No Records Founds for today
						</td>
					</tr>
										<?
									}
									else {
										?>
				<tr>
					<td class="formresultscount">
							<?=$number_of_rows;?> records have been found today, may include closed, archived, or otherwise hidden notams.
						</td>
					</tr>
				<tr>
					<td class="tabledatarow">
						<table border="0" width="100%" id="table1" cellpadding="0" cellspacing="1"  style="border-collapse: collapse">
							<tr>
								<td class="formheaders">
									ID
									</td>
								<? 
								for ($i=0; $i<count($aheadername); $i=$i+1) {
										?>
								<td class="formheaders">
										<? 
										if ($tblheadersort==1) {
												?>
									<a href="#" onfocus="javascript:getvaluesortform('<?=$adatafield[$i];?>');" onclick="javascript:updatesortform('<?=$adatafield[$i];?>');"><font color="#ffffff"><?=$aheadername[$i];?></font></a>
									<br>(<input class="inlinehiddenbox" type="text" size="8" id="<?=$adatafield[$i];?>" name="<?=$adatafield[$i];?>" value="<?=$aheadersort[$i];?>">)
												<? 
											}
											else {
												echo $aheadername[$i];
											}												
										?>
									</td>
										<?
									}
								?>
							</form>
								</tr>
										<?
										while ($objarray = mysqli_fetch_array($objrs, MYSQLI_ASSOC)) {
												//echo $objarray[$tblkeyfield];
												$displayrow				= 1;
												$dontdisplay			= 0;

														if ($tblarchivedsort == 0) {
																//echo "Is user sorting archived rows :".$tblarchivedsort." ";
																$displayrow			= 	$function_archivedsort($objarray[$tblkeyfield],$tblarchivedsort);	// returns 1 if row is archived
																//echo "Should this row be displayed? :".$displayrow."<br><br>";
																if ($displayrow == 0) {
																		$dontdisplay_archived = 1;
																	}
																	else {
																		$dontdisplay_archived = 0;
																	}
																	
															}
														
														if ($tblclosedsort == 0) {														
																//echo "Is user sorting closed rows :".$tblclosedsort." ";
																$displayrow 		= 	$function_closedsort($objarray[$tblkeyfield],$tblclosedsort);		// returns 1 if row is closed
																//echo "Should this row be displayed? :".$displayclosed."<br><br>";
																if ($displayrow == 0) {
																		$dontdisplay_closed = 1;
																	}
																	else {
																		$dontdisplay_closed = 0;
																	}
															}
														
														if ($dontdisplay_archived == 1) {
																// Do not display row
																$displayrow = 0;
															}
															else {
																if ($dontdisplay_closed == 1) {
																		$displayrow = 0;
																	}
															}
															
															
												if ($displayrow==1) {															
														?>
							<tr>
								<td height="32" align="center" class="formresults">
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<form style="margin-bottom:0;" action="<?=$functionprinterpage;?>" method="POST" name="printform" id="printform" target="PrinterFriendlyReport" onsubmit="window.open('', 'PrinterFriendlyReport', 'width=717,height=962,status=no,resizable=no,scrollbars=yes')">
											<td height="32" align="center" class="formresults">
												<input type="hidden" name="recordid" 			value="<?=$objarray[$tblkeyfield];?>">
												<input type="submit" name="b1" 					value="<?=$objarray[$tblkeyfield];?>"			class="formsubmit">
												&nbsp;
												</td>
											</form>
											
											<form style="margin-bottom:0;" action="part139339_sub_n_main_c_entry.php" method="POST" name="printform" id="printform" target="PrinterFriendlyReport" onsubmit="window.open('', 'PrinterFriendlyReport', 'width=717,height=962,status=no,resizable=no,scrollbars=yes')">
											<td height="32" align="center" class="formresults">
												<input type="hidden" name="inspectionid" 		value="<?=$objarray[$tblkeyfield];?>">
												<input type="submit" name="b1" 					value="C"			class="formsubmit" alt="Close NOTAM">
												</td>
											</form>
											</tr>
										</table>
									</td>								
								<? 
								for ($i=0; $i<count($aheadername); $i=$i+1) {
										?>
								<td align="center" valign="middle" class="formresults">
										<? 
										switch ($adatafieldid[$i]) {
												case "notjoined":
														switch ($adataspecial[$i]) {
																case 2:
																		?>
									@ <?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
																case 4:
																		?>
									$ <?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
																case 5:
																		?>
									<?=$objarray[$adatafield[$i]];?> %
																		<? 
																		break;
																default:
																		?>
									<?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
															}
														break;
												case "notjoined":
														switch ($ainputtype[$i]) {
																case "CHECKBOX":
																		if ($objarray[$adatafield[$i]]==0) {
																				$tmpcbfield = "No";
																			}
																			else {
																				$tmpcbfield = "Yes";
																			}
																			?>
									<a href="javascript:updatewhereform('<?=$adatafieldtable[$i];?>.<?=$adatafield[$i];?>=<?=$objarray[$adatafield[$i]];?>');">
										<font color="#000000">
											<?=$tmpcbfield;?>
											</font>
										</a>				
																			<?
																		break;
																default:
																				?>
									<a href="javascript:updatewhereform('<?=$adatafieldtable[$i];?>.<?=$adatafield[$i];?>=<?=$objarray[$adatafield[$i]];?>');">
										<font color="#000000">
											<?=$objarray[$adatafield[$i]];?>
											</font>
										</a>
														<?
																		break;
																}
														break;
												default:											
														$tmpsqlwhereaddon=$objarray[$adatafieldid[$i]];
														?>
									<a href="javascript:updatewhereform('<?=$adatafieldtable[$i];?>.<?=$adatafieldid[$i];?>=<?=$tmpsqlwhereaddon;?>');">
										<font color="#000000">
														<?
											$adataselect[$i]($objarray[$adatafieldid[$i]], "all", $adatafield[$i], "hide", "");
														?>
											</font>
										</a>
														<? 
														break;
												}
											//} 
											?>
									</td>
											<? 
									}
								?>
								</tr>
											<?									
											}	// end of looped data
											$displayrow = 1;
											}
									?>
							</table>
									<?
									}	// end of records found statement
								}	// end of sucessfull conection and execution of sql statement
							}	// end of connection established
							?>
						</td>
					</tr>					
				</table>	<!-- end of ajax load table-->
			</td>
		</tr>
	</table>
	<?
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function quickinfowebsite_comments($tmp) {
	// will the form use any of the following toggle switches	
		// should the form have the ability to sort the data by date ?
		// 1 : yes ; 0 : no;
		$tbldatesort 			= 0;															// Display Date Sorting Options Toggle Switch
		$tbltextsort 			= 0;															// Display Text Sorting Options Toggle Switch
		$tblheadersort			= 0;															// Display Heading Sort Options Toggle Switch
		$tbldisplaytotal		= 0;
		
	// this information is needed to program the datafields and sql statements on the fly without any user interuption
		// what is the primary key field (id field) of the table
		$tblkeyfield			= "tbl_website_comment_id";										// What is the Auto Increment Field for this table ?
		$tbldatesortfield		= "";															// What is the name of field use in date sorting ?
		$tbldatesorttable		= "tbl_website_comments";										// What table  is that field part of ?
		$tbltextsortfield		= "";															// What is the name of the field used in text sorting ?
		$tbltextsorttable		= "tbl_website_comments";										// What is the name of the table used for text sorting ?
		$tblarchivedfield		= "tbl_website_comment_archived_yn";							// What is the name of the field used to mark the record archived ?
		$tblname				= "Website Comments Summary Report";							// What is the name of the table ? (used on edit/summary/printer report pages)
		$tblsubname				= "Here is the information you selected";						// What is the subname of the table ? (used on edit/summary/printer report pages)

	// What php pages are used to control the summary, printer reports, and edit functions?
		// by default these pages should be the following
		// $functioneditpage 			= "edit_record_general.php";
		// $functionsummarypage 		= "summary_report_general.php";
		// $functionprinterpage 		= "printer_report_general.php";
		$functioneditpage 		= "edit_record_general.php";									// Name of page used to edit the record
		$functionsummarypage 	= "summary_report_general.php";									// Name of page used to display a summary of the record
		$functionprinterpage 	= "printer_report_general.php";									// Name of page used to display a printer friendly report

	// what columns should the datagrid display?
		// this is an array of information, from [0] to [...], you may have an unlimited number of columns
		$adatafield[0]			= "tbl_website_comment_title";
		$adatafield[1]			= "tbl_website_comment_comment";
	// in each array specified above, what table does that field come from?
		$adatafieldtable[0]		= "tbl_website_comments";
		$adatafieldtable[1]		= "tbl_website_comments";		
	// do you want the user ot be able to click on the information and have something happen?
		// "notjoined" will cause nothing to happen, only the data will be displayed
		// the name of any field in any of the selected tables will cause only records that have that information to be displayed.
		$adatafieldid[0]		= "notjoined";
		$adatafieldid[1]		= "notjoined";
	// in some cases you may want the information displayed to be prefecned with a $ sign or followed by a % sign. here you can tell the program what to display if anything
		// 0 : nothing ; 2 : @ ; 4 : $ ; 5 : %
		$adataspecial[0]		= 0;
		$adataspecial[1]		= 0;
	// should this column be added to create a totals column at the end of the recods
		$adataputarray[0]		= 0;
		$adataputarray[1]		= 0;
	// what do you want to name the columns
		$aheadername[0]			= "Title";
		$aheadername[1]			= "Comment";
	// Any special comments to make after the input box?
		$ainputcomment[0]		= "Title of Post";
		$ainputcomment[1]		= "Post Comment";
	// what type of input is this field?
		// this can be any type of input
		// text, textarea, select, etc.
		$ainputtype[0]			= "TEXT";
		$ainputtype[1]			= "TEXT";
	// if the input type is a SELECT type you should define the name of the function you want to call to load into that select statement
		$adataselect[0]			= "";
		$adataselect[1]			= "";
	// in the event of an error when a user enteres a wrong date, what should the datagrid say to the user?
		$tmpDateEventErrorMessage	= "Error, reset to default";
		
	// Build SQL Statement
	// Since we have all of the information from the arrays and strings we can assemble the nessary SQL Statement without actually having to supply it verbatum.
	// The Limiting clauses will be left off, all we want to create at this point is the basic SELECT *,*,* FROM syntax
	
		$sql = "SELECT ".$tblkeyfield.",";														// Start SQL adding on the id field first						
		
		for ($i=0; $i<count($adatafield); $i=$i+1) {											// Loop through any of the arrays 0 to array length - 1
				$nsql = " ".$adatafield[$i]."";													// add each new value in the array to a temporary sql string
				$sql = $sql.$nsql;																// add the temporary sql string to the main sql string.
				if ($i == count($adatafield)-1) {												// test to see where we are in the string, in this case are we at the end or not?
						$nsql = "";																// at the end of the arrat dont add a , to the end of the value
						$sql = $sql.$nsql;														// add 'nothing' to the sql string
					}
					else {																		// not at the end of the array so do soemthing else
						$nsql = ", ";															// for each value in the array that is not at the end, add a , after the value
						$sql = $sql.$nsql;														// add the temporary sql string to the main sql string
					}			
		}
		$sql = $sql.$nsql;																		// field index values have all been added to the sql string (this line is reduntent, but there for space)
		$nsql = " FROM ".$tbldatesorttable." ";													// with all field index values added, add the FROM syntax and the applicable table in the DB
		$sql = $sql.$nsql;																		// make it all one nice sql string for use

	// For debugging purposes print out the SQL Statement
		//echo $sql;																				// When dedugging you can uncomment this echo and see the sql statement

	// Start the Real Fun	

		$i 							= 0;	
		$tblheadersortfirstselected="yes";
		$togfrmjoined = "";														//just in case we dont define it latter, set a default here.


//-------------------- [ there should be no need to change any of the code below this line ] ----------------------------------------------------------------------------------------------------------------------

		
	// store this array into a serialized array
		$stradatafield 			= urlencode(serialize($adatafield));			// dont touch
		$stradatafieldtable 	= urlencode(serialize($adatafieldtable));		// dont touch
		$stradatafieldid 		= urlencode(serialize($adatafieldid));			// dont touch	
		$stradataspecial		= urlencode(serialize($adataspecial));			// dont touch
		$straheadername			= urlencode(serialize($aheadername));			// dont touch
		$strainputtype			= urlencode(serialize($ainputtype));			// dont touch
		$stradataselect			= urlencode(serialize($adataselect));			// dont touch
		$strainputcomment		= urlencode(serialize($ainputcomment));			// dont touch
		
	// store this array into a serialized array
		$sadatafield 			= (serialize($adatafield));						// dont touch
		$sadatafieldtable 		= (serialize($adatafieldtable));				// dont touch
		$sadatafieldid 			= (serialize($adatafieldid));					// dont touch	
		$sadataspecial			= (serialize($adataspecial));					// dont touch
		$saheadername			= (serialize($aheadername));					// dont touch
		$sainputtype			= (serialize($ainputtype));						// dont touch
		$sadataselect			= (serialize($adataselect));					// dont touch
		$sainputcomment			= (serialize($ainputcomment));					// dont touch
		
		$sadatafield  			= str_replace("\"","|",$sadatafield);
		$sadatafieldtable 		= str_replace("\"","|",$sadatafieldtable);
		$sadatafieldid 			= str_replace("\"","|",$sadatafieldid);
		$sadataspecial 			= str_replace("\"","|",$sadataspecial);
		$saheadername 			= str_replace("\"","|",$saheadername);
		$sainputtype 			= str_replace("\"","|",$sainputtype);
		$sadataselect 			= str_replace("\"","|",$sadataselect);
		$sainputcomment			= str_replace("\"","|",$sainputcomment);

		$tmpfrmstartdateerror 	= "";
		$tmpfrmenddateerror 	= "";
		$arowtotal[0] 			= "";
		$arowtotal[1] 			= "";
		$arowtotal[2] 			= "";
		$arowtotal[3] 			= "";
		$arowtotal[4] 			= "";
		$arowtotal[5] 			= "";
		$arowtotal[6] 			= "";
		$arowtotal[7] 			= "";
		$arowtotal[8] 			= "";
		$arowtotal[9] 			= "";
		$arowtotal[10] 			= "";
		$arowtotal[11] 			= "";		
		
		
if (!isset($_POST["frmstartdate"])) {
		//$tbldatesortstart=($current_date-30);		
		// setup startdate for query
		$uifrmstartdate 		= date('m/d/Y');
		$sqlfrmstartdate 		= amerdate2sqldatetime($uifrmstartdate);
	}
	else {
		$uifrmstartdate 		= $frmstartdate;
		$sqlfrmstartdate 		= amerdate2sqldatetime($frmstartdate);
		}

if (!isset($_POST["frmenddate"])) {
		//$tbldatesortstart=($current_date-30);		
		// setup startdate for query
		$uifrmenddate 			= date('m/d/Y');
		$sqlfrmenddate 			= amerdate2sqldatetime($uifrmenddate);
	}
	else {
		$uifrmenddate 			= $frmenddate;
		$sqlfrmenddate 			= amerdate2sqldatetime($frmenddate);
		}

if (!isset($_POST["frmjoined"])) {
		//there is no information in this field, set default values
		
		// check to see if the intfrmjoined field has any value
		$intfrmjoined	= 0;
		$intsqlwhereaddon = 0;
		$strsqlwhereaddon = "none";
		
		$togfrmjoined = 1;
		
		if (!isset($_POST["intfrmjoined"])) {
				//echo "there is no value in intfrmjoined";
				$frmjoined 				= 0; //set value to zero (this causes the checkbox to not be checked
				$intfrmjoined 			= 0; //set value to zero (this causes the checkbox to not be checked
				}
			else {
				//echo "value is ".$intfrmjoined." ";
				if ($intfrmjoined==0) {
						// the value of the box has 'no' value
						$frmjoined				= "0";
						$intfrmjoined			= 0;
					}
					else {
						// the value of the box isn't one so we should do what it says
						$frmjoined				= 1;
						$intfrmjoined			= 1;
					}				
			}
		}
	else {
		// the field does exist, what is its current value
		$frmjoined				= $_POST["frmjoined"];
		$intfrmjoined			= 1;
		}	

if (!isset($_POST["strsqlwhereaddon"])) {
		$strsqlwhereaddon="none";
		$intsqlwhereaddon = 0;
	}
	else {
		if ($togfrmjoined==1) {
				// checkbox is not active, clean out sql statement
				$strsqlwhereaddon		= "";
				$intsqlwhereaddon 		= 1;
			}
			else {
	
		$strsqlwhereaddon		= $_POST["strsqlwhereaddon"];
		$strsqlwhereaddon 		= str_replace("%3d","=",$strsqlwhereaddon );
		//$tblsqlwhereaddon 		= 1;
		}
	} 

if (!isset($_POST["intsqlwhereaddon"])) {
		if ($tbldatesort==1) {
				$intsqlwhereaddon = 1;
			}
		//tblsqlwhereaddon = 1
	}
	else {
		$intsqlwhereaddon=$_POST["intsqlwhereaddon"];
		//tblsqlwhereaddon = 1
	}
	
for ($i=0; $i<count($aheadername); $i++) {
	if (!isset($_POST[$adatafield[$i]])) {
			$aheadersort[$i]="NotSorted";
		}
		else {
			$aheadersort[$i]=$_POST[$adatafield[$i]];
		} 
	}


// add where statement to sql statement
if ($tbldatesort==1) {
		$nsql = " where ".$tbldatesorttable.".".$tbldatesortfield." >= '".$sqlfrmstartdate."' and ".$tbldatesorttable.".".$tbldatesortfield." <= '".$sqlfrmenddate."'";
		$sql = $sql.$nsql;
		?>
		<script>	</script>
		<?
	}
if ($tbltextsort==1) {
		if ($tbldatesort==1) {
				$nsql = " and ".$tbltextsorttable.".".$tbltextsortfield." like '%".$frmtextlike."%' ";
				$sql = $sql.$nsql;
				}
			else {
				$nsql = " where ".$tbltextsorttable.".".$tbltextsortfield." like '%".$frmtextlike."%' ";
				$sql = $sql.$nsql;	
				}
	}
if ($strsqlwhereaddon=="none") {
		//do not add any additional sql to the sql statement as they have not been provided
		}
	else {
		if ($intfrmjoined==0) {
				// user has choosen not to enable column joining, so dont do it
				}
			else {
				// user has enabled column joining, so do it
				$sql = $sql.$strsqlwhereaddon;
			}
	}

//order by sql statement
if ($tblheadersort==1) {
	for ($i=0; $i<count($aheadersort); $i=$i+1) {
			if ($aheadersort[$i]=="NotSorted") {
					//do not add sorting column to sql string
				} 
			if ($aheadersort[$i]=="Assending") {
					if ($tblheadersortfirstselected=="yes") {
							//this is the first time a header has been selected
							$tblheadersortfirstselected="no"; //set selected to no
							$nsql=" order by ".$adatafieldtable[$i].".".$adatafield[$i]." ";
							$sql = $sql.$nsql;
						}
						else {
							$sql = $sql.", ".$adatafieldtable[$i].".".$adatafield[$i]." ";
						} 
				} 
			if ($aheadersort[$i]=="Decending") {
					if ($tblheadersortfirstselected=="yes") {
							//this is the first time a header has been selected
							$tblheadersortfirstselected="no"; //set selected to no
							$nsql=" order by ".$adatafieldtable[$i].".".$adatafield[$i]." desc ";
							$sql = $sql.$nsql;
						}
						else {
							$sql = $sql.", ".$adatafieldtable[$i].".".$adatafield[$i]." desc ";
						} 
				} 
		}
	}
	$sql 		= str_replace("%3D","=",$sql);
	//echo $sql;
?>

<table border="0" id="tblbrowseformtable" cellspacing="0" cellpadding="0" class="layoutquickinfocontent" width="100%">
	<tr>
		<td colspan="3">
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td class="tableheadercenter">
						<b><?=$tmp;?></b>
						</td>
					</tr>
				<?
				//make connection to database
				$objconn = mysqli_connect("localhost", "webuser", "limitaces", "KATY_website");
		
				if (mysqli_connect_errno()) {
						// there was an error trying to connect to the mysql database
						printf("connect failed: %s\n", mysqli_connect_error());
						exit();
					}
					else {
						$objrs = mysqli_query($objconn, $sql);
				
						if ($objrs) {
								$number_of_rows = mysqli_num_rows($objrs);
								if ($number_of_rows==0) {
										?>
										<tr>
											<td>
												There are no website comments at this time
												</td>
											</tr>
										<?
										}
										else {
												?>
				<tr>
					<td class="tabledatarow">
						<table border="0" width="100%" id="table1" cellpadding="0" cellspacing="1" style="border-collapse: collapse">
							<tr>
								<td class="formheaders">
									ID
									</td>
								<? 
								for ($i=0; $i<count($aheadername); $i=$i+1) {
										?>
								<td class="formheaders">
										<? 
										if ($tblheadersort==1) {
												?>
									<a href="#" onfocus="javascript:getvaluesortform('<?=$adatafield[$i];?>');" onclick="javascript:updatesortform('<?=$adatafield[$i];?>');"><font color="#ffffff"><?=$aheadername[$i];?></font></a>
									<br>(<input class="inlinehiddenbox" type="text" size="8" id="<?=$adatafield[$i];?>" name="<?=$adatafield[$i];?>" value="<?=$aheadersort[$i];?>">)
												<? 
											}
											else {
												echo $aheadername[$i];
											}	
										?>
									</td>
										<?
									}
								?>
							</form>
								</tr>
										<?
										while ($objarray = mysqli_fetch_array($objrs, MYSQLI_ASSOC)) {
										//$tmpfieldname	= $layer3array['menu_item_name_long'];
										?>
							<tr>
								<td height="32" align="center" class="formresults">
									<?=$objarray[$tblkeyfield];?>
									</td>
								
								<? 
								for ($i=0; $i<count($aheadername); $i=$i+1) {
										if ($adataputarray[$i]==1) {
												//echo $arowtotal;
												$arowtotal[$i] = $arowtotal[$i] + $objarray[$adatafield[$i]];
											}
										?>
								<td align="center" valign="middle" class="formresults">
										<? 
										switch ($adatafieldid[$i]) {
												case "notjoined":
														switch ($adataspecial[$i]) {
																case 2:
																		?>
									@ <?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
																case 4:
																		?>
									$ <?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
																case 5:
																		?>
									<?=$objarray[$adatafield[$i]];?> %
																		<? 
																		break;
																default:
																		?>
									<?=$objarray[$adatafield[$i]];?>
																		<? 
																		break;
															}
														break;
												case "notjoined":
														switch ($ainputtype[$i]) {
																case "CHECKBOX":
																		if ($objarray[$adatafield[$i]]==0) {
																				$tmpcbfield = "No";
																			}
																			else {
																				$tmpcbfield = "Yes";
																			}
																			?>
									<a href="javascript:updatewhereform('<?=$adatafieldtable[$i];?>.<?=$adatafield[$i];?>=<?=$objarray[$adatafield[$i]];?>');">
										<font color="#000000">
											<?=$tmpcbfield;?>
											</font>
										</a>				
																			<?
																		break;
																default:
																				?>
									<a href="javascript:updatewhereform('<?=$adatafieldtable[$i];?>.<?=$adatafield[$i];?>=<?=$objarray[$adatafield[$i]];?>');">
										<font color="#000000">
											<?=$objarray[$adatafield[$i]];?>
											</font>
										</a>
														<?
																		break;
																}
														break;
												default:											
														$tmpsqlwhereaddon=$objarray[$adatafieldid[$i]];
														?>
									<a href="javascript:updatewhereform('<?=$adatafieldtable[$i];?>.<?=$adatafieldid[$i];?>=<?=$tmpsqlwhereaddon;?>');">
										<font color="#000000">
														<?
											$adataselect[$i]($objarray[$adatafieldid[$i]], "all", $adatafield[$i], "hide", "");
														?>
											</font>
										</a>
														<? 
														break;
												}
											//} 
											?>
									</td>
											<? 
									}
								?>
								</tr>
											<?									
											}	// end of looped data
											mysqli_free_result($objrs);
											mysqli_close($objconn);
								if ($tbldisplaytotal==1) {
									?>
								<tr>
									<td colspan="2" align="center" valign="middle" class="formresults">
										Total
										</td>
									<?
									for ($i=1; $i<count($aheadername); $i=$i+1) {
											if ($adataputarray[$i]==1) {
													?>
									<td align="center" valign="middle" class="formresults">
													<?
													echo $arowtotal[$i];
													?>
										</td>
													<?
												}
												else {
													?>
									<td align="center" valign="middle" class="formresults">
										&nbsp;
										</td>
													<?
												}
										}
										?>
									</tr>
										<?
									}
									?>
							</table>
									<?
									}	// end of records found statement
								}	// end of sucessfull conection and execution of sql statement
							}	// end of connection established
							?>
						</td>
					</tr>					
				</table>	<!-- end of ajax load table-->
			</td>
		</tr>
	</table>
	<?
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>