<?php

abstract class LimeInput implements LimeInputInterface
{
  public
    $buffer = '',
    $output = null;

  public function __construct(LimeOutputInterface $output)
  {
    $this->output = $output;
  }

  protected function clearErrors()
  {
    while (!empty($this->buffer))
    {
      if (preg_match('/^\s*([\w\s]+): (.+) in (.+) on line (\d+)/', $this->buffer, $matches))
      {
        $this->buffer = trim(substr($this->buffer, strlen($matches[0])));
        $this->output->error(new LimeError($matches[2], $matches[3], $matches[4], $matches[1]));

        // consume Xdebug call stack
        while (preg_match('/^(Call Stack:|\d\.\d+\s+\d+\s+\d+\.\s+.+:\d+)/', $this->buffer, $matches))
        {
          $this->buffer = trim(substr($this->buffer, strlen($matches[0])));
        }
      }
      else
      {
        break;
      }
    }
  }
}