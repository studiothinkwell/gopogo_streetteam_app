<?php

	require_once( "commonclass.php" ) ;

	///////////// creating object for the commonclass
	$classObj = new commonClass( '' , 'Home Page' ) ;
	
	$postData = $classObj->getValues() ;
	
	$classObj->tbl_playlistmast() ;
	$records[FullName] = addslashes($postData[txtname]) ;
	$records[Neighbourhood] = addslashes($postData[txtnbh]) ;
	$records[BusinessDscptn] = $postData[txtbd] ;
	
	foreach( $postData[txtmood] as $key=>$value)
	{
		$mood[] = $value ;
	}
	$records[MoodType] = implode(",",$mood) ;
	
	foreach( $postData[txtcrowd] as $keycr=>$valuecr)
	{
		$crowd[] = $valuecr ;
	}
	$records[CrowdType] = implode(",",$crowd) ;
	
	$records[DressCodeID] = $postData[txtdc] ;
	
	foreach( $postData[txtrange] as $keyrng=>$valuerng)
	{
		$range[] = $valuerng ;
	}
	$records[AgeRange] = implode(",",$range) ;
	
	foreach( $postData[txtgf] as $keygf=>$valuegf)
	{
		$goodfor[] = $valuegf ;
	}
	$records[RelationType] = implode(",",$goodfor) ;
	
	foreach( $postData[txtbw] as $keybw=>$valuebw)
	{
		$bestwhen[] = $valuebw ;
	}
	$records[BestWhen] = implode(",",$bestwhen) ;
	
	
	$records[Style] = $postData[txtstyle] ;
	$records[Service] = $postData[txtservice] ;
	$records[Quality] = $postData[txtqty] ;
	//Best time to Go?// txtbtg
	//$records[BestTimeGoID] = $postData[txtbtg] ;
	foreach( $postData[txtbtg] as $key=>$value)
	{
		$txtbtgrange[] = $value ;
	}
	$var = implode(",",$txtbtgrange) ;//',Morning,Day,Night';
	// pattern, replacement, string
	$out = ereg_replace(',', ' ', $var); // outputs '123def 123def 123def'
	
	$out = trim($out); 
	//echo $out;
	$out = ereg_replace(' ', ', ', $out);
	//echo $out;
	$records[BestTimeGoID] = $out; //implode(",",$txtbtgrange) ;
	
	
	$records[data] = addslashes($fetch[data]) ;

	$records[TotalTimeSpend] = $postData[txthmt] ;
	$records[PricePerPerson] = $postData[txtppp] ;
	$records[sessionid] = session_id() ;
	
	//Adding field Transportation Mode
	foreach( $postData[txttbs] as $key=>$value)
	{
		$transmood[] = $value ;
	}
	$records[TransportationMode] = implode(",",$transmood) ;
	
	//// insert record in db
	$classObj->insertRecord( $records ) ;
	
	$insertId = mysql_insert_id() ;
	
	//$sqlQuery = " SELECT * FROM temp_playlist WHERE sessid = '".session_id()."' ORDER BY id DESC ";
	$sqlQuery = " SELECT * FROM temp_playlist WHERE sessid = '".session_id()."' ORDER BY id ASC ";
	$resQuery = mysql_query($sqlQuery) or die(mysql_error()) ;
	$i = 0;
	while( $fetch = mysql_fetch_array($resQuery) )
	{
	
		$valuee = $fetch[data] ;
		
		$valueo = explode('class="gs-title"' , $valuee ) ;
		$valu = explode('</a>' , $valueo[1]) ;
		$strbuisness_title = $valu[0] ;
		
		/*$wastrbuisness_title = explode(">",$valu[1]) ;
		$wastrbuisnesstitle = explode("</",$wastrbuisness_title[0]) ;*/
		
		$value1 = explode('gs-street gs-addressLine">' , $valuee ) ;
		$valu1 = explode('<' , $value1[1]) ;
		$strbuisness_adr = $valu1[0].", " ; //$strbuisness_adr = $valu1[0] ;
		
		$value2 = explode('gs-city gs-addressLine">' , $valuee ) ;
		$valu2 = explode('<' , $value2[1]) ;
		$strbuisness_adr .= $valu2[0].", " ; //$strbuisness_adr .= $valu2[0] ;
		
		$value3 = explode('gs-country">' , $valuee ) ;
		$valu3 = explode('<' , $value3[1]) ;
		$strbuisness_adr .= $valu3[0] ;
		
		$value4 = explode('gs-phone">' , $valuee ) ;
		$valu4 = explode('<' , $value4[1]) ;
		$strbuisness_ph = $valu4[0] ;
		
		/*echo $sql = " INSERT INTO playlist (
				`pl_id` ,
				`BusinessName` ,
				`AddressLine1` ,
				`Phone` ,
				`playlist` ,
				`sessionid`
				)
				VALUES ('".$insertId."', '".$strbuisness_title."', '".$strbuisness_adr."', '".$strbuisness_ph."', '".addslashes($valuee)."', '".session_id()."' ) " ;  */
				
		 $sql = " INSERT INTO playlist (
				`pl_id` ,
				`BusinessName` ,
				`AddressLine1` ,
				`Phone` ,
				`playlist` ,
				`sessionid`,
				`updownseqid`
				)
				VALUES ('".$insertId."', '".$strbuisness_title."', '".$strbuisness_adr."', 

'".$strbuisness_ph."', '".addslashes($valuee)."', '".session_id(). "',". $i .")" ;		
				
		$res = mysql_query($sql) or die(mysql_error()) ; 
		
	
	$i++;	
	}

		session_regenerate_id() ;

		echo "Thank you. Your playlist has been submitted. <br> <a href='index.php?'>Create Another Playlist</a>" ;
		//exit();
?>
