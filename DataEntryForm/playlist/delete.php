<?php
	//session_start() ;
	//echo 'We r in delete.php'; exit;
	//$link = mysql_connect("dbase.thinkwellgroup.com" , "twrateform" , "wastedtime1") ;
	//$link = mysql_connect("localhost" , "root" , "") ;
	//$db   = mysql_select_db("dev_rateform") ;
	require_once( "commonclass.php" ) ;
	///////////// creating object for the commonclass
	$classObj = new commonClass( '' , 'Process' ) ;
	
	$sql = " DELETE FROM temp_playlist WHERE id = '".$_REQUEST[id]."' " ;
	$res = mysql_query($sql) or die(mysql_error()) ;
	
	$sqlQuery = " SELECT * FROM temp_playlist WHERE sessid = '".session_id()."' ORDER BY updownseqid ASC " ;
	$resQuery = mysql_query($sqlQuery) or die(mysql_error()) ;
	$j = 0;
	$a = array();
	while($dta = mysql_fetch_array($resQuery))
	{
		if($j == 0)
		{
			$id_top = $dta[id] ;
		}
		else
		{
		 	$id_bottom = $dta[id] ;
		}
		$j++;
	}
	
	$i = 0;
	$a = array();
	$sqlQuery = " SELECT * FROM temp_playlist WHERE sessid = '".session_id()."' ORDER BY updownseqid ASC " ;
	$resQuery = mysql_query($sqlQuery) or die(mysql_error()) ;
	while($fetch = mysql_fetch_array($resQuery))
	{
		
		if($id_top == $fetch[id]) {
			$cond = "dont" ;
			$cond1 = "allow" ;
		}
		else if($id_bottom == $fetch[id]) {
			$cond = "allow" ;
			$cond1 = "dont" ;
		}
		else if($id_bottom != $fetch[id]) {
			$cond = "allow" ;
			$cond1 = "allow" ;
		}
		 
		//$strRecord .= "<div style='height:50;width:40%; background-color:#E3E4E4;padding-left:40px;padding-right:40px;padding-top:20px;padding-bottom:20px;'>".$fetch[data]."  <span style='position:absolute;margin-top:-100px;margin-left:170px;'><img src='images/delete.jpeg' onclick=delet(".$fetch[id].") ;></span></div> <div style='position:absolute;margin-top:-120px;margin-left:0px;'><img src='up_roll.png' onclick=updateBySequence(".$fetch[id].",'up_roll',".$fetch[updownseqid].",'".$cond."');></div><div style='position:absolute;margin-top:-70px;margin-left:0px;'><img src='down_roll.png' onclick=updateBySequence(".$fetch[id].",'down_roll',".$fetch[updownseqid].",'".$cond1."');></div><div style='height:5px;'></div>" ;
		
		$strRecord .= "<table width=100% >" ;
		$strRecord .= "<tr>" ;
		$strRecord .= "<td><table width=100% style='background-color:#E3E4E4; padding:4px;' cellpadding=4 cellspacing=8><tr>" ;
		$strRecord .= "<td valign='middle'><img src='up_roll.png' onclick=updateBySequence(".$fetch[id].",'up_roll',".$fetch[updownseqid].",'".$cond."');><br><img src='down_roll.png' onclick=updateBySequence(".$fetch[id].",'down_roll',".$fetch[updownseqid].",'".$cond1."');></td>" ;
		
		$strRecord .= "<td valign=top>".$fetch[data]."</td>" ;
		
		$strRecord .= "<td valign=top><img src='images/delete.jpeg' onclick=delet(".$fetch[id].") ;></td>" ;
		
		$strRecord .= "</tr>" ;
		$strRecord .= "</table></td></tr><tr><td height=25></td></tr></table>" ;
		
		$i++ ;
	}
	
	echo $strRecord ;

?>