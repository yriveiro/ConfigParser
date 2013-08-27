<?php
namespace tests\ConfigParser;
require_once(dirname(__FILE__) . '/../bootstrap.php');


use \ConfigParser\ConfigParser;
use \PHPUnit_Framework_TestCase;


class ConfigParserTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->cfg = new ConfigParser();

		$this->cfg->addSection('database');
		$this->cfg->addSection('redis');
	}

	public function testAddSection()
	{
		$this->cfg->addSection('http');
		$this->assertEquals(
			array('database', 'redis', 'http'),
			$this->cfg->sections()
		);
	}

    /**
     * @expectedException	\ConfigParser\Exception\DuplicateSectionError
     */
	public function testAddDuplicateSection()
	{
		$this->cfg->addSection('database');
	}

	public function testRemoveSection()
	{
		$this->cfg->removeSection('redis');
		$this->assertFalse($this->cfg->hasSection('redis'));
	}

    /**
     * @expectedException	\ConfigParser\Exception\NoSectionError
     */
	public function testRemoveSectionNoSection()
	{
		$this->cfg->removeSection('noSection');
	}

	public function testGetSection()
	{
		$this->assertEquals(array(), $this->cfg->getSection('database'));
	}

    /**
     * @expectedException	\ConfigParser\Exception\NoSectionError
     */
	public function testGetSectionFail()
	{
		$this->cfg->getSection('noSection');
	}

	public function testHasSection()
	{
		$this->assertTrue($this->cfg->hasSection('database'));
	}

	public function testHasNoSection()
	{
		$this->assertFalse($this->cfg->hasSection('noSection'));
	}

	public function testOptions()
	{
		$this->assertEquals(array(), $this->cfg->options('database'));
		$this->cfg->setOption('database', 'host', 'localhost');
		$this->assertEquals(array('host'), $this->cfg->options('database'));
	}

    /**
     * @expectedException	\ConfigParser\Exception\NoSectionError
     */
	public function testOptionsNoSection()
	{
		$this->cfg->options('noSection');
	}

	public function testGetOption()
	{
		$this->cfg->setOption('database', 'host', 'localhost');
		$this->assertEquals(
			'localhost',
			$this->cfg->getOption('database', 'host')
		);
	}

    /**
     * @expectedException	\ConfigParser\Exception\NoSectionError
     */
	public function testGetOptionNoSection()
	{
		$this->cfg->getOption('noSection', 'host');
	}

    /**
     * @expectedException	\ConfigParser\Exception\NoOptionError
     */
	public function testGetOptionNoOption()
	{
		$this->cfg->getOption('database', 'noOption');
	}

	public function testHasOption()
	{
		$this->cfg->setOption('database', 'host', 'localhost');
		$this->assertTrue($this->cfg->hasOption('database', 'host'));
	}

	public function testHasNoOption()
	{
		$this->cfg->setOption('database', 'host', 'localhost');
		$this->assertFalse($this->cfg->hasOption('database', 'passwd'));
	}

	/**
     * @expectedException	\ConfigParser\Exception\NoSectionError
     */
	public function testHasOptionNoSection()
	{
		$this->assertTrue($this->cfg->hasOption('noSection', 'host'));
	}

	public function testSetOption()
	{
		$this->cfg->setOption('database', 'host', 'localhost');
		$this->assertEquals(
			'localhost',
			$this->cfg->getOption('database', 'host')
		);
	}

	public function testRemoveOption()
	{
		$this->cfg->setOption('database', 'host', 'localhost');
		$this->assertEquals(
			'localhost',
			$this->cfg->getOption('database', 'host')
		);

		$this->cfg->removeOption('database', 'host');
		$this->assertFalse($this->cfg->hasOption('database', 'host'));
	}

	/**
     * @expectedException	\ConfigParser\Exception\NoSectionError
     */
	public function testRemoveOptionNoSection()
	{
		$this->cfg->removeOption('noSection', 'host');
	}

	/**
     * @expectedException	\ConfigParser\Exception\NoOptionError
     */
	public function testRemoveOptionNoOption()
	{
		$this->cfg->removeOption('database', 'noOption');
	}

	public function testParse()
	{
		$this->cfg->removeSection('database');
		$this->cfg->removeSection('redis');

		$ini = parse_ini_file(dirname(__FILE__) . '/fixtures/config.ini', true);

		$this->cfg->parse($ini);

		$expected = array(
			'database' => array(
				'host' => 'localhost',
				'user' => 'root',
				'passwd' => 1234
			),
			'redis' => array(
				'host' => '127.0.0.1'
			)
		);

		$this->assertEquals($expected, $this->cfg->dump());
	}

	/**
     * @expectedException	\RuntimeException
	 */
	public function testParseFileNotExists()
	{
		$this->cfg->read(dirname(__FILE__) . '/fixtures/notExists.ini', true);
	}
}