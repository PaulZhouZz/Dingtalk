<?php
namespace Dingtalk\api;
use Dingtalk\util\Http;

class User{
    private $http;
    public function __construct()
    {
        $this->http = new Http();
    }

    public function getUserInfo($accessToken, $code)
    {
        $response = $this->http->get("/user/getuserinfo",
            array("access_token"=>$accessToken, "code"=>$code));
        return $response;
    }

    public function get($accessToken, $userId)
    {
        $response = $this->http->get("/user/get",
            ["access_token" => $accessToken, "userid" => $userId]);
        return $response;
    }

    public function simplelist($accessToken, $deptId)
    {
        $response = $this->http->get('/user/simplelist',
            ["access_token"=>$accessToken,"department_id"=>$deptId]);
        return $response;
    }
}