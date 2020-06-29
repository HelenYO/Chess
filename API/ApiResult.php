<?php

class ApiResult
{

  /**
   * @var mixed|null
   */
  private $result;

  /**
   * @var string|null
   */
  private $error_code;

  /**
   * @var string|null
   */
  private $message;

  private function __construct($result, $error_code = null, $message = null)
  {
    $this->result = $result;
    $this->error_code = $error_code;
    $this->message = $message;
  }

  /**
   * @param mixed $result
   * @return ApiResult
   */
  public static function success($result)
  {
    return new ApiResult($result);
  }

  /**
   * @param string $error_code
   * @param string|null $message
   * @return ApiResult
   */
  public static function error($error_code, $message = null)
  {
    return new ApiResult(null, $error_code, $message);
  }

  public function isSuccess()
  {
    return $this->error_code === null;
  }

  public function toJson()
  {
    $response = [];

    if ($this->error_code !== null) {
      $response['error_code'] = $this->error_code;
      $response['error_description'] = ApiError::getErrorCodeDescription($this->error_code);
      if ($this->message) {
        $response['error_message'] = $this->message;
      }
    } else {
      $response['result'] = $this->result;
    }

    return json_encode(['response' => $response]);
  }
}
