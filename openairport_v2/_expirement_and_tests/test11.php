<?
include("includes/header.php");																	// This include 'header.php' is the main include file which has the page layout, css, and functions all defined.
include("includes/POSTs.php");																		// This include pulls information from the $_POST['']; variable array for use on this page

	// will the form use any of the following toggle switches	
		// should the form have the ability to sort the data by date ?
		// 1 : yes ; 0 : no;
		$tbldatesort 			= 1;															// Display Date Sorting Options Toggle Switch
		$tbltextsort 			= 0;															// Display Text Sorting Options Toggle Switch
		$tblheadersort			= 1;															// Display Heading Sort Options Toggle Switch
		$tbldisplaytotal			= 0;
		
	// this information is needed to program the datafields AND sql statements on the fly without any user interuption
		// what is the primary key field (id field) of the table
		$tblkeyfield			= "leases_id";												// What is the Auto Increment Field for this table ?
		$tbldatesortfield		= "lease_beganon";															// What is the name of field use in date sorting ?
		$tbldatesorttable		= "tbl_leases_main";										// What table  is that field part of ?
		$tbltextsortfield		= "lease_treason";												// What is the name of the field used in text sorting ?
		$tbltextsorttable		= "tbl_leases_main";										// What is the name of the table used for text sorting ?
		$tblarchivedfield		= "lease_archived_yn";										// What is the name of the field used to mark the record archived ?
		$tblname				= "Lease Summary Report";									// What is the name of the table ? (used on edit/summary/printer report pages)
		$tblsubname			= "Here is the information you selected";						// What is the subname of the table ? (used on edit/summary/printer report pages)

	// What php pages are used to control the summary, printer reports, AND edit functions?
		// by default these pages should be the following
		// $functioneditpage 			= "edit_record_general.php";
		// $functionsummarypage 		= "summary_report_general.php";
		// $functionprinterpage 		= "printer_report_general.php";
		$functioneditpage 		= "edit_record_general.php";									// Name of page used to edit the record
		$functionsummarypage 		= "summary_report_general.php";									// Name of page used to display a summary of the record
		$functionprinterpage 		= "printer_report_general.php";									// Name of page used to display a printer friendly report

	// what columns should the datagrid display?
		// this is an array of information, from [0] to [...], you may have an unlimited number of columns
		$adatafield			= array('lease_beganon','leases_lessee_cb_int','leases_lease_type_cb_int','leases_type_id','lease_terms_cb_int','lease_expectedend','lease_terminatedon','lease_treason','lease_doclocation','lease_archived_yn');
	// in each array specified above, what table does that field come from?
		$adatafieldtable			= array('tbl_leases_main','tbl_leases_main','tbl_leases_main','tbl_leases_main','tbl_leases_main','tbl_leases_main','tbl_leases_main','tbl_leases_main','tbl_leases_main','tbl_leases_main');
	// do you want the user ot be able to click on the information AND have something happen?
		// "notjoined" will cause nothing to happen, only the data will be displayed
		// the name of any field in any of the selected tables will cause only records that have that information to be displayed.
		$adatafieldid			= array('justsort','leases_lessee_cb_int','leases_lease_type_cb_int','leases_type_id','lease_terms_cb_int','justsort','justsort','justsort','justsort','justsort');
	// in some cases you may want the information displayed to be prefecned with a $ sign or followed by a % sign. here you can tell the program what to display if anything
		// 0 : nothing ; 2 : @ ; 4 : $ ; 5 : %
		$adataspecial			= array(0,0,0,0,0,0,0,0,0,0);
	// should this column be added to create a totals column at the end of the recods
		$adataputarray			= array(0,0,0,0,0,0,0,0,0,0);
	// should this column be averaged by the total number of records found for that column?
	// 1: yes, 0:no.
		$adataavgarray			= array(0,0,0,0,0,0,0,0,0,0);
	// what do you want to name the columns
		$aheadername			= array('Date (began on)','Lessee','Type of Lease','Item Leased','Terms','Date (expected end)','Date (Terminated)','Termination Reason','Document Location','Archived');
	// Any special comments to make after the input box?
		$ainputcomment[0]		= "( mm/dd/yyyy )";
		$ainputcomment[1]		= "( 24 hour )";
		$ainputcomment[2]		= "( select from the list )";
		$ainputcomment[3]		= "( select from the list )";
		$ainputcomment[4]		= "( select from the list )";
		$ainputcomment[5]		= "( no special charactors )";
		$ainputcomment[6]		= "( Checked = Archived )";
		$ainputcomment[7]		= "( no special charactors )";
		$ainputcomment[8]		= "( Checked = Archived )";
		$ainputcomment[9]		= "( Checked = Archived )";
	// what type of input is this field?
		// this can be any type of input
		// text, textarea, select, etc.
		$ainputtype			= array('TEXT','SELECT','SELECT','SELECT','SELECT','TEXT','TEXT','TEXTAREA','TEXT','CHECKBOX');
	// if the input type is a SELECT type you should define the name of the function you want to call to load into that select statement
		$adataselect			= array('','organizationcombobox','leasetypescombobox','leaseitemtypecombobox','leasetermscombobox','','','','','');
	// in the event of an error when a user enteres a wrong date, what should the datagrid say to the user?
		$tmpDateEventErrorMessage	= "Error, reset to default";
		
