<?php
	error_reporting(0) ;
    class commonClass
	{
		var $getSideClick_ ;
		var $tblname = "" ;					  //// table name
		var $dblink = "" ;					  //// database connection handle
		var $pTitle = "" ;
		var $rpp = 15;						  //// records per page display
		var $imageWidth = 300 ;				  //// image width 300px 
		var $imageMagickStatus = 1 ;          //// if image magick suport is on your server set to 1
		var $thumbnailImagePath = "thumb_img/" ;
		var $thumbnailImageWidth = 130 ;
		var $thumbnailImageHeight = 110 ;
		var $accepetedPicture = array("image/pjpeg", "image/jpeg", "image/gif", "image/png", "image/x-png") ;
		var $accepetedVideo = array( "mpeg" , "mpg" , "avi" , "3gp" , "wmv" , "wm" ) ;
		var $displayRecordPerPage = array( "showall"=>"Show All",
										   "10"=>"10" , 
										   "15"=>"15" , 
										   "20"=>"20" , 
										   "30"=>"30" , 
										   "40"=>"40" , 
										   "50"=>"50" , 
										   "60"=>"60" , 
										   "70"=>"70" , 
										   "80"=>"80" , 
										   "90"=>"90" , 
										   "100"=>"100" ) ;

		function __construct($flag='' , $pageTitle='')
		{
			error_reporting(0) ;
			session_start() ;
			$leftPanelFlag = "" ;
			$this->pTitle = $pageTitle ;
			
			require_once( "config.php" ) ;

			/// database connection
			$dbobj = new DBCONFIG ;

		    $hostName = $dbobj->DB_HOST ;         
			$userName = $dbobj->DB_USER ;      
			$password = $dbobj->DB_PASSWORD ; 
			$dbName =   $dbobj->DB_NAME ; 

		    $this->dblink = $this->dbconnection($hostName , $userName , $password , $dbName) ;
		}

		

		function dbconnection($hostName , $userName , $password , $dbName)
		{	
			$link = mysql_connect( $hostName , $userName , $password ) or die("Database Connection Error :".mysql_error()) ;

			mysql_select_db($dbName) or die("Database Selection Error ".mysql_error()) ;

			return $link ;
		}

		function closemysqlConnection()
		{
			mysql_close( $this->dblink ) ;
		}

		
		function tbl_custom_table($tableName)
		{
			$this->tblname = $tableName ;
			return $this->tblname ;
		}

		function tbl_formmast()
		{
			$this->tblname = "formmast" ;
			return $this->tblname ;
		}
		
		function tbl_besttimemast()
		{
			$this->tblname = "besttimemast" ;
			return $this->tblname ;
		}
		
		function tbl_dresscodemast()
		{
			$this->tblname = "dresscodemast" ;
			return $this->tblname ;
		}
		
		function tbl_test()
		{
			$this->tblname = "test" ;
			return $this->tblname ;
		}
		
		function tbl_temp_playlist()
		{
			$this->tblname = "temp_playlist" ;
			return $this->tblname ;
		}
		
		function tbl_playlistmast()
		{
			$this->tblname = "playlistmast" ;
			return $this->tblname ;
		}
		
		## ---------------------------------------- ##
		
		function calculateFanRatings( $memberID ) {
			
			$sqlQuery123 = "SELECT max(`member_rating`) FROM member_master  " ;
			$resQuery123 = mysql_query($sqlQuery123) or die(mysql_error()) ;
			$rowQuery123 = mysql_fetch_array($resQuery123) ;
			$maxRatingId123 = $rowQuery123[0] ;
						
			$this->tbl_member_master() ;
			$cond = "member_id = '".$memberID."' " ;			
			
			$resultFan = $this->selectRecord($cond) ;
			$recordFan = $this->recordNumber($resultFan) ;
			if($recordFan > 0) {
				$fetchDataFanPer = $this->fetchRecord($resultFan) ;
				if( $maxRatingId123 != 0 || $maxRatingId123 != "") {
					$fansRating = $fetchDataFanPer[0][member_rating]/$maxRatingId123 * 100 ;
				} else {
					$fansRating = 0 ;
				}
			}

			return number_format($fansRating,1) ;

		}
		////////  CODE FOR FANS  ///////////
		function commissionEvent($eventOwnerId,$commisionLevel)
		{
			$this->tbl_event_fans() ;
			$condition = " member_id = '".$eventOwnerId."' AND fan_status = 'A' " ; ///// EVENT OWNER ID
			$result = $this->selectRecord($condition) ;
			$record = $this->recordNumber($result) ;
			if($record > 0)
			{
				$fetchRecord = $this->fetchRecord($result) ;
				
				foreach($fetchRecord as $fetchRecordKey=>$fetchRecordVal)
				{
					$this->tbl_member_master() ;
					$condition = " member_email = '".$fetchRecordVal[fan_email]."' " ;
					$result = $this->selectRecord($condition) ;
					$record = $this->recordNumber($result) ;
					if($record > 0)
					{
						$fetchFanData = $this->fetchRecord($result) ;
						if($fetchFanData[0][member_id] != $eventOwnerId)  //// INSTEAD OF 6 PUT EVENTS OWNER ID
							$array[$fetchFanData[0][member_id]] = $fetchFanData[0][member_id] ;
					}
				}
			}
			
			for($i=2;$i<=$commisionLevel;$i++)
			{
				//// 2 level
				if($i==2)
				{
					foreach($array as $arrayKey=>$arrayVal)
					{
						$fetchRecordFan = array() ;
						$this->tbl_event_fans() ;
						$condition = " member_id = '".$arrayVal."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0)
						{
							$fetchRecordFan = $this->fetchRecord($result) ;
							
							foreach($fetchRecordFan as $fetchRecordFanKey=>$fetchRecordFanVal)
							{
								$fetchFanDataNew = array() ;
								$this->tbl_member_master() ;
								$condition = " member_email = '".$fetchRecordFanVal[fan_email]."' " ;
								$result = $this->selectRecord($condition) ;
								$record = $this->recordNumber($result) ;
								if($record > 0)
								{
									$fetchFanDataNew = $this->fetchRecord($result) ;
									if($fetchFanDataNew[0][member_id] != $eventOwnerId)  //// INSTEAD OF 6 PUT EVENTS OWNER ID
										$array[$fetchFanDataNew[0][member_id]] = $fetchFanDataNew[0][member_id] ;
								}
							}
						}
					}
				}
				
				//// 3 level
				if($i==3)
				{
					foreach($array as $arrayKey=>$arrayVal)
					{
						$fetchRecordFan = array() ;
						$this->tbl_event_fans() ;
						$condition = " member_id = '".$arrayVal."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0)
						{
							$fetchRecordFan = $this->fetchRecord($result) ;
							
							foreach($fetchRecordFan as $fetchRecordFanKey=>$fetchRecordFanVal)
							{
								$fetchFanDataNew = array() ;
								$this->tbl_member_master() ;
								$condition = " member_email = '".$fetchRecordFanVal[fan_email]."' " ;
								$result = $this->selectRecord($condition) ;
								$record = $this->recordNumber($result) ;
								if($record > 0)
								{
									$fetchFanDataNew = $this->fetchRecord($result) ;
									if($fetchFanDataNew[0][member_id] != $eventOwnerId)  //// INSTEAD OF 6 PUT EVENTS OWNER ID
										$array[$fetchFanDataNew[0][member_id]] = $fetchFanDataNew[0][member_id] ;
								}
							}
						}
					}
				}	
				
				//// 4 level
				if($i==4) 
				{
					foreach($array as $arrayKey=>$arrayVal)
					{
						$fetchRecordFan = array() ;
						$this->tbl_event_fans() ;
						$condition = " member_id = '".$arrayVal."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0)
						{
							$fetchRecordFan = $this->fetchRecord($result) ;
							
							foreach($fetchRecordFan as $fetchRecordFanKey=>$fetchRecordFanVal)
							{
								$fetchFanDataNew = array() ;
								$this->tbl_member_master() ;
								$condition = " member_email = '".$fetchRecordFanVal[fan_email]."' " ;
								$result = $this->selectRecord($condition) ;
								$record = $this->recordNumber($result) ;
								if($record > 0)
								{
									$fetchFanDataNew = $this->fetchRecord($result) ;
									if($fetchFanDataNew[0][member_id] != $eventOwnerId)  //// INSTEAD OF 6 PUT EVENTS OWNER ID
										$array[$fetchFanDataNew[0][member_id]] = $fetchFanDataNew[0][member_id] ;
								}
							}
						}
					}
				}	
			}		
		
			return $array ;
		}
		
		function commeventInv($eventId)
		{
			$arrayInv = array() ;
			///////////////////////////////////////////////////////////
			/////////////////////   INVITED LIST  /////////////////////
			///////////////////////////////////////////////////////////
			$this->tbl_event_invitation() ;
			$condition = " event_id = '".$eventId."' AND event_helper = 'Y' " ;
			$result = $this->selectRecord($condition) ;
			$record = $this->recordNumber($result) ;
			if($record > 0)
			{
				$fetchInvitedMemInfo = $this->fetchRecord($result) ;
				
				foreach($fetchInvitedMemInfo as $fetchInvitedMemInfoKey=>$fetchInvitedMemInfoVal)
				{
					$this->tbl_member_master() ;
					$condition = " member_email = '".$fetchInvitedMemInfoVal[invite_email]."' " ;
					$result = $this->selectRecord($condition) ;
					$record = $this->recordNumber($result) ;
					if($record > 0)
					{
						$fetchInvData = $this->fetchRecord($result) ;
						if($fetchInvData[0][member_id] != $eventOwnerId)  //// INSTEAD OF 6 PUT EVENTS OWNER ID
							$arrayInv[$fetchInvData[0][member_id]] = $fetchInvData[0][member_id] ;
					}
				}
			}
			
			
			return $arrayInv ;	
		}
		
		function commeventReg($eventOwnerId,$eventId)
		{
			$arrayregUn = array() ;
			$this->tbl_member_master() ;
			$condition = " member_id = '".$_SESSION[sess_memId]."' " ;
			$result = $this->selectRecord($condition) ;
			$record = $this->recordNumber($result) ;
			if($record > 0)
			{
				$fetchregInfo = $this->fetchRecord($result) ;
				$masterefcode = $fetchregInfo[0][master_ref_code] ;
			}
			
			if($masterefcode == $eventOwnerId)  // CHECK IF LOGGED IN MEMBER IS REGISTERED UNDER EVENT OWNER MEMBER
			{
				$arrayregUn[] = $fetchregInfo[0][member_id] ;
			}
			
			return $arrayregUn ;
		}
		

		//////////////////////////////////////////////////////////////
		////////  CODE FOR FANS PURCHASE OF EVENT TICKETS  ///////////
		//////////////////////////////////////////////////////////////
		function commissionEventPur($eventOwnerId,$commisionLevel)
		{
			$arrChk = array() ;
			$this->tbl_event_fans() ;
			$condition = " member_id = '".$eventOwnerId."' AND fan_status = 'A' AND status = 'P'" ; ///// EVENT OWNER ID
			$result = $this->selectRecord($condition) ;
			$record = $this->recordNumber($result) ;
			if($record > 0)
			{
				$fetchRecord = $this->fetchRecord($result) ;
				
				foreach($fetchRecord as $fetchRecordKey=>$fetchRecordVal)
				{
					$this->tbl_member_master() ;
					$condition = " member_email = '".$fetchRecordVal[fan_email]."' " ;
					$result = $this->selectRecord($condition) ;
					$record = $this->recordNumber($result) ;
					if($record > 0)
					{
						$fetchFanData = $this->fetchRecord($result) ;
						if($fetchFanData[0][member_id] != $eventOwnerId)  //// INSTEAD OF 6 PUT EVENTS OWNER ID
							$array[1][$fetchFanData[0][member_id]] = $fetchFanData[0][member_id] ;
					}
				}
			}
			
			for($i=2;$i<=$commisionLevel+1;$i++)
			{
				//// 2 level
				if($i==2)
				{
					foreach($array[1] as $arrayKey=>$arrayVal)
					{
						$fetchRecordFan = array() ;
						$this->tbl_event_fans() ;
						$condition = " member_id = '".$arrayVal."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0)
						{
							$fetchRecordFan = $this->fetchRecord($result) ;
							
							foreach($fetchRecordFan as $fetchRecordFanKey=>$fetchRecordFanVal)
							{
								$fetchFanDataNew = array() ;
								$this->tbl_member_master() ;
								$condition = " member_email = '".$fetchRecordFanVal[fan_email]."' " ;
								$result = $this->selectRecord($condition) ;
								$record = $this->recordNumber($result) ;
								if($record > 0)
								{
									$fetchFanDataNew = $this->fetchRecord($result) ;
									if($fetchFanDataNew[0][member_id] != $eventOwnerId && !in_array($fetchFanDataNew[0][member_id] , $array[1]) )  //// INSTEAD OF 6 PUT EVENTS OWNER ID
										$array[2][$fetchFanDataNew[0][member_id]] = $fetchFanDataNew[0][member_id] ;
								}
							}
						}
					}
				}
				
				//// 3 level
				if($i==3)
				{
					foreach($array[2] as $arrayKey=>$arrayVal)
					{
						$fetchRecordFan = array() ;
						$this->tbl_event_fans() ;
						$condition = " member_id = '".$arrayVal."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0)
						{
							$fetchRecordFan = $this->fetchRecord($result) ;
							
							foreach($fetchRecordFan as $fetchRecordFanKey=>$fetchRecordFanVal)
							{
								$fetchFanDataNew = array() ;
								$this->tbl_member_master() ;
								$condition = " member_email = '".$fetchRecordFanVal[fan_email]."' " ;
								$result = $this->selectRecord($condition) ;
								$record = $this->recordNumber($result) ;
								if($record > 0)
								{
									$fetchFanDataNew = $this->fetchRecord($result) ;
									if( $fetchFanDataNew[0][member_id] != $eventOwnerId && !in_array($fetchFanDataNew[0][member_id] , $array[1]) && !in_array($fetchFanDataNew[0][member_id] , $array[2]) )  //// INSTEAD OF 6 PUT EVENTS OWNER ID
										$array[3][$fetchFanDataNew[0][member_id]] = $fetchFanDataNew[0][member_id] ;
								}
							}
						}
					}
				}	
				
				//// 4 level
				if($i==4) 
				{
					foreach($array[3] as $arrayKey=>$arrayVal)
					{
						$fetchRecordFan = array() ;
						$this->tbl_event_fans() ;
						$condition = " member_id = '".$arrayVal."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0)
						{
							$fetchRecordFan = $this->fetchRecord($result) ;
							
							foreach($fetchRecordFan as $fetchRecordFanKey=>$fetchRecordFanVal)
							{
								$fetchFanDataNew = array() ;
								$this->tbl_member_master() ;
								$condition = " member_email = '".$fetchRecordFanVal[fan_email]."' " ;
								$result = $this->selectRecord($condition) ;
								$record = $this->recordNumber($result) ;
								if($record > 0)
								{
									$fetchFanDataNew = $this->fetchRecord($result) ;
									if($fetchFanDataNew[0][member_id] != $eventOwnerId && !in_array($fetchFanDataNew[0][member_id] , $array[1]) && !in_array($fetchFanDataNew[0][member_id] , $array[2]) && !in_array($fetchFanDataNew[0][member_id] , $array[3]) )  //// INSTEAD OF 6 PUT EVENTS OWNER ID
										$array[4][$fetchFanDataNew[0][member_id]] = $fetchFanDataNew[0][member_id] ;
								}
							}
						}
					}
				}
				
				//// 5 level
				if($i==5) 
				{
					foreach($array[4] as $arrayKey=>$arrayVal)
					{
						$fetchRecordFan = array() ;
						$this->tbl_event_fans() ;
						$condition = " member_id = '".$arrayVal."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0)
						{
							$fetchRecordFan = $this->fetchRecord($result) ;
							
							foreach($fetchRecordFan as $fetchRecordFanKey=>$fetchRecordFanVal)
							{
								$fetchFanDataNew = array() ;
								$this->tbl_member_master() ;
								$condition = " member_email = '".$fetchRecordFanVal[fan_email]."' " ;
								$result = $this->selectRecord($condition) ;
								$record = $this->recordNumber($result) ;
								if($record > 0)
								{
									$fetchFanDataNew = $this->fetchRecord($result) ;
									if($fetchFanDataNew[0][member_id] != $eventOwnerId && !in_array($fetchFanDataNew[0][member_id] , $array[1]) && !in_array($fetchFanDataNew[0][member_id] , $array[2]) && !in_array($fetchFanDataNew[0][member_id] , $array[3]) )  //// INSTEAD OF 6 PUT EVENTS OWNER ID
										$array[5][$fetchFanDataNew[0][member_id]] = $fetchFanDataNew[0][member_id] ;
								}
							}
						}
					}
				}


			}		
			
			//$array = $array[1] + $array[2] +$array[3] +$array[4] ;
			
			/*for($i=1;$i<=4;$i++)
			{
				for($j=0;$j<count($array[$i]);$j++)
				{
					$arrChk[] = $array ;
				}	
			}*/
			//echo "<pre>" ;
			//print_r($array) ;
			//die() ;
		
			return $array ;
		}
		
		function commeventInvPur($eventId)
		{
			$arrayInv = array() ;
			///////////////////////////////////////////////////////////
			/////////////////////   INVITED LIST  /////////////////////
			///////////////////////////////////////////////////////////
			$this->tbl_event_invitation() ;
			$condition = " event_id = '".$eventId."' AND event_helper = 'Y' " ;
			$result = $this->selectRecord($condition) ;
			$record = $this->recordNumber($result) ;
			if($record > 0)
			{
				$fetchInvitedMemInfo = $this->fetchRecord($result) ;
				
				foreach($fetchInvitedMemInfo as $fetchInvitedMemInfoKey=>$fetchInvitedMemInfoVal)
				{
					$this->tbl_member_master() ;
					$condition = " member_email = '".$fetchInvitedMemInfoVal[invite_email]."' " ;
					$result = $this->selectRecord($condition) ;
					$record = $this->recordNumber($result) ;
					if($record > 0)
					{
						$fetchInvData = $this->fetchRecord($result) ;
						if($fetchInvData[0][member_id] != $eventOwnerId)  //// INSTEAD OF 6 PUT EVENTS OWNER ID
							$arrayInv[1][$fetchInvData[0][member_id]] = $fetchInvData[0][member_id] ;
					}
				}
			}
			
			return $arrayInv ;
		}
		
		function commeventRegPur($eventOwnerId,$eventId)
		{
			$arrayregUn = array() ;
			$this->tbl_member_master() ;
			$condition = " member_id = '".$_SESSION[sess_memId]."' " ;
			$result = $this->selectRecord($condition) ;
			$record = $this->recordNumber($result) ;
			if($record > 0)
			{
				$fetchregInfo = $this->fetchRecord($result) ;
				$masterefcode = $fetchregInfo[0][master_ref_code] ;
			}
			
			if($masterefcode == $eventOwnerId)  // CHECK IF LOGGED IN MEMBER IS REGISTERED UNDER EVENT OWNER MEMBER
			{
				$arrayregUn[1][] = $fetchregInfo[0][member_id] ;
			}
			
			return $arrayregUn ;
		}
		
		//////////////////////////////////////////////////////
		///////////  END OF TICKET PURCHASE CODE  ////////////
		//////////////////////////////////////////////////////
		
		function createDirectory( $dirPath )
		{
			if( !is_dir( $dirPath ) )
			{
				@mkdir( $dirPath , 0777 ) ;
			}

			return $dirPath ;
		}


		function customQuery( $sql ) 
		{
			$sql = strip_tags( $sql ) ;
			$result = mysql_query( $sql ) or die( "Query error : ".mysql_error() ) ;

			return $result ;
		}
				
		function customQueryPage($sql='' , $pageNum='' , $orderBy='' , $sortOrder='' )
		{
			
			if( trim($orderBy) )
				$sql .= " ORDER BY ".$orderBy." ".$sortOrder ;

			if( $pageNum )
				$sql .= " LIMIT ".$this->queryLimit( $pageNum ) ;

			//echo $sql ; 

			$result = mysql_query( $sql , $this->dblink ) or die("Query Error :".mysql_error()) ;
			
			return $result ;
		}

		function insertRecord($records)
		{
			foreach( $records as $recordKey=>$recordValue )
			{
				$columns .= '`'.$recordKey.'`, ' ;
				$values .= ' "'.mysql_real_escape_string($recordValue).'", ' ;
			}

			$columns = substr( $columns , 0 , strlen($columns)-2 ) ;
			$values = substr( $values , 0 , strlen($values)-2 ) ;

			$sql = " INSERT INTO `".$this->tblname."` ( ".$columns." ) VALUES ( ".$values." )" ;
			
			//echo $sql."<br>" ;

			$result = mysql_query( $sql , $this->dblink ) or die("Query Error :".mysql_error()) ;

			return $result ;
		}

		function insertRecordId()
		{
			$insertId = mysql_insert_id() ;

			return $insertId ;
		}

		function selectRecord($condition='' , $pageNum='' , $orderBy='' , $sortOrder='' )
		{
			
			if( trim($condition) )
				$sql = " SELECT * FROM `".$this->tblname."` WHERE ".$condition ;
			else
				$sql = " SELECT * FROM `".$this->tblname."`" ;
				
			if( trim($orderBy) )
				$sql .= " ORDER BY ".$orderBy." ".$sortOrder ;

			if( $pageNum && $_REQUEST[recordPP] != "showall" )
				$sql .= " LIMIT ".$this->queryLimit( $pageNum ) ;

			//echo $sql."<br>" ;

			$result = mysql_query( $sql , $this->dblink ) or die("Query Error :".mysql_error()."<br>".$sql) ;
			
			return $result ;
		}

		function queryLimit($pageNumber)
		{
			if($_REQUEST[recordPP])  $rppPost = $_REQUEST[recordPP] ;
			else $rppPost = $this->rpp ;
			
			if( trim($pageNumber) )
			{
				$pgnumber = ($pageNumber - 1) * $rppPost ;
			}
			else
				$pgnumber = 0 ;

			return $pgnumber." , ".$rppPost ;
		}

		function pageListNumber( $pageNumber )
		{	
			if( $pageNumber == 1 || !trim($pageNumber) )
				$counter = 1 ;
			else
				$counter = ( ( $pageNumber - 1 ) * $this->rpp ) + 1 ;

			return $counter ;
		}

		function getClassName($count)
		{
			if( $count % 2 == 0 )
				$class = "alternateList" ;
			else
				$class = "List" ;

			return $class ;
		}
		
		function imageSize( $imageName )
		{
			return getimagesize( $imageName ) ;
		}

		function websiteSettings($settingName='')
		{
			if( trim($settingName) )
			{
				$sql = " SELECT * FROM `".$this->tblname."` WHERE stetting_name = '".$settingName."'" ;

				$result = mysql_query( $sql , $this->dblink ) or die("Query Error :".mysql_error()) ;

				$settings = $this->fetchRecord($result) ;
			}

			return trim($settings[0][setting_desc]) ;
		}

		function getDateformat($date , $flag='')
		{

			if( trim($flag) )
				$retDate = date( "Y-m-d" , $date ) ;
			else if( trim($date) )
				$retDate = date( "d M, Y" , $date ) ;
			elseif( !trim($date))
				$retDate = "N/A" ;

			return $retDate ;
		}

		function getDateformatForMonth($date , $flag='')
		{

			if( trim($flag) )
				$retDate = date( "m" , $date ) ;
			else if( trim($date) )
				$retDate = date( "F" , $date ) ;
			elseif( !trim($date))
				$retDate = "N/A" ;

			return $retDate ;
		}

		function checkRecord( $field , $data )
		{
			$sql = " SELECT * FROM `".$this->tblname."` WHERE ".$field." = '".$data."'" ;

			$result = mysql_query( $sql , $this->dblink ) or die("Query Error :".mysql_error()) ;

			$recordCount = $this->recordNumber($result) ;

			return $recordCount ;
		}

		function recordNumber($recordSet)
		{
			$count = @mysql_num_rows($recordSet) ;

			if(!trim($count) ) $count = 0 ;

			return $count ;
		}

		function fetchRecord($recordSet)
		{
			while( $record = mysql_fetch_array($recordSet) )
			{
				$records[] = $record ;
			}

			return $records ;
		}

		function updateRecord($records , $condtion)
		{
			foreach( $records as $recordKey=>$recordValue )
			{
				$columns .= '`'.$recordKey.'` = "'.mysql_real_escape_string($recordValue).'", ' ;
			}

			$columns = substr( $columns , 0 , strlen($columns)-2 ) ;

			$sql = " UPDATE `".$this->tblname."` SET ".$columns." WHERE ".$condtion ;

			$result = mysql_query( $sql , $this->dblink ) or die("Query Error :".mysql_error()) ;

			return $result ;
		}

		function deleteRecord($condition)
		{
			$sql = ' DELETE FROM `'.$this->tblname.'` WHERE '.$condition ;
		
			$result = mysql_query( $sql , $this->dblink ) or die("Query Error :".mysql_error()) ;

			return $result ;
		}

		function getValues()
		{
			/* if( $_POST )
				$userData = $_POST ;
			else if( $_GET )
				$userData = $_GET ;*/
			
			$userData = $_REQUEST ;

			return $userData ;
		}

		function createDropDown($recordArray, $value='')
		{
			if( count($recordArray) > 0 )
			{
				foreach( $recordArray as $recordKey=>$recordValue )
				{
					if( is_array($value) )
					{
						if( in_array( $recordValue[0] , $value ) )  $sel = "selected" ;
						else										$sel = "" ;
					}
					else
					{
						if( $recordValue[0] == $value )    $sel = "selected" ;
						else							   $sel = "" ;
					}
					
					$optionValues .= "<option value='".$recordValue[0]."' ".$sel.">". ucwords($recordValue[1]) ."</option>\r\n" ;
				}
			}

			return $optionValues ;
		}

		function stripOutStringSpectialChars( $inputText )
		{
			$search = array('<', '>', '\\', '/', '=', '+') ; //, '-'
			$replace = array('_', '_', '_', '_', '_', '_') ; //, '_'

			$string = str_replace($search , $replace , $inputText) ;

			return $string ;
		}

		function createNewDropDownSel($recordArray, $value='' , $no='' , $no1='' )
		{
			if( count($recordArray) > 0 )
			{
				foreach( $recordArray as $recordKey=>$recordValue )
				{
					if( $recordValue[$no] == $value )    $sel = "selected" ;
					else							   $sel = "" ;
					
					if( $recordValue[$no1] != "" ) {
						$optionValues .= "<option value='".$recordValue[$no]."' ".$sel.">". $recordValue[$no1] ."</option>\r\n" ;
					}
				}
			}

			return $optionValues ;
		}
		
		function chkfileformates( $file )
		{
		    foreach( $file as $fileKey=>$fileValue )
			{
				$filetype = $fileValue['type'] ;
			
				if( !$filetype )
				{
					return false ;
				}
				elseif (!in_array($filetype,$this->accepetedPicture))
				{
					return false ;
				}
			}
				
			return true ;
		}

		function checkVideoFilesFormats( $file )
		{
		    foreach( $file as $fileKey=>$fileValue )
			{
				$p = $fileValue['name'];
				$pos = strrpos($p,".");
				$fileExt = strtolower(substr($p,$pos+1,strlen($p)-$pos));
			
				if( !$fileExt )
				{
					return false ;
				}
				elseif (!in_array($fileExt,$this->accepetedVideo))
				{
					return false ;
				}
			}
				
			return true ;
		}
		
		function adminMail() {
			$this->tbl_member_master() ;
			$condition = " member_id = '1' AND member_type = 'A' " ;
			$result = $this->selectRecord($condition) ;
			$record = $this->recordNumber($result) ;
			if( $record > 0 ) {
				$fetchAdminInfo = $this->fetchRecord($result) ;
				$adminMail = $fetchAdminInfo[0][member_email] ;
			}
			return $adminMail ;
		}

		function userDetails($field) {
			$this->tbl_member_master() ;
			$condition = " member_id = '".$_SESSION[sess_memId]."' " ;
			$result = $this->selectRecord($condition) ;
			$record = $this->recordNumber($result) ;
			if( $record > 0 ) {
				$fetchAdminInfo = $this->fetchRecord($result) ;
				$userDetail = $fetchAdminInfo[0][$field] ;
			}
			return $userDetail ;
		}
		
		function userDetailsOthers($withCon , $con , $field) {
			$this->tbl_member_master() ;
			$condition = " ".$withCon." = '".$con."' " ;
			$result = $this->selectRecord($condition) ;
			$record = $this->recordNumber($result) ;
			if( $record > 0 ) {
				$fetchAdminInfo = $this->fetchRecord($result) ;
				$userDetail = $fetchAdminInfo[0][$field] ;
			}
			return $userDetail ;
		}
		
		function usersDetail($withCon , $con) {
			$this->tbl_member_master() ;
			$condition = " ".$withCon." = '".$con."' " ;
			$result = $this->selectRecord($condition) ;
			$record = $this->recordNumber($result) ;
			if( $record > 0 ) {
				$fetchAdminInfo = $this->fetchRecord($result) ;
			}
			else
			{
				$fetchAdminInfo = array() ;
			}
			return $fetchAdminInfo ;
		}
		
		function siteSettings( $field ) {
			$this->tbl_site_setting_master() ;
			$condition = "" ; 
			$result = $this->selectRecord($condition) ;
			$record = $this->recordNumber($result) ;
			$eventspromoted = "" ;
			if($record > 0) {
				$fetchRat = $this->fetchRecord($result) ;
				$setValue = $fetchRat[0][$field] ;
			}
			return $setValue ;
		}

		function moduleBox( $postData , $alignment='' )
		{
			
			$this->tbl_event_module_master() ;
			$condition = " event_id = '".$postData[eventId]."' AND event_module = '".$postData[moduleId]."' " ;
			$result = $this->selectRecord( $condition ) ;
			$recCount = $this->recordNumber($result) ;
			if( $recCount > 0 )
			{
				$eventModuleDetails = $this->fetchRecord( $result ) ;
				$txteventmodbxwidth = $eventModuleDetails[0][event_module_boxwidth] ;
				$modId = $eventModuleDetails[0][event_module_id] ;
			}

			if( $txteventmodbxwidth == "650" ) {
				$txtWidthBox = "718" ;
			} else {
				$txtWidthBox = "355" ;
			}
			
			/*if( $txteventmodbxwidth == "650" ) {
				$txteventmoduleboxwidth = "720" ;
				$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$modId.",".$txteventmodbxwidth.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
			} else {
				$txteventmoduleboxwidth = "352" ;
				$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$modId.",".$txteventmodbxwidth.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
			}*/
			
			$this->tbl_event_master() ;
			$condition = " event_id = '".$postData[eventId]."' " ;
			$result = $this->selectRecord( $condition ) ;
			$recordCount = $this->recordNumber($result) ;
			if( $recordCount > 0 )
			{
				$eventDetails = $this->fetchRecord( $result ) ;
				$txtwindowHeader = $eventDetails[0][event_window_header] ;
			}
			
			//echo "<pre>";
			//print_r($postData) ;
			
			/*
			
			$this->tbl_event_module_master() ;
			$condition = " event_module_id = '".$postData[eventModId]."' " ;
			$result = $this->selectRecord($condition) ;
			$record = $this->recordNumber($result) ;
			if($record > 0) {
				$fetchDataModuleList = $this->fetchRecord($result) ;
				$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
			}
			
			if( $bxwd == "650" ) {
				$txteventmoduleboxwidth = "720" ;
				$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
			} else {
				$txteventmoduleboxwidth = "352" ;
				$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
			}
			
			#width:[TABLE_WIDTH]px;width:[TABLE_WIDTH]px;

			*/
			
			if( $alignment )
				$module_alignment = $alignment ;
			
			$module = $postData[module] ; //moduleId

			$moduleC  = "<DIV id=[BOX_ID] dragableBox='true' class='[MODULE_CLASS]' style='width:".$txtWidthBox."px;'>" ;
			$moduleC .= "<center>" ;
			$moduleC .= '<table width=[TABLE_WIDTH] align=center border="0" cellpadding="0" cellspacing="0" id="display">' ;
			$moduleC .= "<tr>" ;
			$moduleC .= '<td colspan=2 valign="middle" class="boxheader">' ;
			
			$moduleC .= " <div id='panel_l'>
							<h2 style='height:20px;'>
							
							<span style='height:20px;'>
							
							[REMOVE_MODULE] [EDIT_MODULE] | [IMG_ARROW] | <a href=\"javascript:hideShowPanel('[BOX_CONTROL_PANEL]','[BOX_CONTROL_PANEL]Collapse');\" title='Hide/Show [BOX_TITLE]'>
							<b  id='[BOX_CONTROL_PANEL]Collapse'>[ - ]</b>
							</a> 
							
							</span>[BOX_TITLE]
							
							</h2>
						
						</div> " ;
			
			$moduleC .= "</td>" ;
			$moduleC .= "</tr>" ;
			$moduleC .= "<tr>" ;
			$moduleC .= "<td id='[BOX_CONTROL_PANEL]' style='cursor:pointer;'>" ;
			$moduleC .= '<table width="100%"  border="0" cellspacing="0" cellpadding="0">' ;
			$moduleC .= '<tr valign="middle">' ;
			$moduleC .= "<td height=20 align=left><center>[BOX_CONTENT]</center></td>" ;
			$moduleC .= "</tr>" ;
			$moduleC .= "</table>" ;
			$moduleC .= "</td>" ;
			$moduleC .= "</tr>" ;
			$moduleC .= "</table>" ;
			$moduleC .= "</center>" ;
			$moduleC .= "</DIV>" ;

			//echo "zzzzzzzz".$postData[eventModId] ; echo "<br>" ;
			switch($module) 
			{
				case "rssfeeds":
						
						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arraowlft.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arraowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;

						$addModule = str_replace( "[BOX_TITLE]" , ucwords($module) , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , "" , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ; 
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "bigArticle" , $addModule ) ; 
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , "RSS Feed content goes here..." , $addModule ) ;

						break;
				case "flyerimage":
						
						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = " | <a href='javascript:;' title='Edit Module' onclick=\"editModuleFlyer('flyerimage' , ".$postData[eventModId]." )\"><b>Edit</b></a> " ;
						
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;

						///// check for the event flyer
						$this->tbl_event_module_flyer_master() ;
						$condition = " event_id = '".$postData[eventId]."' AND event_module_id = '".$postData[eventModId]."' " ;  
						$result = $this->selectRecord( $condition ) ;
						$recordCount = $this->recordNumber( $result ) ;

						if( $recordCount > 0 )
						{
							$flyerList = $this->fetchRecord( $result ) ;

							//// event details
							$this->tbl_event_master() ;
							$condition = " event_id = '".$postData[eventId]."'" ;
							$result = $this->selectRecord( $condition ) ;
							$eventInfo = $this->fetchRecord( $result ) ;
							
							$profileDir = $eventInfo[0][member_id]."/".$eventInfo[0][event_profile_folder]."/" ;
							//// end event details

							//// event directory
							$fpathOriginal = _WEBSITE_ABSOLUTE_PATH._PROFILE_DIRECTORY."/".$profileDir."/" ;
							$fpath = _WEBSITE_ABSOLUTE_PATH._PROFILE_DIRECTORY.$eventInfo[0][member_id]."/events/thumb/" ;
							$flyerCnt = 1 ;
							$flyers = "<table width=100% cellpaddng=2 cellspacing=2 border=0><tr>" ;
							foreach( $flyerList as $flyerKey=>$flyerValue )
							{

								$imageSizeOriginal = getimagesize( $fpathOriginal.$flyerValue[flyer_image] ) ;
								$imageSize = getimagesize( $fpath.$flyerValue[flyer_image] ) ;
								
								$this->tbl_event_module_master() ;
								$condition = " event_module_id = '".$postData[eventModId]."' " ;
								$result = $this->selectRecord($condition) ;
								$record = $this->recordNumber($result) ;
								if($record > 0) {
									$fetchDataModuleList = $this->fetchRecord($result) ;
									$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
								}
								
								/*if( $bxwd == "650" ) {
									if( $imageSize[0] > 650) {
										$wnew = 600  ;
										$hnew = ($imageSize[1] * 60) / 100 ;
									} else {
										$wnew = ($imageSize[0] * 100) / 100 ;
										$hnew = ($imageSize[1] * 100) / 100 ;
									}	
								} else {
									if( $imageSize[0] > 320) {
										$wnew = 270 ;
										$hnew = ($imageSize[1] * 40) / 100 ;
									} else {
										$wnew = ($imageSize[0] * 100) / 100 ;
										$hnew = ($imageSize[1] * 100) / 100 ;
									}
								}*/

								if( $bxwd == "650" ) {
										$wnew = 600  ;
										$hnew = 600 ;
										//$hnew = $imageSizeOriginal[1] ;
								} else {
										$wnew = 270 ;
										/*if($hnew < 270)
											$hnew = $imageSize[1] ;
										else*/	
											$hnew = 270 ;
								}

								$img = $fpathOriginal.$flyerValue[flyer_image] ;
								$flyerId = $flyerValue[flyer_id] ;
								
								$flyers .= "<td align=left>" ;
								//$flyers .= "<div style='float:right;' ></div>" ;
								$flyers .= "<img class='photos' src='/imageresize.php?image=".$fpathOriginal.$flyerValue[flyer_image]."&width=".$wnew."&height=".$hnew."' onclick='winOpen(\"$img\" , \"$imageSizeOriginal[0]\" , \"$imageSizeOriginal[1]\");'>" ;
								$flyers .= "</td><td valign=top ><a  title='Remove Image Flyer' href=\"javascript:removeModuleElement('flyerimage','".$flyerId."');\" ><img class='photosmall' src='../images/delete.png'></a></td>" ;

								$flyerCnt++ ;
							}

							$flyers .= "</tr></table>" ;
						}
						else
						{
							$flyers = "<div style='text-align:left;'>Click on edit to manage Flyers.</div>" ;
						}
						///// end event flyer

						$addModule = str_replace( "[BOX_TITLE]" , "Event Flyer" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ; 
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "bigArticle" , $addModule ) ; 
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , $flyers , $addModule ) ;

						break;
				case "googleanalytics":
						
						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = " | <a href='javascript:;' title='Edit Module' onclick=\"editModule('googleanalytics',".$postData[eventModId].")\"><b>Edit</b></a> " ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;

						///// check for the event flyer
						$this->tbl_event_module_google_analytics() ;

						$condition = " event_id = '".$postData[eventId]."' AND event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord( $condition ) ;
						$recordCount = $this->recordNumber( $result ) ;

						if( $recordCount > 0 )
						{
							$analyticsList = $this->fetchRecord( $result ) ;

							$username = $analyticsList[0][google_username] ;
							$accountList = $username ;
						}
						else
						{
							$accountList = "<div style='text-align:left;'>Click on edit to manage Google Analytics.</div>" ;
						}

						$addModule = str_replace( "[BOX_TITLE]" , "Google Analytics" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ;
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "smallArticle" , $addModule ) ;
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , $accountList , $addModule ) ;

						break;
				case "ticketcounter":
						
						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = "" ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;

						//// ticket quota
						$this->tbl_event_ticket_quota_master() ;
						$condition = " event_id = '".$postData[eventId]."'" ;

						$result = $this->selectRecord( $condition ) ;
						$recordCount = $this->recordNumber( $result ) ;

						if( $recordCount > 0 )
						{
							$ticketQuotaList = $this->fetchRecord( $result ) ;
							$totalAvailabeTickets = 0 ;
							
							foreach( $ticketQuotaList as $ticketQuotaKey=>$ticketQuotaValue )
							{
								$totalAvailabeTickets += $ticketQuotaValue[event_ticket_avaibality] ;
							}
							
							$ticketQuota  = "<table width=100% cellpadding=2 cellspacing=2 border=0>" ;
							$ticketQuota .= "<tr><td width=50% align=right><b>Total Available Tickets :</b></td>" ;
							$ticketQuota .= "<td width=50% align=left>".$totalAvailabeTickets."</td>" ;
							$ticketQuota .= "</tr>" ;
							$ticketQuota .= "<tr><td align=right><b>Total Sold Tickets :</b></td>" ;
							$ticketQuota .= "<td align=left>0</td>" ;
							$ticketQuota .= "</tr>" ;
							$ticketQuota .= "</table>" ;
							
							//$ticketQuota .= "<div>&nbsp; &nbsp; ".$totalAvailabeTickets."</div>" ;
							//$ticketQuota .= "<div>&nbsp;<b>Total Sold Tickets :</b> &nbsp; 0</div>" ;
						}
						else
						{
							$ticketQuota = "<div style='text-align:left;'>Click on edit to manage Ticket Counter.</div>" ;
						}
						///// end ticket quota

						$addModule = str_replace( "[BOX_TITLE]" , "Ticket Counter" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ;
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "smallArticle" , $addModule ) ;
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , $ticketQuota , $addModule ) ;

						break;
				case "dev_rateform":
						
						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = " | <a href='javascript:;' title='Edit Module' onclick=\"editModule('dev_rateform',".$postData[eventModId].")\"><b>Edit</b></a> " ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' > <img src='../images/down.png' border='0' style='cursor:pointer;' > <img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;

						///// event dev_rateform
						$this->tbl_event_module_dev_rateform_master() ;
						$condition = " event_id = '".$postData[eventId]."' AND event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord( $condition ) ;
						$recordCount = $this->recordNumber( $result ) ;

						if( $recordCount > 0 )
						{
							$dev_rateformList = $this->fetchRecord( $result ) ;

							$dev_rateform = "<table width=100% cellpadding=2 cellspacing=2 border=0>" ;
							$questionCnt = 1 ;
							foreach( $dev_rateformList as $dev_rateformKey=>$dev_rateformValue )
							{
								$dev_rateformId = $dev_rateformValue["dev_rateform_id"] ;
								
								$deleteLink = "<div style='float:right'><a href=\"javascript:editModuledev_rateform('dev_rateform',".$postData[eventModId].");\" title='Edit Servey Question'> <img class='photosmall' src='../images/edit.gif'> </a>&nbsp;<a title='Remove Servey Question' href=\"javascript:removeModuleElement('dev_rateform','".$dev_rateformId."');\"><img class='photosmall' src='../images/delete.png'></a></div>" ;
								
								$dev_rateform .= "<tr>" ;
								$dev_rateform .= "<td colspan=2 align=left>".$deleteLink."<b>".$questionCnt.". &nbsp; ".ucfirst($dev_rateformValue["dev_rateform_question"])."</b></td>" ;
								$dev_rateform .= "</tr>" ;
								$dev_rateform .= "<tr>" ;
								$dev_rateform .= "<td width=5% align=right><input type='radio' name='".$dev_rateformId."_q_ans[]' value='".$dev_rateformId."_"."1' id='".$dev_rateformId."_"."1'>" ;
								$dev_rateform .= "<td width=95% align=left><label for='".$dev_rateformId."_"."1'>".$dev_rateformValue["dev_rateform_ans_1"]."</label></td>" ;
								$dev_rateform .= "</tr>" ;
								
								$dev_rateform .= "<tr>" ;
								$dev_rateform .= "<td width=5% align=right><input type='radio' name='".$dev_rateformId."_q_ans[]' value='".$dev_rateformId."_"."2' id='".$dev_rateformId."_"."2'>" ;
								$dev_rateform .= "<td width=95% align=left><label for='".$dev_rateformId."_"."2'>".$dev_rateformValue["dev_rateform_ans_2"]."</label></td>" ;
								$dev_rateform .= "</tr>" ;

								$dev_rateform .= "<tr>" ;
								$dev_rateform .= "<td width=5% align=right><input type='radio' name='".$dev_rateformId."_q_ans[]' value='".$dev_rateformId."_"."3' id='".$dev_rateformId."_"."3'>" ;
								$dev_rateform .= "<td width=95% align=left><label for='".$dev_rateformId."_"."3'>".$dev_rateformValue["dev_rateform_ans_3"]."</label></td>" ;
								$dev_rateform .= "</tr>" ;

								$questionCnt++ ;
							}
							$dev_rateform .= "</table>" ;
						}
						else
							$dev_rateform = "<div style='text-align:left;'>Click on edit to manage dev_rateform.</div>" ;
						///// end event dev_rateform

						$addModule = str_replace( "[BOX_TITLE]" , "dev_rateform" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ;
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "bigArticle" , $addModule ) ;
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , $dev_rateform , $addModule ) ;

						break;
				case "musicplayer":
						
						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = " | <a href='javascript:;' title='Edit Module' onclick=\"editModule('musicplayer',".$postData[eventModId].")\"><b>Edit</b></a> " ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;

						////// music files for event
						$this->tbl_event_module_music_master() ;
						$condition = " event_id = '".$postData[eventId]."' AND event_module_id = '".$postData[eventModId]."' " ;

						$result = $this->selectRecord( $condition ) ;
						$recordCount = $this->recordNumber( $result ) ;

						if( $recordCount > 0 )
						{
							$musicList = $this->fetchRecord( $result ) ;

							$musicPlayer = "<table width=100% cellpadding=2 cellspacing=2 border=0>" ;
							foreach( $musicList as $musicKey=>$musicValue )
							{
								$musicName = ucfirst($musicValue[event_music_name]) ;
								$musicId = $musicValue[music_id] ;

								$musicPlayer .= "<tr>" ;
								$musicPlayer .= "<td width=90% align=left>".$musicName."</td>" ;
								$musicPlayer .= "<td width=10% align=right><a title='Remove Music File' href=\"javascript:removeModuleElement('musicplayer','".$musicId."');\"><img class='photosmall' src='../images/delete.png'></a></td>" ;
								$musicPlayer .= "</tr>" ;
							}
							$musicPlayer .= "</table>" ;
						}
						else
						{
							$musicPlayer .= "<div style='text-align:left;'>Click on edit to manage music playlist.</div>" ;
						}
						///// end music file

						$addModule = str_replace( "[BOX_TITLE]" , "Music Player" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ;
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "smallArticle" , $addModule ) ;
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , $musicPlayer , $addModule ) ;

						break;
				case "pictureslideshow":
						
						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = " | <a href='javascript:;' title='Edit Module' onclick=\"editModule('pictureslideshow' , ".$postData[eventModId].")\"><b>Edit</b></a> " ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;

						///// check for the event slideshow
						$this->tbl_event_module_slideshow_master() ;

						$condition = " event_id = '".$postData[eventId]."' AND event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord( $condition ) ;
						$recordCount = $this->recordNumber( $result ) ;
						
						if( $bxwd == "650" ) {
							$wd = "130" ;
							$ht = "110" ;
							$setvar = 0;
						} else {
							$setvar = 1;
							$ht = "50" ;
							$wd = "50" ;
						}
						
						if( $recordCount > 0 )
						{
							$slideshowPicList = $this->fetchRecord( $result ) ;

							//// event details
							$this->tbl_event_master() ;
							$condition = " event_id = '".$postData[eventId]."'" ;
							$result = $this->selectRecord( $condition ) ;
							$eventInfo = $this->fetchRecord( $result ) ;
							
							$profileDir = $eventInfo[0][member_id]."/".$eventInfo[0][event_profile_folder]."/" ;
							//// end event details

							//// event directory
							$fpath = "../"._PROFILE_DIRECTORY."/".$profileDir ;
							
							$slideshowPicCnt = 1 ;
							$slideshowPic = "<table cellpaddng=2 cellspacing=2 border=0 class='slideshow_table_class'><tr>" ;
							foreach( $slideshowPicList as $slideshowKey=>$slideshowValue )
							{
								$slideshowId = $slideshowValue[slideshow_id] ;

								 $slideshowPic .= "<td class='photos new_photo_class' width='105' align=center>" ;
								 $slideshowPic .= "<table width='100%' height='100%' border='0' cellspecing='0' cellpadding='0' class='photo_table_class' ><tr><td height='140' width='105'>";
								 $slideshowPic .= "<div title=''  class='div_photo_class'>" ;
								//$slideshowPic .= "<div style='float:right'><a title='Remove Slide Show Picture' href=\"javascript:removeModuleElement('pictureslideshow','".$slideshowId."');\"><img class='photosmall' src='../images/delete.png'></a></div>" ;
								
								 $slideshowPic .= "<img src='/imageresize.php?image=".$fpath.$slideshowValue[slideshow_thumb_picture]."&width=".SLIDESHOW_IMG_WIDTH."&height=".SLIDESHOW_IMG_HEIGHT."' >" ;
								 $slideshowPic .= "</div>";
								 $slideshowPic .= "</td></tr></table><div style='float:right' class='photo_title_class_slideshow' ><a title='Remove Slide Show Picture' href=\"javascript:removeModuleElement('pictureslideshow','".$slideshowId."');\"><img class='photosmall photo_remove_image_slideshow' src='../images/delete.png'  ></a></div></td>";
								//$slideshowPic .= "</td>" ;

								if($setvar == 1){
									$value_1 = 3;
								}else{
									$value_1 = 5;
								}
						
								if( $slideshowPicCnt % $value_1 == 0 )  $slideshowPic .= "</tr><tr>" ;

								$slideshowPicCnt++ ;
							}

							if( $slideshowPicCnt <= 5 )
							{
								for( $i=$slideshowPicCnt ; $i<=5 ; $i++ )
								{
									$slideshowPic .= "<td width=105 >&nbsp;</td>" ;
								}
							}

							$slideshowPic .= "</tr></table>" ;
						}
						else
						{
							$slideshowPic = "<div style='text-align:left;'>Click on edit to manage Slideshow picture.</div>" ;
						}
						///// end event slideshow

						$addModule = str_replace( "[BOX_TITLE]" , "Event Picture Slideshow" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ;
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "smallArticle" , $addModule ) ;
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , $slideshowPic , $addModule ) ;

						break;
				case "sitepal":

						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = " | <a href='javascript:;' title='Edit Module' onclick=\"editModule('sitepal',".$postData[eventModId].")\"><b>Edit</b></a> " ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;

						//// sitepal code
						/*$this->tbl_event_module_sitepal_master() ;
						$condition = " event_id = '".$postData[eventId]."'" ;

						$result = $this->selectRecord( $condition ) ;
						$recordCount = $this->recordNumber( $result ) ;

						if( $recordCount > 0 )
						{
							$sitePalCode = $this->fetchRecord( $result ) ;

							$sitePal = stripslashes($sitePalCode[0][sitepal_code]) ;
						}
						else
						{
							$sitePal = "Click on the edit to manage sitepal." ;
						}*/
						$sitePal = "<div style='text-align:left;'>Click on the edit to manage sitepal.</div>" ;
						//// end site pal code

						$addModule = str_replace( "[BOX_TITLE]" , "SitePal" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ;
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "smallArticle" , $addModule ) ;
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , $sitePal , $addModule ) ;
						
						break;
				case "recentpictures":

						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = " | <a href='javascript:;' title='Add Picture' onclick=\"addRecentPictures('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Edit</b></a> " ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						
						//////////// RECENT PHOTOS  ////////////////
						$this->tbl_event_master() ;
						$condition = " event_id = '".$postData[eventId]."'" ;
						$result = $this->selectRecord( $condition ) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$eventData = $this->fetchRecord( $result ) ;
						}
						
						if($eventData[0][allow_to_upload_photos] == "F") {
							$checkedFan1 = "checked" ;
							$checkedUser1 = "" ;
							$dontallow1 = "" ;
						} else if($eventData[0][allow_to_upload_photos] == "U") {
							$checkedFan1 = "" ;
							$checkedUser1 = "checked" ;
							$dontallow1 = "" ;
						} else {
							$checkedFan1 = "" ;
							$checkedUser1 = "" ;
							$dontallow1 = "checked" ;
						}
						
						$photoList = "<div> </div>" ;
						$photoList .= "<div id=setallow ><table><tr><td><input ".$checkedFan1." type=radio name=allow value=F onclick=setallowance(this.value,".$postData[eventId].",1); ></td><td>Allow fans to upload photos </td><td><input type=radio name=allow value=U onclick=setallowance(this.value,".$postData[eventId].",1); ".$checkedUser1." ></td><td>Allow Users to upload photos</td><td><input type=radio name=allow value=D onclick=setallowance(this.value,".$postData[eventId].",1); ".$dontallow1." ></td><td>Dont allow Users to upload photos</td></tr></table></div>" ;
						$photoList .= "<div id=setallowAlter style='display:none;'></div>" ;
						
					  $cond = " member_id = '".$_SESSION["sess_memId"]."' AND album_category != 'R' " ;
					  //// list of my photos
					  $this->tbl_album_master() ;
					  $condition = $cond ;
					  $result = $this->selectRecord( $condition ) ;
					  $recordCount = $this->recordNumber($result) ;
				
					  $pageNumber = ($postData[page])?$postData[page]:1 ;
				
					  if( $postData[recordPP] && $postData[recordPP] == "showall" )
						  $result = $this->selectRecord( $condition , '' ) ;
					  else
						  $result = $this->selectRecord( $condition , $pageNumber ) ;
				
					  $affectRows = $this->recordNumber($result) ;
					  $pagination = $this->displayListPaging( $recordCount , $pageNumber , $affectRows , '' , 1 ) ;
					
					  if( $recordCount > 0 )
					  {
						   $albumList = $this->fetchRecord( $result ) ;
						   $currentCounter = 1 ;
						   
						   $photoListingName = array() ;
						   $photoListingId = array() ;
						   $photoListingSrc = array() ;
						   $photoListingRem = array() ;
						   $photoListingHref = array() ;
						   $photoListingWd[] = array() ;
						   $photoListingHt[] = array() ;
						   $photoListingFont[] = array() ;
				
							$photoList .= "<tr>" ;
							$photoList .= "<td colspan=2 align=center>" ;
							$photoList .= "<table  border='0' cellpadding='1' cellspacing='1' ><tr>" ;
				
						   foreach( $albumList as $albumKey=>$albumValue )
						   {
							  $albumid = $albumValue[album_id] ;
							  $albumName = ucwords($albumValue[album_name]) ;
							  $albumArray[] = $albumid ;
							  
							  //////// photo list
							  $this->tbl_photo_master() ;
							  $condition = " member_id = '".$_SESSION["sess_memId"]."' AND album_id = '".$albumid."' ".$searchKeywordCondition." ORDER BY photo_default DESC" ;
				
							  $result = $this->selectRecord( $condition ) ;
							  $photoCount = $this->recordNumber( $result ) ;
				
							  if( $photoCount > 0 )
							  {
							  	  
								  $photoInfo = $this->fetchRecord( $result ) ;
								  
								  if( ucwords($fetchEventData[0][event_title]) == $albumName ) {
								  $photoPath = _WEBSITE_HTTP_URL._PROFILE_DIRECTORY.$eventData[0][member_id]."/photos/thumb/" ;
									//$photoPath = _WEBSITE_HTTP_URL._PROFILE_DIRECTORY.$fetchEventData[0][member_id]."/".$fetchEventData[0][event_profile_folder]."/thumb/" ;
								  } else {
									$photoPath = _WEBSITE_HTTP_URL._PROFILE_DIRECTORY.$eventData[0][member_id]."/photos/thumb/" ;
								  }
				
								  foreach( $photoInfo as $photoListKey=>$photoListValue )
								  {
									  if( $photoListValue[photo_default] == 1 )  
									  {
										  $photoClass = "defaultPhoto" ;
										  $photoActionClass = "photodefaultAction" ;
									  }
									  else										 
									  {
										  $photoClass = "photos" ;
										  $photoActionClass = "photoAction" ;
									  }
									  
									$imageSize = getimagesize( $photoPath.$photoListValue[photo_name] ) ;
									
									$this->tbl_event_module_master() ;
									$condition = " event_module_id = '".$postData[eventModId]."' " ;
									$result = $this->selectRecord($condition) ;
									$record = $this->recordNumber($result) ;
									if($record > 0) {
										$fetchDataModuleList = $this->fetchRecord($result) ;
										$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
									}
									
									if( $bxwd == "650" ) {
										$wnew = 100 ;
										$hnew = 100 ;
										$fontsize = 12;
									} else {
										$wnew = 50 ;
										$hnew = 50 ;
										$fontsize = 10;
									}
									  	
									  ///// photo title
									  $photoTitle = ucwords($photoListValue[photo_title]) ;
									  $photoId = $photoListValue[photo_id] ;
									  $photoTitle = ucwords($photoListValue[photo_title]) ;
									  $photoId = $photoListValue[photo_id] ;
									  
									  if($photoId != "")
									  {
									  	  $photoListingWd[] = $wnew ;
										  $photoListingHt[] = $hnew ;
										  $photoListingFont[] = $fontsize ;
										  $photoListingName[] = $photoTitle ;
										  $photoListingId[] = $photoId ;
										  $photoListingSrc[] = $photoPath.$photoListValue[photo_name] ;
										  $photoListingRem[] = "" ;
										  $photoListingHref[] = _WEBSITE_HTTP_URL."picture.php?picId=".$photoId."&profile=".$eventData[0][member_id] ;
									  }
										
									  //if( $currentCounter % 5 == 0 )  $photoList .= "</tr><tr valign=top>" ;
									  //$currentCounter++ ;
								  }
							  }
							  
							  	/*print_r($photoListingSrc) ;
								echo "zzzzzzzzzzz" ; 
								die() ;*/
							  
								
						   }
						   
						  /* echo "<br>" ;
							print_r($photoListingSrc) ;
							die() ;*/
							
							//////////   EVENT PHOTOS   /////////////
							$this->tbl_event_master() ;
							$condition = " event_id = '".$postData[eventId]."' " ;
							$result = $this->selectRecord($condition) ;
							$recordFlyer12 = $this->recordNumber($result) ;
							if($recordFlyer12 > 0)
							{
								$fetchDataEvent1 = $this->fetchRecord( $result ) ;
								$photoFlyerPath = _WEBSITE_HTTP_URL._PROFILE_DIRECTORY.$_SESSION["sess_memId"]."/".$fetchDataEvent1[0][event_profile_folder]."/";
								
								foreach( $fetchDataEvent1 as $fetchDataEvent1Key=>$fetchDataEvent1Value )
								{
									$photoClass = "photos" ;
									$photoActionClass = "photoAction" ;
									
									///// photo title
									$photoTitle2 = "Event Image" ;
									$photoId2 = $fetchDataEvent1Value[event_id] ;
									
									  if($photoId2 != "") 
									  {	
										  $photoListingName[] = $photoTitle2 ;
										  $photoListingId[] = $photoId2 ;
										  $photoListingSrc[] = $photoFlyerPath.$fetchDataEvent1Value[event_photo] ;
										  $photoListingRem[] = "" ;
										  $photoListingHref[] = _WEBSITE_HTTP_URL."picture.php?picId=".$photoId2."&imgOf=event&eventId=".$fetchDataEvent1Value[event_id]."&profile=".$_SESSION["sess_memId"] ;
									  }
								 }
							}
						   
						   $currentCounter12 = 1 ;
							for(  $i=0 ; $i<count($photoListingId) ; $i++ )
							{
								  $photoClass = "photos" ;
								  $photoActionClass = "photoAction" ;
								  
								  if($photoListingRem[$i] != "") {
									$func = $photoListingRem[$i] ;
								  } else {
									$func = "" ;
								  }
								  
								  if( $bxwd == "650" ) {
										$wnew = 100 ;
										$hnew = 100 ;
										$fontsize = 12;
										$set_var = 0;
									} else {
										$wnew = 50 ;
										$hnew = 50 ;
										$fontsize = 10;
										$set_var = 1;
									}

								  ///// photo title
								  $photoTitle = ucwords($photoListingName[$i]) ;
								  $photoId = $photoListingId[$i] ;
								  
								  $photoList .= "<td valign=top class='".$photoClass." new_photo_class' align=center>" ;
								  $photoList .= "<table width='100%' height='100%' border='0' cellspecing='0' cellpadding='0' class='photo_table_class' ><tr><td height='140' width='105'>";
								  $photoList .= "<div title='testtitle".$photoTitle."' class='div_photo_class'><a href='".$photoListingHref[$i]."'>" ;
								  $photoList .= "<img src='/imageresize.php?image=".$photoListingSrc[$i]."&width=".SLIDESHOW_IMG_WIDTH."&height=".SLIDESHOW_IMG_HEIGHT."' border=0>" ;
								  //$photoList .= "<br>".substr( $photoTitle , 0 , 25 ) ;
								 // $photoList .= "</a>&nbsp;<a href='javascript:;' onclick='".$photoListingRem[$i].";'><img src='images/delete.png' border=0 title='Remove Picture'></a></div></td>" ;
								
								  $photoList .= "</a></div></td></tr></table><div class='photo_title_class'><b>".substr( $photoTitle , 0 , 25 )."</b><a href='javascript:;' onclick='".$photoListingRem[$i]."'><img src='../images/delete.png' border=0 title='Remove Picture' class='photo_remove_image' ></a></div></td>";
								 if($set_var == 1)
								 {
								 	if( $currentCounter12 % 3 == 0 ) $photoList .= "</tr><tr valign=top>" ;
								 	$currentCounter12++ ;
								 }else{
									  if( $currentCounter12 % 4 == 0 ) $photoList .= "</tr><tr valign=top>" ;
									  $currentCounter12++ ;
								 }
							}
				
						    $photoList .= "</tr></table>" ;
							$photoList .= "</td>" ;
							$photoList .= "</tr>" ;
					  }
					  
					  
					  
					  //echo "zzzzzzzzzzzzzz".$photoList ; die() ;
							
								  ///////  RECENT PHOTOS  /////////

						$addModule = str_replace( "[BOX_TITLE]" , "Event Recent Pictures" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ;
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "bigArticle" , $addModule ) ;
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , $photoList , $addModule ) ;

						break;
				case "guestbook":

						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = " " ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;

						//$addModule = str_replace( "[BOX_TITLE]" , "Guest Book" , $moduleC ) ;
						$addModule = str_replace( "[BOX_TITLE]" , "RSVP Book" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ;
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "bigArticle" , $addModule ) ;
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , "<div style='text-align:left;'>Show's who is invited, who has confirmed their attendance, who are interested and who won't be attending.</div>" , $addModule ) ;

						break;

				case "eventwall":

						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = " " ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;

						$addModule = str_replace( "[BOX_TITLE]" , "Event Wall" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ;
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "bigArticle" , $addModule ) ;
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , "<div style='text-align:left;'>Event wall comments listing.</div>" , $addModule ) ;

						break;
				case "calender":

						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = " " ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;

						$addModule = str_replace( "[BOX_TITLE]" , "Calendar" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ;
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "smallArticle" , $addModule ) ;
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , "<div style='text-align:left;'>Event Calender.</div>" , $addModule ) ;

						break;
				case "locationmap":

						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = " | <a href='javascript:;' title='Edit Module' onclick=\"editModule('locationmap',".$postData[eventModId].")\"><b>Edit</b></a> " ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
			
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}

						///// map location
						$this->tbl_event_module_locationmap_master() ;
						$condition = " event_id = '".$postData[eventId]."' AND event_module_id = '".$postData[eventModId]."' " ;

						$result = $this->selectRecord( $condition ) ;
						$recordCount = $this->recordNumber( $result ) ;

						if( $recordCount > 0 )
						{
							$maplist = $this->fetchRecord( $result ) ;
							//$locationCnt = 1 ;

							$mapLocation = "<table width=100% cellpaddng=2 cellspacing=2 border=0>" ;
							foreach( $maplist as $mapKey=>$mapValue )
							{
								$locationType = ucfirst($mapValue[location_type]) ;
								$locationId = $mapValue[location_id] ;
								
								if( $mapValue[location_type] == "accommodation" )
									$locationAddress = ($mapValue[location_address])?"Show on Map":"Don't show on map" ;
								else
									$locationAddress = $mapValue[location_address].", ".$mapValue[location_zipcode] ;

								//$removeLink = "<div style='float:right'><a title='Remove Slide Map Location' href=\"javascript:removeModuleElement('locationmap','".$locationId."');\"><img class='photosmall' src='../images/delete.png'></a></div>" ;

								$mapLocation .= "<tr>" ;
								$mapLocation .= "<td width=33%>".$removeLink."<b>".$locationType."</b> - ".$locationAddress."</td>" ;
								$mapLocation .= "</tr>" ;
								
								//if( $locationCnt % 3 == 0 )	$mapLocation .= "</tr><tr>" ;

								//$locationCnt++ ;
							}

							//$locationCnt-- ;
							///if( $locationCnt % 3 != 0 )  $mapLocation .= "<td width=50%>&nbsp;</td></tr>" ;
							
							$mapLocation .= "</table>" ;
						}
						///// end map location

						$addModule = str_replace( "[BOX_TITLE]" , "Event Location Map" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "bigArticle" , $addModule ) ;
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , $mapLocation , $addModule ) ;

						break;
				case "youtubevideos":

						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = " | <a href='javascript:;' title='Add Videos' onclick=\"editYoutubeModule('youtube',".$postData[eventModId].")\"><b>Edit</b></a> " ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;

						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$wd = "130" ;
							$ht = "110" ;
						} else {
							$ht = "50" ;
							$wd = "50" ;
						}
	
						$this->tbl_youtube_videos() ;
						$condition = " t_id != '' AND event_id = '".$postData[eventId]."' AND event_module_id = '".$postData[eventModId]."' ORDER BY rand() LIMIT 6" ;

						$result = $this->selectRecord( $condition ) ;
						$recordCount = $this->recordNumber($result) ;
						if($recordCount > 0) 
						{
							//$youtubeVideos = "<table width='30%' align=left cellpadding='0' cellspacing='2' border='0'> " ;
							//$youtubeVideos .= "<tr valign=top>" ;
							
							$fetchYouTubeVideos = $this->fetchRecord($result) ;
								
							$youtubePicCnt = 1 ;
							$youtubeVideos = "<table width=30% cellpaddng=2 cellspacing=2 border=0><tr>" ;
							foreach( $fetchYouTubeVideos as $fetchYouTubeVideosKey=>$fetchYouTubeVideosValue )
							{
								// onclick="viewYoutubeVideo(\''.$uRl.'\' , \''.$tit.'\')";
								
								$u = explode("/",$fetchYouTubeVideosValue[link]) ;
								$ct = count($u) ;
								
								$uRl = $u[$ct-1] ;
								$tit = str_replace("'","",$fetchYouTubeVideosValue[title]) ;
								
								$youtubeVideos .= "<td width=20% align=center>" ;
								//$youtubeVideos .= "<a href='".$fetchYouTubeVideosValue[link]."' title='".$fetchYouTubeVideosValue[title]."' target='_blank'><img src='".$fetchYouTubeVideosValue[desc]."' width=".$wd." height=".$ht." border='0' class=photos></a></td>" ;
								$youtubeVideos .= '<a href="javascript:;" title="'.$fetchYouTubeVideosValue[title].'" onclick="viewYoutubeVideo(\''.$uRl.'\' , \''.$tit.'\')"; ><img src="'.$fetchYouTubeVideosValue[desc].'" width='.$wd.' height='.$ht.' border=0 class=photos></a></td>' ;
								
								if( $youtubePicCnt % 4 == 0 )  $youtubeVideos .= "</tr><tr>" ;

								$youtubePicCnt++ ;
							}
							
							$youtubeVideos .= "</tr></table>" ;
						}
						else
						{
							$youtubeVideos .= "<div style='text-align:left;'>Click on the edit to manage youtube video.</div>" ;
						}

						$addModule = str_replace( "[BOX_TITLE]" , "Event YouTube Videos" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ;
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "bigArticle" , $addModule ) ;
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , $youtubeVideos , $addModule ) ;

						break;
				case "amazonwishlist":
						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = " | <a href='javascript:;' title='Edit Module' onclick=\"editModule('amazonwishlist',".$postData[eventModId].")\"><b>Edit</b></a> " ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;

						$addModule = str_replace( "[BOX_TITLE]" , "Amazon Wish List" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ;
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "bigArticle" , $addModule ) ;
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , "<div style='text-align:left;'>Click on the edit to manage amazon wishlist.</div>" , $addModule ) ;

						break;
				case "merchandisingshop":
						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = " | <a href='javascript:;' title='Edit Shop' onclick=\"editModule('merchandisingshop',".$postData[eventModId].")\"><b>Edit</b></a>" ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;

						//// shop details
						//// member products
						$this->tbl_event_merchant_shop() ;
						$condtion = " event_id = '".$postData[eventId]."' AND event_module_id = '".$postData[eventModId]."' AND product_status = '1'" ;
						$result = $this->selectRecord( $condtion ) ;
						$recordCount = $this->recordNumber($result) ;
						
						if( $recordCount > 0 )
						{
							$memberProductList = $this->fetchRecord( $result ) ;
							
							$merchantProduct = "<table align='left' width=30% cellpadding=2 cellspacing=2 border=0><tr>" ;
							$productCounter = 1 ;
							
							foreach( $memberProductList as $productKey=>$productValue )
							{
								//// product details
								$this->tbl_merchantise_product_master() ;
								$condition = " product_id = '".$productValue[product_id]."'" ;
								$result = $this->selectRecord( $condition ) ;
								$recordCount = $this->recordNumber($result) ;
								
								$productImage = $productName = "" ;
								if( $recordCount > 0 )
								{
									$productList = $this->fetchRecord( $result ) ;
									$productImage = $productList[0][product_image] ;
									$productName = substr($productList[0][product_name],0,20) ;
									$productPrice = $productList[0][product_price] ;
								}
								//// end product details
								
								if( !trim($productImage) )  $productImage = "images/no-image.jpg" ;
								else					    $productImage = "product_img/thumb/".$productImage ;

								$merchantProduct .= "<td width=14% align=left style='text-align:left;'>" ;
								$merchantProduct .= "<img src='"._WEBSITE_HTTP_URL.$productImage."' width=70 height=70><br>".$productName ;
								$merchantProduct .= "</td>" ;
								
								if( $productCounter % 7 == 0 ) $merchantProduct .= "</tr><tr>" ;
								
								$productCounter++ ;
							}

							$merchantProduct .= "</tr></table>" ;
						}
						else
						{
							$merchantProduct .= "<div style='text-align:left;'>Click on edit to manage your shop.</div>" ;
						}
						///// end member products
						////// end shop 

						$addModule = str_replace( "[BOX_TITLE]" , "Merchandising Shop" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ;
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "bigArticle" , $addModule ) ;
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , $merchantProduct , $addModule ) ;

						break;
				case "videoplayer":
						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = " | <a href='javascript:;' title='Edit Module' onclick=\"editModule('videoplayer',".$postData[eventModId].")\"><b>Edit</b></a> " ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;

						//////////// RECENT PHOTOS  ////////////////
						$this->tbl_event_master() ;
						$condition = " event_id = '".$postData[eventId]."' " ;
						$result = $this->selectRecord( $condition ) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$eventData12 = $this->fetchRecord( $result ) ;
						}
						
						if($eventData12[0][allow_to_upload_videos] == "F") {
							$checkedFan = "checked" ;
							$checkedUser = "" ;
							$dontallow = "" ;
						} else if($eventData12[0][allow_to_upload_videos] == "U") {
							$checkedFan = "" ;
							$checkedUser = "checked" ;
							$dontallow = "" ;
						} else {
							$checkedFan = "" ;
							$checkedUser = "" ;
							$dontallow = "checked" ;
						}
						
						$videoPlayer = "<div> </div>" ;
						$videoPlayer .= "<div id=setallowvideo ><table><tr><td><input ".$checkedFan." type=radio name=allowvideo value=F onclick=setallowance(this.value,".$postData[eventId].",2); ></td><td>Allow fans to upload videos </td><td><input type=radio name=allowvideo value=U onclick=setallowance(this.value,".$postData[eventId].",2); ".$checkedUser." ></td><td>Allow Users to upload videos</td><td><input type=radio name=allowvideo value=D onclick=setallowance(this.value,".$postData[eventId].",2); ".$dontallow." ></td><td>Dont allow Users to upload videos</td></tr></table></div>" ;
						$videoPlayer .= "<div id=setallowvideoAlter style='display:none;'></div>" ;

						////// music files for event
						$this->tbl_event_module_videoplayer_master() ;
						$condition = " event_id = '".$postData[eventId]."' AND event_module_id = '".$postData[eventModId]."' " ;

						$result = $this->selectRecord( $condition ) ;
						$recordCount = $this->recordNumber( $result ) ;

						if( $recordCount > 0 )
						{
							$videoList = $this->fetchRecord( $result ) ;

							$videoPlayer .= "<table width=100% cellpadding=2 cellspacing=2 border=0>" ;
							foreach( $videoList as $videoKey=>$videoValue )
							{
								$videoName = ucfirst($videoValue[event_video_name]) ;
								$videoId = $videoValue[video_id] ;

								$videoPlayer .= "<tr>" ;
								$videoPlayer .= "<td width=90% align=left>".$videoName."</td>" ;
								$videoPlayer .= "<td width=10% align=right><a title='Remove Music File' href=\"javascript:removeModuleElement('videoplayer','".$videoId."');\"><img class='photosmall' src='../images/delete.png'></a></td>" ;
								$videoPlayer .= "</tr>" ;
							}
							$videoPlayer .= "</table>" ;
						}
						else
							$videoPlayer .= "<div style='text-align:left;'>Click on edit to manage the video playlist.</div>" ;
						///// end music file

						$addModule = str_replace( "[BOX_TITLE]" , "Video Player" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ;
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "bigArticle" , $addModule ) ;
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , $videoPlayer , $addModule ) ;

						break;
				case "googleadsense":
						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = " | <a href='javascript:;' title='Edit Module' onclick=\"editModule('googleadsense',".$postData[eventModId].")\"><b>Edit</b></a> " ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;

						//// adsense 
						$this->tbl_event_module_google_adsense_master() ;
						$condition = " event_id = '".$postData[eventId]."' AND event_module_id = '".$postData[eventModId]."' " ;

						$result = $this->selectRecord( $condition ) ;
						$recordCount = $this->recordNumber( $result ) ;

						if( $recordCount > 0 )
						{
							$adsenseList = $this->fetchRecord( $result ) ;

							$adsense = "<table width=100% cellpadding=2 cellspacing=2 border=0>" ; 
							$adsense .= "<tr><td>&nbsp; <b>Width : </b>".$adsenseList[0][adsense_width]."</td></tr>" ;
							$adsense .= "<tr><td>&nbsp; <b>Height : </b>".$adsenseList[0][adsense_height]."</td></tr>" ;
							/*$adsense .= "<tr><td>&nbsp; <b>Client Code : </b>".$adsenseList[0][adsense_client_code]."</td></tr>" ;*/
							$adsense .= "</table>" ;
						}
						else
						{
							$adsense = "<div style='text-align:left;'>Click on edit to manage Google Adsense</div>" ;
						}
						//// end adsense

						$addModule = str_replace( "[BOX_TITLE]" , "Google AdSense" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ;
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "bigArticle" , $addModule ) ;
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , $adsense , $addModule ) ;

						break;
						
				case "eventdetails":
						$panelId = str_replace( " " , "" , strtolower($module.$postData[eventModId]) ) ;
						$editLink = " | <a href='javascript:;' title='Edit Module' onclick=\"editModule('eventdetails',".$postData[eventModId].")\"><b>Edit</b></a> " ;
						$boxId = $module."_".$postData[eventModId] ;
						
						$removeModule = " <a href='javascript:;' title='Remove Module' onclick=\"removeModule('".$boxId."',".$postData[eventId].",".$postData[eventModId]." )\"><b>Remove</b></a> " ;
						
						$this->tbl_event_module_master() ;
						$condition = " event_module_id = '".$postData[eventModId]."' " ;
						$result = $this->selectRecord($condition) ;
						$record = $this->recordNumber($result) ;
						if($record > 0) {
							$fetchDataModuleList = $this->fetchRecord($result) ;
							$bxwd = $fetchDataModuleList[0][event_module_boxwidth] ;
						}
						
						if( $bxwd == "650" ) {
							$txteventmoduleboxwidth = "720" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						} else {
							$txteventmoduleboxwidth = "352" ;
							//$imgArrow = "<img src='../images/arraow_left.png' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",".$bxwd.");' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;
						}
						
						$imgArrow = "<img src='../images/arrowlef.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",650);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/arrowright.gif' border='0' onclick='addBoxWidthToEvent(".$postData[eventModId].",320);' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/down.png' border='0' style='cursor:pointer;' >&nbsp;|&nbsp;<img src='../images/up.png' border='0' style='cursor:pointer;' > " ;			
						
						$addModule = str_replace( "[BOX_TITLE]" , "Event Details" , $moduleC ) ;
						$addModule = str_replace( "[BOX_ID]" , $boxId , $addModule ) ;
						$addModule = str_replace( "[EDIT_MODULE]" , $editLink , $addModule ) ;
						$addModule = str_replace( "[IMG_ARROW]" , $imgArrow , $addModule ) ;
						$addModule = str_replace( "[REMOVE_MODULE]" , $removeModule , $addModule ) ;
						$addModule = str_replace( "[EVENT_ID]" , $postData[eventId] , $addModule ) ;
						$addModule = str_replace( "[EVENT_MODULE]" , $postData[moduleId] , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTROL_PANEL]" , $panelId."Panel" , $addModule ) ;
						$addModule = str_replace( "[MODULE_CLASS]" , "bigArticle" , $addModule ) ;
						$addModule = str_replace( "[TABLE_WIDTH]" , $txteventmoduleboxwidth , $addModule ) ;
						$addModule = str_replace( "[BOX_CONTENT]" , "<div style='text-align:left;'>click on edit to manage event details.</div>" , $addModule ) ;
						
						break;
						
				default:
						break;
			}

			return $addModule ;
		}
		
		///// convert video file to flv file
		function convertVideoToFLV( $videoFile , $videothumbname , $videofilename )
		{
			//// check for file extension
			$p = $videofilename ;
			$pos = strrpos($p,".") ;
			$ph = substr($p,$pos+1,strlen($p)-$pos) ;
			$fileExt = strtolower($ph) ;

			//// source file name
			$sourceFile = $videoFile.$videofilename ;

			//// flv file name
			$flvf_name = str_replace( $ph , "flv" , $videofilename ) ;
			$FLVfilename = $videoFile.$flvf_name ;

			/// image name
			$flvi_name = str_replace( ".".$ph , "_%d.jpg" , $videofilename ) ;
			$FLVimagefilename = $videoFile.$flvi_name ;
			
			//// check the file extention and convert file to flv
			if( $fileExt == "wmv" || $fileExt == "wm" )
				exec( _FFMPEG_INSTALL_PATH ." -i ". $sourceFile ." -b 700k -r 25 -s 320x240 -ar 22050 -ab 64 -f flv ".$FLVfilename ) ;
			else if( $fileExt == "avi" )
				exec( _FFMPEG_INSTALL_PATH ." -i ". $sourceFile ." -ar 22050 -ab 32 -f flv -s 320x240 ".$FLVfilename ) ;
			elseif( $fileExt == "3gp" )
				exec( _FFMPEG_INSTALL_PATH ." -i ". $sourceFile ." -sameq -an ".$FLVfilename ) ;
			elseif( $fileExt == "mp3" )
				exec( _FFMPEG_INSTALL_PATH ." -i ". $sourceFile ." -ar 22050 -ab 32 -f flv -s 320x240 ".$FLVfilename ) ;
			elseif( $fileExt == "mpg" || $fileExt == "mpeg" )
				exec( _FFMPEG_INSTALL_PATH ." -i ". $sourceFile ." -f flv ".$FLVfilename ) ;

			//echo _FFMPEG_INSTALL_PATH ." -i ". $sourceFile ." -f flv ".$FLVfilename
			//die();
			//// remove original file
			@unlink( $sourceFile ) ;
			
			/// halt for 10 sec.
			sleep( 10 ) ;

			//// if flv file create successfully. create image of the video
			if( file_exists( $FLVfilename ) )
			{
				//// create image of video
				exec( _FFMPEG_INSTALL_PATH." -i ".$FLVfilename." -an -ss 00:00:08 -t 00:00:01 -r 1 -y -s 320x240 ".$FLVimagefilename ) ;
			}
 
			return array( $flvf_name , $flvi_name ) ;    // return file name , video image name
		}

		//// create image for the video file
		function createVideoImageFromFrame( $FLVfilename , $flvimagePath , $imageName )
		{
			//// if flv file create successfully. create image of the video
			if( file_exists( $FLVfilename ) && !file_exists($imageName) )
			{
				//echo $FLVfilename."----".$flvimagePath."<br>" ;
				//// create image of video
				exec( _FFMPEG_INSTALL_PATH." -i ".$FLVfilename." -an -ss 00:00:03 -t 00:00:01 -r 1 -y -s 320x240 ".$flvimagePath ) ;
			}
		}

		//// upload multiple image
		function uploadImage( $path , $fileArray , $thumbnail='' )
		{
			$currentCounter = 0 ;
			$filename = "" ;
									
			foreach( $fileArray as $fileKey=>$fileValue )
			{
			  if( $fileValue['name'] )
			  {
				$file = explode( "." , $fileValue['name'] ) ;
				$fileExt = $file[count($file)-1] ;
				$filename = rand(0,100000).".".$fileExt ;

				move_uploaded_file( $fileValue['tmp_name'] , $path.$filename ) ;

				if( !trim($thumbnail) )
				 {
					$imageProperties = getimagesize( $path.$filename ) ;

					$imageWidth = $imageProperties[0] ;
					$imageHeight = $imageProperties[1] ;

					$this->resizeImageUsingGDLibrary( $path.$filename , $this->thumbnailImageWidth , $this->thumbnailImageHeight , $path."/thumb/".$filename , $imageWidth , $imageHeight  ) ;
				 }
			  }
			}
						
			return $filename ;
		}

		//// upload single image
		function uploadSingleImage( $path , $fileArray , $thumbnail='' )
		{
			
			$currentCounter = 0 ;
			$filename = "" ;
			$fileValue = $fileArray ;
			
			if( $fileValue['name'] )
			{
				$file = explode( "." , $fileValue['name'] ) ;
				$fileExt = $file[count($file)-1] ;
				$filename = rand(0,100000).".".$fileExt ;
				
				move_uploaded_file( $fileValue['tmp_name'] , $path.$filename ) ;
				
				if( !trim($thumbnail) )
				 {
					$imageProperties = getimagesize( $path.$filename ) ;

					$imageWidth = $imageProperties[0] ;
					$imageHeight = $imageProperties[1] ;

					$this->resizeImageUsingGDLibrary( $path.$filename , $this->thumbnailImageWidth , $this->thumbnailImageHeight , $path."/thumb/".$filename , $imageWidth , $imageHeight  ) ;
				 }
			}
						
			return $filename ;
		}

		function uploadImageMagick( $path , $fileArray , $thumbnail='' )
		{
			$currentCounter = 0 ;

			foreach( $fileArray as $fileKey=>$fileValue )
			{
				if( $fileValue['name'] )
				{
			   		$file = explode( "." , $fileValue['name'] ) ;
					$fileExt = $file[count($file)-1] ;
					$filename = rand(0,100000).".".$fileExt ;
					
					//// image file name
					$uploadedfile = $fileValue['tmp_name'];

					//// image width
					list($width,$height)=getimagesize($uploadedfile);

					$newwidth = $this->imageWidth ;
					
					if( $width )
						$newheight=($height/$width) * $this->imageWidth ;
				
					move_uploaded_file( $fileValue['tmp_name'] , $path.$filename ) ;
					chmod( $path.$filename , 0777 ) ;
					$registerGlobalStatus = 1 ;
					
					///// please use this code if the image magick support is on your server.
					if( trim($registerGlobalStatus) && $this->imageMagickStatus == 1 )
					{
						////////// upload large image
						//$this->resizeImageUsingImagemagick( $path.$filename , $newwidth , $newheight , $path."thumb/".$filename ) ;									
						///////// upload thumbnail image
						$this->resizeImageUsingImagemagick( $path.$filename , $this->thumbnailImageWidth , $this->thumbnailImageHeight , $path."thumb/".$filename ) ;
					}
				}
			}
						
			return $filename ;
		}

	//////////// resize image using GD liberary
		function resizeImageUsingGDLibrary( $uploadedfile , $newwidth , $newheight , $filenameUpload , $width , $height )
		{
			/*$src = imagecreatefromjpeg($uploadedfile);

			$tmp=imagecreatetruecolor($newwidth,$newheight);

			imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);

			//$filenameUpload = $path. $filename ;
			
			imagejpeg($tmp,$filenameUpload,100);

			imagedestroy($src);
			imagedestroy($tmp); */
			
			$filename = $filenameUpload ;
			$name = $uploadedfile ;
			$new_w = $newwidth ;
			$new_h = $newheight ;
			
			$system = substr(strrchr($name, "."), 1 );
			if (preg_match('/jpg|jpeg/',$system))
				{
					$src_img=imagecreatefromjpeg($name);
				}
			else if (preg_match('/png/',$system))
				{
					$src_img=imagecreatefrompng($name);
				}
			else if (preg_match('/gif/',$system))
				{
					$src_img=imagecreatefromgif($name);
				}
			else $src_img=imagecreatefromjpeg($name);

			$old_x=imageSX($src_img);
			$old_y=imageSY($src_img);
			if ($old_x > $old_y) {
				$thumb_w=$new_w;
				$thumb_h=$old_y*($new_h/$old_x);
			}
			if ($old_x < $old_y) {
				$thumb_w=$old_x*($new_w/$old_y);
				$thumb_h=$new_h;
			}
			if ($old_x == $old_y) {
				$thumb_w=$new_w;
				$thumb_h=$new_h;
			}
			
			if($thumb)
			{
			    if($thumb_w > $this->thumbnailImageWidth )
				   $thumb_w = $this->thumbnailImageWidth;
				
				if($thumb_h > $this->thumbnailImageHeight || $thumb_h < $this->thumbnailImageHeight)
				   $thumb_h = $this->thumbnailImageHeight;   
			}
			
			$dst_img= @imagecreatetruecolor($thumb_w,$thumb_h);
			 if($system=="gif")
			 {
				$transparent = @imagecolorallocate($dst_img, "255", "255", "255");
				@imagefill($dst_img, 0, 0, $transparent);
			 }
			@imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
			if (preg_match("/png/",$system))
			{
				imagepng($dst_img,$filename);
			}
			else if (preg_match('/gif/',$system))
			{
				imagegif($dst_img,$filename);
			}
			else
			{
				imagejpeg($dst_img,$filename);
			}

			imagedestroy($dst_img);
			imagedestroy($src_img);
		}

		///////////// resize image using imagemagick
		function resizeImageUsingImagemagick( $filename , $newwidth , $newheight , $newPath='' )
		{
			if( !trim($newPath) ) $newPath = $filename ;
			system( "convert ".$filename." -resize ".$newwidth."x".$newheight." ".$newPath ) ;
		}

		function sendmail( $emailaddress , $emailsubject , $body , $attachments=false)
		{
				 /* To send HTML mail, you can set the Content-type header. */ 
				$headers  = "MIME-Version: 1.0\r\n"; 
				$headers .= "Content-type: text/plain; charset=iso-8859-1\r\n";
				/* additional headers */ 
				$headers .= "From: EventsListed <mail@eventslisted.com>\r";
							  
			  	$body = preg_replace ("'<br[^>]*?>'si" , "\n" , $body ) ;
				$body = strip_tags($body) ;
				#### mail template ####
				
				/*if( file_exists( "mail.html" ) )
					$templateFile = "mail.html" ;
				elseif( file_exists( "../mail.html" ) )
					$templateFile = "../mail.html" ;
				elseif( file_exists( "../../mail.html" ) )
					$templateFile = "../../mail.html" ;
				elseif( file_exists( "../../../mail.html" ) )
					$templateFile = "../../../mail.html" ;

				$fp = fopen( $templateFile , "r" ) or die( "file opening error............" ) ;
				$template = fread( $fp , filesize($templateFile) ) ;
				fclose( $fp ) ;

				$template = str_replace( "[MAIL_TITLE]" , $emailsubject , $template ) ;
				$templateBody = str_replace( "[MAIL_BODY]" , $body , $template ) ;*/

			  ##### end mail template #####

			  $body = str_replace( "{unsubscribe_me}" , base64_encode($emailaddress) , $body ) ;
			  
			  //@mail($emailaddress, $emailsubject, $templateBody , $headers);
			  @mail($emailaddress, stripslashes($emailsubject), stripslashes($body) , $headers);
		}

		function redirect($pageName)
		{
			echo "<script>
			        window.location = '".$pageName."' ;
			      </script>" ;
			exit ;
		}

		// function will generate 12 to 12 digit alphanumeric number.
		function randomAplphanumericNumber( $rangeFrom = '' , $rangeTo = '')  
		{
			 if( !$rangeFrom ) $rangeFrom = 12 ;
			 if( !$rangeTo )   $rangeTo = 12 ;
			 
			 $nameLength = rand( $rangeFrom , $rangeTo) ;  /// specify range of alphanumric number
			 $NameChars = 'abcdefghijklmnopqrstuvwxyz';
			 $Vouel = 'aeiou123467890';
			 $Name = "";

			 $rangeDevide = $rangeFrom / 2 ;
			 
			 for ($index = 1; $index <= $nameLength; $index++) 
			 { 	
				if ($index % $rangeDevide == 0)
				{
				    $randomNumber = rand(1,strlen($Vouel));
				    $Name .= substr($Vouel,$randomNumber-1,1); 
				}
				else
				{
					$randomNumber = rand(1,strlen($NameChars));
					$Name .= substr($NameChars,$randomNumber-1,1);
				} 
			 }
			
			 $alphanumericNumber = strtoupper( $Name ) ;

			 return $alphanumericNumber ;
		}

		function randomNumericNumber( $rangeFrom = '' , $rangeTo = '')  
		{
			 if( !$rangeFrom ) $rangeFrom = 12 ;
			 if( !$rangeTo )   $rangeTo = 12 ;
			 
			 $nameLength = rand( $rangeFrom , $rangeTo) ;  /// specify range of alphanumric number
			 $NameChars = '123467890';
			 $Vouel = '123467890';
			 $Name = "";

			 $rangeDevide = $rangeFrom / 2 ;
			 
			 for ($index = 1; $index <= $nameLength; $index++) 
			 { 	
				if ($index % $rangeDevide == 0)
				{
				    $randomNumber = rand(1,strlen($Vouel));
				    $Name .= substr($Vouel,$randomNumber-1,1); 
				}
				else
				{
					$randomNumber = rand(1,strlen($NameChars));
					$Name .= substr($NameChars,$randomNumber-1,1);
				} 
			 }
			
			 $alphanumericNumber = strtoupper( $Name ) ;

			 return $alphanumericNumber ;
		}


		function explodeContent( $niddle , $value )
		{
			$explodeArray = explode( $niddle , $value ) ;

			return $explodeArray ;
		}

		///// find out the diffrance between two dates parameters ( 22 September 2007 , 10 December 2007 )
		function dateDifference( $startDate , $endDate )
		{
			$uts['start']      =    strtotime( $startDate );
			$uts['end']        =    strtotime( $endDate );
			$days = 0 ;
			
			if( $uts['start']!==-1 && $uts['end']!==-1 )
			{
				if( $uts['end'] >= $uts['start'] )
				{
					$diff    =    $uts['end'] - $uts['start'];
					
					if( $days=intval((floor($diff/86400))) )
						$diff = $diff % 86400;
					if( $hours=intval((floor($diff/3600))) )
						$diff = $diff % 3600;
					if( $minutes=intval((floor($diff/60))) )
						$diff = $diff % 60;
					$diff    =    intval( $diff );     
					
					//return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );

					if( $days == 0 ) 
						$days = "Last " ;
				}
			}
			
			return $days ;
		}

