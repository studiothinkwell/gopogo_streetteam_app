<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Live DB Test</title>
</head>
	<?php
    $link = mysql_connect("localhost" , "techdhar" , "vZB+d5y?T-?D") ;
	//$link = mysql_connect("localhost" , "root" , "") ;
	$db = mysql_select_db("techdhar_rateform") ;
	
	/*$query = " SELECT * FROM test ORDER BY id ASC LIMIT 1" ;
	$result = mysql_query($query) or die(mysql_error()) ;
	$fetchData = mysql_fetch_array($result) ;
	
	$val = str_replace($fetchData[data] , "" , $value ) ;*/
	
	/*$sqlQuery = " SELECT * FROM test " ;
	$resQuery = mysql_query($sqlQuery) or die(mysql_error()) ;
	if(mysql_num_rows($resQuery) > 0)
	{
		echo mysql_num_rows($resQuery);
		//$fetch = mysql_fetch_array($resQuery) ;
		
	}
	else
	{
		echo 'Some error';
    }*/
    ?>
<body>
</body>
</html>