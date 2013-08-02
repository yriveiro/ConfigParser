<?php
namespace ConfigParser\Exception;


use \Exception;


class KeyError extends Exception
{
	public function __construct(
		$message = 'Key not exists.',
		$code = 0,
		Exception $previous = null
	) {
        parent::__construct($message, $code, $previous);
    }
}