$sql 	= "SELECT * FROM tbl_leases_main
		INNER JOIN tbl_leases_terms 		ON tbl_leases_terms.leases_terms_id 		= tbl_leases_main.lease_terms_cb_int 
		INNER JOIN tbl_organization_main		ON tbl_organization_main.Organizations_id = tbl_leases_main.leases_lessee_cb_int 
		INNER JOIN tbl_general_tblrlshp 		ON tbl_general_tblrlshp.tbl_gtr_t_id 		= tbl_leases_main.leases_lease_type_cb_int
		ORDER BY lease_beganon ASC";

//-------------------- [ there should be no need to change any of the code below this line ] ----------------------------------------------------------------------------------------------------------------------

	// build breadcrum trail	
		buildbreadcrumtrail($strmenuitemid);
		
	// store this array into a serialized array
		$stradatafield 			= urlencode(serialize($adatafield));			// dont touch
		$stradatafieldtable 		= urlencode(serialize($adatafieldtable));		// dont touch
		$stradatafieldid 		= urlencode(serialize($adatafieldid));			// dont touch	
		$stradataspecial			= urlencode(serialize($adataspecial));			// dont touch
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
		$tmpfrmenddateerror 		= "";
		
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
		$arowtotal[10] 		= "";
		$arowtotal[11] 		= "";		
		
	$currentmiles	= "";
	$lastmiles		= "";
	$currenteconomy	= "";
	$currenthours	= "";
	$lasthours		= "";
	$currenteconomyh	= "";
	
