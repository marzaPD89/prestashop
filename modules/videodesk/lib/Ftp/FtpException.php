<?php

class FtpException extends Exception {
	const OTHER = 1;
	const CONNECTION_FAILED = 2;
	const AUTHENTICATION_FAILED = 4;
	const NOT_FOUND = 8;
}