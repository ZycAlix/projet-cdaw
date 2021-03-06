<?php


class PromUserController extends Controller {

    public function __construct($name, $request) {
        parent::__construct($name, $request);
    }

    public function processRequest() {
                if($this->request->getHttpMethod() == 'OPTIONS')
            return Response::okResponse('ok');
        if($this->request->getHttpMethod() !== 'PUT')
            return Response::errorResponse('{ "message" : "Unsupported endpoint" }' );

        $json = $this->request->jsonContent();

        if(!isset($json->USER_LOGIN)) {
            $r = new Response(422,"Login field is mandatory");
            $r->send();
        }
        $admin = Admin::tryFindAdminByLogin($json->USER_LOGIN);
        
        if(empty($admin)) {
            $r = new Response(404,"User not founded");
            $r->send();
        }
        Admin::tryPromUser($json);
        $res = array(
                "message" => "User was promoted.",
            );
        return $res;
    }
}