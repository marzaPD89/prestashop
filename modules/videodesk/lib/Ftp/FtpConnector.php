<?php

require(dirname(__FILE__) . '/FtpException.php');

class FtpConnector {
	private $_connection = null;
	private $_host;
	private $_password;
	private $_port;
	private $_passiveFirst = false;
	private $_timeout = 3;
	private $_timestampize = false;
	private $_user;

	/**
	 * @param string $host Le nom d'hôte
	 * @param string $user Le nom d'utilisateur
	 * @param string $password Le mot de passe de l'utilisateur
	 * @param int $port Le numéro de port (21 par défaut)
	 */
	public function __construct($host, $user, $password, $port = 21) {
		$this->_host = (string)$host;
		$this->_user = (string)$user;
		$this->_password = (string)$password;
		$this->_port = (int)$port;
	}

	/**
	 * Close the connection if still opened
	 */
	public function __destruct() {
		if (!is_null($this->_connection)) {
			ftp_close($this->_connection);
			$this->_connection = null;
		}
	}

	/**
	 * Retourne la connexion FTP en la créant si besoin (connection et
	 * authentification)
	 * @return resource La connexion FTP
	 * @throws FtpException En cas d'erreur de connexion
	 */
	protected function _getConnection() {
		if (is_null($this->_connection)) {
			// Connection
			$cnx = ftp_connect($this->_host, $this->_port, $this->_timeout);
			if ($cnx === false) {
				throw new FtpException('Unable to connect to '.$this->_host, FtpException::CONNECTION_FAILED);
			}

			// Login
			$result = @ftp_login($cnx, $this->_user, $this->_password);
			if ($result === false) {
				ftp_close($cnx);
				throw new FtpException('Unable to login to '.$this->_host.' using user '.$this->_user, FtpException::AUTHENTICATION_FAILED);
			}

			// Check passive or active
			ftp_pasv($cnx, $this->isPassiveFirst());
			$result = ftp_nlist($cnx, '.');
			if ($result === false) {
				ftp_pasv($cnx, !$this->isPassiveFirst());
				$result = ftp_nlist($cnx, '.');
				if ($result == false) {
					ftp_close($cnx);
					throw new FtpException('Unable to find a working configuration between active and passive', FtpException::OTHER);
				}
			}

			$this->_connection = $cnx;
		}
		return $this->_connection;
	}
	
	public function getConnection()
	{
		if (is_null($this->_connection)) {
			return $this->_getConnection();
		}
		else
			return $this->_connection;
	}
	
	public function getFileSize($file)
	{
		return ftp_size($this->_getConnection(), $file);
	}

	public function getTimeout() {
		return $this->_timeout;
	}

	public function isPassiveFirst() {
		return $this->_passiveFirst;
	}

	public function isTimestampize() {
		return $this->_timestampize;
	}

	/**
	 * Liste les fichiers correspondant à $path. Il est possible d'utiliser
	 * des caractères génériques (* et ?).
	 * @param string $path Le chemin dont il faut lister le contenu
	 * (peut contenir des caractères génériques, le contenu du dossier courant
	 *  par défaut)
	 * @return array La liste des fichiers correspondant
	 */
	public function listFiles($path = '.') {
		$result = ftp_nlist($this->_getConnection(), $path);
		if ($result === false) {
			throw new FtpException('Unable to list file matching '.$path, FtpException::NOT_FOUND);
		}
		return $result;
	}

	public function getOne($remote, $local) {
		$cnx = $this->_getConnection();
		$result = ftp_get($cnx, (string)$local, (string)$remote, FTP_BINARY, 0);
		if ($result === false) {
			throw new FtpException('File not found', FtpException::NOT_FOUND);
		}
	}

	/**
	 * Récupère tous les fichiers correspondant à $remotePath (peut inclure des
	 * caractères génériques) et les place dans le dossier $localDirectory.
	 *
	 * Cette fonction respecte l'option "timestampize".
	 */
	public function getAll($remotePath, $localDirectory) {
		if (!is_dir($localDirectory)) {
			throw new FtpException('Destination direcory does not exists', FtpException::NOT_FOUND);
		}

		$localDirectory = realPath($localDirectory).'/';
		if ($this->isTimestampize()) {
			$localDirectory .= date('Ymd-His_');
		}

		$remoteFiles = $this->listFiles($remotePath);
		$files = array();
		foreach ($remoteFiles as $remoteFile) {
			$file = basename($remoteFile);
			$localFile = $localDirectory.$file;
			$this->getOne($remoteFile, $localFile);
			$files[] = $localFile;
		}

		return $files;
	}

	public function setTimeout($timeout) {
		$this->_timeout = (int)$timeout;
		return $this;
	}

	public function setTimestampize($timestampize) {
		$this->_timestampize = $timestampize == true;
		return $this;
	}

	public function setPassiveFirst($passiveFirst) {
		$this->_passiveFirst = $passiveFirst == true;
		return $this;
	}
}