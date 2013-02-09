<?php
function _dp_327_discrepancies($dasharray) {
		
		$module_name = '327_discrepancies';
		$target_frame = '_iframe-layouttableiframecontent';
	
		//						0					1						2					3					4					5					6					7					8					9
		//$dasharray	= array($tmp_dash_main_id	,$tmp_dash_main_func	,$tmp_dash_main_nl	,$tmp_dash_main_ns	,$tmp_dash_main_p	,$tmp_dash_main_ml	,$tmp_menu_item_id	,$tmp_menu_item_loc	,$tmp_menu_item_nl	,$tmp_menu_item_ns);
		?>
<div name="div_<?php echo $module_name;?>_container" id="div_<?php echo $module_name;?>_container"  
	class="table_dashpanel_container" />
	<table name="table_<?php echo $module_name;?>" id="table_<?php echo $module_name;?>" 
		class="dashpanel_container_table" />
	<tr>
		<form name="menuitem<?php echo $dasharray[6];?>" id="menuitem<?php echo $dasharray[6];?>"  
			method="POST" 
			action="<?php echo $dasharray[7];?>" 
			target="<?php echo $target_frame;?>" 
			style="margin: 0px; margin-bottom:0px; margin-top:-1px;" />
			<input type="hidden" name="menuitemid" value="<?php echo $dasharray[6];?>">
		<td name="header_for_<?php echo $module_name;?>" id="header_for_<?php echo $module_name;?>" 
			class="perp_menuheader"  
			colspan="2" />
			<input type="button" name="button" value="<?php echo $dasharray[2];?>" 
				class="makebuttonlooklikeaheader" 
				onclick="javascript:document.getElementById('menuitem<?php echo $dasharray[6];?>').submit();" />
			</td>
			</form>	
		</tr>
	<?php
	
		// Loop through active discrepancies and display a summary report for each one
		$sql 		= "SELECT * FROM tbl_139_327_sub_d 
		INNER JOIN tbl_systemusers 		ON tbl_systemusers.emp_record_id = tbl_139_327_sub_d.Discrepancy_by_cb_int 
		INNER JOIN tbl_139_327_main 	ON tbl_139_327_main.inspection_system_id = tbl_139_327_sub_d.Discrepancy_inspection_id 
		INNER JOIN tbl_139_327_sub_t 	ON tbl_139_327_sub_t.inspection_type_id = tbl_139_327_main.type_of_inspection_cb_int 
		INNER JOIN tbl_139_327_sub_c 	ON tbl_139_327_sub_c.conditions_id = tbl_139_327_sub_d.discrepancy_checklist_id  
		INNER JOIN tbl_139_327_sub_c_f 	ON tbl_139_327_sub_c_f.facility_id = tbl_139_327_sub_c.condition_facility_cb_int 
		INNER JOIN tbl_general_conditions ON tbl_general_conditions.general_condition_id = tbl_139_327_sub_d.discrepancy_priority 
		ORDER BY Discrepancy_date DESC";
		
		//echo $sql;
		$objconn 	= mysqli_connect($GLOBALS['hostdomain'], $GLOBALS['hostusername'], $GLOBALS['passwordofdatabase'], $GLOBALS['nameofdatabase']);

		if (mysqli_connect_errno()) {
				printf("connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			else {
				$objrs = mysqli_query($objconn, $sql);		
				if ($objrs) {
						$number_of_rows 	= mysqli_num_rows($objrs);
						if($number_of_rows == 0) {
								?>
	<tr>
		<td colspan="2" class="table_dashpanel_container_noresults" />
			No Outstanding Discrepancies
			</td>
		</tr>
								<?php
							}
						while ($objarray 	= mysqli_fetch_array($objrs, MYSQLI_ASSOC)) {
						
								$displayrow					= 0;
								$displayrow_a				= 0;
								$displayrow_b				= 0;
	
								$tmpdiscrepancyid			= $objarray['Discrepancy_id'];
								$tmpdiscrepancycondition	= $objarray['discrepancy_checklist_id'];	
								
								$displayrow_a				= preflights_tbl_139_327_main_sub_d_a_yn($tmpdiscrepancyid,0); // 0 will not return a row even if it is archieved.
								$displayrow_d				= preflights_tbl_139_327_main_sub_d_d_yn($tmpdiscrepancyid,0); // 0 will not return a row even if it is duplicate.

								if($displayrow_a == 0 OR $displayrow_d == 0) {
										// display nothing
										$displayrow = 0;
									}
									else {
										// Check Status of this Discrepancy, ie. Get the current stage
										
										$status = part139327discrepancy_getstage($tmpdiscrepancyid,0, 0,0,0);
										
										$style_bk		= array('red','yellow','green');
										$style_root		= 'table_dashpanel_container_summary_';
										
										if($status <> 3 ) {
												// Display Summary Report
												?>
	<tr>
		<td colspan="2" class="table_dashpanel_container_summary" align="left" valign="top"/>
												<?php
												display_discrepancy_summary($tmpdiscrepancyid,0,0);
												?>
			</td>
		</tr>
	<tr>
		<td class="<?php echo $style_root;?><?php echo $style_bk[$status];?>_commandrow" />
			<div name="327d_control_<?php echo $tmpdiscrepancyid;?>" id="327d_control_<?php echo $tmpdiscrepancyid;?>" style="display:none;text-align:left;vertical-align:text-top;" />
			
			<?php
			// Load Workorder Controls
			// Lie to the blockform
			$disid					= $tmpdiscrepancyid;
			$imclearlyahijacker		= 1;
			$functionworkorderpage 	= 1;
			$functionworkorderpage	= 'part139327_discrepancy_report_display_workorder.php';
			$functionrepairpage		= 'part139327_discrepancy_report_repair.php';
			$functionbouncepage		= 'part139327_discrepancy_report_bounce.php';
			$functionclosedpage		= 'part139327_discrepancy_report_closed.php';
			$array_repairedcontrol	= array(0,0,'part139327_discrepancy_report_display_repaired.php');
			$array_bouncedcontrol	= array(0,0,'part139327_discrepancy_report_display_bounced.php');
			$array_closedcontrol	= array(0,0,'part139327_discrepancy_report_display_closed.php');
			$has_been_bounced 		= preflights_tbl_139_327_main_sub_d_b_yn($disid,1);
			$has_been_closed 		= preflights_tbl_139_327_main_sub_d_c_yn($disid,1);
			$has_been_repaired 		= preflights_tbl_139_327_main_sub_d_r_yn($disid,1);
			$grid_or_row			= '';
			//echo "Been Bounced 	: ".$has_been_bounced." 	<br>";
			//echo "Been Closed 	: ".$has_been_closed." 		<br>";
			//echo "Been Repaired 	: ".$has_been_repaired." 	<br>";
				
				
			// Utilize our lies
			?>
				<table border="0" cellpadding="0" cellspacing="0" width="100%" height="100%" class="table_dashpanel_divwindow" />
					<tr>
						<td />
							<?php
							include("includes/_template/_tp_blockform_workorder_browser.binc.php");	
							?>
							<?php
							include_ONCE("includes/_template/template.list.php");
							$settingsarray 	= array("SELECT * FROM tbl_139_327_sub_d_a WHERE discrepancy_archeived_inspection_id = ",	"discrepancy",	"part139327_discrepancy_report_display_archived.php");
							$functionpage	= "part139327_discrepancy_report_archieved.php";														
							_tp_control_archived($objarray['Discrepancy_id'], $settingsarray, $functionpage);
							$settingsarray 	= array("SELECT * FROM tbl_139_327_sub_d_d WHERE discrepancy_duplicate_inspection_id = ",	"discrepancy",	"part139327_discrepancy_report_display_duplicate.php");
							$functionpage	= "part139327_discrepancy_report_duplicate.php";														
							_tp_control_duplicate($objarray['Discrepancy_id'], $settingsarray, $functionpage);
							$settingsarray 	= array("SELECT * FROM tbl_139_327_sub_d_e WHERE discrepancy_error_inspection_id = ",	"discrepancy",	"part139327_discrepancy_report_display_error.php");
							$functionpage	= "part139327_discrepancy_report_error.php";														
							_tp_control_error($objarray['Discrepancy_id'], $settingsarray, $functionpage);	
							?>
							</td>
						</tr>
					</table>
				</div>
			</td>
		<tr>
			<td name="opencommands_for_discrepancy<?php echo $disid;?>" id="opencommands_for_discrepancy<?php echo $disid;?>" 
				class="item_name_inactive" 
				onmouseover="opencommands_for_discrepancy<?php echo $disid;?>.className='item_name_active';" 
				onmouseout="opencommands_for_discrepancy<?php echo $disid;?>.className='item_name_inactive';" 
				onclick="divwin=dhtmlwindow.open('discrepancycontrol_div<?php echo $disid;?>', 'div', '327d_control_<?php echo $tmpdiscrepancyid;?>', 'Discrepancy Options for Discrepancy <?php echo $disid;?>', 'width=350px,height=150px,left=200px,top=150px,resize=0,scrolling=0,center=1'); return false;" />
				Commands
				</td>
			</tr>
												<?php
											}
									}	
							}
							?>
	<tr>
		<td colspan="2" class='table_dashpanel_container_footer' />	
			&nbsp;
			</td>
		</tr>
					<?php						
					}
			}
		?>
	</table>
	</div>
	<?php
	}
?>