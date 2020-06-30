<?php
set_include_path(get_include_path() . PATH_SEPARATOR . "Engine");

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

  public function toArray()
  {
    return [
      'name' => $this->name,
      'color' => $this->color == \Engine\Color::WHITE ? 'white' : 'black'
    ];
  }
}