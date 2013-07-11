<?
//		 1		   2		 3		   4		 5		   6		 7		   8	     9		   
//3456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789
//==============================================================================================
//	
//	oooo	o   o	 ooo	ooooo
//	o	o	o	o	o	o	  o
//	o	o	o	o	o	o	  o
//	oooo	o   o	ooooo	  o	
//	o  o	o	o	o	o	  o
//	o	o	o	o	o	o	  o
//	o	o	ooooo	o   o	  o
//
//	The "Are You a Terrorist?" (RUAT) system.
//
//	Designed, Coded, and Supported by Erick Alan Dahl
//
//	Copywrite 2008 - Erick Alan Dahl
//
//	~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~*~
//	
//	Name of Document	:	_addnewcustomsearch.php
//
//	Purpose of Page		:	FORM SIDE 	- Allows User to Create and Save Searchs
//							SERVER SIDE - Takes the users input and searchs the database for any matchs
//
//==============================================================================================
//3456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789
//		 1		   2		 3		   4		 5		   6		 7		   8	     9	

// Prevent System From Timeing out while doing a search of the database	   
		set_time_limit(0);
		
// Include Files nessary to complete any tasks assigned.		
		include("includes/generalsettings.php");
		include("includes/functions.php");
		include("includes/interface.php");
		
// Establish Page Name			
		$pagename = 'Add New Custom Search';
		
