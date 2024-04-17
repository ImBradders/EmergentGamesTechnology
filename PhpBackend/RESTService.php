<?php

class RESTService {
    private $supportedMethods;
    private $filePath;
    
    public function __construct() {
        $this->supportedMethods = "GET, PUT, POST, DELETE";
        $this->filePath = "person.txt";
    }
    
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $headers = apache_request_headers();
        $headers = $headers["Authorization"];
        $headers = explode(":", $headers);

        if (isset($_GET['q'])) {
            $parameters = explode("/", $_GET['q']);
        }
        else
        {
            $parameters = array();
        }

        if(isset($_SERVER['HTTP_ACCEPT'])) {
            $accept = $_SERVER['HTTP_ACCEPT'];
        }
        else
        {
            $accept = "";
        }

        $this->selectMethod($_HEADERS, $method, $parameters, $requestBody, $accept);
    }

    public function selectMethod($_HEADERS, $method, $parameters, $requestBody, $accept) {
        switch($method) {
            case 'GET':
                $this->performGet($_HEADERS, $parameters, $requestBody, $accept);
                break;
            case 'POST': //create stuff
                $this->performPost($_HEADERS, $parameters, $requestBody, $accept);
                break;
            case 'PUT': //this is to update stuff
                $this->performPut($_HEADERS, $parameters, $requestBody, $accept);
                break;
            case 'DELETE':
                $this->performDelete($_HEADERS, $parameters, $requestBody, $accept);
                break;
            default:
                $this->notImplementedResponse();
                break;
        }
    }

    public function performGet($_HEADERS, $parameters, $requestBody, $accept) {
        $this->notImplementedResponse();
    }

    public function performPost($_HEADERS, $parameters, $requestBody, $accept) {
        $this->notImplementedResponse();
    }

    public function performPut($_HEADERS, $parameters, $requestBody, $accept) {
        $this->notImplementedResponse();
    }

    public function performDelete($_HEADERS, $parameters, $requestBody, $accept) {
        $this->notImplementedResponse();
    }

    protected function notImplementedResponse() {
        header('Allow: ' . $this->supportedMethods, true, 501);
    }

    protected function methodNotAllowedResponse() {
        header('Allow: ' . $this->supportedMethods, true, 405);
    }

    protected function notFoundResponse() {
        header("HTTP/1.1 404 Not Found");
    }

    protected function noContentResponse() {
        header("HTTP/1.1 204 No Content");
    }
}