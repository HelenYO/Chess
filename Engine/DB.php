<?php

use Predis\Client;

require_once "../predis/autoload.php";
Predis\Autoloader::register();

class DB
{

  /**
   * @var Client|null
   */
  private static $client = null;

  public static function initClient(): bool
  {
    try {
      self::$client = new Client();
      return true;
    } catch (Exception $e) {
      return false;
    }
  }

  /**
   * @return Client|null
   */
  private static function getClient()
  {
    return self::$client;
  }

  /**
   * @param string $key
   * @return mixed
   */
  private static function readValue(string $key)
  {
    $client = self::getClient();
    return $client->get($key);
  }

  /**
   * @param string $key
   * @param mixed $value
   * @return bool
   */
  private static function writeValue(string $key, $value)
  {
    $client = self::getClient();
    return (bool)$client->set($key, $value);
  }

  public static function getUserGameId(int $user_id)
  {
    return self::readValue(self::getUserToGameIdKey($user_id));
  }

  public static function setUserGameId(int $user_id, $game_id)
  {
    return self::writeValue(self::getUserToGameIdKey($user_id), $game_id);
  }

  public static function getGame(int $game_id)
  {
    return unserialize(self::readValue(self::getGameKey($game_id)));
  }

  public static function saveGame(int $game_id, $game)
  {
    return self::writeValue(self::getGameKey($game_id), serialize($game));
  }

  public static function getNewGameId()
  {
    $client = self::getClient();
    return (int)$client->incr(self::getGamesCounterKey());
  }

  private static function getUserToGameIdKey(int $user_id): string
  {
    return 'user_game_' . $user_id;
  }

  private static function getGameKey(int $game_id): string
  {
    return 'game_' . $game_id;
  }

  private static function getGamesCounterKey(): string
  {
    return 'games_counter';
  }
}
