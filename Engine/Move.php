<?php

use Engine\Cell;

class Move
{
  var $from;
  var $to;

  public function __construct($from, $to)
  {
    $this->from = $from;
    $this->to = $to;
  }
}