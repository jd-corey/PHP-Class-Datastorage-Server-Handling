<?
/* ------------------------------------------------------------------------------------------
PHP Class "datastorage"

Purpose: Provide very simple functionality for storing data on and reading data from servers
That is, the class provides a simple interface for handling different FTP-based storages.
To do so, it wraps PHP's FTP functionalities and combines it with a storage name concept.
// --------------------------------------------------------------------------------------- */

// ------------------------------------------------------------------------------------------
// Dependencies: The class requires a configuration file
// ------------------------------------------------------------------------------------------
require_once 'config_server-infrastructure.php';
// ------------------------------------------------------------------------------------------

// ------------------------------------------------------------------------------------------
// Definition of the class: Variables, constructor and methods
// ------------------------------------------------------------------------------------------
final class datastorage
{
	// ----------------------------------------------------------------------------------------
	// Variables needed for constructing datastorage objects
	// ----------------------------------------------------------------------------------------
	private $storage_type;	// Type of storage server; currently implemented: "fileserver"
	private $storage_name;	// Name of storage server; currently implemented: "retailer", "user" & "product"
	private $data_type;			// Type of data to be stored; currently implemented: "rawdata", e.g. from CURL()
	// ----------------------------------------------------------------------------------------
	
	// ----------------------------------------------------------------------------------------
	// Constructor method
	// ----------------------------------------------------------------------------------------
	public function __construct($storage_type, $storage_name, $data_type)
	{
		$this->storage_type								=			$storage_type;
		$this->storage_name								=			$storage_name;
		$this->data_type									=			$data_type;
	}
	// ----------------------------------------------------------------------------------------
	
	// ----------------------------------------------------------------------------------------
	// Get storage type from respective datastorage object
	// ----------------------------------------------------------------------------------------
	public function getStorageType()
	{
		return $this->storage_type;
	}
	// ----------------------------------------------------------------------------------------
	
	// ----------------------------------------------------------------------------------------
	// Get storage name from respective datastorage object
	// ----------------------------------------------------------------------------------------
	public function getStorageName()
	{
		return $this->storage_name;
	}
	// ----------------------------------------------------------------------------------------
	
	// ----------------------------------------------------------------------------------------
	// Get data type from respective datastorage object
	// ----------------------------------------------------------------------------------------
	public function getDataType()
	{
		return $this->data_type;
	}
	// ----------------------------------------------------------------------------------------
	
	// ----------------------------------------------------------------------------------------
	// Connect to Server based on type of storage and server configuration
	// ----------------------------------------------------------------------------------------
	public function connectToServer()
	{
		// Perform connection handling for storage type "fileserver"
		if (strcmp($this->getStorageType(), "fileserver") == 0)
		{
			global $fileserver;		// Variable is defined in the config file imported above
			$ftp_info									=			array();
			$ftp_handle								=			"";
			
			// Assign server connect info (credentials, ...) with respect to storage name
			switch ($this->getStorageName())
			{
				case "retailer":
					$ftp_info							=			$fileserver['retailer'];
					break;
					
				case "user":
					$ftp_info							=			$fileserver['user'];
					break;
					
				case "product":
					$ftp_info							=			$fileserver['product'];
					break;
			}
			
			if (!empty($ftp_info['server']))
			{			
				// Connect to FTP server based on information imported from config file
				$ftp_handle							=			ftp_connect("$ftp_info[server]");
						
				// Log in to FTP server using credentials
				$login_result						=			ftp_login($ftp_handle, "$ftp_info[username]", "$ftp_info[password]");
						
				// Check, if connection has been established successfully
				if ((!$ftp_handle) || (!$login_result))
				{
					$ftp_handle 					= 		Null;
				}
				else
				{
					// Change directory to target directory, if required
					if (!empty($ftp_info['filedir']))
					{
						$chgdir							=			ftp_chdir($ftp_handle, "$ftp_info[filedir]");
						if (!$chgdir)
						{
						}
						else
						{
							$new_dir					=			ftp_pwd($ftp_handle);
						}
					}
				}
			}
			return $ftp_handle;
		}
	}
	// ----------------------------------------------------------------------------------------

	// ----------------------------------------------------------------------------------------
	// Upload a file to a server based on a connection established beforehand
	// $connection 	= Result returned from connectToServer()
	// $data				= Data to be uploaded to the server
	// $target			=	Target Filename on the server
	// ----------------------------------------------------------------------------------------
	public function uploadFileToServer($connection, $data, $target)
	{
		if ($connection)
		{
			// Write file to target directory
			$upload							=					ftp_put($connection, "$target", "$data", FTP_BINARY);
					
			// Check, if upload was successful
			if (!$upload)
			{
				// Close FTP connection and return result from upload
				ftp_close($connection);
				return Null;
			}
			else
			{
				// Close FTP connection and return result from upload
				ftp_close($connection);
				return $upload;
			}
		}
	}
	// ----------------------------------------------------------------------------------------
	
	// ----------------------------------------------------------------------------------------
	// Download a file from a server based on a connection established beforehand
	// ----------------------------------------------------------------------------------------
	public function downloadFileFromServer($connection, $filename_server, $filename_local)
	{
		if (!empty($connection) AND !empty($filename_server) AND !empty($filename_local))
		{
			ftp_get($connection, $filename_local, $filename_server, FTP_BINARY);
			ftp_close($connection);
		}
	}
	// ----------------------------------------------------------------------------------------
	
	// ----------------------------------------------------------------------------------------
	// Store data on server using connectToServer() and uploadFileToServer()
	// ----------------------------------------------------------------------------------------
	public function storeData($data, $target)
	{
		$result			=			"";
		global $fileserver;			// Variable is defined in the config file imported above
		
		fwrite(fopen($target, "w"), $data);
		$result			=			$this->uploadFileToServer($this->connectToServer(), $target, $target);
		unlink($target);
		
		return $result;
	}
	// ----------------------------------------------------------------------------------------
}
// ------------------------------------------------------------------------------------------
?>