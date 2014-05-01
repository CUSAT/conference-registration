<?php
	/**
	 * loadParser.php	-	pdweek v1.3
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
	 * The parseLoadFile function will convert a data file's contents,
	 * from a string, into an array. Once the array is created, the
	 * large string is destroyed and the array is returned.
	 * 
	 * @param $str_loadContentIn (string) File contents as a string.
	 * 
	 * @return (array)	A 2-dimensional array representation of the CSVs.
	 */
	function parseLoadFile( $str_loadContentIn ) {
		$ary_loadContent_fullFile = (array) str_getcsv( $str_loadContentIn, "\n" );
		$ary_loadContent_multiDimensions = (array) Array();

		foreach( $ary_loadContent_fullFile as $str_record ) {
			$ary_loadContent_record = (array) str_getcsv( $str_record, ',', '"' );
			$ary_loadContent_multiDimensions[] = $ary_loadContent_record;
		}// end foreach loop

		return (array) $ary_loadContent_multiDimensions;
	}// end parseLoadFile function
?>