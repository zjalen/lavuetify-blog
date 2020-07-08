<?php
/**
 * Created by PhpStorm.
 * User: jialinzhang
 * Date: 2019-08-08
 * Time: 09:52
 */
namespace App\Http\Controllers\Traits;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

trait ApiResponse
{
    /**
     * @var int
     */
    protected $statusCode = FoundationResponse::HTTP_OK;

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {

        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param $data
     * @param array $header
     * @return mixed
     */
    public function respond($data, $header = [])
    {
        return Response::json($data,$this->getStatusCode(),$header);
    }

    /**
     * @param array $data
     * @param array $header
     * @return mixed
     */
    public function status(array $data, $header = []){

        return $this->respond($data, $header);

    }

    /**
     * @param $data
     * @param int $code
     * @param array $header
     * @return mixed
     */
    public function success_message($data, $code = FoundationResponse::HTTP_OK, $header = []){

        return $this->setStatusCode($code)->status([
            'error_code' => 0,
            'data' => $data,
        ], $header);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function success($message = "ok")
    {
        return $this->success_message($message, FoundationResponse::HTTP_OK);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function created($message = "created")
    {
        return $this->success_message($message, FoundationResponse::HTTP_CREATED);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function success_no_content($message = null)
    {
        return $this->success_message($message, FoundationResponse::HTTP_NO_CONTENT);
    }

    /**
     * @param $error_message
     * @param int $error_code
     * @param int $code
     * @param array $header
     * @return mixed
     */
    public function error_message($error_message, $error_code = FoundationResponse::HTTP_BAD_REQUEST, $code = FoundationResponse::HTTP_BAD_REQUEST, $header = []){

        return $this->setStatusCode($code)->status([
            'error_code' => $error_code,
            'error_message' => $error_message,
        ], $header);
    }

    /**
     * @param $error_message
     * @param int $code
     * @param int $error_code
     * @return mixed
     */
    public function failed($error_message = '请求错误', $error_code = FoundationResponse::HTTP_BAD_REQUEST, $code = FoundationResponse::HTTP_BAD_REQUEST){

        return $this->error_message($error_message,$error_code, $code);
    }

    /**
     * @param string $error_message
     * @param int $error_code
     * @param int $code
     * @return mixed
     */
    public function internalError($error_message = "网络异常!", $error_code = FoundationResponse::HTTP_INTERNAL_SERVER_ERROR, $code = FoundationResponse::HTTP_INTERNAL_SERVER_ERROR){

        return $this->error_message($error_message,$error_code, $code);
    }

    /**
     * @param string $error_message
     * @param int $error_code
     * @param int $code
     * @return mixed
     */
    public function notFond($error_message = "未找到信息!", $error_code = FoundationResponse::HTTP_NOT_FOUND, $code = FoundationResponse::HTTP_NOT_FOUND)
    {
        return $this->error_message($error_message,$error_code, $code);
    }

    /**
     * @param string $error_message
     * @param int $error_code
     * @param int $code
     * @return mixed
     */
    public function noAuth($error_message = "用户信息无效!", $error_code = FoundationResponse::HTTP_UNAUTHORIZED, $code = FoundationResponse::HTTP_UNAUTHORIZED)
    {
        return $this->error_message($error_message,$error_code, $code);
    }

    /**
     * @param string $error_message
     * @param int $error_code
     * @param int $code
     * @return mixed
     */
    public function forbidden($error_message = "无权操作!", $error_code = FoundationResponse::HTTP_FORBIDDEN, $code = FoundationResponse::HTTP_FORBIDDEN)
    {
        return $this->error_message($error_message,$error_code, $code);
    }

}
