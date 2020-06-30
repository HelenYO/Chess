<?php

use Engine\Cell;
use Engine\Chessboard;
use Engine\Color;
use Engine\Status;
use Engine\Winner;

require_once "../Engine/Cell.php";
require_once "../Engine/Color.php";
require_once "../Engine/Move.php";
require_once "../Engine/Status.php";
require_once "../Engine/Winner.php";
require_once "../Engine/Chessboard.php";
require_once "../Engine/Figures/Rook.php";
require_once "../Engine/Figures/Queen.php";
require_once "../Engine/Figures/Pawn.php";
require_once "../Engine/Figures/Knight.php";
require_once "../Engine/Figures/King.php";
require_once "../Engine/Figures/Bishop.php";

class Game
{
  const STATUS_OK = 'ok';
  var $winner;
  var $board;
  var $moves;
  var $status;
  var $turn;
  var $id_first;
  var $id_second;
  var $is_king_under_attack; //todo:: пропихнуть везде эту проверку, чтобы спасать короля, еще для рокировки понадобится
//возможно, нам это и не надо - просто попробуем применить и посмотрим, под атакой ли король
//todo::троекратное повторение ходов

  public function __construct($user_id1, $user_id2)
  {
    $this->board = new Chessboard();
    $this->is_king_under_attack = false;
    $this->id_first = $user_id1;
    $this->id_second = $user_id2;
    $this->winner = Winner::NONE;
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
    $x1--;
    $y1--;
    $x2--;
    $y2--;
    //проверка, что это вообще в поле
    if (!($this->inBorders($x1, $y1) && $this->inBorders($x2, $y2))) {
      //return error;
      return ApiResult::error(ApiError::ERROR_WRONG_PARAMS,
        'wrong turn\'s parameters: out of bounds');
    }
    //проверка, что вообще его ход
    if (!(($this->turn == Color::WHITE && $id == $this->id_first)
      || ($this->turn == Color::BLACK && $id == $this->id_second))) {
      return ApiResult::error(ApiError::ERROR_WRONG_PARAMS,
        'wrong turn\'s parameters: another player\'s turn');
    }
    $figure = $this->board->board[$x1][$y1];
    if (!$figure) {
      return ApiResult::error(ApiError::ERROR_WRONG_PARAMS,
        'wrong turn\'s parameters: empty cell');
    }
    if ($this->board->board[$x1][$y1]->color != $this->turn) {
      return ApiResult::error(ApiError::ERROR_WRONG_PARAMS,
        'wrong turn\'s parameters: another player\'s figure');
    }

    if (!$this->checkMove($x1, $y1, $x2, $y2)) {
      return ApiResult::error(ApiError::ERROR_WRONG_PARAMS,
        'wrong turn\'s parameters: impossible move');
    }
    $this->applyMove($x1, $y1, $x2, $y2);
    return ApiResult::success(self::STATUS_OK);
  }

  public function getStatus()
  {
    return $this->status;
  }

  /**
   * @return array
   */
  public function toArray()
  {
    return [
      'board' => $this->board->toArray(),
      'moves' => $this->moves,
      'status' => $this->status == 0 ? 'started' : 'finished',
      'turn' => $this->turn == Color::WHITE ? 'white' : 'black',
      'first_player' => $this->id_first,
      'second_player' => $this->id_second,
      'winner' => $this->winner == Winner::WHITE ?
        'white' : ($this->winner == Winner::BLACK ?
          'black' : ($this->winner == Winner::DRAW ?
            'draw' : 'none'))
    ];
  }

  /**
   * @param $x1
   * @param $y1
   * @param $x2
   * @param $y2
   * @return bool
   */
  private function checkMove($x1, $y1, $x2, $y2)
  {
    //проверка, что фигура вообще так ходит
    if (!$this->checkRule($x1, $y1, $x2, $y2)) {
      return false;
    }
    //проверка, что на этом пути фигуре ничего не мешало
    if (!$this->checkPath($x1, $y1, $x2, $y2)) {
      return false;
    }
    //проверка на экстра-условия (шаха)
    if (!$this->checkExtra($x1, $y1, $x2, $y2)) {
      return false;
    }
    return true;
  }

