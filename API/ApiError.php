<?php

class ApiError
{

  const ERROR_WRONG_METHOD = 1;
  const ERROR_WRONG_PARAMS = 2;
  const ERROR_UNKNOWN = 3;
  const ERROR_GAME_NOT_STARTED = 4;
  const ERROR_GAME_ALREADY_STARTED = 5;
  const ERROR_GAME_ALREADY_FINISHED = 6;
  const ERROR_ILLEGAL_MOVE = 7;
  const ERROR_DB_FAIL = 8;

  const ERROR_CODE_TO_MESSAGE = [
    self::ERROR_WRONG_METHOD => 'wrong_method',
    self::ERROR_WRONG_PARAMS => 'wrong_params',
    self::ERROR_UNKNOWN => 'unknown',
    self::ERROR_GAME_NOT_STARTED => 'game_not_started',
    self::ERROR_GAME_ALREADY_STARTED => 'game_already_started',
    self::ERROR_GAME_ALREADY_FINISHED => 'game_already_finished',
    self::ERROR_ILLEGAL_MOVE => 'illegal_move',
    self::ERROR_DB_FAIL => 'internal_er',
  ];

  public static function getErrorCodeDescription($error_code)
  {
    return self::ERROR_CODE_TO_MESSAGE[$error_code] ?? self::ERROR_UNKNOWN;
  }
}
