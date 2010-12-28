<?php
   //mysql_connect("localhost","root","");
   //mysql_select_db("dev_rateform");
   
   require_once( "commonclass.php" ) ;
   
	///////////// creating object for the commonclass
   $classObj = new commonClass( '' , 'cleanDb' ) ;
   
   $tblArray = array( 'playlist', 'formmast', 'playlistmast', 'temp_playlist', 'test' );
  
   for( $i = 0; $i < count($tblArray); $i++ )
   {
	    //echo '<br> tables=>'.$tblArray[$i] . '<br>';
		blankoutdb( $tblArray[$i]);
		echo '<br> ( Successfull ) <br><br>';
   }
   
  
  function blankoutdb( $tblName ) {
	   $rowID = "001";
	   echo $query = "DELETE FROM $tblName";
	  // die();
	   $result = mysql_query($query) or die(mysql_error());
	
	   
   }
?>