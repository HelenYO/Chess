<?php

use Engine\Cell;
use Engine\Color;
use Engine\Status;
use Engine\Chessboard;
use Engine\Figures\Rook;
use Engine\Figures\Queen;
use Engine\Figures\Pawn;
use Engine\Figures\Knight;
use Engine\Figures\King;
use Engine\Figures\Bishop;

class Game
{
  var $board;
  var $moves;
  var $status;
  var $turn;
  var $id_first;
  var $id_second;
  var $is_king_under_attack; //todo:: пропихнуть везде эту проверку, чтобы спасать короля, еще для рокировки понадобится

  public function __construct()
  {
    $this->board = new Chessboard();
    $this->is_king_under_attack = false;
  }

  public function startGame()
  {
    $this->board->init();
    $this->status = Status::STARTED;
    $this->turn = Color::WHITE;
  }

  public function makeMove($id, $x1, $y1, $x2, $y2)
  {
    //проверка, что это вообще в поле
    if (!($this->inBorders($x1, $y1) && $this->inBorders($x2, $y2))) {
      return error;
    }
    //проверка, что вообще его ход
    if (($this->turn == Color::WHITE && $id == $this->id_first) || ($this->turn == Color::BLACK && $id == $this->id_second)) {
      return error;
    }
    if (!$this->checkMove($x1, $y1, $x2, $y2)) {
      return error;
    }
    $this->applyMove($x1, $y1, $x2, $y2);
  }

  public function getStatus()
  {
    return $this->status;
  }

  private function checkMove($x1, $y1, $x2, $y2)
  {
    $from = new Cell($x1, $y1);
    $to = new Cell($x2, $y2);
    $move = new Move($from, $to);
    $figure = $this->board[$x1][$y1];
    //проверка, что фигура вообще так ходит
    if (!checkRule()) {
      return error;
    }
    //проверка, что на этом пути фигуре ничего не мешало
    if (!checkPath()) {
      return error;
    }
    //проверка на экстра-условия типа шаха
    if (!checkExtra()) {
      return error;
    }
    return true;
  }

  private function applyMove($x1, $y1, $x2, $y2)
  {
    $from = new Cell($x1, $y1);
    $to = new Cell($x2, $y2);
    $move = new Move($from, $to);
    $this->moves[] = $move;
    $this->changeTurn();
    $this->changeIsKingUnderAttack();
    if ($this->isFinal($move)) {
      $this->changeStatus();
    }
  }

  private function changeTurn()
  {
    $this->turn = $this->turn == Color::BLACK ? Color::WHITE : Color::BLACK;
  }

  private function changeIsKingUnderAttack()
  {
    $this->is_king_under_attack = $this->is_king_under_attack ? false : true;
  }

  private function changeStatus()
  {
    $this->status = Status::FINISHED;
  }

  private function inBorders($x, $y)
  {
    return ($x > 0 && $x < 8 && $y > 0 && $y < 8);
  }

  private function checkRule($x1, $y1, $x2, $y2)
  {
    $from = new Cell($x1, $y1);
    $to = new Cell($x2, $y2);
    $move = new Move($from, $to);
    $figure = $this->board[$x1][$y1];
  }
}