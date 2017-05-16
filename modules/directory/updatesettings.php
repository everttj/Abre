<?php

	/*
	* Copyright 2015 Hamilton City School District
	*
	* This program is free software: you can redistribute it and/or modify
    * it under the terms of the GNU General Public License as published by
    * the Free Software Foundation, either version 3 of the License, or
    * (at your option) any later version.
	*
    * This program is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU General Public License for more details.
	*
    * You should have received a copy of the GNU General Public License
    * along with this program.  If not, see <http://www.gnu.org/licenses/>.
    */

	//Required configuration files
	require(dirname(__FILE__) . '/../../configuration.php');
	require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require('permissions.php');
	require_once('functions.php');

	//Update Directory Settings
	if($pageaccess==1)
	{
		foreach($_POST as $key => $value){
			$query = $db->query("SELECT * FROM `directory_settings` WHERE dropdownID ='$key'");

			if($query->num_rows > 0){
				$stmt = $db->stmt_init();
				$sql = "UPDATE `directory_settings` set `options`='$value' WHERE `dropdownID`='$key'";
				$stmt->prepare($sql);
				$stmt->execute();
			}
			else{
				$stmt = $db->stmt_init();
				$sql = "INSERT INTO `directory_settings` (`dropdownID`,`options`) VALUES ('$key','$value');";
				$stmt->prepare($sql);
				$stmt->execute();
			}
		}
		$stmt->close();
		$db->close();
		echo "Settings have been updated.";
  }

?>