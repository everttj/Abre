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
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php'); 
	require(dirname(__FILE__) . '/../../core/abre_functions.php'); 

	$VendorLinkURL=sitesettings("sitevendorlinkurl");
	$json=vendorLinkGet("$VendorLinkURL/GBService/HA/teacher");
	
	//Retrieve employee information from database
	foreach ($json as $key => $result) 
	{
	    foreach ($result as $key => $result)
		{
			$employeeTeacherID=encrypt($result[TeacherID]);
			$staffrefid=$result[ExternalRefId];
			$json2=vendorLinkGet("$VendorLinkURL/SisService/Staff?staffPersonalRefId=$staffrefid");
			foreach ($json2 as $key => $result) 
			{
				foreach ($result as $key => $result)
				{
					$employeeemail=$result[EmailList][0][Value];
					$employeeemailencrypted=encrypt($employeeemail, "");
					$employeeRefID=encrypt($result[RefId]);
					$employeeStateID=encrypt($result[StateProvinceId]);
					$employeeLocalId=encrypt($result[LocalId]);
					
					//Add information to employee database
					mysqli_query($db, "UPDATE directory set RefID='$employeeRefID', StateID='$employeeStateID', TeacherID='$employeeTeacherID', LocalId='$employeeLocalId' where email='$employeeemailencrypted'");
				}
			}	
		}
	}
	
?>