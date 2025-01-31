<?php

namespace Meven\Exchange;

class Logger
{
	CONST LOG_DIR = 'local/exchange-log/';
	CONST TYPE_INFO = 'info';	
	CONST TYPE_ERROR = 'error';	
	
    public function __construct()
    {
		
    }
	
	protected function write($message, $category, $type)
	{
		$date = date('Y-m-d');
		$filename = self::LOG_DIR . "exchange-$date.log";
		
		$logData = [
			date('Y-m-d H:i:s'),
			"[$type]",
			"[$category]",
			$message,
		];
		
		\Bitrix\Main\Diag\Debug::writeToFile(implode("\t", $logData), null, $filename);
	}
	
	public function info($message, $category)
	{
		$this->write($message, $category, self::TYPE_INFO);
	}
	
	public function error($message, $category)
	{
		$this->write($message, $category, self::TYPE_ERROR);
	}
}
