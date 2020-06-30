<?php

use Engine\Color;

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
      'color' => $this->color == Color::WHITE ? 'white' : 'black'
    ];
  }

  protected function checkDiagonalUpDown($b, $x1, $y1, $x2, $y2)
  {
    if ($x2 > $x1) {
      for ($i = $x1 + 1; $i < $x2; $i++) {
        if ($b->board[$i][$x1 + $y1 - $i]) {
          return false;
        }
      }
    } else {
      for ($i = $x1 - 1; $i > $x2; $i--) {
        if ($b->board[$i][$x1 + $y1 - $i]) {
          return false;
        }
      }
    }
    return true;
  }

  protected function checkDiagonalDownUp($b, $x1, $y1, $x2, $y2)
  {
    if ($x2 > $x1) {
      for ($i = $x1 + 1; $i < $x2; $i++) {
        if ($b->board[$i][$i - $x1 + $y1]) {
          return false;
        }
      }
    } else {
      for ($i = $x1 - 1; $i > $x2; $i--) {
        if ($b->board[$i][$i - $x1 + $y1]) {
          return false;
        }
      }
    }
    return true;
  }

  protected function checkVerticalHorizontal($b, $x1, $y1, $x2, $y2)
  {
    if ($x1 == $x2) {
      if ($y1 < $y2) {
        for ($i = $y1 + 1; $i < $y2; $i++) {
          if ($b->board[$x1][$i]) {
            return false;
          }
        }
      } else {
        for ($i = $y1 - 1; $i > $y2; $i--) {
          if ($b->board[$x1][$i]) {
            return false;
          }
        }
      }
    } else {
      if ($x1 < $x2) {
        for ($i = $x1 + 1; $i < $x2; $i++) {
          if ($b->board[$i][$y1]) {
            return false;
          }
        }
      } else {
        for ($i = $x1 - 1; $i > $x2; $i--) {
          if ($b->board[$i][$y1]) {
            return false;
          }
        }
      }
    }
    return true;
  }
}