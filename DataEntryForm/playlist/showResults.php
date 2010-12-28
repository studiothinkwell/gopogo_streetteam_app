<?php
	
	session_start() ;
	
	$link = mysql_connect("localhost" , "root" , "") ;
	$db   = mysql_select_db("survey") ;	
    
    $sqlQuery = " SELECT * FROM temp_playlist WHERE sessid = '".session_id()."' ORDER BY id DESC " ;
	$resQuery = mysql_query($sqlQuery) or die(mysql_error()) ;
	while($fetch = mysql_fetch_array($resQuery))
	{
		$strRecord .= "<div style='height:50;width:40%; background-color:#E3E4E4;padding-left:40px;padding-right:40px;padding-top:20px;padding-bottom:20px;'>".$fetch[data]."  <span style='position:absolute;margin-top:-100px;margin-left:320px;'><img src='images/delete.jpeg' onclick=delet(".$fetch[id].") ;></span></div><div style='height:5px;'></div>" ;	
	}
	
	echo $strRecord ;

?>