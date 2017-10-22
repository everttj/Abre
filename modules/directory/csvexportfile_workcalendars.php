<?php

	/*
	* Copyright (C) 2016-2017 Abre.io LLC
	*
	* This program is free software: you can redistribute it and/or modify
    * it under the terms of the Affero General Public License version 3
    * as published by the Free Software Foundation.
	*
    * This program is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU Affero General Public License for more details.
	*
    * You should have received a copy of the Affero General Public License
    * version 3 along with this program.  If not, see https://www.gnu.org/licenses/agpl-3.0.en.html.
    */

	//Required configuration files
	require(dirname(__FILE__) . '/../../configuration.php');
	require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
	require_once('permissions.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');

	if($pageaccess == 1){

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=staffworkcalendars.csv');
		$output = fopen('php://output', 'w');
		$array = array('First Name', 'Last Name', 'Email', 'Contracted Days');
		$setupCSV = false;

		include "../../core/abre_dbconnect.php";
		$rows = mysqli_query($db, 'SELECT * FROM directory WHERE archived = 0');
		while ($row = mysqli_fetch_assoc($rows)) {
			$email = htmlspecialchars($row["email"], ENT_QUOTES);
			$email = stripslashes(decrypt($email, ""));
			$firstname = htmlspecialchars($row["firstname"], ENT_QUOTES);
			$firstname = stripslashes(decrypt($firstname, ""));
			$lastname = htmlspecialchars($row["lastname"], ENT_QUOTES);
			$lastname = stripslashes(decrypt($lastname, ""));
			$contractdays = htmlspecialchars($row["contractdays"], ENT_QUOTES);
			$contractdays = stripslashes(decrypt($contractdays, ""));
			if($contractdays != ""){
				$rowsselected = mysqli_query($db, "SELECT * FROM profiles WHERE email = '$email'");
				while ($rowselect = mysqli_fetch_assoc($rowsselected)){
					$data = [$firstname, $lastname, $email, $contractdays];
					$work_calendar = $rowselect["work_calendar"];
					$work_calendar_decode = json_decode($work_calendar, true);
					foreach($work_calendar_decode as $key => $value){
						$entry2 = $key . " Selected Days Count";
						$entry3 = $key . " Selected Days";
						array_push($array, $entry2, $entry3);
					}
					if(!$setupCSV){
						fputcsv($output, $array);
						$setupCSV = true;
					}
					foreach($work_calendar_decode as $key => $value){
						$work_calendar_count = substr_count($value, ",");
						if($work_calendar_count != 0){ $work_calendar_count=$work_calendar_count + 1; }
						array_push($data, $work_calendar_count, $value);
					}
					fputcsv($output, $data);
				}
			}
		}
		// fputcsv($output, $data);
		fclose($output);
		mysqli_close($db);
		exit();
	}
?>