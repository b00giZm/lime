<?php

/*
 * This file is part of the Lime framework.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) Bernhard Schussek <bernhard.schussek@symfony-project.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

LimeAnnotationSupport::enable();

class TestCase extends LimeTestCase
{
  public $impl;

  public function setUp()
  {
    $this->impl->setUp();
  }

  public function tearDown()
  {
    $this->impl->tearDown();
  }

  public function testDoSomething()
  {
    $this->impl->testDoSomething();
  }

  public function testDoSomethingElse()
  {
    $this->impl->testDoSomethingElse();
  }
}


$t = new LimeTest();


// @Before

  $output = $t->mock('LimeOutputInterface', array('nice' => true));
  $configuration = $t->stub('LimeConfiguration');
  $configuration->getTestOutput()->returns($output);
  $configuration->replay();
  $test = new TestCase($configuration);
  $output->reset();
  $test->impl = $t->mock('Test');


// @Test: The methods setUp() and tearDown() are called before and after each test method

  // fixtures
  $test->impl->setUp();
  $test->impl->testDoSomething();
  $test->impl->tearDown();
  $test->impl->setUp();
  $test->impl->testDoSomethingElse();
  $test->impl->tearDown();
  $test->impl->replay();
  // test
  $test->run();


// @Test: The method names are converted to passed tests

  $output->method('pass')->parameter(1)->is('Do something');
  $output->method('pass')->parameter(1)->is('Do something else');
  $output->replay();
  // test
  $test->run();