?>
<form action="<?=$_SERVER["PHP_SELF"];?>" method="POST" name="sorttable" id="sorttable">
<input type="hidden" name="menuitemid" value="<?=$_POST['menuitemid'];?>">
<table border="0" width="100%" id="tblbrowseformtable" cellspacing="0" cellpadding="0">
	<tr>
		<td width="10" class="tableheaderleft">&nbsp;</td>
		<td class="tableheadercenter">
			<?
			getnameofmenuitemid($strmenuitemid, "long", 4, "#ffffff");
			?>
			</td>
		<td class="tableheaderright">
			(
			<?
			getpurposeofmenuitemid($strmenuitemid, 1, "#FFFFFF");
			?>
			)
			</td>
		</tr>
	<tr>
		<td colspan="3" class="tablesubcontent">
			<table border="0" width="100%" cellspacing="3" cellpadding="5" id="table2" height="10">
				<input class="commonfieldbox" type="hidden" name="frmurl" size="1" value="<?=$frmurl;?>" >
				<input class="combobox" type="hidden" size="4" name="intfrmjoined" value="<?=$intfrmjoined;?>" >
				<input class="combobox" type="hidden" size="40" name="strsqlWHEREaddon" value="<?=$strsqlWHEREaddon;?>" >
				<input class="combobox" type="hidden" size="4" name="intsqlWHEREaddon" id="intsqlWHEREaddon" value="<?=$intsqlWHEREaddon;?>" >
				<tr>
					<td class="formoptions" align="center">
						<?
						if ($tbldatesort==1) {
								?>
						Start Date<br><input class="commonfieldbox" type="text" name="frmstartdate" size="10" value="<?=$uifrmstartdate;?>" onchange="javascript:(isdate(this.form.frmstartdate.value,'mm/dd/yyyy'))">
								<?
							}
							else {
								?>
						Turned Off
								<?
							}
						?>
						</td>
					<td class="formoptions" align="center">
						<?
						if ($tbldatesort==1) {
								?>
						End Date<br><input class="commonfieldbox" type="text" name="frmenddate" size="10" value="<?=$uifrmenddate;?>" onchange="javascript:(isdate(this.form.frmenddate.value,'mm/dd/yyyy'))">
								<?
							}
							else {
								?>
						Turned Off
								<?
							}
						?>
						</td>
					<td class="formoptions" align="center">
						<?
						if ($tbltextsort==1) {
								?>
						Text Like<br><input class="commonfieldbox" type="text" name="frmtextlike" size="25" value="<?=$frmtextlike;?>">
								<?
							}
							else {
								?>
						Tuned Off
								<?
							}
						?>
						</td>
					<td class="formoptions" align="center" onMouseover="ddrivetip('Joined will limit the number of rows to those rows which meet the clicked data <br> To use Joined, click the joined checkbox, then click submit. Then click on row AND data you want to limit the search to. You can add an unlimited number of limitation to any search')"; onMouseout="hideddrivetip()">
						Joined <input class="commonfieldbox" type="checkbox" name="frmjoined" id="frmjoined" size="25" value="1"
						<?
						if ($frmjoined=="1") {
								
								?>
						checked="checked">
								<?
								}
							else {
								?>
							>
								<?
							}
						?>
						</td>
					<td class="formoptions" align="center">
						<input class="formsubmit" type="button" name="button" value="submit" onclick="javascript:document.sorttable.submit()">
						</td>
					</tr>
				</table>
			<table border="0" width="100%" cellspacing="4">
				<tr>
					<td>
						<table cellspacing="0" width="100%">
							<tr>
								<td class="formoptionsavilabletop">
									The following options are avilable to you
									</td>
								</tr>
							<tr>
								<td class="formoptionsavilablebottom">
									<table>
										<tr>
											<?
											$encoded = urlencode($sql);
											?>
											<td class="formoptionsubmit" onclick="window.open('/browse_report_general.php?frmurl=<?=$encoded;?>&menuitemid=<?=$strmenuitemid?>&aheadername=<?=$straheadername;?>&adatafield=<?=$stradatafield;?>&tblkeyfield=<?=$tblkeyfield;?>&tbldatesortfield=<?=$tbldatesortfield;?>&tbldatesorttable=<?=$tbldatesorttable;?>&tbltextsortfield=<?=$tbltextsortfield;?>&tbltextsorttable=<?=$tbltextsorttable;?>&adatafieldtable=<?=$stradatafieldtable;?>&adatafieldid=<?=$stradatafieldid;?>&adataspecial=<?=$stradataspecial;?>&adataselect=<?=$stradataselect?>&tblarchivedfield=<?=$tblarchivedfield?>','printerfriendlyreport','width=750,height=550');">
												&nbsp;Printer Friendly Report&nbsp;
												</td>	
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				<tr>
					<td class="tabledatarow">
						<table border="0" width="100%" id="table1" cellpadding="0" cellspacing="1"  style="border-collapse: collapse">
							<tr>
								<td class="formheaders">
									ID
									</td>
								<td class="formheaders">
									Functions
									</td>
								<? 
								$tmpcounter	= count($aheadername);
								for ($i=0; $i<$tmpcounter; $i=$i+1) {
										?>
								<td class="formheaders">
										<? 
										if ($tblheadersort==1) {
												?>
									<a href="#" onfocus="javascript:getvaluesortform('<?=$adatafield[$i];?>');" onclick="javascript:updatesortform('<?=$adatafield[$i];?>');"><font color="#ffffff"><?=$aheadername[$i];?></font></a>
									<br>(<input class="inlinehiddenbox" type="text" size="8" id="<?=$adatafield[$i];?>" name="<?=$adatafield[$i];?>" value="<?=$aheadersort[$i];?>">)
												<? 
											} 
										?>
									</td>
										<?
									}
								?>
							</form>
					</tr>
					<?
					$objconn = mysqli_connect($hostdomain, $hostusername, $passwordofdatabase, $nameofdatabase);
				
						if (mysqli_connect_errno()) {
								// there was an error trying to connect to the mysql database
								printf("connect failed: %s\n", mysqli_connect_error());
								exit();
							}
							else {
								$objrs = mysqli_query($objconn, $sql);
						
								if ($objrs) {
										while ($objarray = mysqli_fetch_array($objrs, MYSQLI_ASSOC)) {
										//$tmpfieldname	= $layer3array['menu_item_name_long'];
										?>
							<tr>
								<td height="32" align="center" class="formresults">
									<?=$objarray[$tblkeyfield];?>
									</td>
								<td align="center" class="formresults">
									<table border="1" width="50" cellspacing="0" id="table1" class="formsubmit cellpadding="0">
										<tr>
											<form style="margin-bottom:0;" action="<?=$functioneditpage;?>" method="POST" name="editform" id="editform">
											<td class="formoptionsubmit">
												<input type="hidden" name="editpage" 			value="<?=$functioneditpage;?>">
												<input type="hidden" name="summarypage" 		value="<?=$functionsummarypage;?>">
												<input type="hidden" name="printerpage" 		value="<?=$functionprinterpage;?>">
												<input type="hidden" name="recordid" 			value="<?=$objarray[$tblkeyfield];?>">
												<input type="hidden" name="menuitemid" 			value="<?=$strmenuitemid?>">
												<input type="hidden" name="aheadername" 		value="<?=$saheadername;?>">
												<input type="hidden" name="adatafield" 			value="<?=$sadatafield;?>">
												<input type="hidden" name="adatafieldtable" 	value="<?=$sadatafieldtable;?>">
												<input type="hidden" name="adatafieldid" 		value="<?=$sadatafieldid;?>">
												<input type="hidden" name="adataspecial" 		value="<?=$sadataspecial;?>">
												<input type="hidden" name="ainputtype" 			value="<?=$sainputtype;?>">
												<input type="hidden" name="adataselect" 		value="<?=$sadataselect;?>">
												<input type="hidden" name="ainputcomment" 		value="<?=$sainputcomment;?>">
												<input type="hidden" name="tblname" 			value="<?=$tblname;?>">
												<input type="hidden" name="tblsubname" 			value="<?=$tblsubname?>">
												<input type="hidden" name="tblkeyfield" 		value="<?=$tblkeyfield;?>">
												<input type="hidden" name="tblarchivedfield" 	value="<?=$tblarchivedfield;?>">
												<input type="hidden" name="tbldatesortfield" 	value="<?=$tbldatesortfield;?>">
												<input type="hidden" name="tbldatesorttable" 	value="<?=$tbldatesorttable;?>">
												<input type="hidden" name="tbltextsortfield" 	value="<?=$tbltextsortfield;?>">
												<input type="hidden" name="tbltextsorttable" 	value="<?=$tbltextsorttable;?>">
												<input type="submit" value="E" name="b1" class="formsubmit">
												</td>
											</form>
											<form style="margin-bottom:0;" action="<?=$functionsummarypage;?>" method="POST" name="summaryform" id="summaryform">
											<td class="formoptionsubmit">
												<input type="hidden" name="editpage" 			value="<?=$functioneditpage;?>">
												<input type="hidden" name="summarypage" 		value="<?=$functionsummarypage;?>">
												<input type="hidden" name="printerpage" 		value="<?=$functionprinterpage;?>">
												<input type="hidden" name="recordid" 			value="<?=$objarray[$tblkeyfield];?>">
												<input type="hidden" name="menuitemid" 			value="<?=$strmenuitemid?>">
												<input type="hidden" name="aheadername" 		value="<?=$saheadername;?>">
												<input type="hidden" name="adatafield" 			value="<?=$sadatafield;?>">
												<input type="hidden" name="adatafieldtable" 	value="<?=$sadatafieldtable;?>">
												<input type="hidden" name="adatafieldid" 		value="<?=$sadatafieldid;?>">
												<input type="hidden" name="adataspecial" 		value="<?=$sadataspecial;?>">
												<input type="hidden" name="ainputtype" 			value="<?=$sainputtype;?>">
												<input type="hidden" name="ainputcomment" 		value="<?=$sainputcomment;?>">
												<input type="hidden" name="adataselect" 		value="<?=$sadataselect;?>">
												<input type="hidden" name="tblname" 			value="<?=$tblname;?>">
												<input type="hidden" name="tblsubname" 			value="<?=$tblsubname?>">
												<input type="hidden" name="tblkeyfield" 		value="<?=$tblkeyfield;?>">
												<input type="hidden" name="tblarchivedfield" 	value="<?=$tblarchivedfield;?>">
												<input type="hidden" name="tbldatesortfield" 	value="<?=$tbldatesortfield;?>">
												<input type="hidden" name="tbldatesorttable" 	value="<?=$tbldatesorttable;?>">
												<input type="hidden" name="tbltextsortfield" 	value="<?=$tbltextsortfield;?>">
												<input type="hidden" name="tbltextsorttable" 	value="<?=$tbltextsorttable;?>">
												<input type="submit" value="S" name="b1" class="formsubmit">
												</td>
											</form>
											<form style="margin-bottom:0;" action="<?=$functionprinterpage;?>" method="POST" name="reportform" id="reportform" target="PrinterFriendlyWindow" onsubmit="window.open('', 'PrinterFriendlyWindow', 'width=750,height=550,status=no,resizable=no,scrollbars=yes')">
											<td class="formoptionsubmit">
												<input type="hidden" name="editpage" 			value="<?=$functioneditpage;?>">
												<input type="hidden" name="summarypage" 		value="<?=$functionsummarypage;?>">
												<input type="hidden" name="printerpage" 		value="<?=$functionprinterpage;?>">
												<input type="hidden" name="recordid" 			value="<?=$objarray[$tblkeyfield];?>">
												<input type="hidden" name="menuitemid" 			value="<?=$strmenuitemid?>">
												<input type="hidden" name="aheadername" 		value="<?=$saheadername;?>">
												<input type="hidden" name="adatafield" 			value="<?=$sadatafield;?>">
												<input type="hidden" name="adatafieldtable" 	value="<?=$sadatafieldtable;?>">
												<input type="hidden" name="adatafieldid" 		value="<?=$sadatafieldid;?>">
												<input type="hidden" name="adataspecial" 		value="<?=$sadataspecial;?>">
												<input type="hidden" name="ainputtype" 			value="<?=$sainputtype;?>">
												<input type="hidden" name="ainputcomment" 		value="<?=$sainputcomment;?>">
												<input type="hidden" name="adataselect" 		value="<?=$sadataselect;?>">
												<input type="hidden" name="tblname" 			value="<?=$tblname;?>">
												<input type="hidden" name="tblsubname" 			value="<?=$tblsubname?>">
												<input type="hidden" name="tblkeyfield" 		value="<?=$tblkeyfield;?>">
												<input type="hidden" name="tblarchivedfield" 	value="<?=$tblarchivedfield;?>">
												<input type="hidden" name="tbldatesortfield" 	value="<?=$tbldatesortfield;?>">
												<input type="hidden" name="tbldatesorttable" 	value="<?=$tbldatesorttable;?>">
												<input type="hidden" name="tbltextsortfield" 	value="<?=$tbltextsortfield;?>">
												<input type="hidden" name="tbltextsorttable" 	value="<?=$tbltextsorttable;?>">
												<input type="submit" value="R" name="b1" class="formsubmit">
												</td>
											</form>
											</tr>
										</table>
									</td>
								<td align="center" valign="middle" class="formresults">
									<?=$objarray['leaseid'];?>
									</td>
								<td align="center" valign="middle" class="formresults">
									<?=$objarray['leases_lessee_cb_int'];?>
									</td>
								<td align="center" valign="middle" class="formresults">
									<?=$objarray['leases_lease_type_cb_int'];?>
									</td>
								<td align="center" valign="middle" class="formresults">
									<?=$objarray['leases_type_id'];?>
									</td>
								<td align="center" valign="middle" class="formresults">
									<?=$objarray['lease_terms_cb_int'];?>
									</td>
								<td align="center" valign="middle" class="formresults">
									<?=$objarray['lease_expectedend'];?>
									</td>
								<td align="center" valign="middle" class="formresults">
									<?=$objarray['lease_terminatedon'];?>
									</td>
								<td align="center" valign="middle" class="formresults">
									<?=$objarray['lease_treason'];?>
									</td>
								<td align="center" valign="middle" class="formresults">
									<?=$objarray['lease_doclocation'];?>
									</td>
								<td align="center" valign="middle" class="formresults">
									<?=$objarray['lease_archived_yn'];?>
									</td>
								<?
											//} 
											?>
									</td>
											<? 
									}
								?>
								</tr>
							</table>
									<?
								}	// end of sucessfull conection AND execution of sql statement
							}	// end of connection established
							?>
						</td>
					</tr>					
				</table>	<!-- end of ajax load table-->
			</td>
		</tr>
	</table>
