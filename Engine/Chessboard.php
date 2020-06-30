<?php

namespace Engine;

use Bishop;
use King;
use Knight;
use Pawn;
use Queen;
use Rook;

require_once "../Engine/Figures/Rook.php";
require_once "../Engine/Figures/Queen.php";
require_once "../Engine/Figures/Pawn.php";
require_once "../Engine/Figures/Knight.php";
require_once "../Engine/Figures/King.php";
require_once "../Engine/Figures/Bishop.php";

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
    $this->init();
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

    $this->board[0][7] = new Rook(Color::BLACK);
    $this->board[1][7] = new Knight(Color::BLACK);
    $this->board[2][7] = new Bishop(Color::BLACK);
    $this->board[3][7] = new Queen(Color::BLACK);
    $this->board[4][7] = new King(Color::BLACK);
    $this->board[5][7] = new Bishop(Color::BLACK);
    $this->board[6][7] = new Knight(Color::BLACK);
    $this->board[7][7] = new Rook(Color::BLACK);
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