// Check to see if a user is currently logged Into the System.  We do this to prevent direct linking
//	to the document. If someone tries to direct link to the page, the broweser will show a 404 error.
		//echo "Session ID is [".$_SESSION['user_id'],"]";
		if ($_SESSION['user_id'] == '') {
				//echo "There has been a request for this page outside of normal operating procedures<br>";
				send_404();
			}
			else {
				//echo "The request for this page seems to be in order, allow page to be displayed <br>";
				?>
<HTML>
	<HEAD>
		<TITLE>
			Transportation Security Administration SSI - Manage Search Queries
			</TITLE>
		<link rel="stylesheet" href="scripts/style.css" type="text/css">
		</HEAD>
	<BODY >
		<?
		// This Div layer will show a precent completed of the current search query. It relates directly 
		//	to the procedures that follow the While loop.
		?>
		<div id="myDiv" style="display:none; position:absolute; z-index:9; left:0; top:0; width:302;">
			<table width="100%" Class="windowMain" CELLSPACING=1 CELLPADDING=2>
				<tr>
					<td class="newTopicTitle">Search Query Progress</td>
					</tr>
				<tr>
					<td class="newTopicBoxes">
						<div id="myDiv_bar" style="display:none; width:0; background-color: #7198BF;background-image:url('images/animated_timer_bar.gif'); background-repeat:no-repeat;background-position: left center;">
							<table>
								<tr>
									<td class="newTopicTitle" id="percent" align="center" valign="middle">
										&nbsp;
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>				
				<tr>
					<td colspan="2" class="newTopicEnder">&nbsp;</td>
					</tr>
				</table>
			</div>
		<table width="100%" Class="windowMain" CELLSPACING=1 CELLPADDING=2>
<?
$time_b 	= timer();		
if ($_POST['submitform']==1) {
		?>
			<tr>
				<td colspan="12">
					<?
					breadcrumbs('_addnewcusotmsearch.php', 'Here are your query results');
					put_systemactivity($_SESSION["user_id"],'Results from Add New Search Query are displayed');
					?>
					</td>
				</tr>
			<tr>
				<td colspan="12" class="newTopicTitle">
					Here are your search query results
					</td>
				</tr>
			<tr>
				<td colspan="12" class="newTopicBoxes">
					<p>
						Listed here are is a comprehensive list of all people on a watch list that 
						meet the critera you set in your search. Please look over the list carefully
						to see if there is a match. Depending on the type of query you ran, there may 
						people on this list that look like matches but are listed because they share 
						a common part of their name or pass some other logical test.
						</p>
					</td>
				</tr>
			<tr>
				<td colspan="12" class="newTopicBoxes">
					<table>
						<tr>						
							<form action="_managesearch.php" METHOD="POST" target="layouttableiframecontent" style="margin: 0px; margin-bottom:0px; margin-top:-1px;">
							<td class="newTopicBoxes">
								<input type="hidden" name="userid" 	value="<?=$_SESSION["user_id"];?>">
								<input type="hidden" name="displaylist" value="<?=$_POST['displaylist'];?>">
								<input type="hidden" name="displaypage" value="<?=$_POST['displaypage'];?>">
								<input type="submit" name="submit"	value="<<< Back" class="button">
								</td>
								</form>
							</tr>
						</table>
					</td>
				</tr>	
		<?
		// Looks like the user has submited the search for. Now the system has to develope a way to 
		//	search the watch lists and come up with some results. This is a multi-step process and 
		//	at times it is a little trying.
		
		// STEP ONE: 
				//	We need the name of the file the user uploaded to compare the watch lists against. 
				//	Pretty basec SQL Statement.		

				// Is user using their own CSV file or are they defining a new person?
				
				$definedname	= $_POST['usecsvfile'];
				
				if ($definedname == "YES") {
						// User is using their CSV on file. Use those settings
				
						$sql = "SELECT * FROM tbl_users_files WHERE user_f_parent_id = '".$_SESSION["user_id"]."' LIMIT 1";
						$objconn_support = mysqli_connect("localhost", "webuser", "limitaces", "tsa_ruat");
						if (mysqli_connect_errno()) {
								printf("connect failed: %s\n", mysqli_connect_error());
								exit();
							}
							else {
								$objrs_support = mysqli_query($objconn_support, $sql);
								if ($objrs_support) {
										$number_of_rows = mysqli_num_rows($objrs_support);
										if ($number_of_rows == 0) {
												?>
												Sorry, you do not have a file on record to compare the lists to.
												<?
											}
										while ($objfields = mysqli_fetch_array($objrs_support, MYSQLI_ASSOC)) {
												$filename = $objfields['user_f_name'];
												//echo "File is ".$filename."<br>";
											}
									}
									mysqli_free_result($objrs_support);
									mysqli_close($objconn_support);
							}
					
						// 	Now tat we have the name of the users CSV file. We need to do some caclulations 
						//	to determine the number of rows in the file and establish a connection to the file.
		
						$fcontents 			= file ($filename); 									// fcontents is the file object
						$tmp_sizeoffile		= sizeof($fcontents);									// tmp_sizeoffile is the total number of rows in the file
						$known_sids 		= 0;													// Depreciated, on path to deletion.
						$counter			= 0;													// Depreciated, on path to deletion.
						$totalrowsprocessed = 0;													// Depreciated, on path to deletion.
						$nsql				= '';													// Used to assemble the SQL Statement. Listed here to remind us it is a string.
						//echo "The User's CSV file has ".$tmp_sizeoffile." rows <br>";
						//echo "The Counter has a value of [".$counter."] <br>";
						//echo "The NSQL has a value of [".$nsql."] <br>";
					}
					else {					
						// User is providing us with a new name to compare to
						
						$tmp_last			= $_POST['definedname_last'];
						$tmp_middle			= $_POST['definedname_middle'];
						$tmp_first			= $_POST['definedname_first'];
						
						//echo "Defined Name (Last) is ".$tmp_last." <br>";
						//echo "Defined Name (Middle) is ".$tmp_middle." <br>";
						//echo "Defined Name (Frist) is ".$tmp_first." <br>";
						
						$tmp_sizeoffile 	= 1;
						$fcontents			= 1;
						$known_sids 		= 0;													// Depreciated, on path to deletion.
						$counter			= 0;													// Depreciated, on path to deletion.
						$totalrowsprocessed = 0;													// Depreciated, on path to deletion.
						$nsql				= '';													// Used to assemble the SQL Statement. Listed here to remind us it is a string.
						
					}
						
						
						
						
						
						
		// STEP TWO: 
				//	With Step One completed, we now need to set some variables based on the type of 
				//	watch list the user has selected to compare their list to.						
						
						$tmp_liststocheck = $_POST['group1'];										// Radio Group for The Watch list Selection
						$tmp_typeoflisttc = $_POST['group4'];										// Radio Group for The Watch list Selection
						//echo "Group 4 is ".$tmp_typeoflisttc."<br>";
						//echo "Group 1 is ".$tmp_liststocheck." <br>";
		
		
						switch ($tmp_liststocheck) {
								case "NOFLY":
										switch ($tmp_typeoflisttc) {
												case "FULL":								
														$scobby				= 1;										// Important for Progress Bar Procress
														$array_tables[0] 	= 'tbl_nofly_list';							// The name of the table in the database where the information is stored
														$totalsize			= getnumberoflistrows($array_tables[0]);	// The total number of rows in the selected watch list.								
														break;
												case "ADD":
														//echo "You have selected ".$tmp_liststocheck." ".$tmp_typeoflisttc." <br>";
														$scobby				= 1;										// Important for Progress Bar Procress
														$array_tables[0] 	= 'tbl_nofly_add_list';						// The name of the table in the database where the information is stored
														$totalsize			= getnumberoflistrows($array_tables[0]);	// The total number of rows in the selected watch list.								
														break;
												default:
														//echo "There was a problem with your NO FLY input. You should not see this<br>";
														$scobby				= 1;										// Important for Progress Bar Procress
														$array_tables[0] 	= 'tbl_nofly_list';							// The name of the table in the database where the information is stored
														$totalsize			= getnumberoflistrows($array_tables[0]);	// The total number of rows in the selected watch list.								
														break;
											}
										break;
								case "SELECTEE":
											switch ($tmp_typeoflisttc) {
												case "FULL":								
														$scobby				= 1;										// Important for Progress Bar Procress
														$array_tables[0] 	= 'tbl_selectee_list';							// The name of the table in the database where the information is stored
														$totalsize			= getnumberoflistrows($array_tables[0]);	// The total number of rows in the selected watch list.								
														break;
												case "ADD":
														$scobby				= 1;										// Important for Progress Bar Procress
														$array_tables[0] 	= 'tbl_selectee_add_list';						// The name of the table in the database where the information is stored
														$totalsize			= getnumberoflistrows($array_tables[0]);	// The total number of rows in the selected watch list.								
														break;
												default:
														$scobby				= 1;										// Important for Progress Bar Procress
														$array_tables[0] 	= 'tbl_selectee_list';							// The name of the table in the database where the information is stored
														$totalsize			= getnumberoflistrows($array_tables[0]);	// The total number of rows in the selected watch list.								
														break;
											}
										break;
								case "CLEARED":
											switch ($tmp_typeoflisttc) {
												case "FULL":								
														$scobby				= 1;										// Important for Progress Bar Procress
														$array_tables[0] 	= 'tbl_cleared_list';							// The name of the table in the database where the information is stored
														$totalsize			= getnumberoflistrows($array_tables[0]);	// The total number of rows in the selected watch list.								
														break;
												case "ADD":
														$scobby				= 1;										// Important for Progress Bar Procress
														$array_tables[0] 	= 'tbl_cleared_add_list';						// The name of the table in the database where the information is stored
														$totalsize			= getnumberoflistrows($array_tables[0]);	// The total number of rows in the selected watch list.								
														break;
												default:
														$scobby				= 1;										// Important for Progress Bar Procress
														$array_tables[0] 	= 'tbl_cleared_list';							// The name of the table in the database where the information is stored
														$totalsize			= getnumberoflistrows($array_tables[0]);	// The total number of rows in the selected watch list.								
														break;
											}
										break;
								case "ALL":
											switch ($tmp_typeoflisttc) {
												case "FULL":								
														$scobby				= 3;
														$array_tables[0] 	= 'tbl_nofly_list';
														$array_tables[1] 	= 'tbl_selectee_list';
														$array_tables[2] 	= 'tbl_cleared_list';
														$a					= getnumberoflistrows($array_tables[0]);
														//echo "There are ".$a." rows in the no fly list <br>";
														$b					= getnumberoflistrows($array_tables[1]);
														//echo "There are ".$b." rows in the selectee list <br>";
														$c					= getnumberoflistrows($array_tables[2]);
														//echo "There are ".$c." rows in the cleared list <br>";
														$totalsize			= ($a + $b + $c);
														break;
												case "ADD":
														//echo "You have selected to display all lists searching just the ADD files <br>";
														$scobby				= 3;
														$array_tables[0] 	= 'tbl_nofly_add_list';
														$array_tables[1] 	= 'tbl_selectee_add_list';
														$array_tables[2] 	= 'tbl_cleared_add_list';
														$a					= getnumberoflistrows($array_tables[0]);
														$b					= getnumberoflistrows($array_tables[1]);
														$c					= getnumberoflistrows($array_tables[2]);
														$totalsize			= ($a + $b + $c);		
														break;
												default:
														//echo "You have selected to display ALL Lists with no preset list length <br>";
														$scobby				= 3;
														$array_tables[0] 	= 'tbl_nofly_list';
														$array_tables[1] 	= 'tbl_selectee_list';
														$array_tables[2] 	= 'tbl_cleared_list';
														$a					= getnumberoflistrows($array_tables[0]);
														//echo "There are ".$a." rows in the no fly list <br>";
														$b					= getnumberoflistrows($array_tables[1]);
														//echo "There are ".$b." rows in the selectee list <br>";
														$c					= getnumberoflistrows($array_tables[2]);
														//echo "There are ".$c." rows in the cleared list <br>";
														$totalsize			= ($a + $b + $c);
														break;
											}
										break;
								default:															// If for whatever reason the other three dont catch...
										//echo "There was a problem with all of your input. You should not see this <br>";
										$scobby				= 3;
										$array_tables[0] 	= 'tbl_nofly_list';
										$array_tables[1] 	= 'tbl_selectee_list';
										$array_tables[2] 	= 'tbl_cleared_list';
										$a					= getnumberoflistrows($array_tables[0]);
										//echo "There are ".$a." rows in the no fly list <br>";
										$b					= getnumberoflistrows($array_tables[1]);
										//echo "There are ".$b." rows in the selectee list <br>";
										$c					= getnumberoflistrows($array_tables[2]);
										//echo "There are ".$c." rows in the cleared list <br>";
										$totalsize			= ($a + $b + $c);
										break;
							}
							
				//	Watch list values have now been set. Now setting the total number of rows we can 
				//	expect to have to search through reguardless if we find a match or not.
		
						$totalexpectedcycles = ($tmp_sizeoffile * $totalsize); 										// Math says that given a certain number of people in list A, and a certain number in list a,b,c or all, the product of the two lists will give the number of rows to search.
						//echo "Total Number of Expected Cycles is ".$totalexpectedcycles." <br>";
						//echo "This was calcuated by (tmp_sizeoffile * totalsize) or (".$tmp_sizeoffile." * ".$totalsize.") <br>";
		
				// Display some debugging code if needed.
						//echo "Table Array [0] ".$array_tables[0]."<br>";
						//echo "Table Array [1] ".$array_tables[1]."<br>";
						//echo "Table Array [2] ".$array_tables[2]."<br>";
						//echo "There are ".$totalsize." rows in the watch list(s)<br>";
						//echo "We can expect to have to compare a total of ".$totalexpectedcycles." rows<br>";
						
		// STEP THREE: 
				//	With Step One and Step Two completed we have all trhe information we need to 
				//	start earching through the watch lists to see if there are any matchs to the 
				//	users CSV.
				
				//	Given that the user could have selected up to three different lists in three 
				//	seperate tables, we have no choice but to assume the user selected three and 
				//	loop through each of the lists as set in Step Two.
										
						for($k=0; $k<sizeof($array_tables); $k++) {									// The Big Picture Loop, Loops through 1 to 3 tables of data.
								$known_sids 		= 0;											// Depreciated, on path to deletion.
								$counter			= 0;											// Depreciated, on path to deletion.
								
				// Create the header row for this new Table	
								?>
			<tr>
				<td class="newTopicTitle">
					<?=$array_tables[$k];?>
					</td>
				<td class="newTopicTitle">
					SID
					</td>
				<td class="newTopicTitle">
					CLEARED
					</td>
				<td class="newTopicTitle">	
					LAST NAME
					</td>
				<td class="newTopicTitle">
					FIRST NAME
					</td>
				<td class="newTopicTitle">
					MIDDLE NAME
					</td>
				<td class="newTopicTitle">
					TYPE
					</td>
				<td class="newTopicTitle">
					DOB
					</td>
				<td class="newTopicTitle">
					POB
					</td>
				<td class="newTopicTitle">
					Citizanship
					</td>
				<td class="newTopicTitle">
					PASSPORT
					</td>
				<td class="newTopicTitle">
					MISC
					</td>
				</tr>
			<tr>
				<td colspan="12" class="newTopicBoxes">
					If the progress bar has completed to 100% and the window has disapeared, and no results are shown below. Then there are no matchs for your search.
					</td>
				</tr>
								<?
					
					// 	Inside of each table we need to compare the User's CSV to the People in the 
					//	given list. This is done via another for loop. For each member in the USV, Do.
								
								for($i=0; $i<sizeof($fcontents); $i++) {
										
										if ($definedname == "YES") {
												// Use file settings								
												$line 				= trim($fcontents[$i],',');
												//$arr 				= explode(",",$line);												
												$arr				= splitWithEscape($line);
												$tmp_last			= addslashes($arr[0]);
												$tmp_first			= addslashes($arr[1]);
												$tmp_misc 			= $arr[8];
												$tmp_misc			= trim($tmp_misc);
												//echo "------------------------------------------------------------------<br>";
												//echo "New User Row in CSV, This is row [".$i."] of the CSV file <br>";
												//echo "RAW Data in this row is ".$line." <br>";
												//echo "Placing all elements of the row into an array <br>";
												//echo "Treating the 8th element of the array a little different <br>";
												//echo "The Array we will compare to the Watch list is ('".$arr[0]."','".$arr[1]."','".$arr[2]."','".$arr[3]."','".$arr[4]."','".$arr[5]."','".$arr[6]."','".$arr[7]."','".$tmp_misc."') <br>"; 
											}
											else {
												$arr[0]	= $tmp_last;
												$arr[1]	= $tmp_first;
												$arr[2]	= $tmp_middle;
											}
					//	Now we assemble the SQL Statement for this Search based on the information 
					//	in the users CSV file. Doing the SQL this way allows us to create a different 
					//	SQL statement for each row in the CSV. Important for fucntions latter in the program.										
										
										switch ($_POST['lastname']) {
												case "=":
														$nsql 		= $nsql."`ruat_tsa_last_name` = '".$arr[0]."' ";
														$existing 	= 1;
														break;
												case "!=":
														$nsql 		= $nsql."`ruat_tsa_last_name` != '".$arr[0]."' ";
														$existing 	= 1;
														break;
												case "LIKE":
														$nsql 		= $nsql.'`ruat_tsa_last_name` LIKE CONVERT(_utf8 \'%'.$arr[0].'%\' USING latin1) COLLATE latin1_swedish_ci ';
														$existing 	= 1;
														break;								
												case "NOT LIKE":
														$nsql 		= $nsql.'`ruat_tsa_last_name` NOT LIKE CONVERT(_utf8 \''.$arr[0].'\' USING latin1) COLLATE latin1_swedish_ci ';
														$existing 	= 1;
														break;
												default:
														// Do not do anything with this SQL Statement
														//$existing = 0;
														break;														
											}
										switch ($_POST['firstname_xor']) {
												// Determine additional XOR type
												case "AND":
														$nsql 		= $nsql."AND ";
														switch ($_POST['firstname']) {
																case "=":
																		$nsql 		= $nsql."`ruat_tsa_first_name` = '".$arr[1]."' ";
																		$existing 	= 1;
																		break;
																case "!=":
																		$nsql 		= $nsql."`ruat_tsa_first_name` != '".$arr[1]."' ";
																		$existing 	= 1;
																		break;
																case "LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_first_name` LIKE CONVERT(_utf8 \'%'.$arr[1].'%\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;								
																case "NOT LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_first_name` NOT LIKE CONVERT(_utf8 \''.$arr[1].'\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;
																default:
																		// Do not do anything with this SQL Statement
																		//$existing = 0;
																		break;														
															}
														break;
												case "OR":
														$nsql 		= $nsql."OR ";
														switch ($_POST['firstname']) {
																case "=":
																		$nsql 		= $nsql."`ruat_tsa_first_name` = '".$arr[1]."' ";
																		$existing 	= 1;
																		break;
																case "!=":
																		$nsql 		= $nsql."`ruat_tsa_first_name` != '".$arr[1]."' ";
																		$existing 	= 1;
																		break;
																case "LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_first_name` LIKE CONVERT(_utf8 \'%'.$arr[1].'%\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;								
																case "NOT LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_first_name` NOT LIKE CONVERT(_utf8 \''.$arr[1].'\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;
																default:
																		// Do not do anything with this SQL Statement
																		//$existing = 0;
																		break;												
															}								
														break;
												default:
														// Do not do anything with this SQL Statement
														//$existing = 0;
														break;
											}
										switch ($_POST['middlename_xor']) {
												// Determine additional XOR type
												case "AND":
														$nsql 		= $nsql."AND ";
														switch ($_POST['middlename']) {
																case "=":
																		$nsql 		= $nsql."`ruat_tsa_middle_name` = '".$arr[2]."' ";
																		$existing 	= 1;
																		break;
																case "!=":
																		$nsql 		= $nsql."`ruat_tsa_middle_name` != '".$arr[2]."' ";
																		$existing 	= 1;
																		break;
																case "LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_middle_name` LIKE CONVERT(_utf8 \'%'.$arr[2].'%\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;								
																case "NOT LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_middle_name` NOT LIKE CONVERT(_utf8 \''.$arr[2].'\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;
																default:
																		// Do not do anything with this SQL Statement
																		//$existing = 0;
																		break;														
															}
														break;
												case "OR":
														$nsql 		= $nsql."OR ";
														switch ($_POST['middlename']) {
																case "=":
																		$nsql 		= $nsql."`ruat_tsa_middle_name` = '".$arr[2]."' ";
																		$existing 	= 1;
																		break;
																case "!=":
																		$nsql 		= $nsql."`ruat_tsa_middle_name` != '".$arr[2]."' ";
																		$existing 	= 1;
																		break;
																case "LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_middle_name` LIKE CONVERT(_utf8 \'%'.$arr[2].'%\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;								
																case "NOT LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_middle_name` NOT LIKE CONVERT(_utf8 \''.$arr[2].'\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;
																default:
																		// Do not do anything with this SQL Statement
																		//$existing = 0;
																		break;												
															}								
														break;
												default:
														// Do not do anything with this SQL Statement
														//$existing = 0;
														break;
											}
										switch ($_POST['type_xor']) {
												// Determine additional XOR type
												case "AND":
														$nsql 		= $nsql."AND ";
														switch ($_POST['type']) {
																case "=":
																		$nsql 		= $nsql."`ruat_tsa_last_POB` = '".$arr[3]."' ";
																		$existing 	= 1;
																		break;
																case "!=":
																		$nsql 		= $nsql."`ruat_tsa_last_POB` != '".$arr[3]."' ";
																		$existing 	= 1;
																		break;
																case "LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_last_POB` LIKE CONVERT(_utf8 \'%'.$arr[3].'%\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;								
																case "NOT LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_last_POB` NOT LIKE CONVERT(_utf8 \''.$arr[3].'\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;
																default:
																		// Do not do anything with this SQL Statement
																		//$existing = 0;
																		break;														
															}
														break;
												case "OR":
														$nsql 		= $nsql."OR ";
														switch ($_POST['type']) {
																case "=":
																		$nsql 		= $nsql."`ruat_tsa_last_POB` = '".$arr[3]."' ";
																		$existing 	= 1;
																		break;
																case "!=":
																		$nsql 		= $nsql."`ruat_tsa_last_POB` != '".$arr[3]."' ";
																		$existing 	= 1;
																		break;
																case "LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_last_POB` LIKE CONVERT(_utf8 \'%'.$arr[3].'%\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;								
																case "NOT LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_last_POB` NOT LIKE CONVERT(_utf8 \''.$arr[3].'\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;
																default:
																		// Do not do anything with this SQL Statement
																		//$existing = 0;
																		break;												
															}								
														break;
												default:
														// Do not do anything with this SQL Statement
														//$existing = 0;
														break;
											}
										switch ($_POST['dob_xor']) {
												// Determine additional XOR type
												case "AND":
														$nsql 		= $nsql."AND ";
														switch ($_POST['dob']) {
																case "=":
																		$nsql 		= $nsql."`ruat_tsa_DOB` = '".$arr[4]."' ";
																		$existing 	= 1;
																		break;
																case "!=":
																		$nsql 		= $nsql."`ruat_tsa_DOB` != '".$arr[4]."' ";
																		$existing 	= 1;
																		break;
																case "LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_DOB` LIKE CONVERT(_utf8 \'%'.$arr[4].'%\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;								
																case "NOT LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_DOB` NOT LIKE CONVERT(_utf8 \''.$arr[4].'\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;
																default:
																		// Do not do anything with this SQL Statement
																		//$existing = 0;
																		break;														
															}
														break;
												case "OR":
														$nsql 		= $nsql."OR ";
														switch ($_POST['dob']) {
																case "=":
																		$nsql 		= $nsql."`ruat_tsa_DOB` = '".$arr[4]."' ";
																		$existing 	= 1;
																		break;
																case "!=":
																		$nsql 		= $nsql."`ruat_tsa_DOB` != '".$arr[4]."' ";
																		$existing 	= 1;
																		break;
																case "LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_DOB` LIKE CONVERT(_utf8 \'%'.$arr[4].'%\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;								
																case "NOT LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_DOB` NOT LIKE CONVERT(_utf8 \''.$arr[4].'\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;
																default:
																		// Do not do anything with this SQL Statement
																		//$existing = 0;
																		break;												
															}								
														break;
												default:
														// Do not do anything with this SQL Statement
														//$existing = 0;
														break;
											}
										switch ($_POST['pob_xor']) {
												// Determine additional XOR type
												case "AND":
														$nsql 		= $nsql."AND ";
														switch ($_POST['pob']) {
																case "=":
																		$nsql 		= $nsql."`ruat_tsa_last_POB` = '".$arr[5]."' ";
																		$existing 	= 1;
																		break;
																case "!=":
																		$nsql 		= $nsql."`ruat_tsa_last_POB` != '".$arr[5]."' ";
																		$existing 	= 1;
																		break;
																case "LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_last_POB` LIKE CONVERT(_utf8 \'%'.$arr[5].'%\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;								
																case "NOT LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_last_POB` NOT LIKE CONVERT(_utf8 \''.$arr[5].'\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;
																default:
																		// Do not do anything with this SQL Statement
																		//$existing = 0;
																		break;														
															}
														break;
												case "OR":
														$nsql 		= $nsql."OR ";
														switch ($_POST['pob']) {
																case "=":
																		$nsql 		= $nsql."`ruat_tsa_last_POB` = '".$arr[5]."' ";
																		$existing 	= 1;
																		break;
																case "!=":
																		$nsql 		= $nsql."`ruat_tsa_last_POB` != '".$arr[5]."' ";
																		$existing 	= 1;
																		break;
																case "LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_last_POB` LIKE CONVERT(_utf8 \'%'.$arr[5].'%\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;								
																case "NOT LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_last_POB` NOT LIKE CONVERT(_utf8 \''.$arr[5].'\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;
																default:
																		// Do not do anything with this SQL Statement
																		//$existing = 0;
																		break;												
															}								
														break;
												default:
														// Do not do anything with this SQL Statement
														//$existing = 0;
														break;
											}
										switch ($_POST['citizanship_xor']) {
												// Determine additional XOR type
												case "AND":
														$nsql 		= $nsql."AND ";
														switch ($_POST['citizanship']) {
																case "=":
																		$nsql 		= $nsql."`ruat_tsa_citizanship` = '".$arr[6]."' ";
																		$existing 	= 1;
																		break;
																case "!=":
																		$nsql 		= $nsql."`ruat_tsa_citizanship` != '".$arr[6]."' ";
																		$existing 	= 1;
																		break;
																case "LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_citizanship` LIKE CONVERT(_utf8 \'%'.$arr[6].'%\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;								
																case "NOT LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_citizanship` NOT LIKE CONVERT(_utf8 \''.$arr[6].'\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;
																default:
																		// Do not do anything with this SQL Statement
																		//$existing = 0;
																		break;														
															}
														break;
												case "OR":
														$nsql 		= $nsql."OR ";
														switch ($_POST['citizanship']) {
																case "=":
																		$nsql 		= $nsql."`ruat_tsa_citizanship` = '".$arr[6]."' ";
																		$existing 	= 1;
																		break;
																case "!=":
																		$nsql 		= $nsql."`ruat_tsa_citizanship` != '".$arr[6]."' ";
																		$existing 	= 1;
																		break;
																case "LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_citizanship` LIKE CONVERT(_utf8 \'%'.$arr[6].'%\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;								
																case "NOT LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_citizanship` NOT LIKE CONVERT(_utf8 \''.$arr[6].'\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;
																default:
																		// Do not do anything with this SQL Statement
																		//$existing = 0;
																		break;												
															}								
														break;
												default:
														// Do not do anything with this SQL Statement
														//$existing = 0;
														break;
											}
										switch ($_POST['passport_xor']) {
												// Determine additional XOR type
												case "AND":
														$nsql 		= $nsql."AND ";
														switch ($_POST['passport']) {
																case "=":
																		$nsql 		= $nsql."`ruat_tsa_passport` = '".$arr[7]."' ";
																		$existing 	= 1;
																		break;
																case "!=":
																		$nsql 		= $nsql."`ruat_tsa_passport` != '".$arr[7]."' ";
																		$existing 	= 1;
																		break;
																case "LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_passport` LIKE CONVERT(_utf8 \'%'.$arr[7].'%\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;								
																case "NOT LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_passport` NOT LIKE CONVERT(_utf8 \''.$arr[7].'\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;
																default:
																		// Do not do anything with this SQL Statement
																		//$existing = 0;
																		break;														
															}
														break;
												case "OR":
														$nsql 		= $nsql."OR ";
														switch ($_POST['passport']) {
																case "=":
																		$nsql 		= $nsql."`ruat_tsa_passport` = '".$arr[7]."' ";
																		$existing 	= 1;
																		break;
																case "!=":
																		$nsql 		= $nsql."`ruat_tsa_passport` != '".$arr[7]."' ";
																		$existing 	= 1;
																		break;
																case "LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_passport` LIKE CONVERT(_utf8 \'%'.$arr[7].'%\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;								
																case "NOT LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_passport` NOT LIKE CONVERT(_utf8 \''.$arr[7].'\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;
																default:
																		// Do not do anything with this SQL Statement
																		//$existing = 0;
																		break;												
															}								
														break;
												default:
														// Do not do anything with this SQL Statement
														//$existing = 0;
														break;
											}
										switch ($_POST['misc_xor']) {
												// Determine additional XOR type
												case "AND":
														$nsql 		= $nsql."AND ";
														switch ($_POST['misc']) {
																case "=":
																		$nsql 		= $nsql."`ruat_tsa_misc` = '".$tmp_misc."' ";
																		$existing 	= 1;
																		break;
																case "!=":
																		$nsql 		= $nsql."`ruat_tsa_misc` != '".$tmp_misc."' ";
																		$existing 	= 1;
																		break;
																case "LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_misc` LIKE CONVERT(_utf8 \'%'.$tmp_misc.'%\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;								
																case "NOT LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_misc` NOT LIKE CONVERT(_utf8 \''.$tmp_misc.'\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;
																default:
																		// Do not do anything with this SQL Statement
																		//$existing = 0;
																		break;														
															}
														break;
												case "OR":
														$nsql 		= $nsql."OR ";
														switch ($_POST['misc']) {
																case "=":
																		$nsql 		= $nsql."`ruat_tsa_misc` = '".$tmp_misc."' ";
																		$existing 	= 1;
																		break;
																case "!=":
																		$nsql 		= $nsql."`ruat_tsa_misc` != '".$tmp_misc."' ";
																		$existing 	= 1;
																		break;
																case "LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_misc` LIKE CONVERT(_utf8 \'%'.$tmp_misc.'%\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;								
																case "NOT LIKE":
																		$nsql 		= $nsql.'`ruat_tsa_misc` NOT LIKE CONVERT(_utf8 \''.$tmp_misc.'\' USING latin1) COLLATE latin1_swedish_ci ';
																		$existing 	= 1;
																		break;
																default:
																		// Do not do anything with this SQL Statement
																		//$existing = 0;
																		break;												
															}								
														break;
												default:
														// Do not do anything with this SQL Statement
														//$existing = 0;
														break;
											}
							
					// 	SQL Statement has been created in the NSQL variable. We will now need to add 
					//	the other parts to the SQL statement to make MySQL understand our request.
											
										$sql 		= "SELECT * FROM ".$array_tables[$k]." WHERE ";
										$sql 		= $sql.$nsql."ORDER BY ruat_tsa_sid ";
										$sql_encode = urlencode($sql);
										$user_s_who = $arr[0].", ".$arr[1];
										//echo "The completed SQL Statement is ".$sql." <br>";
										//echo "The SQL Statement encoded is ".$sql_encode." <br>";
										//echo "The Current SQL is looking for matchs to ".$user_s_who." <br>";
					
		//	STEP FOUR : With the previous steps completed we can dive into the actual job of searching 
		//	through the tables and seeing if there is a match to display.
		
					// 	The First part of the fourth step is to establish the connection with the 
					//	database.
					
										$objconn_support = mysqli_connect("localhost", "webuser", "limitaces", "tsa_ruat");
										if (mysqli_connect_errno()) {
												printf("connect failed: %s\n", mysqli_connect_error());
												exit();
											}
											else {
												$objrs_support = mysqli_query($objconn_support, $sql);
												if ($objrs_support) {
														$number_of_rows = mysqli_num_rows($objrs_support);
														if ($number_of_rows == 0) {
																// Update the total number of queries the user has completed.
																updateuserqueries($_SESSION["user_id"]);
											
																// No Matchs have been found in this query, so increase the 
																//	totalrowsfound plus the number of rows plus the total number of rows in this search.											
																$totalcellsblanks = ( $totalcellsblanks + 1 );
											
																//$totalrowsprocessed = ($totalrowsprocessed + 1);
																//echo "There are no matches for ".$arr[0].", ".$arr[1]." <br><br><br>";
															}
															else {
																//echo "At least one match was found for ".$arr[0].", ".$arr[1]." <br><br><br>";
															}

					// 	Now Loop through each of the records in the database that were found as part
					//	of the search SQL. Determine if we already have seen them, and display accordingly.
					
														while ($newarray = mysqli_fetch_array($objrs_support, MYSQLI_ASSOC)) {
																// Update the total number of queries the user has completed.
																updateuserqueries($_SESSION["user_id"]);
																
																// Set a temporary variable that will be equal to the SID of the current found record
																$tmp_id = $newarray['ruat_tsa_sid'];
																
																// Looks like we are cycling through the found matches 
																// 	for the given search query. So add Plus 1 to the cells found
																$totalcellsfound = ($totalcellsfound + 1);
											
																$debug = 1;
																if ($debug == 1 ) {
																		?>
																		<tr>
																			<td colspan="3" class="newTopicNames">
																				<font size="1"> Possible Match Found
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[0];?>
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[1];?>
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[2];?>
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[3];?>
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[4];?>
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[5];?>
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[6];?>
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[7];?>
																				</td>
																			<td class="newTopicNames">
																				<font size="1"><?=$arr[8];?>
																				</td>
																			</tr>
																			<?
																	}
											
																// Check to see if our array of Found ID's already exisits											
																if ($idarray[$tmp_id] == '') {
																		//echo "Value does not exisit - Display Row";
																		$idarray[$tmp_id] = $tmp_id;
																		?>
			<tr>
				<td class="newTopicNames">
					<?=$array_tables[$k];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_sid'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_cleared'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_last_name'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_first_name'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_middle_name'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_type'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_DOB'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_last_POB'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_citizanship'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_passport'];?>
					</td>
				<td class="newTopicNames">
					<?=$newarray['ruat_tsa_misc'];?>
					</td>
				</tr>												
																		<?
																	}
																	else {
																		//echo "This is a repeat row resulting from the SQL Statement
																	}

															}	// End of While Loop
													$counter = $counter + 1;
													//echo "Number of Rows Found Across all Cycles ".$totalcellsfound." <br>";
													//echo "Number of Empty Returns Across all Cycles ".$totalcellsblanks." <br>";
													//echo "There are ".$totalsize." rows in each cycle <br>";
													//echo "Increase tmp_a by the totalsize <br>";
													$tmp_a	= ( $tmp_a + $totalsize );
													//echo ">>> a > The Value is ".$tmp_a." <br>";
													//echo "The DIV layer is 300 pixels wide, what percent of the pixel is completed? <br>";
													$tmp_b	= ( (300 / $scobby) / $totalexpectedcycles );
													//echo ">>> b >The Vlaue is ".$tmp_b." <br>";
													//echo "Take the percentage times the amount of records completed, and round the result <br>";
													$tmp_c	= round(( $tmp_b * $tmp_a ),0);
													//echo ">>> c > The Value is ".$tmp_c." <br>";
													//echo "What percent of the progress bar is completed? <br>";
													$tmp_d	= ( round( ( $tmp_c / 300 ), 4 ) * 100 );
													//echo ">>> d > The value is ".$tmp_d." <br>";
													//echo "--------------------------------------------------------------------- <br>";										
													?>
																<script>														
																	document.getElementById("myDiv").style.display 		= "block";
																	document.getElementById("myDiv_bar").style.display 	= "block";
																	document.getElementById("myDiv_bar").style.width 	= "<?=$tmp_c;?>";
																	document.getElementById("percent").innerHTML 		= "<font color='#FFFFFF'><?=$tmp_d;?>%</font>";
																	//alert('You have unread messages \n Please check them by clicking the Notice Button');
																	</script>
													<?	
													}
												mysqli_free_result($objrs_support);
												mysqli_close($objconn_support);
											}
									$nsql = '';		
									}	// End of user's CSV For Loop Statement
							}	// End of Table Loop For Statement
					$time_a 	= timer();
					$tmp_time	= ($time_a - $time_b);
					//echo "This Query has taken ".$tmp_time." seconds to run ";
					?>
			<tr>
				<td colspan="12" class="newTopicEnder">
					&nbsp;
					</td>
				</tr>
		<script>														
			document.getElementById("myDiv").style.display 		= "none";
			document.getElementById("myDiv_bar").style.display 	= "none";
			//alert('You have unread messages \n Please check them by clicking the Notice Button');
			</script>
				<?
	}
	else {
		// The form has not been submited yet
		?>
			<tr>
				<td>
					<?
					breadcrumbs('_addnewsearch.php', $pagename);
					$function = "Accessed Page: ".$pagename;
					put_systemactivity($_SESSION["user_id"],$function);
					?>
					</td>
				</tr>
			<tr>
				<td colspan="3" class="newTopicTitle">
					Add New Search Query
					</td>
				</tr>
			<tr>
				<td colspan="3" class="newTopicBoxes">
					This form will allow you to make a custom search of the TSA Watchlists that will not meet the 
					requirements of SD 1542-01-10E. This search will give you the ability to have greater 
					flexability in the information you get back and will allow you to search in additional fields, 
					not just the first, last, or middle name. Your search will not be saved, and you will not get 
					an email based on the results of this search.
					</td>
				</tr>
			<tr>
				<td colspan="11" class="newTopicBoxes">
					<table>
						<tr>						
							<form action="_managesearch.php" METHOD="POST" target="layouttableiframecontent" style="margin: 0px; margin-bottom:0px; margin-top:-1px;">
							<td class="newTopicBoxes">
								<input type="hidden" name="userid" 	value="<?=$_SESSION["user_id"];?>">
								<input type="hidden" name="displaylist" value="<?=$_POST['displaylist'];?>">
								<input type="hidden" name="displaypage" value="<?=$_POST['displaypage'];?>">
								<input type="submit" name="submit"	value="<<< Back" class="button">
								</td>
								</form>
							</tr>
						</table>
					</td>
				</tr>	
		<form action="_addnewcustomsearch.php" Method="POST" style="margin: 0px; margin-bottom:0px; margin-top:-1px;">
			<input type="hidden" name="submitform" 		value="1">
			<input type="hidden" name="sd15420101e" 	value="NO">
			<input type="hidden" name="userid" 	value="<?=$_SESSION["user_id"];?>">
			<input type="hidden" name="displaylist" value="<?=$_POST['displaylist'];?>">
			<input type="hidden" name="displaypage" value="<?=$_POST['displaypage'];?>">
			<input type="hidden" name="userid" 			value="<?=$_SESSION["user_id"];?>">	
			<tr>
				<td colspan="3" class="newTopicTitle">
					Custom Search Options
					</td>
				</tr>	
			<tr>
				<td class="newTopicNames">
					Use CSV on file ?
					</td>
				<td colspan="2" class="newTopicNames">
					<select class="newTopicInput" name="usecsvfile">
						<option value="NO" SELECTED>NO</option>
						<option value="YES">YES</option>
						</select>
					</td>
				</tr>
				<td class="newTopicNames">
					Use New Defined Name ?
					</td>
				<td colspan="2" class="newTopicNames">
				<table width="425" style="margin: 0px; margin-bottom:0px; margin-top:-1px;" >
					<tr>
						<td align="center" valign="middle">						
							<input class="newTopicInput" type="text" name="definedname_last" size="25">
							</td>
						<td align="center" valign="middle">								
							<input class="newTopicInput" type="text" name="definedname_middle" size="10">
							</td>
						<td align="center" valign="middle">								
							<input class="newTopicInput" type="text" name="definedname_first" size="25">
							</td>
						</tr>
					<tr>
						<td class="attachmentCategory">						
							Last Name
							</td>
						<td class="attachmentCategory">						
							Middle
							</td>
						<td class="attachmentCategory">						
							First Name
							</td>
						</tr>
					</table>
					</td>
				</tr>
			<tr>
				<td class="newTopicTitle">
					Cell Values
					</td>
				<td class="newTopicTitle">
					Operator
					</td>
				<td class="newTopicTitle">
					Value
					</td>
				</tr>
			<tr>
				<td class="newTopicNames">
					Last Name
					</td>
				<td class="newTopicNames">
					</td>
				<td class="newTopicNames">
					<select class="newTopicInput" name="lastname">
						<option value="">do not include</option>
						<option value="=">=</option>
						<option value="!=">!=</option>
						<option value="LIKE">LIKE</option>
						<option value="NOT LIKE">NOT LIKE</option>
						</select>
					</td>	
				</tr>
			<tr>
				<td class="newTopicNames">
					First Name
					</td>
				<td class="newTopicNames">
					<select class="newTopicInput" name="firstname_xor">
						<option value="">do not include</option>
						<option value="AND">AND</option>
						<option value="OR">OR</option>
						</select>
					</td>
				<td class="newTopicNames">
					<select class="newTopicInput" name="firstname">
						<option value="">do not include</option>
						<option value="=">=</option>
						<option value="!=">!=</option>
						<option value="LIKE">LIKE</option>
						<option value="NOT LIKE">NOT LIKE</option>
						</select>
					</td>	
				</tr>
			<tr>
				<td class="newTopicNames">
					Middle Name
					</td>
				<td class="newTopicNames">
					<select class="newTopicInput" name="middlename_xor">
						<option value="">do not include</option>
						<option value="AND">AND</option>
						<option value="OR">OR</option>
						</select>
					</td>
				<td class="newTopicNames">
					<select class="newTopicInput" name="middlename">
						<option value="">do not include</option>
						<option value="=">=</option>
						<option value="!=">!=</option>
						<option value="LIKE">LIKE</option>
						<option value="NOT LIKE">NOT LIKE</option>
						</select>
					</td>	
				</tr>
			<tr>
				<td class="newTopicNames">
					Type
					</td>
				<td class="newTopicNames">
					<select class="newTopicInput" name="type_xor">
						<option value="">do not include</option>
						<option value="AND">AND</option>
						<option value="OR">OR</option>
						</select>
					</td>
				<td class="newTopicNames">
					<select class="newTopicInput" name="type">
						<option value="">do not include</option>
						<option value="=">=</option>
						<option value="!=">!=</option>
						<option value="LIKE">LIKE</option>
						<option value="NOT LIKE">NOT LIKE</option>
						</select>
					</td>	
				</tr>
			<tr>
				<td class="newTopicNames">
					Date of Birth
					</td>
				<td class="newTopicNames">
					<select class="newTopicInput" name="dob_xor">
						<option value="">do not include</option>
						<option value="AND">AND</option>
						<option value="OR">OR</option>
						</select>
					</td>
				<td class="newTopicNames">
					<select class="newTopicInput" name="dob">
						<option value="">do not include</option>
						<option value="=">=</option>
						<option value="!=">!=</option>
						<option value="LIKE">LIKE</option>
						<option value="NOT LIKE">NOT LIKE</option>
						</select>
					</td>					
				</tr>
			<tr>
				<td class="newTopicNames">
					Place of Birth
					</td>
				<td class="newTopicNames">
					<select class="newTopicInput" name="pob_xor">
						<option value="">do not include</option>
						<option value="AND">AND</option>
						<option value="OR">OR</option>
						</select>
					</td>
				<td class="newTopicNames">
					<select class="newTopicInput" name="pob">
						<option value="">do not include</option>
						<option value="=">=</option>
						<option value="!=">!=</option>
						<option value="LIKE">LIKE</option>
						<option value="NOT LIKE">NOT LIKE</option>
						</select>
					</td>					
				</tr>
			<tr>
				<td class="newTopicNames">
					Citizanship
					</td>
				<td class="newTopicNames">
					<select class="newTopicInput" name="citizanship_xor">
						<option value="">do not include</option>
						<option value="AND">AND</option>
						<option value="OR">OR</option>
						</select>
					</td>
				<td class="newTopicNames">
					<select class="newTopicInput" name="citizanship">
						<option value="">do not include</option>
						<option value="=">=</option>
						<option value="!=">!=</option>
						<option value="LIKE">LIKE</option>
						<option value="NOT LIKE">NOT LIKE</option>
						</select>
					</td>	
				</tr>
			<tr>
				<td class="newTopicNames">
					Passport
					</td>
				<td class="newTopicNames">
					<select class="newTopicInput" name="passport_xor">
						<option value="">do not include</option>
						<option value="AND">AND</option>
						<option value="OR">OR</option>
						</select>
					</td>
				<td class="newTopicNames">
					<select class="newTopicInput" name="passport">
						<option value="">do not include</option>
						<option value="=">=</option>
						<option value="!=">!=</option>
						<option value="LIKE">LIKE</option>
						<option value="NOT LIKE">NOT LIKE</option>
						</select>
					</td>	
				</tr>
			<tr>
				<td class="newTopicNames">
					Misc
					</td>
				<td class="newTopicNames">
					<select class="newTopicInput" name="misc_xor">
						<option value="">do not include</option>
						<option value="AND">AND</option>
						<option value="OR">OR</option>
						</select>
					</td>
				<td class="newTopicNames">
					<select class="newTopicInput" name="misc">
						<option value="">do not include</option>
						<option value="=">=</option>
						<option value="!=">!=</option>
						<option value="LIKE">LIKE</option>
						<option value="NOT LIKE">NOT LIKE</option>
						</select>
					</td>					
				</tr>
			<tr>
				<td rowspan="2" class="newTopicNames">
					Watch List(s)
					</td>
				<td colspan="2" class="newTopicNames">
					No Fly <input class="newTopicInput" type="radio" name="group1" value="NOFLY"><br>
					Selectee <input class="newTopicInput" type="radio" name="group1" value="SELECTEE"><br>
					Cleared <input class="newTopicInput" type="radio" name="group1" value="CLEARED"><br>
					All <input class="newTopicInput" type="radio" name="group1" value="ALL" checked>
					</td>
				</tr>
			<tr>
				<td colspan="2" class="newTopicNames">
					Full List Compare <input class="newTopicInput" type="radio" name="group4" value="FULL"><br>
					Just Recent Additions <input class="newTopicInput" type="radio" name="group4" value="ADD" checked><br>
					</td>
				</tr>
			<tr>
				<td colspan="3" class="newTopicNames">
					<input type="submit" name="submit" value="submit" class="button">
					</td>					
				</tr>
			<tr>
				<td colspan="3" class="newTopicEnder">
					&nbsp;
					<?
					display_copywrite_footer();
					?>
					</td>
				</tr>						
			</table>
			</form>
		<?
	}
	?>
		</body>
	</html>
	<?
	}
	?>