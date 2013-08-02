<?php
namespace ConfigParser\Exception;


use \Exception;


class ParsingError extends Exception
{
	public function __construct(
		$message = 'Parsing error.',
		$code = 0,
		Exception $previous = null
	) {
        parent::__construct($message, $code, $previous);
    }
}