<?php
namespace ConfigParser;


use \ConfigParser\Exception\KeyError;
use \ConfigParser\Exception\NoSectionError;
use \ConfigParser\Exception\NoOptionError;
use \ConfigParser\Exception\DuplicateSectionError;


abstract class BaseConfigParser
{
	protected $defaults = array();
	protected $sections = array();
	protected $emptySection = array();

	abstract public function read($filename);
	abstract public function write();

	public function defaults()
	{
		return $this->defaults;
	}

	public function sections()
	{
		return array_keys($this->sections);
	}

	public function dump()
	{
		return $this->sections;
	}

	public function getSection($section)
	{
		if (!array_key_exists($section, $this->sections)) {
			throw new KeyError(sprintf("Key %s not exists", $section));
		}

		return $this->sections[$section];
	}

	public function hasSection($section)
	{
		try {
			if (is_array($this->getSection($section))) {
				return true;
			}
		} catch (KeyError $e) {
			return false;
		}
	}

	public function addSection($section)
	{
		if ($this->hasSection($section)) {
			throw new DuplicateSectionError();
		}

		$this->sections[$section] = $this->emptySection;
	}

	public function removeSection($section)
	{
		if (!$this->hasSection($section)) {
			throw new NoSectionError();
		}

		unset($this->sections[$section]);
	}

	public function options($section)
	{
		try {
			$_section = $this->getSection($section);

			return array_keys($_section);
		} catch (KeyError $e) {
			throw new NoSectionError($section);
		}
	}

	public function getOption($section, $option)
	{
		try {
			$_section = $this->getSection($section);
		} catch (KeyError $e) {
			$msg = sprintf("Section %s not exists in config file.", $section);

			throw new NoSectionError($msg);
		}

		if (!array_key_exists($option, $_section)) {
			$msg = sprintf(
				"Option %s not exists in section %s.",
				$option, 
				$section
			);

			throw new NoOptionError($msg);
		}

		return $this->sections[$section][$option];
	}

	public function hasOption($section, $option)
	{
		try {
			$this->getSection($section);
		} catch (KeyError $e) {
			throw new NoSectionError($section);
		}

		try {
			 if ($this->getOption($section, $option)) {
			 	return true;
			 }
		} catch (NoOptionError $e) {
			return false;
		}
	}

	public function setOption($section, $option, $value='')
	{
		try {
			$_section = $this->getSection($section);
		} catch (KeyError $e) {
			throw new NoSectionError($section);
		}

		$_section[$option] = $value;

		$this->sections[$section] = $_section;
	}

	public function removeOption($section, $option)
	{
		try {
			if ($this->hasOption($section, $option)) {
				$_section = $this->getSection($section);
				unset($_section[$option]);

				$this->sections[$section] = $_section;
			} else {
				throw new NoOptionError();
			}
		} catch (NoSectionError $e) {
			throw new NoSectionError($section);
		}
	}

	public function parse($data)
	{
		foreach ($data as $section => $options) {
			$this->addSection($section);

			foreach ($options as $option => $value) {
				$this->setOption($section, $option, $value);
			}
		}
	}
}