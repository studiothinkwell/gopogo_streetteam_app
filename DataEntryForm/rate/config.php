<?php

						
   class DBCONFIG 
	{

		static $DB_HOST = ''; 
		static $DB_USER = '' ;
		static $DB_PASSWORD = '' ;
		static $DB_NAME = '' ; 
//$link = mysql_connect("localhost" , "root" , "") ;
		function DBCONFIG()
		{
			 $this->DB_HOST = 'dbase.thinkwellgroup.com';
			 $this->DB_USER = 'twrateform' ;
			 $this->DB_PASSWORD = 'wastedtime1' ;
			 $this->DB_NAME = 'dev_rateform' ;
			/* $this->DB_HOST = 'localhost'; 
			 $this->DB_USER = 'root' ;
			 $this->DB_PASSWORD = '' ;
			 $this->DB_NAME = 'dev_rateform' ;*/
		}

	}
	
	
	///////error messages
    class ErrorMessages
	{
		var $err_message = array() ;
		var $errNumber = "" ;

		 function __construct($errorNumber)
		 {
			 $this->err_message['10001'] = "Username or Email address already exists." ;
			 $this->err_message['10002'] = "Page content updated successfully." ;
			 $this->err_message['10003'] = "Page deleted successfully." ;
			 $this->err_message['10006'] = "Status Changed successfully." ;

			 $this->err_message['10004'] = "Account activated successfully." ;
			 $this->err_message['10005'] = "Invalid activation Id." ;

			 $this->err_message['10007'] = "Profile updated successfully." ;

			 $this->err_message['10010'] = "Thank you for registering with us. Please check your email account for futher details." ;
			 
			 $this->err_message['10011'] = "Account is not activated." ;
			 $this->err_message['10012'] = "Invalid Username / Password." ;

			 $this->err_message['10013'] = "Please check your mailbox for username and password." ;
			 $this->err_message['10014'] = "Please check the Username / E-mail Address." ;
			 $this->err_message['10009'] = "Password changed sucessfully." ;
			 $this->err_message['10008'] = "Wrong Current Password. Please check the password." ;

			 $this->err_message['10015'] = "Promoter updated successfully." ;
			 $this->err_message['10016'] = "Promoter added successfully." ;
			 $this->err_message['10017'] = "Promoter deleted successfully." ;
			 
			 $this->err_message['10030'] = "Member Affiliates Reset successfully." ;

			 $this->err_message['10018'] = "Super Promoter updated successfully." ;
			 $this->err_message['10019'] = "Super Promoter added successfully." ;
			 $this->err_message['10020'] = "Super Promoter deleted successfully." ;
			 
			 $this->err_message['10021'] = "Category updated successfully." ;
			 $this->err_message['10022'] = "Category deleted successfully." ;
			 $this->err_message['10023'] = "Sub-category updated successfully." ;
			 $this->err_message['10024'] = "Sub-category deleted successfully." ;

			 $this->err_message['10025'] = "Product updated successfully." ;
			 $this->err_message['10026'] = "Product deleted successfully." ;

			 $this->err_message['10027'] = "Banner updated successfully." ;
			 $this->err_message['10029'] = "Banner added successfully." ;
			 $this->err_message['10028'] = "Banner deleted successfully." ;

			 $this->err_message['10047'] = "Invalid file format. Please try again." ;
			 $this->err_message['10054'] = "Photo uploaded successfully." ;
			 $this->err_message['10031'] = "Photo removed successfully." ;
			 $this->err_message['10032'] = "Default photo set successfully." ;
			 
			 $this->err_message['10100'] = "Please select Album." ;
			 $this->err_message['10036'] = "Album removed successfully." ;

			 $this->err_message['10033'] = "Video uploaded successfully." ;
			 $this->err_message['10034'] = "Video removed successfully." ;

			 $this->err_message['10035'] = "Address book imported successfully." ;
			 $this->err_message['10037'] = "Contact Removed successfully from Address book." ;
			 //$this->err_message['10036'] = "Some error occured while importing address book." ;
			 $this->err_message['10038'] = "Address book contact updated successfully." ;
			 $this->err_message['10039'] = "Address book contact added successfully." ;
			 $this->err_message['10040'] = "This contact is already added in address book." ;
			 $this->err_message['10041'] = "All contacts removed successfully." ;

			 $this->err_message['10042'] = "Messages sent successfully." ;

			 $this->err_message['10043'] = "Commission updated successfully." ;
			 $this->err_message['10044'] = "Commission added successfully." ;
			 $this->err_message['10045'] = "Commission deleted successfully." ;

			 $this->err_message['10046'] = "Dispute status changed successfully." ;

			 $this->err_message['10047'] = "Event updated successfully." ;
			 $this->err_message['10048'] = "Event added successfully." ;
			 $this->err_message['10049'] = "Event deleted successfully." ;

			 $this->err_message['10050'] = "Rating updated successfully." ;
			 $this->err_message['10051'] = "Rating added successfully." ;
			 $this->err_message['10052'] = "Rating deleted successfully." ;

			 $this->err_message['10053'] = "Website settings saved successfully." ;
			 
			 $this->err_message['10055'] = "Task assigned successfully." ;
			 $this->err_message['10056'] = "Task deleted successfully." ;

			 $this->err_message['10057'] = "Message sent successfully." ;

			 $this->err_message['10058'] = "Please check the password." ;
			 
			 $this->err_message['10059'] = "Document deleted successfully ." ;

			 $this->err_message['10060'] = "You can not buy tickets/products from multiple events." ;
			 
			 $this->err_message['10061'] = "Feed added successfully." ;
			 
			 $this->err_message['10062'] = "Feed updated successfully." ;
			 
			 $this->err_message['10063'] = "Please enter video album." ;

			 $this->err_message['10064'] = "Invalid invitation details. Please check your email for details." ;

			 $this->err_message['10065'] = "Invalid invitation details. These details already used." ;

			 $this->err_message['10066'] = "Invalid super promoters details." ;

			 $this->err_message['10067'] = "Settings saved successfully." ;
			 
			 $this->err_message['10068'] = "Fan Club Campaign added successfully." ;
			 
			 $this->err_message['10069'] = "Fan Club Campaign updated successfully." ;
			 
			 $this->err_message['10070'] = "Event Campaign added successfully." ;
			 
			 $this->err_message['10071'] = "Event Campaign updated successfully." ;

			 $this->err_message['10072'] = "Superpromoter Campaign added successfully." ;
			 
			 $this->err_message['10073'] = "Superpromoter Campaign updated successfully." ;

			 $this->err_message['10074'] = "Superpromoter Campaign deleted successfully." ;
			 
			 $this->err_message['10075'] = "Broadcast message added successfully." ;
			 
			 $this->err_message['10076'] = "Broadcast message updated successfully." ;

			 $this->err_message['10077'] = "Security code incorrect !" ;

			 $this->err_message['10078'] = " Already registered using this link." ;
				
			 $this->err_message['10079'] = " Please sign in. " ;

			 $this->err_message['10080'] = "Username already exists." ;
		
			 $this->err_message['10081'] = "E-mail already exists." ;

			 $this->err_message['10082'] = "has been added to your fan club." ;
			 
			 
			 $this->errNumber = $errorNumber ;
		 }
//newsrss.bbc.co.uk/rss/newsonline_uk_edition/technology/rss.xml
		 function getError()
		 {
			 if( $this->errNumber )
			 {
			    $numbers = explode( "," , $this->errNumber ) ;

				foreach( $numbers as $key=>$value )
				 {
					if( trim($value) )
					 {
						$massage .= "<LI>". $this->err_message[trim($value)]."</LI>" ;
					 }
				 }
			 }
			
			 return $massage ;
		 }
	}

			 
?>