<?php

namespace Engine;

use Engine\Figures\Rook;
use Engine\Figures\Queen;
use Engine\Figures\Pawn;
use Engine\Figures\Knight;
use Engine\Figures\King;
use Engine\Figures\Bishop;

class Chessboard
{
  var $board = [];

  public function __construct()
  {
    for ($i = 0; $i < 8; $i++) {
      for ($j = 0; $j < 8; $j++) {
        $this->board[$i][$j] = null;
      }
    }
  }

  public function init()
  {
    for ($i = 0; $i < 8; $i++) {
      $this->board[$i][1] = new Pawn(Color::WHITE);
    }

    for ($i = 0; $i < 8; $i++) {
      $this->board[$i][6] = new Pawn(Color::BLACK);
    }

    $this->board[0][0] = new Rook(Color::WHITE);
    $this->board[1][0] = new Knight(Color::WHITE);
    $this->board[2][0] = new Bishop(Color::WHITE);
    $this->board[3][0] = new Queen(Color::WHITE);
    $this->board[4][0] = new King(Color::WHITE);
    $this->board[5][0] = new Bishop(Color::WHITE);
    $this->board[6][0] = new Knight(Color::WHITE);
    $this->board[7][0] = new Rook(Color::WHITE);

    $this->board[0][7] = new Rook(Color::WHITE);
    $this->board[1][7] = new Knight(Color::WHITE);
    $this->board[2][7] = new Bishop(Color::WHITE);
    $this->board[3][7] = new Queen(Color::WHITE);
    $this->board[4][7] = new King(Color::WHITE);
    $this->board[5][7] = new Bishop(Color::WHITE);
    $this->board[6][7] = new Knight(Color::WHITE);
    $this->board[7][7] = new Rook(Color::WHITE);
  }

  public function toArray()
  {

    $board_array = [];
    for ($i = 0; $i < 8; $i++) {
      for ($j = 0; $j < 8; $j++) {
        $board_array[$i][$j] = $this->board[$i][$j] ? $this->board[$i][$j]->toArray() : ' ';
      }
    }
    return ['chessboard' => $board_array];
  }
}