<!DOCTYPE html>
<?php
	/*
	 * index.php v1.3.4	-	pdweek
	 */

	require_once( 'data/environment.php' );

	$ctlIn = filter_input(INPUT_GET, 'ctl', FILTER_SANITIZE_NUMBER_INT);
	if( !empty( $ctlIn ) ) {
		$ctlMember = (bool) true;
	} else {
		$ctlMember = (bool) false;
	}// end if statemenet

?>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title>Georgian College :: <?=$str_pdweekName;?> <?=$str_currentYear;?></title>
		<link rel="stylesheet" href="css/gl.css" type="text/css">
		<script type="text/javascript" src="js/checkregfields.js"></script>
	</head>
	<body>
		<div class="main ui-corner-bottom">
<?php
	include "pdweek.php";
	$usersQuery = "select userid from users";

	// Open the database connection
	$dbConnectionObject = @mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );
	
	// Die on connection failures. Link to mailto:$str_supportEmail with a nice interface.
	if( mysqli_connect_error() ) {

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
				</div>
			</div></div></body></html>';

		$dbConnectionObject = null;
		unset( $dbConnectionObject );

		//exit execution of the script here
		exit();
	}// end if statement

	//Set the character set, for use with mysqli_real_escape_string
	mysqli_set_charset( $dbConnectionObject, $str_dbCharset );

	//Query the Database
	$usersResultObject = mysqli_query( $dbConnectionObject, $usersQuery );

	//Close the Database connection
	mysqli_close( $dbConnectionObject );

	if( !$ctlMember ) {
?>
			<h3>Registration Information</h3>
			<p>In total, there are 3 steps to the registration process:</p>
			<ol>
				<li>To begin your registration process, please provide us with your Georgian email address below.</li>
				<li>After submitting your address below you will receive an email with a link. This step will help us verify you as Georgian employee. Please click on the link emailed to you to proceed to step 3 of the registration process. </li>
				<li>In the 3rd and final step, you will be asked to provide us with additional information about yourself. Additionally, you will be able to customize your PD Week to suit your interests.</li>
			</ol>
			<div class="formbox rounded-corner">
				<form name="registration" action="register1-regopen.php" method="post" accept-charset="utf-8" onsubmit="return checkEmail();">
					<script>lastEntered = '';</script>
					<h4>Step 1</h4>
					Please enter the first part of your Georgian email address
					<br>(Example, <strong>Jane.Smith</strong>)
					<br><input name="emailnew" class="input-text" type="text" width="20" maxlength="30" value="FirstName.LastName" onClick="lastEntered=this.value; this.value='';" onBlur="this.value=!this.value?lastEntered:this.value;">@georgiancollege.ca
					<p>If you have any questions, please email <a href="mailto:<?=$str_supportEmail;?>"><?=$str_supportEmail;?></a></p>
					<p><input class="button-green" type="submit" value="Submit"></p>
				</form>
			</div>
<?php
	} else {
?>
			<h3>Pre-Registration for <q>special</q> users (CTL use only)</h3>
			<div class="ui-state-info upper-space lower-space">
				<p>User will be registered with the following options:</p>
				<ul>
					<li>Monday Keynote = No</li>
					<li>Monday Lunch = No</li>
					<li>FoTC = Yes</li>
					<li>Tuesday Lunch = Yes</li>
					<li>Thursday Keynote = No</li>
					<li>Thursday Lunch = No</li>
					<li>Reviewed Profile = No</li>
					<li>Registered = No</li>
				</ul>
			</div>
			<div class="formbox rounded-corner">
				<form name="registration" action="register1-regopen.php" method="post" accept-charset="utf-8" onsubmit="return checkEmail();">
					<script>lastEntered = '';</script>
					<h4>Step 1</h4>
					Please enter the first part of your Georgian email address
					<br>(Example, <strong>John.Smith</strong>)
					<br><input name="emailnew" class="input-text" type="text" width="20" maxlength="30" value="FirstName.LastName" onClick="lastEntered=this.value; this.value='';" onBlur="this.value=!this.value?lastEntered:this.value;">@GeorgianCollege.ca
					<p>If you have any questions, please email <a href="mailto:<?=$str_supportEmail;?>"><?=$str_supportEmail;?></a></p>
					<input type="hidden" name="ctlMember" value="1">
					<p><input class="button-green" type="submit" value="Submit"></p>
				</form>
			</div>
<?php
	}// end if statement
?>
		</div>
    </body>
</html>