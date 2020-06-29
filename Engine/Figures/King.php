<?php

namespace Engine\Figures;

use Engine\Figure;

class King extends Figure
{
  var $has_moved = false;//todo::использовать в рокировке


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
    $figure = $b[$x2][$y2];
    return !$figure || $figure->color != $b[$x1][$y1]->color;
  }

  public function toArray()
  {
    return [
      'name' => 'king',
      'color' => $this->color
    ];
  }
}