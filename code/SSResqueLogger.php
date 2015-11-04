<?php

/**
 * SSResqueLogger
 *
 * Will proxy the error message into SS_Logger
 *
 */
class SSResqueLogger extends Psr\Log\AbstractLogger {

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed   $level    PSR-3 log level constant, or equivalent string
	 * @param string  $message  Message to log, may contain a { placeholder }
	 * @param array   $context  Variables to replace { placeholder }
	 * @return null
	 */
	public function log($level, $message, array $context = array()) {
		$message = $this->interpolate($message, $context);
		SS_Log::log($message, $this->convertLevel($level));
	}

	/**
	 * Fill placeholders with the provided context
	 * @author Jordi Boggiano j.boggiano@seld.be
	 * 
	 * @param  string  $message  Message to be logged
	 * @param  array   $context  Array of variables to use in message
	 * @return string
	 */
	public function interpolate($message, array $context = array()) {
		// build a replacement array with braces around the context keys
		$replace = array();
		foreach($context as $key => $val) {
			$replace['{' . $key . '}'] = $val;
		}
		// interpolate replacement values into the message and return
		return strtr($message, $replace);
	}
	
	/**
	 *
	 * @param string $resqueError
	 *
	 * @return int
	 */
	protected function convertLevel($resqueError) {
		switch($resqueError) {
			case 'emergency':
			case 'alert':
			case 'critical':
			case 'error':
				return SS_Log::ERR;
				break;
			case 'warning':
				return SS_Log::WARN;
				break;
			case 'notice':
			case 'info':
			case 'debug':
				return SS_Log::NOTICE;
				break;
			default:
				return SS_Log::NOTICE;
				break;
		}
	}
}
