<?php

require_once "Input.php";
require_once "ApiResult.php";
require_once "ApiError.php";
require_once "../Engine/DB.php";

class ApiMethods
{

  const STATUS_OK = 'ok';

  /**
   * @return ApiResult
   */
  public static function startGame()
  {
    $user_id1 = Input::getInt('user_id1');
    $user_id2 = Input::getInt('user_id2');
    if (!$user_id1 || !$user_id2) {
      return ApiResult::error(ApiError::ERROR_WRONG_PARAMS, 'user ids must be specified');
    }

    $user_id1_game_id = DB::getUserGameId($user_id1);
    $user_id2_game_id = DB::getUserGameId($user_id2);

    $user_id1_game = DB::getGame($user_id1_game_id);
    $user_id2_game = DB::getGame($user_id2_game_id);

    return ApiResult::success(self::STATUS_OK);
  }

  /**
   * @return ApiResult
   */
  public static function getGameStatus()
  {
    $user_id = Input::getInt('user_id');
    if (!$user_id) {
      return ApiResult::error(ApiError::ERROR_WRONG_PARAMS, 'user id must be specified');
    }

    $game_id = DB::getUserGameId($user_id);
    if (!$game_id) {
      return ApiResult::success('not_started');
    }

    $game = DB::getGame($game_id);
  }

  /**
   * @return ApiResult
   */
  public static function makeMove()
  {
    $user_id = Input::getInt('user_id');
    if (!$user_id) {
      return ApiResult::error(ApiError::ERROR_WRONG_PARAMS, 'user id must be specified');
    }

    $from_x = Input::getInt('from_x');
    $from_y = Input::getInt('from_y');
    $to_x = Input::getInt('to_x');
    $to_y = Input::getInt('to_y');

    if (!$from_x || !$from_y || !$to_x || !$to_y) {
      return ApiResult::error(ApiError::ERROR_WRONG_PARAMS, 'from and to coordinates must be specified');
    }

    $game_id = DB::getUserGameId($user_id);
    if (!$game_id) {
      return ApiResult::error(ApiError::ERROR_GAME_NOT_STARTED);
    }

    $game = DB::getGame($game_id);

    return ApiResult::success(self::STATUS_OK);
  }

  /**
   * @return ApiResult
   */
  public static function surrender()
  {
    $user_id = Input::getInt('user_id');
    if (!$user_id) {
      return ApiResult::error(ApiError::ERROR_WRONG_PARAMS, 'user id must be specified');
    }

    $game_id = DB::getUserGameId($user_id);
    if (!$game_id) {
      return ApiResult::error(ApiError::ERROR_GAME_NOT_STARTED);
    }

    $game = DB::getGame($game_id);

    return ApiResult::success(self::STATUS_OK);
  }
}
