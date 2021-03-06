<?php

require_once "../Engine/Figure.php";

class King extends Figure
{
  var $has_moved = false;//todo::использовать в рокировке

  public function __construct($c)
  {
    parent::__construct($c);
    $this->name = 'king';
  }

  public function checkRule($x1, $y1, $x2, $y2)
  {
    $t1 = abs($x1 - $x2);
    $t2 = abs($y1 - $y2);
    return $t1 < 2 && $t2 < 2 && ($t1 + $t2 > 0);
  }

  public function checkPath($b, $x1, $y1, $x2, $y2)
  {
    //todo::проверить, что король не идет на битое поле
    //проверить для каждого поля что оно не бьет
    //надо удалить короля и чекать
    $figure = $b->board[$x2][$y2];
    return !$figure || $figure->color != $b->board[$x1][$y1]->color;
  }
}