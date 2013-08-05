<?php
namespace ConfigParser;


use \RuntimeException;
use \ConfigParser\BaseConfigParser;
use \ConfigParser\Exception\ParsingError;


class ConfigParser extends BaseConfigParser
{
	public function __construct()
	{
	}

	public function read($filename)
	{
		if (!file_exists($filename)) {
			throw new RuntimeException("File not exists.");
		}

		$ini = parse_ini_file($filename, true);

		if (!$ini) {
			throw new ParsingError();
		}

		$this->parse($ini);
	}

	public function write()
	{
		// pass
	}
}