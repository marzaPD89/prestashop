<?php

class HttpResponder {
	// Status
	private $_version;
	private $_status;
	private $_statusMessage;
	// Headers
	private $_headers;
	// Body
	private $_formData;
	private $_jsonData;
	private $_rawData;
	private $_xmlData;

	public function __construct($httpData) {
		// Extract body
		$httpData = explode("\r\n\r\n", $httpData, 2);
		if (count($httpData) != 2) {
			throw new HttpExceptioner('Invalid data: unable to find end of headers.');
		}
		$this->_rawData = $httpData[1];

		// Split headers
		$httpData = explode("\r\n", $httpData[0]);
		if (count($httpData) < 1) {
			throw new HttpExceptioner('Invalid data: unable split headers.');
		}

		// Status
		$status = array_shift($httpData);
		if (!preg_match('#http/([0-9.]+) +([0-9]{3}) +(.*)$#i', $status, $matches)) {
			throw new HttpExceptioner('Invalid data: no status found');
		}
		$this->_version = (float)$matches[1];
		$this->_status = (int)$matches[2];
		$this->_statusMessage = $matches[3];

		// Headers
		$this->_headers = array();
		foreach ($httpData as $header) {
			if (!preg_match('# *([-0-9A-Za-z]+) *: *(.*) *$#i', $header, $matches)) {
				throw new HttpExceptioner('Invalid header "'.$header.'"');
			}
			$name = strtolower($matches[1]);
			$value = $matches[2];
			if (!isset($this->_headers[$name])) {
				$this->_headers[$name] = array();
			}
			$this->_headers[$name][] = $value;
		}
	}

	public function getContentType() {
		return $this->getFirstHeader('content-type');
	}

	public function getFirstHeader($name) {
		$values = $this->getHeader($name);
		if (empty($values)) {
			return null;
		}
		return $values[0];
	}

	public function hasHeader($name) {
		$name = strtolower((string)$name);
		return isset($this->_headers[$name]);
	}

	public function getHeader($name) {
		$name = strtolower((string)$name);
		if (!isset($this->_headers[$name])) {
			return array();
		}
		return $this->_headers[$name];
	}

	public function getHeaders() {
		return $this->_headers;
	}

	public function getStatus() {
		return $this->_status;
	}

	public function getStatusMessage() {
		return $this->_statusMessage;
	}

	public function getVersion() {
		return $this->_version;
	}

	/**
	 * Decode the body as form value encoded using
	 * application/x-www-form-urlencoded content type
	 * @return array the decoded body
	 */
	public function asForm() {
		if (is_null($this->_formData)) {
			$data = $this->asRawData();
			parse_str($data, $values);
			$this->_formData = $values;
		}
		return $this->_formData;
	}

	/**
	 * Decode the body as a Json object
	 * @return mixed the decoded body
	 */
	public function asJson() {
		if (is_null($this->_jsonData)) {
// 			if (!function_exists('json_decode')) {
// 				throw new HttpExceptioner('json_decode function is required'
// 					.' to decode the body as a Json document.');
// 			}
			$data = $this->asRawData();
			
			if (function_exists('json_decode'))
				$data = json_decode($data);
			else
			{
				include_once(dirname(__FILE__) . '/../../../../tools/json/json.php');
				$pear_json = new Services_JSON();
				$data = $pear_json->decode($data);
			}			

// 			$data = json_decode($data);
			if (function_exists('json_last_error') && (json_last_error() != JSON_ERROR_NONE)) {
				throw new HttpExceptioner('Not a valid Json document');
			}
			$this->_jsonData = $data;
		}
		return $this->_jsonData;
	}

	/**
	 * Does not decode the body
	 * @return string the body data
	 */
	public function asRawData() {
		return $this->_rawData;
	}

	/**
	 * Decode the body as an XML document and returns a DOMDocument
	 * @return DOMDocument the decoded body
	 */
	public function asXml() {
		if (is_null($this->_xmlData)) {
			if (!class_exists('DOMDocument')) {
				throw new HttpExceptioner('DOMDocument is required to '
					.'decode the body as a XML document.');
			}
			$dom = new DOMDocument();
			$data = $this->asRawData();
			if (!$dom->loadXML($data)) {
				throw new HttpExceptioner('Not a valid XML document');
			}
			$this->_xmlData = $dom;
		}
		return $this->_xmlData;
	}
}