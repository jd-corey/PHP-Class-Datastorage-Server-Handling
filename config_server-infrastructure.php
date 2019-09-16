<?
/* ------------------------------------------------------------------------------------------
Configuration file

Purpose: Provide data (credentials, ...) about servers - such as database or file servers
// --------------------------------------------------------------------------------------- */

// ------------------------------------------------------------------------------------------
// Connection data for fileservers
//
// Variable "server"		=		Address of FTP server
// Variable "username"	=		Username required for logging into the FTP server
// Variable "password"	=		Password required for logging into the FTP server
// Variable "filedir"		=		Set, if you want to go into a sub directory
// ------------------------------------------------------------------------------------------
$fileserver																	=				array();

// Information about FTP server "retailer"
$fileserver['retailer']['server']						=				"ftp.<AddServerInfo>.<tld>";
$fileserver['retailer']['username']					=				"<ChangeToYourUsername>";
$fileserver['retailer']['password']					=				"<ChangeToYourPassword>";
$fileserver['retailer']['filedir']					=				null;

// Information about FTP server "user"
$fileserver['user']['server']								=				"ftp.<AddServerInfo>.<tld>";
$fileserver['user']['username']							=				"<ChangeToYourUsername>";
$fileserver['user']['password']							=				"<ChangeToYourPassword>";
$fileserver['user']['filedir']							=				"user";											// If needed

// Information about FTP server "product"
$fileserver['product']['server']						=				"ftp.<AddServerInfo>.<tld>";
$fileserver['product']['username']					=				"<ChangeToYourUsername>";
$fileserver['product']['password']					=				"<ChangeToYourPassword>";
$fileserver['product']['filedir']						=				null;
// ------------------------------------------------------------------------------------------

?>