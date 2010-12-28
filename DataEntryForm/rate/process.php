<?php
	//session_start() ;
	require_once( "commonclass.php" ) ;
	///////////// creating object for the commonclass
	$classObj = new commonClass( '' , 'Process' ) ;

	// This is just a code fragment from a larger PHP server-side script
	/*require_once('json.php');
	
	$json = new Services_JSON();
	
	// accept POST data and decode it
	//$value = $json->decode($GLOBALS['HTTP_RAW_POST_DATA']);
	$value = json_decode($_GET['json']);
	
	$classObj->tbl_test() ;
	$records[data] = $value ;
	$insert = $classObj->insertRecord($records) ;*/
	
	$value = preg_replace('/%([0-9a-f]{2})/ie', "chr(hexdec('\\1'))", $_REQUEST[data]);
	
	$value = preg_replace("/'/", '',$value );
	
    //echo $value;
	//$link = mysql_connect("dbase.thinkwellgroup.com" , "twrateform" , "wastedtime1") ;
	//$db   = mysql_select_db("dev_rateform") ;
	
	/*$query = " SELECT * FROM test ORDER BY id ASC LIMIT 1" ;
	$result = mysql_query($query) or die(mysql_error()) ;
	$fetchData = mysql_fetch_array($result) ;
	
	$val = str_replace($fetchData[data] , "" , $value ) ;*/
	
	$sqlQuery = " SELECT * FROM test WHERE sessid = '".session_id()."'  ORDER BY id DESC LIMIT 1" ;
	 
	$resQuery = mysql_query($sqlQuery) or die(mysql_error()) ;
	if(mysql_num_rows($resQuery) > 0)
	{
		$fetch = mysql_fetch_array($resQuery) ;
		$sql = " UPDATE test SET data = '".$value."' WHERE sessid = '".session_id()."' " ;
		 
		$res = mysql_query($sql) or die(mysql_error()) ;
	}
	else
	{
		
		$sql = " INSERT INTO `test` (`id` ,`data` ,`date` , 
sessid) VALUES (NULL , '".$value."', '' , '".session_id()."' )" ;
		 
		$res = mysql_query($sql) or die(mysql_error()) ;
	}
	
	$sqlQuery = " SELECT * FROM test ORDER BY id DESC LIMIT 1" ;
	$resQuery = mysql_query($sqlQuery) or die(mysql_error()) ;
	$fetch = mysql_fetch_array($resQuery) ;
	
	echo $fetch[data] ;

?>
