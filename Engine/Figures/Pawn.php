<?php

namespace Engine\Figures;

use Engine\Figure;

class Pawn extends Figure
{

  public function checkRule($x1, $y1, $x2, $y2)
  {
    return ($x1 == $x2 && ($y2 == $y1 + 1 || ($y2 == $y1 + 2 && $y1 == 1)));
  }

  public function checkPath($b, $x1, $y1, $x2, $y2)
  {
    if ($x1 == $x2) {
      for ($i = $x1 + 1; $i < $x2 + 1; $i++) {
        if ($b[$x2][$i]) {
          return false;
        }
      }
    } else {
      $figure = $b[$x2][$y2];
      return $figure && $figure->color != $b[$x1][$y1]->color;
    }
    //todo::добавить превращение пешки в любую другую фигуру
  }

  public function toArray()
  {
    return [
      'name' => 'pawn',
      'color' => $this->color
    ];
  }
}