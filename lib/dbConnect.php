<?php
/**
 * dbConnect.php v0.1	-	pdweek
 */

	if( !isset( $str_dbDomain ) ) {
		if( file_exists( 'data/environment.php' ) ) {
			require_once 'data/environment.php';
		} else if( file_exists( '../data/environment.php' ) ) {
			require_once '../data/environment.php';
		} else if( file_exists( '../../data/environment.php' ) ) {
			require_once '../../data/environment.php';
		} else if( file_exists( '../../../data/environment.php' ) ) {
			require_once '../../../data/environment.php';
		}// end if statement
	}// end if statement

	if( @!function_exists(showPrettyError) ) {
		if( file_exists( 'prettyErrors.php' ) ) {
			require_once 'prettyErrors.php';
		} else if( file_exists( 'lib/prettyErrors.php' ) ) {
			require_once 'lib/prettyErrors.php';
		} else if( file_exists( '../lib/prettyErrors.php' ) ) {
			require_once '../lib/prettyErrors.php';
		} else if( file_exists( '../../lib/prettyErrors.php' ) ) {
			require_once '../../lib/prettyErrors.php';
		} else if( file_exists( '../../../lib/prettyErrors.php' ) ) {
			require_once '../../../lib/prettyErrors.php';
		}// end if statement
	}// end if statement

	// Open the database connection
	$dbConnectionObject = @mysqli_connect( $str_dbDomain, $str_dbUser, $str_dbPass, $str_dbDb );

	// Die on connection failures. Link to mailto:$str_supportEmail with a nice interface.
	if( mysqli_connect_error() ) {
		$errorMessage = '<strong>Alert:</strong> Our system could not connect to the internal database.
			This is likely because you followed an old (archived) link. If you continue to reach this error page, please contact
			<a href="mailto:' . $str_supportEmail . '" style="color: blue;">' . $str_supportEmail . '</a>
			for further assistance.<br><br>If you are a developer, the connection error is below:
			<span class="block upper-space lower-space left-margin"><strong>Error Code</strong> '
			. mysqli_connect_errno() . '<br><strong>Error</strong> ' . mysqli_connect_error() . '</span>';

		showPrettyError( $errorMessage, 'error', false );
	}// end if statement

	// Set the character set, for use with mysqli_real_escape_string
	mysqli_set_charset( $dbConnectionObject, $str_dbCharset );