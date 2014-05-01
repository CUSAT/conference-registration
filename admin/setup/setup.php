<?php
	/*
	 * setup.php v1.1	- 	pdweek
	 * 
	 */

	if( isset( $bln_subComponent ) ) {	// Is this loaded in index.php?
		if( isset( $_POST['setupAction'] ) || isset( $_GET['setupAction'] ) ) {	// Are we performing an action?

			if( isset( $_POST['setupAction'] ) ) {
				$_GET['setupAction'] = $_POST['setupAction'];
			} else {
				$_POST['setupAction'] = $_GET['setupAction'];
			}// end if statement

			switch( $_POST['setupAction'] ) {	// Which action is performed

				/**
				 * Delete
				 */
				case "delete":
					if( isset( $_POST['deleteTarget'] ) ) {

						//Set the 'target' variable for delete.php
						$_GET['target'] = $_POST['deleteTarget'];

						//Run delete.php
						if( file_exists( "setup/delete.php" ) ) {
							require_once( "setup/delete.php" );
						} else {
							echo( "<p style=\"color: red;\">Could not locate the required module.</p>\n" );
						}

					} else {
						echoToConsole( "Confirm choice was not set, displaying form", true );

						echo( "				<h3>Delete Records in a Table</h3>\n
					<form method=\"post\" action=\"index.php?action=setup\" onSubmit=\"return confirmDelete();\">\n
						Select a table in the {$str_dbDb} database to empty:<br>\n
						&nbsp;&nbsp;<select name=\"deleteTarget\" id=\"deleteTarget\">\n
							<option value=\"users\">Users</option>\n
							<option value=\"registered\">Registered</option>\n
							<option value=\"waitlist\">Waitlist</option>\n
							<option value=\"workshops\">Workshops</option>\n
							<option value=\"keynotes\">Keynotes</option>\n
						</select>\n
						<input type=\"hidden\" name=\"setupAction\" value=\"delete\" id=\"setupAction\">\n
						<input type=\"submit\" value=\"submit\" id=\"deleteSubmit\">\n
					</form>\n" );
					}// end if statement
					break;

				/**
				 * Reset Keynotes
				 */
				case "reset-keynotes":

					if( !isset( $_GET['confirm'] ) ) {
						echoToConsole( "confirm choice was not set!", true );
						echo( "<script>confirmReset();</script>\n" );
					} else {

						if( $_GET['confirm'] == "yes" ) {
							echoToConsole( "User chose Yes as confirm choice", true );
							
							//Run resetKeynotes.php
							if( file_exists( "setup/resetKeynotes.php" ) ) {
								require_once( "setup/resetKeynotes.php" );
							} else {
								echo( "<p style=\"color: red;\">Could not locate the required module.</p>\n" );
							}// end if statement
						} else {
							echoToConsole( "User chose No as confirm choice", true );
						}// end if statement

					}// end if statement

					break;

				/**
				 * Load Keynotes
				 */
				case "load-keynotes":

					if( !isset( $_GET['confirm'] ) ) {
						echoToConsole( "confirm choice was not set!", true );
						echo( "<script>confirmLoad( \"keynotes\" );</script>\n" );
					} else {

						if( $_GET['confirm'] == "yes" ) {
							echoToConsole( "User chose Yes as confirm choice", true );

							//Run loadKeynotes.php
							if( file_exists( "setup/loadKeynotes.php" ) ) {
								require_once( "setup/loadKeynotes.php" );
							} else {
								echo( "<p style=\"color: red;\">Could not locate the required module.</p>\n" );
							}// end if statement

						} else {
							echoToConsole( "User chose No as confirm choice", true );
						}// end if statement

					}// end if statement

					break;

				/**
				 * Load Workshops
				 */
				case "load-workshops":

					echo '<h3>Load workshops from a CSV file</h3>';

					//Run loadWorkshops.php
					if( file_exists( "setup/loadWorkshops.php" ) ) {
						require_once( "setup/loadWorkshops.php" );
					} else {
						echo( "<p style=\"color: red;\">Could not locate the required module.</p>\n" );
					}// end if statement

					break;

				/**
				 * Default Action
				 */
				default:
					//
					//No default action
					//
					break;
			}// end switch case

		} else {
			echoToConsole( "setupAction was not set, displaying form", true );

			echo <<<END

				<br><strong>Waitlist Controls:</strong>&nbsp;
				<a href="index.php?action=inviteWaitlisted">Invite all Waitlisted users</a><br><br>

				<h3>Setup the {$str_pdweekName} Registration System.</h3>\n
				<form method="post" action="index.php?action=setup">\n
					Select a setup action to perform:<br>\n
					&nbsp;&nbsp;<select name="setupAction" id="setupAction">\n
						<option value="default" selected> -- Choose and Action -- </option>\n
						<optgroup label="Keynote Actions">
							<option value="load-keynotes">Load New Keynotes</option>\n
							<option value="reset-keynotes">Reset the Keynote Seats</option>\n
						</optgroup>
						<optgroup label="Workshop Actions">
							<option value="load-workshops">Load New Workshops</option>\n
						</optgroup>
						<optgroup label="Database Actions">
							<option value="delete">Delete All Records From a Table</option>\n
						</optgroup>
					</select>\n
					<input type="submit" value="submit">\n
				</form>

END;
		}// end if statement
	} else {
		echo( "<p style=\"color: red;\">This is a setup component and cannot be used directly. Use it from the PDWeek administrative Dashboard.</p>\n" );
	}// end if statement
?>
