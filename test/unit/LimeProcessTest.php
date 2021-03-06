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

$t = new LimeTest();


// @Test: A PHP file can be executed

  // fixtures
  $executable = new LimeExecutable(LimeExecutable::php().' %file%');
  $output = '';
  $errors = '';
  $file = tempnam(sys_get_temp_dir(), 'lime');
  file_put_contents($file, <<<EOF
<?php
echo "Test";
file_put_contents("php://stderr", "Errors");
exit(1);
EOF
  );
  // test
  $command = new LimeProcess($executable, $file);
  $command->execute();
  while (!$command->isClosed())
  {
    $output .= $command->getOutput();
    $errors .= $command->getErrors();
  }
  // assertions
  $t->is($output, 'Test', 'The output is correct');
  $t->is($errors, 'Errors', 'The errors are correct');
