<?php
namespace ConfigParser\Exception;


use \Exception;


class NoSectionError extends Exception
{
	public function __construct(
		$message = 'Section not exists.',
		$code = 0,
		Exception $previous = null
	) {
        parent::__construct($message, $code, $previous);
    }
}