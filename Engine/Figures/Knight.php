<?php

require_once "../Engine/Figure.php";

class Knight extends Figure
{

  public function __construct($c)
  {
    parent::__construct($c);
    $this->name = 'knight';
  }

  public function checkRule($x1, $y1, $x2, $y2)
  {
    return (abs($x1 - $x2) == 2 && abs($y1 - $y2) == 1)
      || (abs($x1 - $x2) == 1 && abs($y1 - $y2) == 2);
  }

  public function checkPath($b, $x1, $y1, $x2, $y2)
  {
    $figure = $b->board[$x2][$y2];
    return !$figure || $figure->color != $b->board[$x1][$y1]->color;
  }
}