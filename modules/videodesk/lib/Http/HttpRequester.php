<?php

class HttpRequester {
	const VERB_DELETE = 'DELETE';
	const VERB_HEAD = 'HEAD';
	const VERB_GET = 'GET';
	const VERB_OPTIONS = 'OPTIONS';
	const VERB_POST = 'POST';
	const VERB_PUT = 'PUT';

	private $_verb;
	private $_url;
	private $_version = 1.0;
	private $_headers = array();
	private $_data = null;

	/**
	 * Do a GET request to the URL.
	 */
	public static function get($url) {
		$request = new self($url, self::VERB_GET);
		return $request->execute();
	}

	/**
	 * Do a POST request to the URL with the data. $data can be
	 * <ul>
	 *  <li> a string: setData will be called,</li>
	 *  <li> a DOMDocument object: setDataAsXml will be called,</li>
	 *  <li> an array: setDataAsForm will be called,</li>
	 *  <li> anything else: setDataAsJson.</li>
	 * </ul>
	 * Il no content type is provided, the right content type will be
	 * guessed from the data, when possible.
	 */
	public static function post($url, $data, $contentType = null) {
		$request = new self($url, self::VERB_POST);
		if (is_string($data)) {
			$request->setData($data);
		}
		else if ($data instanceof DOMDocument) {
			$request->setDataAsXml($data);
		}
// 		else if (is_array($data)) {
// 			$request->setDataAsForm($data);
// 		}
		else {
			$request->setDataAsJson($data);
		}
		if (!is_null($contentType)) {
			$request->setContentType($contentType);
		}
		return $request->execute();
	}

	public function __construct($url, $verb = 'GET') {
		$this->setUrl($url);
		$this->setVerb($verb);
	}

	/**
	 * Append values to an existing header (or define it). If the
	 * value is an array, the request will has one header with that
	 * name per element.
	 * @param string $name header name
	 * @param string|array $value header value
	 */
	public function addHeader($name, $value) {
		if (is_array($value)) {
			$tmp = array();
			foreach ($value as $v) {
				$tmp[] = $name.': '.$v;
			}
			$value = $tmp;
		}
		else {
			$value = array($name.': '.$value);
		}
		$name = strtolower($name);
		if (isset($this->_headers[$name])) {
			$value = array_merge($this->_headers[$name], $value);
		}
		$this->_headers[$name] = $value;
		return $this;
	}

	public function asGet() {
		$this->setVerb(self::VERB_GET);
		return $this;
	}

	public function asPost() {
		$this->setVerb(self::VERB_POST);
		return $this;
	}

	public function execute() {
		// Split URL
		$urlParts = parse_url($this->_url);
		if (!isset($urlParts['path'])) {
			$urlParts['path'] = '/';
		}

		// Action
		$msg = $this->_verb.' '.$urlParts['path'];
		if (isset($urlParts['query'])) {
			$msg .= '?'.$urlParts['query'];
		}
		$version = $this->_version != (int)$this->_version ? $this->_version : $this->_version.'.0';
		$msg .= ' HTTP/'.$version."\r\n";

		// Headers
		$msg .= 'Host: '.$urlParts['host']."\r\n";
		foreach ($this->_headers as $values) {
			foreach ($values as $value) {
				$msg .= $value."\r\n";
			}
		}
		$msg .= 'Connection: close'."\r\n";
		if (!is_null($this->_data)) {
			$msg .= 'Content-Length: '.strlen($this->_data)."\r\n";
		}
		$msg .= "\r\n";

		// Add data if needed
		if (!is_null($this->_data)) {
			$msg .= $this->_data;
		}

		// Open a connexion
		$port = 80;
		if (isset($urlParts['port'])) {
			$port = (int)$urlParts['port'];
		}
		$cnx = fsockopen($urlParts['host'], $port);

		if (fwrite($cnx, $msg) === false) {
			throw new HttpException('Unable to connect to "'.$urlParts['host']);
		}

		$response = '';
		while (!feof($cnx)) {
			$line = fread($cnx, 1024);
			if ($line === false) {
				throw new HttpException('Unable to read data');
			}
			$response .= $line;
		}

		$response = new HttpResponder($response);
		return $response;
	}

	public function setContentType($type) {
		$this->setHeader('Content-Type', (string)$type);
		return $this;
	}

	public function setData($data) {
		$this->_data = $data;
		return $this;
	}

	public function setDataAsForm($values, $contentType = 'application/x-www-form-urlencoded') {
		$data = http_build_query($values);
		$this->setContentType($contentType);
		$this->setData($data);
		return $this;
	}

	public function setDataAsJson($value, $contentType = 'application/json') {
		
		if (function_exists('json_encode'))
			$data = json_encode($value);
		else
		{
			include_once(dirname(__FILE__) . '/../../../../tools/json/json.php');
			$pear_json = new Services_JSON();
			$data = $pear_json->encode($value);
		}
// 		$data = json_encode($value);
		if ($data === false) {
			throw new HttpException('Unable to encode data as json');
		}
		$this->setContentType($contentType);
		$this->setData($data);
		return $this;
	}

	public function setDataAsXml(DOMDocument $document, $contentType = 'text/xml') {
		$data = $document->saveXML();
		$this->setContentType($contentType);
		$this->setData($data);
		return $this;
	}

	/**
	 * Define (or override) a header. If the value is an array, the
	 * request will has one header with that name per element.
	 * @param string $name header name
	 * @param string|array $value header value
	 */
	public function setHeader($name, $value) {
		if (is_array($value)) {
			$tmp = array();
			foreach ($value as $v) {
				$tmp[] = $name.': '.$v;
			}
			$value = $tmp;
		}
		else {
			$value = array($name.': '.$value);
		}
		$name = strtolower($name);
		$this->_headers[$name] = $value;
		return $this;
	}

	/**
	 * Define the referrer
	 * @param string $referer referrer
	 * @return $this
	 */
	public function setReferer($referer) {
		$this->setHeader('Referer', (string)$referer);
		return $this;
	}

	/**
	 * Define the URL
	 * @param string $url URL
	 * @return $this
	 */
	public function setUrl($url) {
		$this->_url = (string)$url;
		return $this;
	}

	/**
	 * Define the user agent
	 * @param string $ua user agent
	 * @return $this
	 */
	public function setUserAgent($ua) {
		$this->setHeader('User-Agent', (string)$ua);
		return $this;
	}

	/**
	 * Define the HTTP verb
	 * @param string $verb HTTP verb
	 * @return $this
	 */
	public function setVerb($verb) {
		$this->_verb = (string)$verb;
		return $this;
	}

	/**
	 * Define the HTTP version
	 * @param float $version HTTP version
	 * @return $this
	 */
	public function setVersion($version) {
		$this->_version = (float)$version;
		return $this;
	}

	/**
	 * Remove a header.
	 * @param string $name header name
	 */
	public function unsetHeader($name) {
		$name = strtolower($name);
		unset($this->_headers[$name]);
		return $this;
	}
}