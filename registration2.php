<!DOCTYPE html>
<?php
	/*
	 * registration2.php v1.6	-	pdweek
	 */
	$flt_time_start = (float) microtime( true );

	require_once( "data/environment.php" );
	require_once( "lib/logging.php" );
?>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title>Georgian College :: <?=$str_pdweekName;?> <?=$str_currentYear;?></title>

		<!--
			JQuery-UI css definitions were manually implemented in gl.css
			to fix IE v11 performance issue when full jquery-ui.css was
			loaded.
		-->
		<link rel="stylesheet" href="css/gl.css" type="text/css">

		<!--
			Legacy JQuery and JQuery-UI used for IE8 functionality.
		-->
		<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.min.js"></script>

		<script src="js/accordion.js"></script>
		<script src="js/checkregfields.js"></script>
		<script src="js/formControlLogic.js"></script>
<?php
/**
 * Get Keynote information
 */

	// Open the database connection
	$dbConnectionObject = @mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );
	
	// Die on connection failures. Link to mailto:$str_supportEmail with a nice interface.
	if( mysqli_connect_error() ) {

		echo '</head><body>
		<div class="main ui-corner-bottom">';

		if( file_exists( 'pdweek.php' ) ) {
			require_once 'pdweek.php';
		}// end if statement

		echo '			<div class="ui-widget">
				<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
					<p>
						<strong>Alert:</strong> Our system could not connect to the internal database.
						This is likely because you followed an old (archived) link. If you continue to
						reach this error page, please contact
						<a href="mailto:' . $str_supportEmail . '" style="color: blue;">' . $str_supportEmail . '</a>
						for further assistance.<br><br>If you are a developer, the connection error is below:
						<span class="block upper-space lower-space left-margin">
							<strong>Error Code</strong> ' . mysqli_connect_errno() . '<br>
							<strong>Error</strong> ' . mysqli_connect_error() . '
						</span>
					</p>
				</div><!-- ui-state-error -->
			</div><!-- ui-widget -->
		</div><!-- main -->
	</body>
</html>';

		$dbConnectionObject = null;
		unset( $dbConnectionObject );

		//exit execution of the script here
		exit();
	}// end if statement

	// Set the character set, for use with mysqli_real_escape_string
	mysqli_set_charset( $dbConnectionObject, $str_dbCharset );

	$keynoteQuery = (string) "SELECT * FROM keynotes;";
	$keynoteResultObject = mysqli_query( $dbConnectionObject, $keynoteQuery );

	if( is_object( $keynoteResultObject ) ) {
		while( $row = mysqli_fetch_array( $keynoteResultObject ) ) {
			extract( $row );

			switch( $day ) {
				case "mon":
					$mon_keynote = (array) $row;
					break;
				case "tue":
					$tue_keynote = (array) $row;
					break;
				case "wed":
					$wed_keynote = (array) $row;
					break;
				case "thu":
					$thur_keynote = (array) $row;
					break;
			}// end switch case statement
		}// end while loop
		
		mysqli_free_result( $keynoteResultObject );
	} else {
		echoToConsole( "Failed query for Keynote information", true );
	}// end if statement
