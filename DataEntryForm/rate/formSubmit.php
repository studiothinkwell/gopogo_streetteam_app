<?php

	require_once( "commonclass.php" ) ;

	///////////// creating object for the commonclass
	$classObj = new commonClass( '' , 'Home Page' ) ;
	
	$postData = $classObj->getValues() ;
	
	$classObj->tbl_formmast() ;
	/* $condition = "BusinessName = '".$postData[txtbname]."'" ;
	$result = $classObj->selectRecord( $condition ) ;
	$recordCount = $classObj->recordNumber($result) ;
	// echo '<br> recordCount =>>>' .$recordCount . '<br>';
	if( $recordCount == 0 )
	{	*/				  
	$sqlQuery = " SELECT * FROM test WHERE sessid = '".session_id()."'  ORDER BY id DESC LIMIT 1" ;
	$resQuery = mysql_query($sqlQuery) or die(mysql_error()) ;
	$fetch = mysql_fetch_array($resQuery) ;
	
	$valuee = $fetch[data] ;
	
	$valueo = explode('class="gs-title">' , $valuee ) ;
	$valu = explode('</a>' , $valueo[1]) ;
	$strbuisness_title = $valu[0] ;
	
	/*$wastrbuisness_title = explode(">",$valu[1]) ;
	$wastrbuisnesstitle = explode("</",$wastrbuisness_title[0]) ;*/
	
	$value1 = explode('gs-street gs-addressLine">' , $valuee ) ;
	$valu1 = explode('<' , $value1[1]) ;
	$strbuisness_adr = $valu1[0].", " ;
	
	$value2 = explode('gs-city gs-addressLine">' , $valuee ) ;
	$valu2 = explode('<' , $value2[1]) ;
	$strbuisness_adr .= $valu2[0].", " ;
	
	$value3 = explode('gs-country">' , $valuee ) ;
	$valu3 = explode('<' , $value3[1]) ;
	$strbuisness_adr .= $valu3[0] ;
	
	$value4 = explode('gs-phone">' , $valuee ) ;
	$valu4 = explode('<' , $value4[1]) ;
	$strbuisness_ph = $valu4[0] ;
	
	
	$records[FullName] = $postData[txtnbh] ;
	
	/*echo "Value :: ".$valuee ;
	echo "<pre>" ;
	print_r($records) ;
	die() ;*/
	
	if($valuee != "")
	{
		$records[BusinessName] = $strbuisness_title ;
		$records[AddressLine1] = $strbuisness_adr ;
		$records[Phone] = $strbuisness_ph ;
	}
	else
	{
		$records[BusinessName] = $postData[txtbname] ;
		$records[AddressLine1] = $postData[txtadd1] ;
	}
	
	$records[Neighbourhood] = $postData[txtnbh] ;
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
	
	$records[TotalTimeSpend] = $postData[txthmt] ;
	$records[PricePerPerson] = $postData[txtppp] ;
	
	$records[data] = addslashes($fetch[data]) ;
	
	//Adding field Like/Dislike
	$likedislike = array() ;
	foreach( $postData[txtld] as $key=>$value)
	{
		$likedislike[] = $value ;
	}
	$records[PlaceLike] = implode(",",$likedislike) ;
	 
				
	//// insert record in db
	$classObj->insertRecord( $records ) ;
	
	$insertId = $classObj->insertRecordId() ;
	//echo 'Working till here...';
	//}
	
	session_regenerate_id() ;
	
	echo "Thank you. Your review has been submitted. <br> <a href='index.php?'>Review Another Business</a>" ;
?>
