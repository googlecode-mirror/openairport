<?php

function systemusercomboboxwall($user_id, $archived, $nameofinput, $showcombobox, $default) {
	// This function is called when you need to make a combo box which lists the susyem users for selection. 
	// you may limit the search to a specific userid, or archived or non archived system users
	// you also need to specify the name of the input
	$sql	= "";
	$nsql 	= "";
	
	$sql = "SELECT * FROM `tbl_systemusers` ";

	if ($user_id=="all") {
			// do not add any employee ID information to the SQL String
			$tmp_flagger = 0;
		}
		else {
			$nsql = "WHERE `emp_record_id` = ".$user_id." ";
			$sql = $sql.$nsql;
			$tmp_flagger = 1;
		}

	if ($archived == "all") {
			// Do not add any systemuser archived information to the SQL string
		}
		else {
			if ($archived=="yes") {
					if ($tmp_flagger==0) {
							$nsql = "WHERE tbl_systemusers.emp_archieved_yn = -1 AND emp_record_id <> 65 ";
							$sql = $sql.$nsql;
							$tmp_flagger = 1;
						}
						else {
							$nsql = "AND Table_employee_listing.emp_archieved_yn = -1 AND emp_record_id <> 65 ";
							$sql = $sql.$nsql;
						}
				}
				else {
					if ($tmp_flagger==0) {
							$nsql = "WHERE tbl_systemusers.emp_archieved_yn = 0 AND emp_record_id <> 65 ";
							$sql = $sql.$nsql;
							$tmp_flagger = 1;
						}
						else {
							$nsql = "AND tbl_systemusers.emp_archieved_yn = 0 AND emp_record_id <> 65 ";
							$sql = $sql.$nsql;
						}
				}
		}
	//echo $sql;
	
	$objconn_support = mysqli_connect($GLOBALS['hostdomain'], $GLOBALS['hostusername'], $GLOBALS['passwordofdatabase'], $GLOBALS['nameofdatabase']);

	if (mysqli_connect_errno()) {
			printf("connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		else {
			$objrs_support = mysqli_query($objconn_support, $sql);
			if ($objrs_support) {
					$number_of_rows = mysqli_num_rows($objrs_support);
					//printf("result set has %d rows. \n", $number_of_rows);
					if ($showcombobox=="show") {
							?>
	<SELECT class="Commonfieldbox" name="<?php echo $nameofinput;?>">
		<option value="all">All Users</option>
					<?php
						}
					while ($objfields = mysqli_fetch_array($objrs_support, MYSQLI_ASSOC)) {
							$tmpfirstname 	= $objfields['emp_firstname'];
							$tmplastname 	= $objfields['emp_lastname'];
							$tmpinitials	= $objfields['emp_initials'];
							$tmpuserid 		= $objfields['emp_record_id'];
						if ($showcombobox=="show") {
								?>
		<option 
								<?php
							}
							if ($user_id = "all") {
									$intuserid	= (double) $default;
									if ($tmpuserid == $intuserid) {
											if ($showcombobox=="show") {
													?>
				SELECTED
													<?php
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
				value="<?php echo $tmpuserid;?>"><?php echo $tmpfirstname;?>&nbsp;&nbsp;<?php echo $tmplastname;?>&nbsp;&nbsp;(<?php echo $tmpinitials;?>)</option>
										<?php
									}
									else {
										?>
				<?php echo $tmpfirstname;?>&nbsp;&nbsp;<?php echo $tmplastname;?>&nbsp;&nbsp;(<?php echo $tmpinitials;?>)
										<?php
									}
								}	// End of while loop
								mysqli_free_result($objrs_support);
								mysqli_close($objconn_support);
								if ($showcombobox=="show") {
										?>
		</SELECT>
										<?php
									}
						}	// end of Res Record Object						
				}
	return $tmpfirstname." ".$tmplastname." ".$tmpinitials;
	}
	?>