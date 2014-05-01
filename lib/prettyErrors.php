<?php

	if( !isset( $str_supportEmail ) ) {
		if( file_exists( 'data/environment.php' ) ) {
			require_once( 'data/environment.php' );
		} else if( file_exists( '../data/environment.php' ) ) {
			require_once( '../data/environment.php' );
		} else if( file_exists( '../../data/environment.php' ) ) {
			require_once( '../../data/environment.php' );
		} else if( file_exists( '../../../data/environment.php' ) ) {
			require_once( '../../../data/environment.php' );
		}// end if statement
	}// end if statement

	function showPrettyError( $str_messageIn, $str_errorLevel = 'info', $bln_isAlreadyWrapped = false ) {
		global $str_supportEmail, $str_pdweekName, $str_currentYear;

		if( $bln_isAlreadyWrapped === false ) {
			echo '</head><body><div class="main ui-corner-bottom">';

			if( file_exists( 'pdweek.php' ) ) {
				require_once 'pdweek.php';
			} else if( file_exists( '../pdweek.php' ) ) {
				require_once '../pdweek.php';
			} else if( file_exists( '../../pdweek.php' ) ) {
				require_once '../../pdweek.php';
			} else if( file_exists( '../../../pdweek.php' ) ) {
				require_once '../../../pdweek.php';
			}// end if statement

		}// end if statement

		echo '<div class="ui-widget"><div class="ui-state-' . $str_errorLevel . ' ui-corner-all" style="padding: 0 .7em;"> 
			<p>' . $str_messageIn . '</p>
			</div><!-- ui-state-error --></div><!-- ui-widget --></div><!-- main --></body></html>';

		//Discard whatever memory was used
		if( isset( $dbConnectionObject ) ) {
			//Are we connected?
			if( is_object( $dbConnectionObject ) ) {
				mysqli_close( $dbConnectionObject );
			}// end if statement

			$dbConnectionObject = null;
			unset( $dbConnectionObject );
		}// end if statement

		//Stop execution of the script here
		exit();
	}// end showPrettyError function