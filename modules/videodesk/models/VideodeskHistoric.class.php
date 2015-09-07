<?php
/**
 * Module videodesk - Historic handler
 *
 * @category   	Module / front_office_features
 * @author     	BM Services
 * @copyright  	2013 BM Services
 * @version   	2.0
 */

// Loading Models
require_once(_PS_MODULE_DIR_ . 'videodesk/models/VideodeskShopConfiguration.class.php');
require_once(_PS_MODULE_DIR_ . 'videodesk/models/VideodeskHistoricLog.class.php');

// Loading Libs
require_once(_PS_MODULE_DIR_ . 'videodesk/lib/Ftp/FtpConnector.php');

class VideodeskHistoric
{
	private $context;
	private $module;
	private $ftp_host;
	private $ftp_login;
	private $ftp_password;
	private $localdir;
	private $separator;
	private $errors;
	
	private $ftpConnection;
	private $shop;
	
	public function __construct($module)
	{
		$this->context = Context::getContext();
		$this->module = $module;
		$this->localdir = _PS_UPLOAD_DIR_;
		$this->errors = array();
		$this->separator = ';';
	}
	
	/**
	 * Main function
	 * 
	 * @return boolean
	 */
	public function processHistoric()
	{
		try {
			$active_shops = VideodeskShopConfiguration::getAllActiveHistoricShops();
			foreach ($active_shops as $shop) {
				$this->shop = $shop;
				
				// Init FTP Connection for the current shop
				$this->initShopConnection();
				
				// Retrieve the Historic files list
				$files = $this->getHistoricFilesList();
				if (count($files) > 0) {
					
					// Retrieve Historic local files
					$local_files = $this->getHistoricLocalFilesList();
					
					$files_to_integrate = array();
					foreach ($files as $filename => $file) {
						if (array_key_exists($filename, $local_files)) {
							if (intval($file['size']) != intval($local_files[$filename]['size'])) {
								$files_to_integrate[] = $file;
							}
						}
						else {
							$files_to_integrate[] = $file;
						}
					}
					
					// Integrate the new files
					if (count($files_to_integrate) > 0) {
						foreach ($files_to_integrate as $file) {
							// Download the file on temp storage
							$this->downloadHistoricFile($file['name']);
							
							// Import content
							if (!$this->importCSV($file['name'])) {
								return false;
							}
							
							// Save the filename and size for next import
							$log_historic = new VideodeskHistoricLog();
							$log_historic->id_shop = $this->shop->id;
							$log_historic->filename = $file['name'];
							$log_historic->size = $file['size'];
							
							if ($log_historic->save()) {
								// Delete the file on temp storage
								$this->cleanHistoricLocalFile($file['name']);
							}
						}
					}
				}
				// Close FTP Connection for the current shop
				$this->closeShopConnection();
			}
			return true;
		}
		catch (Exception $ex) {
			return true;
		}
		
	}
	
	/**
	 * Init the connection to the FTP of the shop
	 */
	private function initShopConnection()
	{
		$this->ftpConnection = new FtpConnector($this->shop->ftp_host, $this->shop->ftp_login, $this->shop->ftp_password);
	}
	
	/**
	 * Close the connection to the FTP of the shop
	 */
	private function closeShopConnection()
	{
		$this->ftpConnection = null;
	}
	
	/**
	 * Retrieve the CSV files
	 * 
	 * @return list of files
	 */
	private function getHistoricFilesList()
	{
		$historic_files = array();
		$files = $this->ftpConnection->listFiles($this->shop->ftp_dir);
		foreach ($files as $file) {
			if (defined('PATHINFO_EXTENSION')) {
				if (pathinfo($file, PATHINFO_EXTENSION) == "csv") {
					$historic_files[$file] = array(
						'name' => $file,
						'size' => $this->ftpConnection->getFileSize($file)
					);
				}
			}
		}
		return $historic_files;
	}
	
	/**
	 * Retrieve the CSV already imported
	 * 
	 * @return list of files
	 */
	private function getHistoricLocalFilesList()
	{
		$historic_files = array();
		
		$files = VideodeskHistoricLog::getFilesForShop($this->shop->id);
		
		foreach ($files as $file) {
			$historic_files[$file['filename']] = array(
				'name' => $file['filename'],
				'size' => $file['size']
			);
		}
		return $historic_files;
	}
	
	/**
	 * Download Historic file to import
	 * 
	 * @param string $filename
	 */
	private function downloadHistoricFile($filename)
	{
		$this->ftpConnection->getOne($filename, $this->localdir . "/" . $filename);
	}
	
	/**
	 * Delete Historic file once integrated
	 * 
	 * @param string $filename
	 */
	private function cleanHistoricLocalFile($filename)
	{
		$path = $this->localdir . "/" . $filename;
		if (file_exists($path))
			unlink($path);
	}
	
	/**
	 * Explode a CSV file in rows of indexed array
	 * 
	 * @param string $filename
	 * @param caracter $delimiter
	 * @return boolean|array
	 */
	private function csv_to_array($filename = '', $delimiter = ',')
	{
		if (!file_exists($filename) || !is_readable($filename))
			return false;
		
		$data = array();
		ini_set('auto_detect_line_endings', true);
		if (($handle = fopen($filename, 'r')) !== false) {
			while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
				$data[] = $row;
			}
			fclose($handle);
		}
		ini_set('auto_detect_line_endings', false);
		return $data;
	}
	
	/**
	 * Import the downloaded CSV file in table
	 *  
	 * @param string $filename
	 * @return boolean
	 */
	private function importCSV($filename)
	{
		$path = $this->localdir . "/" . $filename;
		if (file_exists($path)) {
			$files = $this->csv_to_array($path, $this->separator);
			VideodeskCall::importCsv($files, $this->shop->id);
			return true;
		}
		else {
			return false;
		}
	}
}
