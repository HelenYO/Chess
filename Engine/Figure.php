<?php

namespace Engine;

abstract class Figure
{
  var $name;
  var $color;

  public function __construct($c)
  {
    $this->color = $c;
  }

  abstract public function checkRule($x1, $y1, $x2, $y2);

  abstract public function checkPath($b, $x1, $y1, $x2, $y2);

  abstract public function toArray();

//  public function toArray()
//  {
//    return [
//      'name' => $this->name,
//      'color' => $this->color
//    ];
//  }
}