  /**
   * @param $x1
   * @param $y1
   * @param $x2
   * @param $y2
   */
  private function applyMove($x1, $y1, $x2, $y2)
  {
    $from = new Cell($x1 + 1, $y1 + 1);
    $to = new Cell($x2 + 1, $y2 + 1);
    $move = new Move($from, $to);
    $this->moves[] = $move;
    $this->board->board[$x2][$y2] = $this->board->board[$x1][$y1];
    $this->board->board[$x1][$y1] = null;
    $this->changeTurn();
    $this->changeIsKingUnderAttack();
//    if ($this->isFinal($move)) {//todo::check final turn
//      $this->changeStatus();
//    }
  }

  //здесь проверяем, что король будет не под шахом
  private function checkExtra($x1, $y1, $x2, $y2)
  {
    $board_temp = clone $this->board;
    $c = $board_temp->board[$x1][$y1]->color;
    $board_temp->board[$x2][$y2] = $board_temp->board[$x1][$y1];
    $board_temp->board[$x1][$y1] = null;
    $pos = $this->findPositionOfKing($c, $board_temp);//таким образом даже если был ход короля, то все ок
    return !$this->checkCheck($board_temp, $pos[0], $pos[1], $c);
    //return true;
  }

  private function findPositionOfKing($c, $b)
  {
    for ($i = 0; $i < 8; $i++) {
      for ($j = 0; $j < 8; $j++) {
        $cell = $b->board[$i][$j];
        if ($cell && ($cell instanceof King) && $cell->color == $c) {
          return [$i, $j];
        }
      }
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

  private function inBorders($x, $y)
  {
    return ($x >= 0 && $x < 8 && $y >= 0 && $y < 8);
  }

  /**
   * @param $x1
   * @param $y1
   * @param $x2
   * @param $y2
   * @return bool
   */
  private function checkRule($x1, $y1, $x2, $y2)
  {
    $figure = $this->board->board[$x1][$y1];
    if (!$figure) {
      return false;
    }
    if (!$figure->checkRule($x1, $y1, $x2, $y2)) {
      if ($figure instanceof Pawn) {
        if ((abs($x2 - $x1) == 1) && ($y1 + 1 == $y2) && ($this->board->board[$x2][$y2])) {
          return true;
        }
      }
      return false;
    }
    return true;
  }

  /**
   * @param $x1
   * @param $y1
   * @param $x2
   * @param $y2
   * @return bool
   */
  private function checkPath($x1, $y1, $x2, $y2)
  {
    $figure = $this->board->board[$x1][$y1];
    if ($figure->checkPath($this->board, $x1, $y1, $x2, $y2)) {
      return true;
    } else {
      return false;
    }
  }

  //когда проверяешь, надо самого короля с поля убрать(он может заслонять)
  public function checkCellAttack($b, $x, $y, $c)
  {//color who are being attacked
    $mayAttack = [];
    for ($i = 0; $i < 8; $i++) {
      for ($j = 0; $j < 8; $j++) {
        $cell = $b->board[$i][$j];
        if (($i == $x) && ($j == $y)) {
          continue;
        }
        if (!$cell) {
          continue;
        }
        if ($cell->color == $c) {
          continue;
        }
        if (!$this->checkMove($i, $j, $x, $y)) {
          continue;
        }
        $mayAttack[] = $cell;
      }
    }
    return $mayAttack;
  }

  public function checkCheck($b, $x, $y, $c)
  {//true - under attack
    $mayAttack = Game::checkCellAttack($b, $x, $y, $c);
    $size = sizeof($mayAttack);
    return ($size != 0);
  }
}