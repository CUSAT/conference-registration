<?php
/**
 * fileUploads.php v1.1	-	pdweek
 * 
 * Copyright 2014 Micheal Walls <michealpwalls@gmail.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 */

	//Load echoToConsole function
	if( !function_exists( echoToConsole ) ) {
		require_once( "logging.php" );
	}// end if statement

	/**
	 * The getFileUpload function is a simple wrapper for file uploads.
	 * Makes use of echoToConsole function in logging.php to log errors
	 * to the browser's javascript debugging console.
	 * 
	 * @param $fileNameIn (string) Name of input containing file.
	 * @param $fileTypeIn (string) An acceptable file type.
	 * @return (bool)	True on successful fetch & storage of file,
	 * 					False upon unrecoverable failures.
	 * 
	 * @return (string) If fetch succeeds but storage fails, file
	 * 					contents will be returned as a string.
	 */
	function getFileUpload( $fileNameIn, $fileTypeIn, $targetIn ) {
		//Map local reference to global variable
		global $str_appLocation;

// Is a file incoming?
		if( isset( $_FILES[$fileNameIn] ) ) {

	// Incoming File
			echoToConsole( "A file is incoming", true );

			if ($_FILES[$fileNameIn]["error"] > 0) {
		// Error with incoming file
				echoToConsole( "There was an error with the incoming file: {$_FILES[$fileNameIn]['error']}", true );
				echo( "<p style=\"color: red;\">There was an error with the incoming file: {$_FILES[$fileNameIn]['error']}</p>\n" );
				return false;
			} else {
		// Good file received!
				echoToConsole( "A file was received!", true );
				// If an 'uploads' directory does not exist, create one
				if( !file_exists( "{$str_appLocation}admin/setup/uploads" ) ) {
					echoToConsole( "Upload directory did not exist, attempting to create it", true );

					$mkdirResult = mkdir( "{$str_appLocation}admin/setup/uploads" );

					if( $mkdirResult ) {
						echoToConsole( "Upload directory created successfully.", true );
					} else {
						echoToConsole( "Failed to create upload directory!", true );
					}// end if statement

				}// end if statement
				/**
				 * Move the file from /tmp/ area to permanent storage
				 * in ./uploads area
				 */
				$bln_moveResult = @move_uploaded_file( $_FILES[$fileNameIn]["tmp_name"], "{$str_appLocation}admin/setup/uploads/{$fileNameIn}" );

				if( $bln_moveResult ) {
					// All done!
					echoToConsole( "The file was successfully saved to the server", true );
					return true;
				} else {
					/**
					* The "magic" of PHP lets us return whatevs
					* I'm PHP, I do what I want :P
					 */
					echoToConsole( "The file was NOT saved to the server successfully. STORAGE FAILED!", true );
					echoToConsole( "Returning file contents as a STRING.", true );

					$str_fileContents = (string) file_get_contents( $_FILES[$fileNameIn]['tmp_name'] );
					
					//Can't store file, so return contents as a string
					return (string) $str_fileContents;
				}// end if statment
			}// end if statement
		} else {

// No incoming file, show Input Form
			echoToConsole( "No incoming file, displaying input form", true );
			echo <<<END

	<form class="upper-space left-margin" action="index.php?action=setup&setupAction=load-{$targetIn}&confirm=yes" method="post" enctype="multipart/form-data">
		<label for="{$fileNameIn}">Keynote Archive File:</label>
		<input type="file" name="{$fileNameIn}" id="{$fileNameIn}"><br><br>
		<label for="dataStructure">Please describe the structure of the incoming data</label><br>
		<input type="text" name="dataStructure" size="60" value="(title,description,seats,presenter,start_time,day,release_date)"><br>
		<input type="hidden" name="setupAction" value="load-workshops">
		<input type="submit" name="submit" value="Submit">
	</form>

END;
		}// end if statement
	}// end getFileUpload() function
?>