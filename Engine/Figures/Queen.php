<?php

namespace Engine\Figures;

use Engine\Figure;

class Queen extends Figure
{

  public function checkRule($x1, $y1, $x2, $y2)
  {
    return (((($x1 + $y1 == $x2 + $y2) || (8 - $x1 + $y1 == 8 - $x2 + $y2)) && ($x1 != $x2))) || ($x1 == $x2 && $y1 != $y2) || ($x1 != $x2 && $y1 == $y2);
  }

  public function checkPath($b, $x1, $y1, $x2, $y2)
  {
    if ($x1 + $y1 == $x2 + $y2) {
      if ($x2 > $x1) {
        for ($i = $x1 + 1; $i < $x2; $i++) {
          if ($b[$i][$x1 + $y1 - $i]) {
            return false;
          }
        }
      } else {
        for ($i = $x1 - 1; $i > $x2; $i--) {
          if ($b[$i][$x1 + $y1 - $i]) {
            return false;
          }
        }
      }
    } else if (8 - $x1 + $y1 == 8 - $x2 + $y2) {
      if ($x2 > $x1) {
        for ($i = $x1 + 1; $i < $x2; $i++) {
          if ($b[$i][$i - $x1 + $y1]) {
            return false;
          }
        }
      } else {
        for ($i = $x1 - 1; $i > $x2; $i--) {
          if ($b[$i][$i - $x1 + $y1]) {
            return false;
          }
        }
      }
    } else if ($x1 == $x2) {
      if ($y1 < $y2) {
        for ($i = $y1 + 1; $i < $y2; $i++) {
          if ($b[$x1][$i]) {
            return false;
          }
        }
      } else {
        for ($i = $y1 - 1; $i > $y2; $i--) {
          if ($b[$x1][$i]) {
            return false;
          }
        }
      }
    } else {
      if ($x1 < $x2) {
        for ($i = $x1 + 1; $i < $x2; $i++) {
          if ($b[$i][$y1]) {
            return false;
          }
        }
      } else {
        for ($i = $x1 - 1; $i > $x2; $i--) {
          if ($b[$i][$y1]) {
            return false;
          }
        }
      }
    }
    //проверка что ты либо втсал на пустое, либо съел
    $figure = $b[$x2][$y2];
    return !$figure || $figure->color != $b[$x1][$y1]->color;
  }

  public function toArray()
  {
    return [
      'name' => 'queen',
      'color' => $this->color
    ];
  }
}