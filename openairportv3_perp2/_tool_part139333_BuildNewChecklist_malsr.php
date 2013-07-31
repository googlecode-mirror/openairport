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
//	Name of Document		:	part139333_report_browse.php
//
//	Purpose of Page			:	Browse Part 139.333 NavAid Inspection Records
//
//	Special Notes			:	Change the information here for your airport.
//
//==============================================================================================
//2345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345
//		  1		    2		  3		    4		  5		    6		  7		    7	      8	

// Load Global Include Files
	
		include("includes/_template_header.php");										// This include 'header.php' is the main include file which has the page layout, css, AND functions all defined.
		include("includes/POSTs.php");													// This include pulls information from the $_POST['']; variable array for use on this page
	
// Load Page Specific Includes

		include("includes/_modules/part139333/part139333.list.php");

// Define Variables	for Auto Entry Function
		
		$navigation_page 			= 19;							// Belongs to this Nav Item ID, see function for notes!
		$type_page 					= 15;							// Page is Type ID, see function for notes!
		$date_to_display_new		= AmerDate2SqlDateTime(date('m/d/Y'));
		$time_to_display_new		= date("H:i:s");

// Start Process

	// [1]. Loop through all equipment of type 1 (PAPI)
	//		[1.a.].	Loop through all active facility types
	//		[1.b.].	Assemble a relevent condition name and ID string
	//		[1.c.].	INSERT new record into the table

// [1].

	$inspection_name = 'MALSR Inspection';

	$sql	= "SELECT * FROM tbl_inventory_sub_e WHERE equipment_serialnumber_b = 'UseME'";
	$objconn = mysqli_connect($GLOBALS['hostdomain'], $GLOBALS['hostusername'], $GLOBALS['passwordofdatabase'], $GLOBALS['nameofdatabase']);
				
	if (mysqli_connect_errno()) {
			// there was an error trying to connect to the mysql database
			printf("connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		else {
			$objrs = mysqli_query($objconn, $sql);
						
			if ($objrs) {
					$number_of_rows = mysqli_num_rows($objrs);
					while ($objarray = mysqli_fetch_array($objrs, MYSQLI_ASSOC)) {	
					
							$equipment_id	= $objarray['equipment_id'];
							$equipment_name	= $objarray['equipment_name'];
					
							$sql2		= "SELECT * FROM tbl_139_333_sub_c_f WHERE facility_archived_yn = 0";
							$objconn2 	= mysqli_connect($GLOBALS['hostdomain'], $GLOBALS['hostusername'], $GLOBALS['passwordofdatabase'], $GLOBALS['nameofdatabase']);	
							if (mysqli_connect_errno()) {
										// there was an error trying to connect to the mysql database
										printf("connect failed: %s\n", mysqli_connect_error());
										exit();
									}
									else {
										$objrs2 = mysqli_query($objconn2, $sql2);
													
										if ($objrs2) {
												$number_of_rows2 	= mysqli_num_rows($objrs2);
												while ($objarray2 	= mysqli_fetch_array($objrs2, MYSQLI_ASSOC)) {	

														$facility_id	= $objarray2['facility_id'];
														$facility_name	= $objarray2['facility_name'];
														
														if($facility_id == 6 OR $facility_id == 7 OR $facility_id == 19 OR $facility_id == 16 OR $facility_id == 21 OR $facility_id == 23 OR $facility_id == 24) {
														
																$condition_name = 'Check '.$facility_name.' of '.$equipment_id.' '.$inspection_name.''; 

																// INSERT RECORD
																
																$sql3 		= "INSERT INTO tbl_139_333_sub_c (condition_name,condition_facility_cb_int,condition_type_cb_int,condition_tied_id) 
																				VALUES ( '".$condition_name."', '".$facility_id."', '2', '".$equipment_id."')";
																$objcon3 	= mysqli_connect($GLOBALS['hostdomain'], $GLOBALS['hostusername'], $GLOBALS['passwordofdatabase'], $GLOBALS['nameofdatabase']);
			
																//echo $sql3."<br><br>";
																if (mysqli_connect_errno()) {
																		// there was an error trying to connect to the mysql database
																		printf("connect failed: %s\n", mysqli_connect_error());
																		exit();
																	}		
																	else {
																		//mysql_insert_id();
																		$objrs3 	= mysqli_query($objcon3, $sql3) or die(mysqli_error($objcon3));
																		$lastchkid 	= mysqli_insert_id($objcon3);
																	}
															}
													}
											}
									}
							}
					}
			}
	?>