<?php
namespace ConfigParser\Exception;


use \Exception;


class NoOptionError extends Exception
{
	public function __construct(
		$message = 'Option not exists.',
		$code = 0,
		Exception $previous = null
	) {
        parent::__construct($message, $code, $previous);
    }
}