<?php

use Engine\Color;

require_once "../Engine/Figure.php";

class Pawn extends Figure
{

  public function __construct($c)
  {
    parent::__construct($c);
    $this->name = 'pawn';
  }

  public function checkRule($x1, $y1, $x2, $y2)
  {
    return ($x1 == $x2 && (abs($y2 - $y1) == 1 || ($y2 == $y1 + 2 && $y1 == 1) || ($y2 == $y1 - 2 && $y1 == 6)))
      || (abs($x1 - $x2) == 1 && abs($y1 - $y2) == 1);
    //todo::проверка должна зависеть от цвета, поэтому важная часть будет в проверке пути, где есть доступ
  }

  public function checkPath($b, $x1, $y1, $x2, $y2)
  {
    if ($x1 == $x2) {
      if ($b->board[$x1][$y1]->color == Color::WHITE) {
        for ($i = $y1 + 1; $i < $y2 + 1; $i++) {
          if ($b->board[$x2][$i]) {
            return false;
          }
        }
      } else {
        for ($i = $y1 - 1; $i > $y2 - 1; $i--) {
          if ($b->board[$x2][$i]) {
            return false;
          }
        }
      }
    } else {
      $figure = $b->board[$x2][$y2];
      if (!($figure && ($figure->color != ($b->board[$x1][$y1]->color)))) {
        return false;
      }
      if (!((($figure->color == Color::BLACK) && (abs($x1 - $x2) == 1) && ($y2 == $y1 + 1)) ||
        (($figure->color == Color::WHITE) && (abs($x1 - $x2) == 1) && ($y2 == $y1 - 1)))) {
        return false;
      }
    }
    return true;
    //todo::добавить превращение пешки в любую другую фигуру
    //todo::добавить взятие на проходе
  }
}