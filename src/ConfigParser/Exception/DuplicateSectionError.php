<?php
namespace ConfigParser\Exception;


use \Exception;


class DuplicateSectionError extends Exception
{
	public function __construct(
		$message = 'Duplicated section error.',
		$code = 0,
		Exception $previous = null
	) {
        parent::__construct($message, $code, $previous);
    }
}