?>

		<script>
			$(document).ready(function() {
				var $dialog1 = $('<div></div>')
					.html('Photos will be taken throughout the day and will be used in a conference slideshow and for other promotional purposes.')
					.dialog({
						autoOpen: false,
						title: 'Photo Disclaimer'
					});

				var $dialog2 = $('<div></div>')
					.html('The Tech Café is a collection of stations where you can informally experience many technologies that support learning. Experts at each station will share their knowledge. It will run from 10:30am - 12:15pm. Some of the stations will include: Clickers, Media Services, Library Research, Films on Demand Platform, Blackboard 9.1, Adaptive Technology.')
					.dialog({
						autoOpen: false,
						title: 'The Tech Cafe'
						});

				$('#photodialog').click(function() {
					$dialog1.dialog('open');
					// prevent the default action, e.g., following a link
					return false;
				});

				$('#tcdialog').click(function() {
					$dialog2.dialog('open');
					// prevent the default action, e.g., following a link
					return false;
				});

				var $dialog7 = $('<div></div>')
					.html('On Thursday from <strong>9:00am – 12:00pm</strong> we are excited to be offering various "Extraordinary Experience" opportunities at the Barrie Campus. The goal of these opportunities is to provide staff an opportunity to learn and explore the different academic areas from a "Students View" and learn more about Georgian College and some of the programs and experiences we offer our students.<br><br>If you are interested in attending please click here and you will be notified once registration opens for these sessions.')
					.dialog({
						autoOpen: false,
						title: 'Extraordinary Experiences'
						});

				$('#extraordinary-experiences-info').click(function() {
					$dialog7.dialog('open');
					// prevent the default action, e.g., following a link
					return false;
				});
				
				$('#hldialog').click(function() {
					$dialog8.dialog('open');
					// prevent the default action, e.g., following a link
					return false;
				});
				
				var $dialog8 = $('<div></div>')
					.html('The Human Library will take place at 10:30am as part of the Focus on Teaching Conference.<br><br><strong>What is the Human Library?</strong> (Definition adapted from <a href="http://humanlibrary.org">humanlibrary.org</a>)<br><br>The Human Library is an innovative experiential learning method designed to promote dialogue, reduce prejudices, build connections, and encourage understanding. It is set up as a space for dialogue and interaction. Visitors to a Human Library are given the opportunity to be "readers" though informal conversations with "people on loan". The people on loan or the "books" of the library are selected to represent student diversity. They have volunteered to share their experiences with library visitors through informal conversations. The human library has been proven to be a powerful event for breaking stereotypes and gaining insight into the rich and diverse lived experiences of the people in our classrooms.')
					.dialog({
						autoOpen: false,
						title: 'The Human Library'
						});

<?php
	if( isset( $mon_keynote ) ) {
		$mon_keynote['speaker'] = stripslashes( $mon_keynote['speaker'] );
		$mon_keynote['description'] = stripslashes( $mon_keynote['description'] );
		$mon_keynote['time'] = stripslashes( $mon_keynote['time'] );
		$mon_keynote['seats'] = stripslashes( $mon_keynote['seats'] );
		$mon_keynote['location'] = stripslashes( $mon_keynote['location'] );
		
		echo <<<END

				var \$dialog3 = \$('<div></div>')
					.html("<strong>Speaker</strong>: {$mon_keynote['speaker']}<br><strong>Description:</strong> {$mon_keynote['description']}<br><strong>Time</strong>: {$mon_keynote['time']}<br><strong>Seats Remaining</strong>: {$mon_keynote['seats']}<br><strong>Location:</strong> {$mon_keynote['location']}")
					.dialog({
						autoOpen: false,
						title: '{$mon_keynote['title']}'
						});

				\$('#mon-keynote-info').click(function() {
					\$dialog3.dialog('open');
					// prevent the default action, e.g., following a link
					return false;
				});

END;
	} else {
		$noMondayKeynote = (bool) true;
		echoToConsole( "No Monday Keynote found", false );
	}// end if statement

	/**
	 * Tuesday Keynote
	 */
	if( isset( $tue_keynote ) ) {
		$tue_keynote['speaker'] = stripslashes( $tue_keynote['speaker'] );
		$tue_keynote['description'] = stripslashes( $tue_keynote['description'] );
		$tue_keynote['time'] = stripslashes( $tue_keynote['time'] );
		$tue_keynote['seats'] = stripslashes( $tue_keynote['seats'] );
		$tue_keynote['location'] = stripslashes( $tue_keynote['location'] );

		echo <<<END

				var \$dialog4 = \$('<div></div>')
					.html("<strong>Speaker</strong>: {$tue_keynote['speaker']}<br><strong>Description:</strong> {$tue_keynote['description']}<br><strong>Time</strong>: {$tue_keynote['time']}<br><strong>Seats Remaining</strong>: {$tue_keynote['seats']}<br><strong>Location:</strong> {$tue_keynote['location']}")
					.dialog({
						autoOpen: false,
						title: '{$tue_keynote['title']}'
						});

				\$('#tue-keynote-info').click(function() {
					\$dialog4.dialog('open');
					// prevent the default action, e.g., following a link
					return false;
				});

END;
	} else {
		$noTuesdayKeynote = (bool) true;
		echoToConsole( "No Tuesday Keynote found", false );
	}// end if statement

	/**
	 * Wednesday Keynote
	 */
	if( isset( $wed_keynote ) ) {
		$wed_keynote['speaker'] = stripslashes( $wed_keynote['speaker'] );
		$wed_keynote['description'] = stripslashes( $wed_keynote['description'] );
		$wed_keynote['time'] = stripslashes( $wed_keynote['time'] );
		$wed_keynote['seats'] = stripslashes( $wed_keynote['seats'] );
		$wed_keynote['location'] = stripslashes( $wed_keynote['location'] );
		
		echo <<<END

				var \$dialog5 = \$('<div></div>')
					.html("<strong>Speaker</strong>: {$wed_keynote['speaker']}<br><strong>Description:</strong> {$wed_keynote['description']}<br><strong>Time</strong>: {$wed_keynote['time']}<br><strong>Seats Remaining</strong>: {$wed_keynote['seats']}<br><strong>Location:</strong> {$wed_keynote['location']}")
					.dialog({
						autoOpen: false,
						title: '{$wed_keynote['title']}'
						});

				\$('#wed-keynote-info').click(function() {
					\$dialog5.dialog('open');
					// prevent the default action, e.g., following a link
					return false;
				});

END;
	} else {
		$noWednesdayKeynote = (bool) true;
		echoToConsole( "No Wednesday Keynote found", false );
	}// end if statement
	
	/**
	 * Thursday Keynote
	 */
	if( isset( $thur_keynote ) ) {
		$thur_keynote['speaker'] = stripslashes( $thur_keynote['speaker'] );
		$thur_keynote['description'] = stripslashes( $thur_keynote['description'] );
		$thur_keynote['time'] = stripslashes( $thur_keynote['time'] );
		$thur_keynote['seats'] = stripslashes( $thur_keynote['seats'] );
		$thur_keynote['location'] = stripslashes( $thur_keynote['location'] );
		
		/**
		 * Test if seats full
		 */
		$thursdayKeynoteFull = (bool) false;
		if( $thur_keynote['seats'] < 1 ) {
			$thursdayKeynoteFull = (bool) true;
		}// end if statement (Seats full)

		echo <<<END

				var \$dialog6 = \$('<div></div>')
					.html("<strong>Speaker</strong>: {$thur_keynote['speaker']}<br><strong>Description:</strong> {$thur_keynote['description']}<br><strong>Time</strong>: {$thur_keynote['time']}<br><strong>Seats Remaining</strong>: {$thur_keynote['seats']}<br><strong>Location:</strong> {$thur_keynote['location']}")
					.dialog({
						autoOpen: false,
						title: '{$thur_keynote['title']}'
						});

				\$('#thur-keynote-info').click(function() {
					\$dialog6.dialog('open');
					// prevent the default action, e.g., following a link
					return false;
				});

END;
	} else {
		$noThursdayKeynote = (bool) true;
		echoToConsole( "No Thursday Keynote found", false );
	}// end if statement
?>
			});
		</script>
	</head>
	<body>
<?php

	error_reporting(E_ALL);

	if (isset($_GET['r'])) {
		$check = filter_input( INPUT_GET, 'r', FILTER_SANITIZE_MAGIC_QUOTES );
	} else {
		$check = "";
	}
	
	if (strlen($check) == 16) {
		$check = substr($check, 0, 15);
	}

	$lastname = $firstname = $email = $registered = $dept = $otherdept = $lunch = $techcafe = $amworkshop = $pmworkshop = $userid = '';

	$prevfound = 1;
	if ($check != "") {
		$checkforprev = "select * from users where validate2='" . $check . "'";

		$result = mysqli_query( $dbConnectionObject, $checkforprev );

		$alreadyregistered = 0;
		if (mysqli_num_rows($result) == 0) {
			$prevfound = 0;
		} else {
			$row = mysqli_fetch_array($result);
			extract($row);

			$selectw = "select mon_amworkshop, mon_pmworkshop, tue_amworkshop, tue_pmworkshop, wed_amworkshop, wed_pmworkshop, wed_pmworkshop2, thur_amworkshop, thur_pmworkshop from registered where userid=$userid";
			$resultw = mysqli_query( $dbConnectionObject, $selectw );

			//Initialize 'registered' table if userid does not exist there.
			if (mysqli_num_rows($resultw) == 0) {
				$mon_amworkshop = $tue_amworkshop = $wed_amworkshop = $thur_amworkshop = (int) 100;
				$mon_pmworkshop = $tue_pmworkshop = $wed_pmworkshop = $wed_pmworkshop2 = $thur_pmworkshop = (int) 101;
				$initializeRegistered_query = "insert into registered (userid, mon_amworkshop, mon_pmworkshop, tue_amworkshop, tue_pmworkshop, wed_amworkshop, wed_pmworkshop, wed_pmworkshop2, thur_amworkshop, thur_pmworkshop) values ($userid, 100, 101, 100, 101, 100, 101, 101, 100, 101)";

				$initializeRegistered_result = mysqli_query( $dbConnectionObject, $initializeRegistered_query );

				if( $initializeRegistered_result == false ) {
					echoToConsole( "Failed to initialize registered table!", true );
				}// end if statement
			} else {
				$row = mysqli_fetch_array($resultw);
				extract($row);		
			}

			//Initialize 'fotcAttendees' table if userid does not exist there.
			$checkFoTC_query = "SELECT choice FROM fotcAttendees WHERE userid=$userid;";
			$checkFoTC_result = mysqli_query( $dbConnectionObject, $checkFoTC_query );

			if( mysqli_num_rows( $checkFoTC_result ) == 0 ) {
				$initializeFotc_query = "INSERT INTO fotcAttendees(userid,choice) VALUES($userid,'$fotc');";
				$initializeFotc_result = mysqli_query( $dbConnectionObject, $initializeFotc_query );

				if( $initializeFotc_result == false ) {
					echoToConsole( "Failed to initialize FoTC Attendees table!", true );
				}// end if statement
			}// end if statement

			//Set the 'review' flag in the users table
			$setReviewQuery = "UPDATE users SET review='yes' WHERE userid=$userid;";
			$setReviewResult = mysqli_query( $dbConnectionObject, $setReviewQuery );

			if( $setReviewResult == false ) {
				echoToConsole( "Failed to set review flag.", true );
			}// end if statement
		}// end if statement
	} else {
		$prevfound = 0;
	}
	?>
		<div class="main ui-corner-bottom">

