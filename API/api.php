<?php

require_once "Input.php";
require_once "ApiResult.php";
require_once "ApiError.php";
require_once "ApiMethods.php";
require_once "../Engine/DB.php";

execute();

function execute()
{
  $init_db_result = DB::initClient();
  if (!$init_db_result) {
    die(ApiResult::error(ApiError::ERROR_DB_FAIL));
  }

  $method = Input::getString('method');

  /**
   * @var ApiResult|null
   */
  $result = null;

  switch ($method) {
    case 'startGame':
      $result = ApiMethods::startGame();
      break;
    case 'getStatus':
      $result = ApiMethods::getGameStatus();
      break;
    case 'makeMove':
      $result = ApiMethods::makeMove();
      break;
    case 'surrender':
      $result = ApiMethods::surrender();
      break;
    default:
      $result = ApiResult::error(ApiError::ERROR_WRONG_METHOD, 'unknown method passed');
  }

  if ($result === null) {
    $result = ApiResult::error(ApiError::ERROR_UNKNOWN);
  }

  die($result->toJson());
}