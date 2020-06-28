<?php

class Input
{

  /**
   * @param string $key
   * @return int
   */
  public static function getInt(string $key)
  {
    return isset($_REQUEST[$key]) ? (int)$_REQUEST[$key] : 0;
  }

  /**
   * @param string $key
   * @return string
   */
  public static function getString(string $key)
  {
    return isset($_REQUEST[$key]) ? (string)$_REQUEST[$key] : '';
  }
}