<?php include "pdweek.php"; ?>

<?php
if ($prevfound == 0) {
?>
			<div class="ui-widget">
				<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"> 
					<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
						<strong>Alert:</strong> Our system has indicated that you have not completed Part 1 of the registration process. Please go back to the <a href="index.php" style="color: blue;">Registration Page</a> to begin the process. If you continue to get this error, please contact <a href="<?=$str_supportEmail;?>" style="color: blue;"><?=$str_supportEmail;?></a> for technical support. Thank you.</p>
				</div><!-- ui-state-error -->
			</div><!-- ui-widget -->
<?php
} else {

?>		
			<h3>Session Registration</h3>
			<p>If you have any questions or problems regarding the session registrations, please contact <a href="mailto:<?=$str_supportEmail;?>"><?=$str_supportEmail;?></a></p>

			<div class="formbox2">
				<form name="registration" action="register3.php" method="post" accept-charset="utf-8" onsubmit="return checkFields();">
					<script>lastEntered = '';</script>
					<div class="ui-widget">
						<div class="ui-state-info ui-corner-all" style="padding: 0 .7em;"> 
							<p>Before choosing your sessions, please check your profile and make changes (if necessary).</p>
						</div><!-- ui-state-info -->
					</div><!-- ui-widget -->
					<p>Note that your first name, last name, and department on your profile specify what will appear on your conference name tag. Please click on the Submit button only after selecting your sessions.</p>
					<p>Reminder to Presenters: Please do not choose your own session - do not make a selection for that time slot.</p>
				<!-- Begin Profile -->
					<div class="regbox">
						<h3 class="regboxtitle">Profile</h3>
						<div class="regLabelContainer">
							<div class="lower-space"><label for="firstname">First name</label></div>
							<div class="lower-space"><label for="lastname">Last name</label></div>
							<div class="lower-space"><label for="email">Email</label></div>
							<div class="lower-space"><label for="department">Department</label></div>
							<div class="lower-space"><label for="otherdept">If Other, please specify</label></div>
							<div class="lower-space"><label>By attending, you consent to have your photo taken and used for the conference</label></div>
						</div><!-- regLabelContainer -->
						<div class="regControlContainer">
							<input class="regControlTop input-text" type="text" id="firstname" name="firstname" width="100" maxlength="40" value="<?php echo htmlentities( stripslashes($firstname) ); ?>" onClick="lastEntered=this.value; this.value='';" onBlur="this.value=!this.value?lastEntered:this.value;">
							<input class="regControl input-text" type="text" id="lastname" name="lastname" width="100" maxlength="40" value="<?php echo htmlentities( stripslashes($lastname) ); ?>" onClick="lastEntered=this.value; this.value='';" onBlur="this.value=!this.value?lastEntered:this.value;">
							<input class="regControl input-text" type="text" id="email" name="email" width="100" maxlength="40" value="<?php echo htmlentities( stripslashes($email) ); ?>" readonly>
							<select class="regControl input-text" name="department" id="department">
<?php
	$departmentListArray_bottom = array(
		"School of Business, Automotive, and Hospitality; OMVIC",
		"School of Technology and Visual Arts",
		"Centre for Applied Research",
		"Midland Campus",
		"Owen Sound Campus",
		"School of Health and Wellness",
		"Centre for Teaching and Learning",
		"School of Human Services and Community Safety (Orillia)",
		"Continuing Education and Workforce Development",
		"School of Liberal Arts and Access Programs",
		"Government and Employment Programs",
		"John Di Poce South Georgian Bay Campus",
		"Muskoka Campus",
		"Orangeville Campus",
		"School College Partnerships",
		"University Partnership Centre",
		"VP, Corporate Services and Innovation",
		"Physical Resources",
		"Accounting",
		"Financial Planning and Risk Management",
		"Kempenfelt Conference Centre",
		"Purchasing and Printing",
		"Human Resources  and Organizational Development",
		"Information Technology",
		"Institutional Research",
		"Campus Safety and Security",
		"VP, Communications, Marketing and External Relations",
		"Marketing and Communications",
		"Office of Development and Alumni Relations/Conference Services",
		"VP, Student Engagement and University Partnerships",
		"Athletics and Fitness Centre",
		"Student Life",
		"Coop Education and Career Services",
		"Georgian Stores",
		"International Recruitment and Partnerships",
		"Libraries and Learning Resources",
		"Office of the Registrar",
		"Student Centre Food/Beverage Operations",
		"Student Success Services",
		"Other"
	);
	asort( $departmentListArray_bottom );
	
	$departmentListArray = array(
		"Choose your department",
		"President's Office",
		"VP, Academic and University Programming"
	);

	$departmentListArray = array_merge( $departmentListArray, $departmentListArray_bottom );

	foreach ($departmentListArray as $departmentListItem) {
		if ($department == $departmentListItem) {
			echo "								<option value=\"$departmentListItem\" selected>$departmentListItem</option>\n";
		} else {
			echo "								<option value=\"$departmentListItem\">$departmentListItem</option>\n";
		}
	}
	?>
							</select>
							<input class="regControl input-text" type="text" id="otherdept" name="otherdept" width="150" maxlength="80" value="<?php echo htmlentities( stripslashes($otherdept) ); ?>" onClick="lastEntered=this.value; this.value='';" onBlur="this.value=!this.value?lastEntered:this.value;">
							<button class="regControl" id="photodialog">More info</button>
							<input type="hidden" name="userid" value="<?php echo $userid; ?>">
						</div><!-- regControlContainer -->
						<div class="clear"></div><!-- clear -->
					</div><!-- regbox -->
					<!-- End Profile -->

					<div class="ui-widget lower-space">
						<div class="ui-state-info ui-corner-all" style="padding: 0 .7em;">
							<p><strong>Please note</strong> that your selections are not guaranteed until you hit Submit at the bottom of this page. There could be many people registering at the same time, the seats available for each event/session could change before you click Submit.</p>
						</div><!-- ui-state-info -->
					</div><!-- ui-widget -->

					<div class="regbox">
						<h3 class="regboxtitle">Event Selection</h3>
						<span class="block upper-space lower-space center">Click on a date below to reveal the available options.</span>
						<!-- Start Workshop Accordion -->
						<div id="workshopAccordion">

							<!-- Begin Monday Workshops -->
							<h3>Monday, April 28th - Keynote, BoG Awards, PD Sessions</h3>
							<div class="subordinateRegbox">
								<div class="upper-space lower-space">
									<div class="ui-state-info">
<?php
if( !isset( $noMondayKeynote ) ) {
?>
										<span class="block">Will you be attending the Board of Governor&#96;s Awards &#38; Keynote?</span>
		<?php if ($mon_keynote == "yes") { ?>
										<label><input id="mon_keynote" type="radio" name="mon_keynote" value="yes" checked>Yes</label> <label><input id="mon_keynote" type="radio" name="mon_keynote" value="no">No</label>
		<?php } else { ?>
										<label><input id="mon_keynote" type="radio" name="mon_keynote" value="yes">Yes</label> <label><input id="mon_keynote" type="radio" name="mon_keynote" value="no" checked>No</label>
		<?php } ?>
										<button id="mon-keynote-info">More Info</button>
<?php
}// end if statement
?>

										<span class="block">Will you attend the BBQ lunch? (<em>Barrie Campus&#96; TLC &#64; 12pm - 1pm</em>.)<br></span>										
		<?php if ($mon_lunch == "nnn") { ?>
										<span class="regControl">Lunch tickets are &#34;sold out&#34;</span>
										<input type="hidden" name="lunch" value="nnn">
		<?php } else {
							
			if ($mon_lunch == "yes") {
				echo "										<label><input id=\"mon_lunch\" type=\"radio\" name=\"mon_lunch\" value=\"yes\" checked onClick=\"javascript:showElement('regBbqVegetarian');enableOption('regBbqVegetarianYes');enableOption('regBbqVegetarianNo');\">Yes</label> <label><input id=\"mon_lunch\" type=\"radio\" name=\"mon_lunch\" value=\"no\" onClick=\"javascript:hideElement('regBbqVegetarian');disableOption('regBbqVegetarianYes');disableOption('regBbqVegetarianNo');\">No</label>\n";
				echo '										<div class="block left-margin" id="regBbqVegetarian">
											Would you prefer a Vegetarian lunch?&nbsp;';
				if ($vegetarian == "yes") {
					echo "											<label><input id=\"regBbqVegetarianYes\" type=\"radio\" name=\"vegetarian\" value=\"yes\" checked>Yes</label> <label><input id=\"regBbqVegetarianNo\" type=\"radio\" name=\"vegetarian\" value=\"no\">No</label>\n";
				} else {
					echo "											<label><input id=\"regBbqVegetarianYes\" type=\"radio\" name=\"vegetarian\" value=\"yes\">Yes</label> <label><input id=\"regBbqVegetarianNo\" type=\"radio\" name=\"vegetarian\" value=\"no\" checked>No</label>\n";
				}
				echo "										</div><!-- vegetarianOption -->\n";
			} else {
				echo "										<label><input id=\"mon_lunch\" type=\"radio\" name=\"mon_lunch\" value=\"yes\" onClick=\"javascript:showElement('regBbqVegetarian');enableOption('regBbqVegetarianYes');enableOption('regBbqVegetarianNo');\">Yes</label> <label><input id=\"mon_lunch\" type=\"radio\" name=\"mon_lunch\" value=\"no\" checked onClick=\"javascript:hideElement('regBbqVegetarian');disableOption('regBbqVegetarianYes');disableOption('regBbqVegetarianNo');\">No</label>\n";
				echo '										<div class="block left-margin" id="regBbqVegetarian" style="display: none;">
											Would you prefer a Vegetarian lunch?&nbsp;';
				if ($vegetarian == "yes") {
					echo "											<label><input id=\"regBbqVegetarianYes\" type=\"radio\" name=\"vegetarian\" value=\"yes\" checked>Yes</label> <label><input id=\"regBbqVegetarianNo\" type=\"radio\" name=\"vegetarian\" value=\"no\">No</label>\n";
				} else {
					echo "											<label><input id=\"regBbqVegetarianYes\" type=\"radio\" name=\"vegetarian\" value=\"yes\">Yes</label> <label><input id=\"regBbqVegetarianNo\" type=\"radio\" name=\"vegetarian\" value=\"no\" checked>No</label>\n";
				}
				echo "										</div><!-- vegetarianOption -->\n";
			}

		} ?>
									</div><!-- ui-state-info -->
								</div><!-- spacer -->

								<span class="regboxtitle">Concurrent Sessions (Starting in the Afternoon)</span>
								<span class="block left-margin lower-space">Please select a session from the list below.</span>
	<?php
		$select = "SELECT * FROM workshops WHERE time='AM' AND day='mon' AND datediff(now(), release_date)>=0 AND (start_time='1:00pm') OR workshopid=100 ORDER BY title;";

		$result = mysqli_query( $dbConnectionObject, $select );

		if( is_bool( $result ) ) {
			echoToConsole( "Failed to query for Monday AM workshops!", true );
		}// end if statement

		$workshopsStillOpen = 0;
		$n = 1;
		$n2 = mysqli_num_rows($result);
		while ($row = mysqli_fetch_array($result))
		{
			extract($row);
			
			$select2 = "select userid from registered where mon_amworkshop={$workshopid}";
			$result2 = mysqli_query( $dbConnectionObject, $select2 );
			
			if( is_bool( $result2 ) ) {
				echoToConsole( "Failed to query user's Monday AM workshop selection", true );
			}// end if statement
			
			$registered = mysqli_num_rows($result2);
			$seatsleft = $seats - $registered;
			
			$title = stripslashes($title);
			$description = stripslashes($description);
			
			$dclasses = "";
			if ($n == 1) {
				$dclasses .= "borderall ";
			} elseif ($n == $n2) {
				$dclasses .= "borderend ";
			} else {
				$dclasses .= "bordernotop ";
			}
			if ($workshopid == $mon_amworkshop) {
				$dclasses .= "wselected ";
			} else {
				if ($seatsleft == 0) {
					$dclasses .= "wfull ";
				}
			}
			echo "									<div class=\"left-margin {$dclasses}\">\n";
			if ($seatsleft <= 0) {
				if ($workshopid == $mon_amworkshop) {
					echo "										<input type=\"radio\" name=\"mon_amworkshop\" value=\"" . $workshopid . "\" checked> <strong>(FULL) " . $title . "</strong>\n";
				} else {
					echo "										<strong>(FULL) $title</strong>";
				}								
			} else {
				if ($workshopid == $mon_amworkshop) {
					echo "										<input type=\"radio\" name=\"mon_amworkshop\" value=\"" . $workshopid . "\" checked> <strong>" . $title . "</strong>\n";
				} else {
					echo "										<input type=\"radio\" name=\"mon_amworkshop\" value=\"" . $workshopid . "\"> <strong>" . $title . "</strong>\n";
				}
			}
			if ($workshopid == 100) {
				echo "										No session selected for this time slot\n";
			} else {
				echo '										<br><strong>Presenter(s):  ' .  $presenter . '</strong>
										<br><strong>Seats left = ' . $seatsleft . '</strong>
										<br><strong>Location = ' . $room . '</strong>
										<br><br>' . $description . "\n";
			}
			echo "									</div><!-- workshopContainer -->\n";
			$n += 1;
		}// end of while loop
	?>
									<span class="regboxtitle">Concurrent Sessions (Starting at: 2:30pm)</span>
									<span class="block left-margin lower-space">Please select a session from the list below.</span>
	<?php
		$select = "SELECT * FROM workshops WHERE time='PM' AND day='mon' AND datediff(now(), release_date)>=0 AND (start_time='2:30pm') OR workshopid=101 ORDER BY title;";

		$result = mysqli_query( $dbConnectionObject, $select );
		$workshopsStillOpen = 0;
		
		if( is_bool( $result ) ) {
			echoToConsole( "Failed to query for Monday PM workshops!", true );
		}// end if statement
		
		$n = 1;
		$n2 = mysqli_num_rows($result);
		while ($row = mysqli_fetch_array($result))
		{
			extract($row);

			$select2 = "select userid from registered where mon_pmworkshop={$workshopid}";
			$result2 = mysqli_query( $dbConnectionObject, $select2 );
			
			if( is_bool( $result2 ) ) {
				echoToConsole( "Failed to query user's Monday PM workshop selection", true );
			}// end if statement
			
			$registered = mysqli_num_rows($result2);
			$seatsleft = $seats - $registered;
			
			$title = stripslashes($title);
			$description = stripslashes($description);

			$dclasses = "";
			if ($n == 1) {
				$dclasses .= "borderall ";
			} elseif ($n == $n2) {
				$dclasses .= "borderend ";
			} else {
				$dclasses .= "bordernotop ";
			}
			if ($workshopid == $mon_pmworkshop) {
				$dclasses .= "wselected ";
			} else {
				if ($seatsleft == 0) {
					$dclasses .= "wfull ";
				}
			}
			echo "									<div class=\"left-margin {$dclasses}\">\n";
			if ($seatsleft <= 0) {
				if ($workshopid == $mon_pmworkshop) {
					echo "										<input type=\"radio\" name=\"mon_pmworkshop\" value=\"" . $workshopid . "\" checked> <strong>(FULL) $title</strong>\n";
				} else {
					echo "										<strong>(FULL) $title</strong>\n";
				}								
			} else {
				if ($workshopid == $mon_pmworkshop) {
					echo "										<input type=\"radio\" name=\"mon_pmworkshop\" value=\"" . $workshopid . "\" checked> <strong>$title</strong>\n";
				} else {
					echo "										<input type=\"radio\" name=\"mon_pmworkshop\" value=\"" . $workshopid . "\"> <strong>$title</strong>\n";
				}
			}
			if ($workshopid == 101) {
				echo "										No session selected for this time slot\n";
			} else {
				echo '										<br><strong>Presenter(s):  ' . $presenter . '</strong>
										<br><strong>Seats left = ' . $seatsleft . '</strong>
										<br><strong>Location = ' . $room . '</strong>
										<br><br>' . $description . "\n";
			}
			echo "									</div><!-- workshopContainer -->\n";
			$n += 1;
		}
	?>
							</div><!-- subordinateRegbox -->
							<!-- End Monday Workshops -->

							<!-- Begin Tuesday Workshops -->
							<h3>Tuesday, April 29th - Focus on Teaching Conference</h3>
							<div class="subordinateRegbox">
								<div class="upper-space lower-space">
<?php

	include( "fotc.php" );
	require_once( "lib/releaseDate.php" );

	$tueDaysRemaining = daysRemaining( 'tue' );

	require_once( "lib/fotcSeats.php" );
	$fotcSeatsRemaining = fotcSeatsRemaining();

	if( $tueDaysRemaining == 0 ) {
		echoToConsole( "FoTC workshops have been released!", true );

		if( $fotcSeatsRemaining == 0 ) {
			echo <<<END

								<div class="lower-space ui-state-info">
									<label>The Conference is currently full. Would you like to be added to a Waiting list in case a seat becomes available?</label>

END;
		} else {
			echo <<<END

								<div class="lower-space ui-state-info">
									<label>Will you attend the Focus on Teaching Conference? ({$fotcSeatsRemaining} seats remaining)</label>

END;
		}// end if statement

		if( $fotc == "yes" || $fotc == "wl" ) {
			echo( "										<label><input id=\"attendFotc\" type=\"radio\" name=\"attendFotc\" value=\"yes\" checked>Yes</label> <label><input id=\"attendFotc\" type=\"radio\" name=\"attendFotc\" value=\"no\">No</label><br>\n" );
		} else {
			echo( "										<label><input id=\"attendFotc\" type=\"radio\" name=\"attendFotc\" value=\"yes\">Yes</label> <label><input id=\"attendFotc\" type=\"radio\" name=\"attendFotc\" value=\"no\" checked>No</label><br>\n" );
		}// end if statement

		echo <<<END

									<br><span class="block lower-space left-margin"><em>The Focus on Teaching Conference includes:
									<ul>
										<li>Keynote at 9:00am</li>
										<li><a href="#" id="tcdialog" style="color: blue;">TechCaf&eacute;</a> &amp; <a href="#" id="hldialog" style="color: blue">The Human Library</a> at 10:30am</li>
										<li>Lunch at 12:00pm</li>
									</ul>
									<strong>Note:</strong> If you choose Yes, you will receive an email once session registration is open.</em></span>
								</div><!-- ui-state-info: FoTC -->

								<span class="regboxtitle">Concurrent Sessions (From 1:15pm to 2:15pm)</span>
								<span class="block left-margin lower-space">Please select a session from the list below.</span>

END;

		if( $tueDaysRemaining == 0 )
		$select = "SELECT * FROM workshops WHERE time='AM' AND day='tue' AND datediff(now(), release_date)>=0 AND (start_time='1:15pm') OR workshopid=100 ORDER BY title;";

		$result = mysqli_query( $dbConnectionObject, $select );
		$workshopsStillOpen = 0;
		
		if( is_bool( $result ) ) {
			echoToConsole( "Failed to query for Tuesday AM workshops!", true );
		}// end if statement
		
		$n = 1;
		$n2 = mysqli_num_rows($result);
		while ($row = mysqli_fetch_array($result))
		{
			extract($row);
			
			$select2 = "select userid from registered where tue_amworkshop={$workshopid}";
			$result2 = mysqli_query( $dbConnectionObject, $select2 );
			
			if( is_bool( $result2 ) ) {
				echoToConsole( "Failed to query user's Tuesday AM workshop selection", true );
			}// end if statement
			
			$registered = mysqli_num_rows($result2);
			$seatsleft = $seats - $registered;
			
			$title = stripslashes($title);
			$description = stripslashes($description);
			
			$dclasses = "";
			if ($n == 1) {
				$dclasses .= "borderall ";
			} elseif ($n == $n2) {
				$dclasses .= "borderend ";
			} else {
				$dclasses .= "bordernotop ";
			}
			if ($workshopid == $tue_amworkshop) {
				$dclasses .= "wselected ";
			} else {
				if ($seatsleft == 0) {
					$dclasses .= "wfull ";
				}
			}
			echo "									<div class=\"left-margin {$dclasses}\">\n";
			if ($seatsleft <= 0) {
				if ($workshopid == $tue_amworkshop) {
					echo "										<input type=\"radio\" name=\"tue_amworkshop\" value=\"" . $workshopid . "\" checked> <strong>(FULL) {$title}</strong>\n";
				} else {
					echo "										<strong>(FULL) {$title}</strong>\n";
				}								
			} else {
				if ($workshopid == $tue_amworkshop) {
					echo "										<input type=\"radio\" name=\"tue_amworkshop\" value=\"" . $workshopid . "\" checked> <strong>{$title}</strong>\n";
				} else {
					echo "										<input type=\"radio\" name=\"tue_amworkshop\" value=\"" . $workshopid . "\"> <strong>{$title}</strong>\n";
				}
			}
			if ($workshopid == 100) {
				echo "										No session selected for this time slot\n";
			} else {
				echo '										<br><strong>Presenter(s):  ' . $presenter . '</strong>
										<br><strong>Seats left = ' . $seatsleft . '</strong>
										<br><br>' . "{$description}\n";
			}
			echo "									</div><!-- workshopContainer -->\n";
			$n += 1;
		}

		echo <<<END

									<span class="regboxtitle">Concurrent Sessions (From 2:30pm to 3:30pm)</span>
									<span class="block left-margin lower-space">Please select a session from the list below.</span>

END;

		$select = "SELECT * FROM workshops WHERE time='PM' AND day='tue' AND datediff(now(), release_date)>=0 AND (start_time='2:30pm') OR workshopid=101 ORDER BY title;";

		$result = mysqli_query( $dbConnectionObject, $select );
		$workshopsStillOpen = 0;
		
		if( is_bool( $result ) ) {
			echoToConsole( "Failed to query for Tuesday PM workshops!", true );
		}// end if statement
		
		$n = 1;
		$n2 = mysqli_num_rows($result);
		while ($row = mysqli_fetch_array($result))
		{
			extract($row);

			$select2 = "select userid from registered where tue_pmworkshop={$workshopid}";
			$result2 = mysqli_query( $dbConnectionObject, $select2 );
			
			if( is_bool( $result2 ) ) {
				echoToConsole( "Failed to query user's Tuesday PM workshop selection", true );
			}// end if statement
			
			$registered = mysqli_num_rows($result2);
			$seatsleft = $seats - $registered;
			
			$title = stripslashes($title);
			$description = stripslashes($description);

			$dclasses = "";
			if ($n == 1) {
				$dclasses .= "borderall ";
			} elseif ($n == $n2) {
				$dclasses .= "borderend ";
			} else {
				$dclasses .= "bordernotop ";
			}
			if ($workshopid == $tue_pmworkshop) {
				$dclasses .= "wselected ";
			} else {
				if ($seatsleft == 0) {
					$dclasses .= "wfull ";
				}
			}
			echo "									<div class=\"left-margin {$dclasses}\">\n";
			if ($seatsleft <= 0) {
				if ($workshopid == $tue_pmworkshop) {
					echo "										<input type=\"radio\" name=\"tue_pmworkshop\" value=\"{$workshopid}\" checked> <strong>(FULL) {$title}</strong>\n";
				} else {
					echo "										<strong>(FULL) {$title}</strong>\n";
				}								
			} else {
				if ($workshopid == $tue_pmworkshop) {
					echo "										<input type=\"radio\" name=\"tue_pmworkshop\" value=\"{$workshopid}\" checked> <strong>{$title}</strong>\n";
				} else {
					echo "										<input type=\"radio\" name=\"tue_pmworkshop\" value=\"{$workshopid}\"> <strong>{$title}</strong>\n";
				}
			}
			if ($workshopid == 101) {
				echo "										No session selected for this time slot\n";
			} else {
				echo '										<br><strong>Presenter(s):  ' . $presenter . '</strong>
										<br><strong>Seats left = ' . $seatsleft . '</strong>
										<br><br>' . "{$description}\n";
			}
			echo "									</div><!-- workshopContainer -->\n";
			$n += 1;
		}
	} else {
		echoToConsole( "FoTC workshops have not yet been released. Days remaining: {$tueDaysRemaining}", true );

		if( $fotcSeatsRemaining == 0 ) {
			echo <<<END

								<div class="lower-space ui-state-info">
									<p>Session registration for the conference will be ready soon. In the meantime, please let us know if you will be attending the conference so that we can email you when session registration becomes available.</p>
									<label>The Conference is currently full. Would you like to be added to a Waiting list in case a seat becomes available?</label>

END;
		} else {
			echo <<<END

								
								<div class="lower-space ui-state-info">
									<p>Session registration for the conference will be ready soon. In the meantime, please let us know if you will be attending the conference so that we can email you when session registration becomes available.</p>
									<label>Will you attend the Focus on Teaching Conference? ({$fotcSeatsRemaining} seats remaining)</label>

END;
		}// end if statement

		if( $fotc == "yes" || $fotc == "wl" ) {
			echo( "										<label><input id=\"attendFotc\" type=\"radio\" name=\"attendFotc\" value=\"yes\" checked>Yes</label> <label><input id=\"attendFotc\" type=\"radio\" name=\"attendFotc\" value=\"no\">No</label><br>\n" );
		} else {
			echo( "										<label><input id=\"attendFotc\" type=\"radio\" name=\"attendFotc\" value=\"yes\">Yes</label> <label><input id=\"attendFotc\" type=\"radio\" name=\"attendFotc\" value=\"no\" checked>No</label><br>\n" );
		}// end if statement

		echo "										<br><span class=\"block lower-space left-margin\"><em>The Focus on Teaching Conference includes:
														<ul>
															<li>Keynote at 9:00am</li>
															<li><a href=\"#\" id=\"tcdialog\" style=\"color: blue;\">TechCaf&eacute;</a> &amp; <a href=\"#\" id=\"hldialog\" style=\"color: blue\">The Human Library</a> at 10:30am</li>
															<li>Lunch at 12:00pm</li>
														</ul>
														<strong>Note:</strong> If you choose Yes, you will receive an email once session registration is open.</em>
													</span>\n
												</div><!-- ui-state-info: FoTC -->\n";
	}// end if statement
?>
								</div><!-- spacer -->
							</div><!-- subordinateRegbox -->
							<!-- End Tuesday Workshops -->

							<!-- Begin Wednesday Workshops -->
							<h3>Wednesday, April 30th  - PD Sessions</h3>
							<div class="subordinateRegbox">
<?php
if( !isset( $noWednesdayKeynote ) ) {
?>
								<div class="lower-space ui-state-info">
									<span class="block">Will you be attending Wednesday's Keynote?</span>
		<?php if ($wed_keynote == "yes") { ?>
									<label><input id="wed_keynote" type="radio" name="wed_keynote" value="yes" checked>Yes</label> <label><input id="wed_keynote" type="radio" name="wed_keynote" value="no">No</label>
		<?php } else { ?>
									<label><input id="wed_keynote" type="radio" name="wed_keynote" value="yes">Yes</label> <label><input id="wed_keynote" type="radio" name="wed_keynote" value="no" checked>No</label>
		<?php } ?>
									<button id="wed-keynote-info">More Info</button>
								</div><!-- ui-state-info -->
<?php
}// end if statement
?>
								<span class="regboxtitle">Concurrent Sessions (Starting in the Morning)</span>
								<span class="block left-margin lower-space">Please select a session from the list below.</span>
	<?php
		$select = "SELECT * FROM workshops WHERE time='AM' AND day='wed' AND datediff(now(), release_date)>=0 AND (start_time='9:00am' OR start_time='9:30am' OR start_time='10:00am' OR start_time='10:30am' OR start_time='11:00am' OR start_time='11:30am') OR workshopid=100 ORDER BY title;";

		$result = mysqli_query( $dbConnectionObject, $select );
		$workshopsStillOpen = 0;
		
		if( is_bool( $result ) ) {
			echoToConsole( "Failed to query for Wednesday AM workshops!", true );
		}// end if statement
		
		$n = 1;
		$n2 = mysqli_num_rows($result);
		while ($row = mysqli_fetch_array($result))
		{
			extract($row);
			
			$select2 = "select userid from registered where wed_amworkshop={$workshopid}";
			$result2 = mysqli_query( $dbConnectionObject, $select2 );
			
			if( is_bool( $result2 ) ) {
				echoToConsole( "Failed to query user's Wednesday AM workshop selection", true );
			}// end if statement
			
			$registered = mysqli_num_rows($result2);
			$seatsleft = $seats - $registered;
			
			$title = stripslashes($title);
			$description = stripslashes($description);
			
			$dclasses = "";
			if ($n == 1) {
				$dclasses .= "borderall ";
			} elseif ($n == $n2) {
				$dclasses .= "borderend ";
			} else {
				$dclasses .= "bordernotop ";
			}
			if ($workshopid == $wed_amworkshop) {
				$dclasses .= "wselected ";
			} else {
				if ($seatsleft == 0) {
					$dclasses .= "wfull ";
				}
			}
			echo "									<div class=\"left-margin $dclasses\">\n";
			if ($seatsleft <= 0) {
				if ($workshopid == $wed_amworkshop) {
					echo "										<input type=\"radio\" name=\"wed_amworkshop\" value=\"" . $workshopid . "\" checked> <strong>(FULL) $title</strong>\n";
				} else {
					echo "										<strong>(FULL) $title</strong>\n";
				}								
			} else {
				if ($workshopid == $wed_amworkshop) {
					echo "										<input type=\"radio\" name=\"wed_amworkshop\" value=\"" . $workshopid . "\" checked> <strong>$title</strong>\n";
				} else {
					echo "										<input type=\"radio\" name=\"wed_amworkshop\" value=\"" . $workshopid . "\"> <strong>$title</strong>\n";
				}
			}
			if ($workshopid == 100) {
				echo "										No session selected for this time slot\n";
			} else {
				echo '										<br><strong>Presenter(s):  ' . $presenter . '</strong>
										<br><strong>Seats left = ' . $seatsleft . '</strong>
										<br><strong>Location = ' . $room . '</strong>
										<br><br>' . "{$description}\n";
			}
			echo "									</div><!-- workshopContainer -->\n";
			$n += 1;
		}
	?>
									<span class="regboxtitle">Concurrent Sessions (Starting in the Afternoon)</span>
									<span class="block left-margin lower-space">Please select a session from the list below.</span>
	<?php
		$select = "SELECT * FROM workshops WHERE time='PM' AND day='wed' AND datediff(now(), release_date)>=0 AND (start_time='12:00pm' OR start_time='12:30pm' OR start_time='1:00pm' OR start_time='1:30pm' OR start_time='2:00pm') OR workshopid=101 ORDER BY title;";

		$result = mysqli_query( $dbConnectionObject, $select );
		$workshopsStillOpen = 0;
		
		if( is_bool( $result ) ) {
			echoToConsole( "Failed to query for Wednesday PM workshops!", true );
		}// end if statement
		
		$n = 1;
		$n2 = mysqli_num_rows($result);
		while ($row = mysqli_fetch_array($result))
		{
			extract($row);

			$select2 = "select userid from registered where wed_pmworkshop={$workshopid}";
			$result2 = mysqli_query( $dbConnectionObject, $select2 );
			
			if( is_bool( $result2 ) ) {
				echoToConsole( "Failed to query user's Wednesday PM workshop selection", true );
			}// end if statement
			
			$registered = mysqli_num_rows($result2);
			$seatsleft = $seats - $registered;
			
			$title = stripslashes($title);
			$description = stripslashes($description);

			$dclasses = "";
			if ($n == 1) {
				$dclasses .= "borderall ";
			} elseif ($n == $n2) {
				$dclasses .= "borderend ";
			} else {
				$dclasses .= "bordernotop ";
			}
			if ($workshopid == $wed_pmworkshop) {
				$dclasses .= "wselected ";
			} else {
				if ($seatsleft == 0) {
					$dclasses .= "wfull ";
				}
			}
			echo "									<div class=\"left-margin {$dclasses}\">\n";
			if ($seatsleft <= 0) {
				if ($workshopid == $wed_pmworkshop) {
					echo "										<input type=\"radio\" name=\"wed_pmworkshop\" value=\"{$workshopid}\" checked> <strong>(FULL) {$title}</strong>\n";
				} else {
					echo "										<strong>(FULL) {$title}</strong>\n";
				}								
			} else {
				if ($workshopid == $wed_pmworkshop) {
					echo "										<input type=\"radio\" name=\"wed_pmworkshop\" value=\"{$workshopid}\" checked> <strong>{$title}</strong>\n";
				} else {
					echo "										<input type=\"radio\" name=\"wed_pmworkshop\" value=\"{$workshopid}\"> <strong>{$title}</strong>\n";
				}
			}
			if ($workshopid == 101) {
				echo "										No session selected for this time slot\n";
			} else {
				echo '										<br><strong>Presenter(s):  ' . $presenter . '</strong>
										<br><strong>Seats left = ' . $seatsleft . '</strong>
										<br><strong>Location = ' . $room . '</strong>
										<br><br>' . "{$description}\n";
			}
			echo "									</div><!-- workshopContainer -->\n";
			$n += 1;
		}
	?>
									<span class="regboxtitle">Concurrent Sessions (Starting at: 2:30pm)</span>
									<span class="block left-margin lower-space">Please select a session from the list below.</span>
	<?php
		$select = "SELECT * FROM workshops WHERE time='PM' AND day='wed' AND datediff(now(), release_date)>=0 AND start_time='2:30pm' OR workshopid=101 ORDER BY title;";

		$result = mysqli_query( $dbConnectionObject, $select );
		$workshopsStillOpen = 0;
		
		if( is_bool( $result ) ) {
			echoToConsole( "Failed to query for Wednesday PM-2 workshops!", true );
		}// end if statement
		
		$n = 1;
		$n2 = mysqli_num_rows($result);
		while ($row = mysqli_fetch_array($result))
		{
			extract($row);

			$select2 = "select userid from registered where wed_pmworkshop2={$workshopid}";
			$result2 = mysqli_query( $dbConnectionObject, $select2 );
			
			if( is_bool( $result2 ) ) {
				echoToConsole( "Failed to query user's Wednesday PM-2 workshop selection", true );
			}// end if statement
			
			$registered = mysqli_num_rows($result2);
			$seatsleft = $seats - $registered;
			
			$title = stripslashes($title);
			$description = stripslashes($description);

			$dclasses = "";
			if ($n == 1) {
				$dclasses .= "borderall ";
			} elseif ($n == $n2) {
				$dclasses .= "borderend ";
			} else {
				$dclasses .= "bordernotop ";
			}
			if ($workshopid == $wed_pmworkshop2) {
				$dclasses .= "wselected ";
			} else {
				if ($seatsleft == 0) {
					$dclasses .= "wfull ";
				}
			}
			echo "									<div class=\"left-margin {$dclasses}\">\n";
			if ($seatsleft <= 0) {
				if ($workshopid == $wed_pmworkshop2) {
					echo "										<input type=\"radio\" name=\"wed_pmworkshop2\" value=\"{$workshopid}\" checked> <strong>(FULL) {$title}</strong>\n";
				} else {
					echo "										<strong>(FULL) {$title}</strong>\n";
				}								
			} else {
				if ($workshopid == $wed_pmworkshop2) {
					echo "										<input type=\"radio\" name=\"wed_pmworkshop2\" value=\"{$workshopid}\" checked> <strong>{$title}</strong>\n";
				} else {
					echo "										<input type=\"radio\" name=\"wed_pmworkshop2\" value=\"{$workshopid}\"> <strong>{$title}</strong>\n";
				}
			}
			if ($workshopid == 101) {
				echo "										No session selected for this time slot\n";
			} else {
				echo '										<br><strong>Presenter(s):  ' . $presenter . '</strong>
										<br><strong>Seats left = ' . $seatsleft . '</strong>
										<br><strong>Location = ' . $room . '</strong>
										<br><br>' . "{$description}\n";
			}
			echo "									</div><!-- workshopContainer -->\n";
			$n += 1;
		}
?>
							</div><!-- subordinateRegbox -->
							<!-- End Wednesday Workshops -->

							<!-- Begin Thursday Workshops -->
							<h3>Thursday, May 1st - Extraordinary Experience, College-wide Update</h3>
							<div class="subordinateRegbox">
								<span class="regboxtitle">Concurrent Sessions (Starting in the Morning)</span>
								<span class="block left-margin lower-space">Please select a session from the list below.</span>
<?php
		$select = "SELECT * FROM workshops WHERE time='AM' AND day='thu' AND datediff(now(), release_date)>=0 AND (start_time='9:00am' OR start_time='9:30am' OR start_time='10:00am' OR start_time='10:30am' OR start_time='11:00am' OR start_time='11:30am') OR workshopid=100 ORDER BY title;";

		$result = mysqli_query( $dbConnectionObject, $select );
		$workshopsStillOpen = 0;
		
		if( is_bool( $result ) ) {
			echoToConsole( "Failed to query for Thursday AM workshops!", true );
		}// end if statement
		
		$n = 1;
		$n2 = mysqli_num_rows($result);
		while ($row = mysqli_fetch_array($result))
		{
			extract($row);
			
			$select2 = "select userid from registered where thur_amworkshop={$workshopid}";
			$result2 = mysqli_query( $dbConnectionObject, $select2 );
			
			if( is_bool( $result2 ) ) {
				echoToConsole( "Failed to query user's Thursday AM workshop selection", true );
			}// end if statement
			
			$registered = mysqli_num_rows($result2);
			$seatsleft = $seats - $registered;
			
			$title = stripslashes($title);
			$description = stripslashes($description);
			
			$dclasses = "";
			if ($n == 1) {
				$dclasses .= "borderall ";
			} elseif ($n == $n2) {
				$dclasses .= "borderend ";
			} else {
				$dclasses .= "bordernotop ";
			}
			if ($workshopid == $thur_amworkshop) {
				$dclasses .= "wselected ";
			} else {
				if ($seatsleft == 0) {
					$dclasses .= "wfull ";
				}
			}
			echo "									<div class=\"left-margin {$dclasses}\">\n";
			if ($seatsleft <= 0) {
				if ($workshopid == $thur_amworkshop) {
					echo "										<input type=\"radio\" name=\"thu_amworkshop\" value=\"{$workshopid}\" checked> <strong>(FULL) {$title}</strong>\n";
				} else {
					echo "										<strong>(FULL) {$title}</strong>\n";
				}								
			} else {
				if ($workshopid == $thur_amworkshop) {
					echo "										<input type=\"radio\" name=\"thu_amworkshop\" value=\"{$workshopid}\" checked> <strong>{$title}</strong>\n";
				} else {
					echo "										<input type=\"radio\" name=\"thu_amworkshop\" value=\"{$workshopid}\"> <strong>{$title}</strong>\n";
				}
			}
			if ($workshopid == 100) {
				echo "										No session selected for this time slot\n";
			} else {
				echo '										<br><strong>Presenter(s):  ' . $presenter . '</strong>
										<br><strong>Seats left = ' . $seatsleft . '</strong>
										<br><strong>Location = ' . $room . '</strong>
										<br><br>' . "{$description}\n";
			}
			echo "									</div><!-- workshopContainer -->\n";
			$n += 1;
		}

		if( !isset( $noThursdayKeynote ) ) {
?>
								<span class="regboxtitle upper-space">Starting in the Afternoon (1:00pm)</span>

								<div class="upper-space lower-space">
									<div class="ui-state-info">
										<span class="block">Will you be attending the College-wide update?</span>
<?php
			if( !$thursdayKeynoteFull ) {
				if ($thur_keynote == "yes") {
					echo '										<label><input id="thur_keynote" type="radio" name="thur_keynote" value="yes" checked>Yes</label> <label><input id="thur_keynote" type="radio" name="thur_keynote" value="no">No</label>';
				} else {
					echo '										<label><input id="thur_keynote" type="radio" name="thur_keynote" value="yes">Yes</label> <label><input id="thur_keynote" type="radio" name="thur_keynote" value="no" checked>No</label>';
				}// end if statement
			} else {
				echo '<p><strong>(We\'re sorry, but the seats are FULL)</strong></p>';
			}// end if statement
?>

										<span class="block upper-space lower-space">
											<strong>Time: </strong> 1:00pm-2:00pm (Doors Open at 12:30pm)<br>
											<strong>Speaker: </strong>President & CEO MaryLynn West-Moynes<br>
											<strong>Location: Alumni Hall/Barrie Campus</strong><br><br>
											<strong>Note: </strong><em>The college-wide update will be live streamed to the other campus locations for those employees who cannot attend at the Barrie campus and details will be provided as we get closer to the event.</em>
										</span>
									</div><!-- ui-state-info -->
								</div><!-- spacer -->
							</div><!-- subordinateRegbox -->
							<!-- End Thursday Workshops -->
<?php
		}// end if statement (no thur_keynote)
?>
						</div><!-- workshopAccordion -->
						<!-- End Workshop Accordion -->
					</div><!-- regbox -->
					<p class="warning">Please click on the Submit button only after selecting your sessions.</p>
					<p style="text-align: right">
						<input type="submit" value="Submit" class="button-green">
					</p>

				</form>
			</div><!-- formbox2 -->

<?php
		
} // end if statement (prevfound=0)

	$flt_time_end = (float) microtime( true );
	$flt_time_duration = (float) $flt_time_end - $flt_time_start;
	echoToConsole( "Executed in: {$flt_time_duration} seconds.", true );
?>
		</div><!-- main -->
    </body>
</html>