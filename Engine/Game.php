<?php

require_once "Cell.php";
require_once "Color.php";
require_once "Status.php";
require_once "Chessboard.php";
require_once "Figures/Rook.php";
require_once "Figures/Queen.php";
require_once "Figures/Pawn.php";
require_once "Figures/Knight.php";
require_once "Figures/King.php";
require_once "Figures/Bishop.php";

class Game
{
  var $board;
  var $moves;
  var $status;
  var $turn;
  var $id_first;
  var $id_second;
  var $is_king_under_attack; //todo:: пропихнуть везде эту проверку, чтобы спасать короля, еще для рокировки понадобится

  //todo::сделать инфу кто победил

  public function __construct($user_id1, $user_id2)
  {
    $this->board = new Chessboard();
    $this->is_king_under_attack = false;
    $this->id_first = $user_id1;
    $this->id_second = $user_id2;
    $this->startGame();
  }

  private function startGame() //todo::
  {
    $this->board->init();
    $this->status = Status::STARTED;
    $this->turn = Color::WHITE;
  }

  /**
   * @param $id
   * @param $x1
   * @param $y1
   * @param $x2
   * @param $y2
   * @return ApiResult
   */
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

  public function toArray()
  {
    return [
      'board' => $this->board->toArray(),
      'moves' => $this->moves,
      'status' => $this->status,
      'turn' => $this->turn,
      'first_player' => $this->id_first,
      'second_player' => $this->id_first
    ];
  }

  private function checkMove($x1, $y1, $x2, $y2)
  {
    $from = new Cell($x1, $y1);
    $to = new Cell($x2, $y2);
    $move = new Move($from, $to);
    $figure = $this->board[$x1][$y1];
    //проверка, что фигура вообще так ходит
    if (!$this->checkRule($x1, $y1, $x2, $y2)) {
      return error;
    }
    //проверка, что на этом пути фигуре ничего не мешало
    if (!$this->checkPath($x1, $y1, $x2, $y2)) {
      return error;
    }
    //проверка на экстра-условия типа шаха
//    if (!checkExtra()) {//todo::do it
//      return error;
//    }
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
//    if ($this->isFinal($move)) {//todo::check final turn
//      $this->changeStatus();
//    }
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
    if (!$figure->checkRule($x1, $y1, $x2, $y2)) {
      if ($figure instanceof Pawn) {
        if ((abs($x2 - $x1) == 1) && ($y1 + 1 == $y2) && ($this->board[$x2][$y2])) {
          return true;
        }
      }
      return error;
    }
    return true;
  }

  private function checkPath($x1, $y1, $x2, $y2)
  {
    $from = new Cell($x1, $y1);
    $to = new Cell($x2, $y2);
    $move = new Move($from, $to);
    $figure = $this->board[$x1][$y1];
    if ($figure->checkPath($this->board, $x1, $y1, $x2, $y2)) {
      return true;
    } else {
      return error;
    }
  }
}