<?php

namespace Engine;

class Cell
{
  var $x;
  var $y;

  public function __construct($x, $y)
  {
    $this->x = $x;
    $this->y = $y;
  }
}