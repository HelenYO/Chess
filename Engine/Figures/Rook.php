<?php

//set_include_path(get_include_path() .  "");

require_once "../Engine/Figure.php";


class Rook extends Figure
{
  var $has_moved = false;

  public function __construct($c)
  {
    parent::__construct($c);
    $this->name = 'rook';
  }

  public function checkRule($x1, $y1, $x2, $y2)
  {
    return ($x1 == $x2 && $y1 != $y2) || ($x1 != $x2 && $y1 == $y2);
  }

  public function checkPath($b, $x1, $y1, $x2, $y2)
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
    $figure = $b->board[$x2][$y2];
    return !$figure || $figure->color != $b->board[$x1][$y1]->color;
  }
}