///// find out the age from the number of days
		function ageFromDays( $totalDays='' )
		{
			if( is_numeric($totalDays) )
			{
				$age = ceil( $totalDays / 365) ;
			}

			return $age ;
		}

		function setSession($name, $mem_id, $mem_type, $directory='')
		{
			session_register("sess_name") ;
			session_register("sess_memId") ;
			session_register("sess_memType") ;

			$_SESSION["sess_name"] = ucwords($name) ;
			$_SESSION["sess_memId"] = $mem_id ;
			$_SESSION["sess_memType"] = $mem_type ;

			setcookie( "userProfileID" , $mem_id ) ; 
		}

		function setAdminSession($name, $mem_id)
		{
			session_register("sess_adminName") ;
			session_register("sess_adminId") ;

			$_SESSION["sess_adminName"] = $name ;
			$_SESSION["sess_adminId"] = $mem_id ;

		}

	
		function displayErrorMessage($errorNumber='')
		{
			$errorMsaage = new ErrorMessages($errorNumber) ;
			$errMsg = $errorMsaage->getError() ;

			/*$showMsg .= "<table width=50% height=30 cellpadding=0 cellspacing=0 border=0 class='boxstyle'>" ;
			$showMsg .= "<tr><td class=message>".$errMsg."</td></tr>" ;
			$showMsg .= "</table>" ;*/

			return $errMsg ;
		}

		function welcomeMessage($loginName)
		{
			$message = "Welcome ".$loginName ;

			return $message ;
		}

		function logoutMember()
		{
			setcookie ( "userProfileID", "", time() - 3600) ; 
			session_destroy() ;
			
			if( file_exists("../index.php") )
				$this->redirect("../index.php") ;
			else
				$this->redirect("index.php") ;
		}

		function validateUser()
		{
			if( !$_SESSION["sess_memId"] )
			{
				if( ereg( "member_profile.php" , $_SERVER['HTTP_REFERER'] ) )
				{
					$redirectpage = "doors_closed.php" ;
				}
				else
				{
					$redirectpage = "index.php" ;
				}
				
				if( file_exists("../".$redirectpage) )
					$this->redirect("../".$redirectpage) ;
			    else
					$this->redirect($redirectpage) ;

			} else if( $_SESSION["sess_memId"] && strlen($_SESSION['sess_memId']) > 10  ) {
				if( file_exists("../index.php") )
					$this->redirect("../index.php") ;
			    else
					$this->redirect("index.php") ;
			}
		}

		function validateAdminUser()
		{
			if( !$_SESSION["sess_adminId"] )
			{
				$this->redirect("index.php") ;
			}
		}

		function logoutAdmin()
		{
			session_destroy() ;
			$this->redirect("index.php") ;
		}

		function encryptPassword($password)
		{
			return md5($password) ;
		}

		function languageText($text)
		{
			return $text ;
		}

		function invitedStatus($status)
		{
			if( $status == "I" )
				return "Inviated" ;
			else if( $status == "A" )
				return "Accepted" ;
			else if( $status == "R" )
				return "Rejected" ;
			else if( $status == "P" )
				return "Pending" ;
		}

		function validateURL($pageURL)
		{
			if( ereg( $pageURL , $_SERVER[PHP_SELF] ) )
				return true ;
			else
				return false ;
		}

		function getDayDate()
		{
			for( $i=1 ; $i<=31 ; $i++ )
			{
				if( $i < 10 ) $i = "0".$i ;

				$date[$i][0] = $i ;
				$date[$i][1] = $i ;
			}

			return $date ;
		}

		function getDayMonth()
		{
			for( $i=1 ; $i<=12 ; $i++ )
			{
				$month[$i][0] = $i ;
				$month[$i][1] = date( "M" , mktime( 0 , 0 , 0 , $i , 1 , date("Y") ) ) ;
			}

			return $month ;
		}

		function getDayYear($startYear = '' , $endyear='')
		{
			if( !$startYear )  $startYear = 2005  ;
			
			if( !$endyear )    $endyear = date("Y") + 10 ;
			else			   $endyear = date("Y") + $endyear ;

			for( $i=$startYear ; $i<=$endyear ; $i++ )
			{
				$year[$i][0] = $i ;
				$year[$i][1] = $i ;
			}

			return $year ;
		}

		function getTimeinHour()
		{
			for( $i=1 ; $i<=24 ; $i++ )
			{
				$timeHr[$i][0] = $i ;
				$timeHr[$i][1] = $i ;
			}

			return $timeHr ;
		}

		/// get time
		function getTimeinMinute()
		{
			for( $i=0 ; $i<=59 ; $i++ )
			{
				if( $i < 10 ) $i = "0".$i ;

				$timeMin[$i][0] = $i ;
				$timeMin[$i][1] = $i ;
			}

			return $timeMin ;
		}

		/// get time
		function getTimeinSeconds()
		{
			for( $i=0 ; $i<=59 ; $i++ )
			{
				if( $i < 10 ) $i = "0".$i ;

				$timeMin[$i][0] = $i ;
				$timeMin[$i][1] = $i ;
			}

			return $timeMin ;
		}

		/// get time
		function getTimein()
		{
			$timeMin[0][0] = "AM" ;
			$timeMin[0][1] = "AM" ;

			$timeMin[1][0] = "PM" ;
			$timeMin[1][1] = "PM" ;

			return $timeMin ;
		}

		/// read directory
		function readdirectory($dirPath)
		{
			if (is_dir($dirPath)) 
			{
				if ($dh = opendir($dirPath)) 
				{
					$counter = 0 ;
					while (($file = readdir($dh)) !== false) 
					{
						if( $file != "." && $file != ".." && $file != "Thumbs.db" )
						{
							$directoryContent[$counter] = $file ;
							//$directoryContent[$counter][1] = $file ;
							$counter++ ;
						}
					}
					closedir($dh);
				}
			}

			return $directoryContent ;
		}
		
		/// display stars
		function ratingStars($num)
		{
			for($i=1;$i<=10;$i++)
			{
				if( $num >= $i )
					$star .= "<img src='images/full_rating.gif' border='0'>" ;
				elseif( $num > ($i - 1) && $num < ($i + 1) )
					$star .= "<img src='images/half_rating.gif' border='0'>" ;
				else
					$star .= "<img src='images/no_rating.gif' border='0'>" ;
			}
			
			return $star ;
		}

		
		function displayListPaging( $num , $currentPage , $currentNum , $showCounter='' , $collapseFlag='' , $showNotShow='' )
		   {
		    
			  if($_REQUEST[recordPP])
				  $this->rpp = $rpp = $_REQUEST[recordPP] ;
			  else
				  $this->rpp = $rpp = $this->rpp ;
			 
			 if( $num>$this->rpp && $this->rpp && is_numeric($this->rpp) )
			   {
				   $ct = $num / $this->rpp ;
				   $ct = ceil($ct) ;
				  				   
				   if( $currentPage > 1 )
						$pagingPrevious = "<a class='pagination' href='javascript: getpage(".($currentPage-1).");' title='Previous Page'>Previous</a> &nbsp;&nbsp;&nbsp;&nbsp;" ;
				   else
					    $pagingPrevious = "Previous &nbsp;&nbsp;&nbsp;&nbsp;" ;

				   if($currentPage < $ct && $ct > 5)
					    $pagingEnd = " ..." ;
				   else
					    $pagingEnd = "" ;

				   if($currentPage > 6 )
					   $pagingStart = "... " ;
				   else
					   $pagingStart = "" ;
				   
				   if( $currentPage < 5 && $ct > 5)
				   {
					  $cct = 5 ;
					  $start = 1 ;
				   }
				   else if($currentPage < 5 && $ct <= 5)
				   {
					  $cct = $ct ;
					  $start = 1 ;
				   }
				   else
				   {
					  $start = $currentPage - 5 ;

					  if($start == 0 ) $start = 1 ;
					  $cct = $currentPage ;
				   }
					
				   for($i=$start;$i<=$cct;$i++)
					 {  
						if($currentPage == $i || !trim($currentPage) && $i == 1)
							$paging .= " <b>$i&nbsp;</b> | " ;
					    else
							$paging .= "<a class='pagination' title='".$i." Page' href='javascript: getpage($i);'>$i&nbsp;</a> | " ;
					 }
				  
				  if( trim($currentPage) == "" ) $currentPage++ ;

				  if( $currentPage < $ct )
					  $pagingNext = "&nbsp;&nbsp;&nbsp;&nbsp; <a class='pagination' title='Next Page' href='javascript: getpage(".($currentPage+1).");'>Next</a>" ;
				  else
					  $pagingNext = "&nbsp;&nbsp;&nbsp;&nbsp; Next" ;

				  $paging = $pagingPrevious.$pagingStart.$paging.$pagingEnd.$pagingNext ;
			   }
			  else
			   {
				  $paging = "" ;
			   }
			 
			 if( $currentNum )
			   {
				  $currentShowRange = (($currentPage - 1) * $this->rpp)?(($currentPage - 1) * $this->rpp)+1 :1 ;
				  $totalRecordNumber = ( ( $currentPage - 1 ) * $this->rpp ) + $currentNum ;
				  
				  
				  /*if($_REQUEST[recordPP])
				  	  $rpp = $_REQUEST[recordPP] ;
				  else
				  	  $rpp = $this->rpp ;*/
				  
				  $recordPerPage = $this->createDropDown( $this->recordsPerPage( $this->displayRecordPerPage ) , $rpp ) ; 
			   	  $filterListing = " | <select name='recordPP' class=input onchange='setRecordsPerPage(this.value)'>".$recordPerPage."</select> " ;

				  if( !trim($showCounter) )
					   $showingRecords = " Displaying ".$currentShowRange." - ".$totalRecordNumber." OF ".$num." records ";
					   
				  if( trim($collapseFlag) && trim($showNotShow) == "" )
				  	   $collapseExpand = " | <span id='expandCollapse'><a href='javascript:;' onclick='collapseExpandPanel(\"collapse\" , \"$collapseId\")'>[ - ] Collapse</a></span>" ;
				  else
				  	   $collapseExpand = "" ;
			   }
			   else
				   $showingRecords = "" ;

			   ////// display number of records and pagination
			   //$finalPagination  = "<tr height=5><td></td></tr>" ;
			   $finalPagination .= "<tr><td width=60% align=left> ".$showingRecords.$filterListing.$collapseExpand."</td>" ;
			   $finalPagination .= "<td width=40% align=right style='text-align:right;'>".$paging."</td></tr>" ;
			   
			   return $finalPagination ;
		   }
		
		
		## SOME OTHER TYPE
		
		function displayListPaging1($num , $currentPage , $currentNum , $showCounter='')
		   {
		    
			 if( $num>$this->rpp && $this->rpp && is_numeric($this->rpp) )
			   {
				   $ct = $num / $this->rpp ;
				   $ct = ceil($ct) ;
				  				   
				   if( $currentPage > 1 )
						$pagingPrevious = "<a class='pagination' href='javascript: getpage(".($currentPage-1).");' title='Previous
						Page'>Previous</a> &nbsp;&nbsp;&nbsp;&nbsp;" ;
				   else
					    $pagingPrevious = "Previous &nbsp;&nbsp;&nbsp;&nbsp;" ;

				   if($currentPage < $ct && $ct > 5)
					    $pagingEnd = " ..." ;
				   else
					    $pagingEnd = "" ;

				   if($currentPage > 6 )
					   $pagingStart = "... " ;
				   else
					   $pagingStart = "" ;
				   
				   if( $currentPage < 5 && $ct > 5)
				   {
					  $cct = 5 ;
					  $start = 1 ;
				   }
				   else if($currentPage < 5 && $ct < 5)
				   {
					  $cct = $ct ;
					  $start = 1 ;
				   }
				   else
				   {
					  $start = $currentPage - 5 ;

					  if($start == 0 ) $start = 1 ;
					  $cct = $currentPage ;
				   }
					
				   for($i=$start;$i<=$cct;$i++)
					 {  
						if($currentPage == $i || !trim($currentPage) && $i == 1)
							$paging .= " <b>$i&nbsp;</b> | " ;
					    else
							$paging .= "<a class='pagination' title='".$i." Page' href='javascript: getpage($i);'>$i&nbsp;</a> | " ;
					 }
				  
				  if( trim($currentPage) == "" ) $currentPage++ ;

				  if( $currentPage < $ct )
					  $pagingNext = "&nbsp;&nbsp;&nbsp;&nbsp; <a class='pagination' title='Next Page' href='javascript: getpage(".($currentPage+1).");'>Next</a>" ;
				  else
					  $pagingNext = "&nbsp;&nbsp;&nbsp;&nbsp; Next" ;

				  $paging = $pagingPrevious.$pagingNext ;
			   }
			  else
			   {
				  $paging = "" ;
			   }
			 
			 if( $currentNum )
			   {
				  $currentShowRange = (($currentPage - 1) * $this->rpp)?(($currentPage - 1) * $this->rpp)+1 :1 ;
				  $totalRecordNumber = (($currentPage - 1 ) * $this->rpp) + $currentNum ;
				  
				  $recordPerPage = $this->createDropDown( $this->recordsPerPage( $this->displayRecordPerPage ) , $this->rpp ) ; 
			   	  $filterListing = " | <select name='recordPP' class=input onchange='setRecordsPerPage(this.value)'>".$recordPerPage."</select> " ;

				  if( !trim($showCounter) )
					   $showingRecords = " Displaying ".$currentShowRange." - ".$totalRecordNumber." OF ".$num ;
			   }
			   else
				   $showingRecords = "" ;

			   ////// display number of records and pagination
			   $finalPagination  = "<tr height=5><td></td></tr>" ;
			   $finalPagination .= "<tr><td width=60% align=right><b> ".$showingRecords.$filterListing."  </b></td>" ;
			   $finalPagination .= "<td width=40% align=right>".$paging."</td></tr>" ;

			 		   			   
			  return $finalPagination ;
		   }





		//// record per page
		function recordsPerPage( $pageRecord )
		{
			if( count($pageRecord) > 0 )
			{
				$recordCounter = 0 ;
				foreach( $pageRecord as $pKey=>$vKey )
				{
					$records[$recordCounter][0] = $pKey ;
					$records[$recordCounter][1] = $vKey ;

					$recordCounter++ ;
				}
			}

			return $records ;
		}
		
		/// display pagination
		function displayPaging($num , $currentPage , $currentNum , $showCounter='')
		   {
		    
			 if($num>$this->rpp)
			   {
				   $ct = $num / $this->rpp ;
				   $ct = ceil($ct) ;
				  				   
				   if( $currentPage > 1 )
						$pagingPrevious = "<a class='pagination' href='javascript: getpage(".($currentPage-1).");' title='Previous Page'>Previous</a> &nbsp;&nbsp;&nbsp;&nbsp;" ;
				   else
					    $pagingPrevious = "Previous &nbsp;&nbsp;&nbsp;&nbsp;" ;

				   if($currentPage < $ct && $ct > 5)
					    $pagingEnd = " ..." ;
				   else
					    $pagingEnd = "" ;

				   if($currentPage > 6 )
					   $pagingStart = "... " ;
				   else
					   $pagingStart = "" ;
				   
				   if( $currentPage < 5 && $ct > 5)
				   {
					  $cct = 5 ;
					  $start = 1 ;
				   }
				   else if($currentPage < 5 && $ct < 5)
				   {
					  $cct = $ct ;
					  $start = 1 ;
				   }
				   else
				   {
					  $start = $currentPage - 5 ;

					  if($start == 0 ) $start = 1 ;
					  $cct = $currentPage ;
				   }
					
				   for($i=$start;$i<=$cct;$i++)
					 {  
						if($currentPage == $i || !trim($currentPage) && $i == 1)
							$paging .= " <b>$i&nbsp;</b> | " ;
					    else
							$paging .= "<a class='pagination' title='".$i." Page' href='javascript: getpage($i);'>$i&nbsp;</a> | " ;
					 }
				  
				  if( trim($currentPage) == "" ) $currentPage++ ;

				  if( $currentPage < $ct )
					  $pagingNext = "&nbsp;&nbsp;&nbsp;&nbsp; <a class='pagination' title='Next Page' href='javascript: getpage(".($currentPage+1).");'>Next</a>" ;
				  else
					  $pagingNext = "&nbsp;&nbsp;&nbsp;&nbsp; Next" ;

				  $paging = $pagingPrevious.$pagingStart.$paging.$pagingEnd.$pagingNext ;
			   }
			  else
			   {
				  $paging = "" ;
			   }
			 
			 if( $currentNum )
			   {
				  $currentShowRange = (($currentPage - 1) * $this->rpp)?(($currentPage - 1) * $this->rpp)+1 :1 ;
				  $totalRecordNumber = ( ( $currentPage - 1 ) * $this->rpp ) + $currentNum ;
				  
				  if( !trim($showCounter) )
				  $showingRecords = "Showing Records : ".$currentShowRange." To ".$totalRecordNumber." OF ".$num ;
			   }
			   else
				   $showingRecords = "" ;

			   
			   ////// display number of records and pagination
			   $finalPagination  = "<tr height=5><td></td></tr>" ;
			   $finalPagination .= "<tr><td width=40% align=left><b> ".$showingRecords." </b></td>" ;
			   $finalPagination .= "<td width=60% align=right>".$paging ."</td></tr>" ;

			 		   			   
			  return $finalPagination ;
		   }

		//////////////////// pagination in diffrant style
		  function displayPagingStyle($num , $currentPage , $currentNum)
		   {
		    
			 if($num>$this->rpp)
			   {
				   $ct = $num / $this->rpp ;
				   $ct = ceil($ct) ;
				   			   
				   if( $currentPage > 1 )
						$pagingPrevious = "<a class='classLink' href='javascript: pagination(".($currentPage-1).");'>Previous</a> &nbsp;&nbsp;&nbsp;&nbsp;" ;
				   else
					    $pagingPrevious = "Previous &nbsp;&nbsp;&nbsp;&nbsp;" ;

				   if($currentPage < $ct && $ct > 5)
					    $pagingEnd = " ..." ;
				   else
					    $pagingEnd = "" ;

				   if($currentPage > 5 )
					   $pagingStart = "... " ;
				   else
					   $pagingStart = "" ;
				   
				   if( $currentPage < 5 && $ct > 5)
				   {
					  $cct = 5 ;
					  $start = 1 ;
				   }
				   else if($currentPage < 5 && $ct <= 5 )
				   {
					  $cct = $ct ;
					  $start = 1 ;
				   }
				   else
				   {
					  $start = $currentPage - 4 ;

					  if($start <= 0 ) $start = 1 ;
					  $cct = $currentPage ;
				   }
				   
				   for($i=$start;$i<=$cct;$i++)
					 {  
						if($currentPage == $i || !trim($currentPage) && $i == 1)
							$paging .= " <b>$i&nbsp;</b> | " ;
					    else
							$paging .= "<a class='classLink' href='javascript: pagination($i);'>$i&nbsp;</a> | " ;
					 }
				  
				  if( trim($currentPage) == "" ) $currentPage++ ;

				  if( $currentPage < $ct )
					  $pagingNext = "&nbsp;&nbsp;&nbsp;&nbsp; <a class='classLink' href='javascript: pagination(".($currentPage+1).");'>Next</a>" ;
				  else
					  $pagingNext = "&nbsp;&nbsp;&nbsp;&nbsp; Next" ;

				  $paging = $pagingPrevious.$pagingStart.$paging.$pagingEnd.$pagingNext ;
			   }
			  else
			   {
				  $paging = "" ;
			   }
			 
			 if( $currentNum )
			   {
				  $currentShowRange = (($currentPage - 1) * $this->rpp)?(($currentPage - 1) * $this->rpp)+1 :1 ;
				  $totalRecordNumber = ( ( $currentPage - 1 ) * $this->rpp ) + $currentNum ;
				  
				  $showingRecords = "Showing Records : ".$currentShowRange." To ".$totalRecordNumber." OF ".$num ;
			   }
			   else
				   $showingRecords = "" ;

			   
			   ////// display number of records and pagination
			   $finalPagination  = "<tr height=5><td></td></tr>" ;
			   $finalPagination .= "<tr><td width=40%><b> ".$showingRecords." </b></td>" ;
			   $finalPagination .= "<td width=60% height=30 align=right>".$paging ."</td></tr>" ;

			 		   			   
			  return $finalPagination ;
		   }
		   
	
