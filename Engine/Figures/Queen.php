<?php

require_once "../Engine/Figure.php";

class Queen extends Figure
{
  public function __construct($c)
  {
    parent::__construct($c);
    $this->name = 'queen';
  }

  public function checkRule($x1, $y1, $x2, $y2)
  {
    return (((($x1 + $y1 == $x2 + $y2) || (8 - $x1 + $y1 == 8 - $x2 + $y2)) && ($x1 != $x2)))
      || ($x1 == $x2 && $y1 != $y2) || ($x1 != $x2 && $y1 == $y2);
  }

  public function checkPath($b, $x1, $y1, $x2, $y2)
  {
    if ($x1 + $y1 == $x2 + $y2) {
      if (!Figure::checkDiagonalUpDown($b, $x1, $y1, $x2, $y2)) {
        return false;
      }
    } else if (8 - $x1 + $y1 == 8 - $x2 + $y2) {
      if (!Figure::checkDiagonalDownUp($b, $x1, $y1, $x2, $y2)) {
        return false;
      }
    } else {
      if (!Figure::checkVerticalHorizontal($b, $x1, $y1, $x2, $y2)) {
        return false;
      }
    }

    //проверка что ты либо втсал на пустое, либо съел
    $figure = $b->board[$x2][$y2];
    return !$figure || ($figure->color != $b->board[$x1][$y1]->color);
  }
}