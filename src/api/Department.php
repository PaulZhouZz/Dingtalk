<?php
namespace Dingtalk\api;
use Dingtalk\util\Http;

class Department
{
    private $http;
    public function __construct() {
        $this->http = new Http();
    }

    public function createDept($accessToken, $dept)
    {
        $response = $this->http->get("/department/create",
            array("access_token" => $accessToken),
            json_encode($dept));
        return $response;
    }


    public function listDept($accessToken)
    {
        $response = $this->http->get("/department/list",
            array("access_token" => $accessToken));
        return $response;
    }


    public function deleteDept($accessToken, $id)
    {
        $response = $this->http->get("/department/delete",
            array("access_token" => $accessToken, "id" => $id));
        return $response;
    }
}