///////// calculation is based on the latitude and longitude present in db
	  function zipcodeDistance($latitude1, $latitude2, $longitude1, $longitude2) 
		{   
			  if( trim($latitude1) && trim($latitude2) && trim($longitude1) && trim($longitude2) )
				{
				  // Convert lattitude/longitude (degrees) to radians for calculations
				  $latitude1 = deg2rad($latitude1);
				  $longitude1 = deg2rad($longitude1);
				  $latitude2 = deg2rad($latitude2);
				  $longitude2 = deg2rad($longitude2);
				  
				  // Find the deltas
				  $delta_lat = $latitude2 - $latitude1;
				  $delta_lon = $longitude2 - $longitude1;
				
				  // Find the Great Circle distance 
				  $temp = pow(sin($delta_lat/2.0),2) + cos($latitude1) * cos($latitude2) * pow(sin($delta_lon/2.0),2);
				  $distance = 3956 * 2 * atan2(sqrt($temp),sqrt(1-$temp));

				}
			
			   //// return distance in miles
			    return round( $distance );

		  }

////////////// convert miles to kilometers
		function convertMilesToKM( $miles )
		  {
				$kiloMeter = $miles * 1.609 ;

				return round($kiloMeter) ;
		  }

	///////////////// function to create mysql dump
		function exportSqlFile( $backupdir )
		{   
			   $dbobj = new DBCONFIG ;

			// Enter your MySQL access data  
				 $host= $dbobj->DB_HOST ;         
				 $user= $dbobj->DB_USER ;      
				 $pass= $dbobj->DB_PASSWORD ; 
				 $db =   $dbobj->DB_NAME ; 

		   
				 // Compute day, month, year, hour and min.
				 $today = getdate();
				 $day = $today[mday];
				 if ($day < 10) {
					 $day = "0$day";
				 }
				 $month = $today[mon];
				 if ($month < 10) {
					 $month = "0$month";
				 }
				 
				 $year = $today[year]."-";
				 $day = $day."-" ;
				 $month = $month."-" ;

				 $hour = $today[hours]."-";
				 $min = $today[minutes]."-";
				 $sec = "00";

				 // Execute mysqldump command.
				 // It will produce a file named $db-$year$month$day-$hour$min.gz 
				 // under $DOCUMENT_ROOT/$backupdir
				 system(sprintf( 
				   'mysqldump --opt -h %s -u %s -p%s %s  > %s/%s/%s-%s%s%s-%s%s.sql',                                                  
				   $host,
				   $user,
				   $pass,
				   $db,
				   getenv('DOCUMENT_ROOT')."/dating/".$backupdir ,
				   '',
				   $db,
				   $year,
				   $month,
				   $day,
				   $hour,
				   $min
				 )) ;  
				 
				$exportPath = getenv('DOCUMENT_ROOT')."/dating/".$backupdir ;

				return $exportPath ;
		}
		
		function mysqldumpTableList($mysql_database = '' )           /// getting all table list from specified db
		{
			$sql="show tables;";
			$result= mysql_query($sql);
			if( $result)
			{
				while( $row= mysql_fetch_row($result))
				{
					$sqlDumpContent .= $this->mysqldump_table_structure($row[0]) ;
					
					$sqlDumpContent .= $this->mysqldump_table_data($row[0]) ;
				}
			}
			else
			{
				$sqlDumpContent = "/* no tables in $mysql_database */\n";
			}
			
			mysql_free_result($result);

			return $sqlDumpContent ;
		}

		function mysqldump_table_structure($table)				////// getting info about the table structure
		{
			$tableStructure = "/* Table structure for table `$table` */\n";
			
			$tableStructure .= "DROP TABLE IF EXISTS `$table`;\n\n" ;
			
			$sql="show create table `$table`; ";
			$result=mysql_query($sql);
			
			if( $result)
			{
				if($row= mysql_fetch_assoc($result))
				{
					$tableStructure .= $row['Create Table'].";\n\n" ;
				}
			}
			
			mysql_free_result($result);

			return $tableStructure ;
		}

		function mysqldump_table_data($table)				//////// getting table data
		{
			
			$sql="select * from `$table`;";
			
			$result=mysql_query($sql);
			if( $result)
			{
				$num_rows= mysql_num_rows($result);
				$num_fields= mysql_num_fields($result);
				
				if( $num_rows > 0)
				{
					$tableRecords = "/* dumping data for table `$table` */\n";
					
					$field_type=array();
					$i=0;
					while( $i < $num_fields)
					{
						$meta= mysql_fetch_field($result, $i);
						array_push($field_type, $meta->type);
						$i++;
					}
					
					$tableRecords .= "insert into `$table` values\n";
					$index=0;
					while( $row= mysql_fetch_row($result))
					{
						$tableRecords .= "(";
						for( $i=0; $i < $num_fields; $i++)
						{
							if( is_null( $row[$i]))
								$tableRecords .= "null";
							else
							{
								switch( $field_type[$i])
								{
									case 'int':
										$tableRecords .= $row[$i];
										break;
									case 'string':
									case 'blob' :
									default:
										$tableRecords .= "'".mysql_real_escape_string($row[$i])."'";
										
								}
							}
							if( $i < $num_fields-1)
								$tableRecords .= ",";
						}
						$tableRecords .= ")";
						
						if( $index < $num_rows-1)
							$tableRecords .= ",";
						else
							$tableRecords .= ";";

						$tableRecords .= "\n";
						
						$index++;
					}
				}
			}
			mysql_free_result($result);
			$tableRecords .= "\n";

			return $tableRecords ;
		}
	///////////////// end mysql dump


	//////////////////// importing data in database
		function mysql_import_file($filename, &$errmsg)
		{
		   /* Read the file */
		   $lines = file($filename);

		   if(!$lines)
		   {
			  $errmsg = "cannot open file $filename";
			  return false;
		   }

		   $scriptfile = false;

		   /* Get rid of the comments and form one jumbo line */
		   foreach($lines as $line)
		   {
			  $line = trim($line);

			  if(!ereg('^--', $line))
			  {
				 $scriptfile.= " ".$line;
			  }
		   }

		   if(!$scriptfile)
		   {
			  $errmsg = "no text found in $filename";
			  return false;
		   }

		   /* Split the jumbo line into smaller lines */

		   $queries = explode(';', $scriptfile);

		   /* Run each line as a query */

		   foreach($queries as $query)
		   {
			  $query = trim($query);
			  if($query == "") { continue; }
			  if(!mysql_query($query.';'))
			  {
				 $errmsg = "query ".$query." failed";
				 return false;
			  }
		   }

		   return true;
		}
	///////////////////// end importing



		function readDirectoryIntreeStructure( $dirPath , $profileFolder , $eventFolder , $wcwd = false , $dirFlag='' ) 
		{
			//echo "comes here " ;
			if( trim($eventFolder) )
			{
				if( !$dirFlag )
				{
					chdir( $profileFolder ) ;
					$c = $eventFolder ;
				}
				else
				{
					$c = $dirPath ;
				}
				
				//echo $c ;
	
				if($wcwd === false)
				  $wcwd = substr($wcwd = $_SERVER['REQUEST_URI'], 0, strrpos($wcwd, '/') + 1);
	
				$d = opendir($c) ;
	
				while($f = readdir($d)) 
				{
					  if(strpos($f, '.') === 0) continue; 
		
					   $ff = $c . '/' . $f ;
					  
					  if( !is_dir($f) && !is_dir($ff) )
					  {
						@unlink($ff) ;
						//echo $ff."<br>" ;
					  }
					  
					  if(is_dir($ff))
					  {
						 $this->readDirectoryIntreeStructure($ff, '' , $ff , $wcwd , 1 );
					  }
				}
			}
			
			return true ;
		}


	################# shopping cart function #########################
	
	function add_to_cart( $prod_id , $qty , $order )
	 {
	   if(@array_key_exists($prod_id , $_SESSION['cart']))
	     {
		  	$_SESSION['cart'][$order][$prod_id] = $_SESSION['cart'][$prod_id] + $qty ;
		 }
	   else
	     {
			$_SESSION['cart'][$order][$prod_id] = $qty ;
		 }
	 }
	 
	function update_cart( $product_id , $pro_qty , $order )
	 {
	    $_SESSION['cart'][$order][$product_id] = $pro_qty ;
	 }
	 
	/*function delete_product( $product_id , $order )
	 {
		$temp_array = array();
		
		if( count($_SESSION['cart'][$order]) > 0 )
		{
		  foreach( $_SESSION['cart'] as $key => $value )
		  {
			 if( count($value) > 0 )
			 {
				foreach( $value as $cartK=>$cartV )
				 {
					if( $cartK != $product_id && $key == $order )
					{
					 	$temp_array[$key][$cartK] = $cartV ; 
					}
				 }
			 }
		  }
		}
	 }*/


	 function delete_product( $product_id , $order )
	 {
		$temp_array = array();
		
		if( count($_SESSION['cart'][$order]) > 0 )
		{
		  foreach( $_SESSION['cart'] as $key => $value )
		  {
			 if( count($value) > 0 )
			 {
				foreach( $value as $cartK=>$cartV )
				 {
					  if( $cartK != $product_id && $key == $order )
					  {
						 $temp_array[$key][$cartK] = $cartV ; 
					  }
				 }
			 }
		  }
		}
		  		 		  
		unset($_SESSION['cart']);
		$_SESSION['cart'] = $temp_array ;
		  	
	 }




	################# end shopping cart ##############################


	function convertBrowsingUserCurrency( $price )
	{
		$browseCountry = $this->browseingUserCountry() ;

		$this->tbl_country_currency() ;
		$condition = "country = '".$browseCountry."'" ;
		$result = $this->selectRecord( $condition ) ;
		$recordCount = $this->recordNumber( $result ) ;

		if( $recordCount > 0 )
		{
			$countryCurrencyList = $this->fetchRecord( $result ) ;

			$currencyCode = $countryCurrencyList[0][currency_code] ;
			$defaultCurrency = _DEFAULT_CURRENCY ;

			$convertedAmount = $this->currencyConversion($price , $defaultCurrency , $currencyCode) ;
		}
		else
		{
			$convertedAmount = $price ;
			$defaultCurrency = _DEFAULT_CURRENCY ;
		}
		
		return $convertedAmount ." ".$defaultCurrency ;
	}

	function browseingUserCountry()
	{
		if( file_exists( "getaddress/geoip.inc" ) )   $localPath = "./" ;
		else										  $localPath = "../" ;

		///////////////////// get browing user country
			$ipadd = $_SERVER['REMOTE_ADDR'];
			
			require_once( $localPath."getaddress/geoip.inc");

			$handle = geoip_open( $localPath."getaddress/GeoIP.dat", GEOIP_STANDARD); 

			$country =   geoip_country_name_by_addr($handle, $ipadd);
			
			geoip_close($handle);
		///////////////////////////// end browsing user	

		return $country ;
	}

	function currencyConversion( $amount , $currencyfrom , $currencyTo )
	{
			$post[form_amount] = $amount ;
			$post[form_from_currency] = $currencyfrom ;
			$post[form_to_currency] = $currencyTo ;
			$post[convert_it] = "true" ;
			

			$o="";
			if(!empty($post))
			  foreach ($post as $k=>$v)
				$o.= $k."=".urlencode($v)."&";
			
			$post_data = substr($o,0,-1);

			////////////////// fetching the data using curl

			$url = "http://www2.gcitrading.com/quotes/converter_nrw2.asp" ;
						
			$ch = curl_init() ;
			curl_setopt($ch ,CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch ,CURLOPT_FOLLOWLOCATION, false);
			curl_setopt($ch ,CURLOPT_VERBOSE, false); 
			curl_setopt($ch ,CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch ,CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch ,CURLOPT_HEADER, false);
			curl_setopt($ch ,CURLOPT_NOBODY, false);
			curl_setopt($ch ,CURLOPT_COOKIEJAR, false);
			curl_setopt($ch ,CURLOPT_COOKIEFILE, false);
			curl_setopt($ch ,CURLOPT_POST, true);
			curl_setopt($ch ,CURLOPT_CUSTOMREQUEST,'POST');
			curl_setopt($ch ,CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch ,CURLOPT_URL, $url);
			$result = curl_exec($ch) ;
			curl_close($ch) ;

			//////////////////// end fetching data

			$result = preg_replace ( "'([\r\n])[\s]+'" , " " , $result ) ;

		$currencyCode = $result ;

		$currencyExtra = explode( "<br>" , $currencyCode ) ;

		$c_list = explode( "<h1>" , $currencyExtra[5]) ;

		list( $from_c , $to_c ) = explode( "=" , $c_list[0] ) ;
		
		$currency_val = preg_replace( "'&(nbsp|#160);'i" , " " , trim($to_c) ) ;
		list( $param1 , $param2 ) = explode( " " , $currency_val ) ;
		
		$totatlCurr = $param1 * $amount ;

		return $totatlCurr ;
	}

}

	

?>