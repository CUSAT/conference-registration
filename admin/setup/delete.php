<?php

	/*
	 * delete.php	-	pdweek v1.3
	 */

	if( !isset( $str_appURL ) ) {
		require_once( "../../data/environment.php" );
	}// end if statement

	// HTTP GET challenge
	if( !isset( $_GET['target'] ) ) {
		die( "An error occured, aborting!..\n\nThis tool expects to be passed a parameter named \"target\" to control which database table is emptied." );
	}

	echo( "<p>Deleting all records from the <strong>" . htmlentities( $_GET['target'] ) . "</strong> table...</p>\n" );

	//Connect to the database
	$dbConnectionObject = mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb )
		or die( "Failed to connect to database. Impossible to continue." . mysqli_error( $dbConnectionObject  ) );

	// Set the character set, for use with mysqli_real_escape_string
	mysqli_set_charset( $dbConnectionObject, $str_dbCharset );

	$deleteQuery = "delete from " . mysqli_real_escape_string( $dbConnectionObject, $_GET['target'] );

	$deleteResult = mysqli_query( $dbConnectionObject, $deleteQuery );

	if( $deleteResult ) {
		echo( "<p style=\"color: green;\">Finished deleting " . mysqli_affected_rows( $dbConnectionObject ) . " records from the <strong>" . htmlentities( $_GET['target'] ) . "</strong> table</p>\n" );
	} else {
		echo( "<p style=\"color: red;\">There was an internal issue with the database.</p>\n" );
	}

	// Close the database connection
	mysqli_close( $dbConnectionObject );
?>
