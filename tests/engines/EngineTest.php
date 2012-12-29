<?php
/**
 * @copyright	Copyright 2006-2013, Miles Johnson - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://milesj.me/code/php/decoda
 */

namespace mjohnson\decoda\tests\engines;

use mjohnson\decoda\Decoda;
use mjohnson\decoda\tests\TestCase;
use mjohnson\decoda\tests\TestEngine;
use mjohnson\decoda\tests\TestFilter;

class EngineTest extends TestCase {

	/**
	 * Set up Decoda.
	 */
	protected function setUp() {
		parent::setUp();

		$this->object = new TestEngine();
	}

	/**
	 * Test setPath() sets a path, and getPath() returns it.
	 */
	public function testGetSetPath() {
		$this->assertEquals(DECODA . 'templates/', $this->object->getPath());

		$this->object->setPath(DECODA .'tpls');
		$this->assertEquals(DECODA . 'tpls/', $this->object->getPath());
	}

	/**
	 * Test that setFilter() sets a Filter, and getFilter() returns it.
	 */
	public function testGetSetFilter() {
		$this->assertEquals(null, $this->object->getFilter());

		$this->object->setFilter(new TestFilter());
		$this->assertInstanceOf('mjohnson\decoda\filters\Filter', $this->object->getFilter());
	}

}