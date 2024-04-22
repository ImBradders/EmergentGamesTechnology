<?php

require "Person.php";

class RESTService {
    private $supportedMethods;
    private $filePath;
    
    public function __construct() {
        $this->supportedMethods = "GET, PUT, POST, DELETE";
        $this->filePath = "/var/www/data/person.txt";
    }
    
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $headers = apache_request_headers();
        $headers = $headers["Authorization"];
        $headers = explode(":", $headers);

        if (isset($_GET['q'])) {
            $parameters = explode("/", $_GET['q']);
        }
        else {
            $parameters = array();
        }

        if(isset($_SERVER['HTTP_ACCEPT'])) {
            $accept = $_SERVER['HTTP_ACCEPT'];
        }
        else {
            $accept = "";
        }

        $this->selectMethod($headers, $method, $parameters, $accept);
    }

    public function selectMethod($headers, $method, $parameters, $accept) {
        switch($method) {
            case 'GET':
                $this->performGet($headers, $parameters, $accept);
                break;
            case 'POST': //create stuff
                $this->performPost($headers, $parameters, $accept);
                break;
            case 'PUT': //this is to update stuff
            case 'DELETE':
            default:
                $this->notImplementedResponse();
                break;
        }
    }

    public function performGet($headers, $parameters, $accept) {
        // load the file
        $people = array();
        try {
            $myfile = fopen($this->filePath, "r");
            while(!feof($myfile)) {
                $line = fgets($myfile);
                $details = explode(',', $line);
                if (count($details) == 4){
                    $people[] = new Person(trim($details[0]), trim($details[1]), trim($details[2]), trim($details[3]));
                }
            }
            fclose($myfile);
        }
        catch (Exception $e) {
            echo $e->getMessage();
            $this->internalErrorResponse();
        }

        switch($parameters[0]) {
            case "people":
                echo json_encode($people);
                break;
            case "person":
                if (count($parameters) < 2) {
                    $this->badRequestResponse();
                    return;
                }
                foreach ($people as &$person) {
                    if (strpos($person->name, $parameters[1]) !== false) {
                        echo json_encode($person);
                        return;
                    }
                }
                break;
            default:
                $this->noContentResponse();
                break;
        }
    }

    public function performPost($headers, $parameters, $accept) {
        switch($parameters[0]) {
            case "person":
                // expected format name/nickname/age/role
                if (count($parameters) < 5) {
                    $this->badRequestResponse();
                    return;
                }
                // create the new person and write to file.
                $person = new Person($parameters[1], $parameters[2], $parameters[3], $parameters[4]);
                if (!$person->saveToFile($this->filePath)) {
                    $this->internalErrorResponse();
                }
                break;
            default:
                $this->noContentResponse();
                break;
        }
    }

    public function performPut($headers, $parameters, $accept) {
        $this->notImplementedResponse();
    }

    public function performDelete($headers, $parameters, $accept) {
        $this->notImplementedResponse();
    }

    private function notImplementedResponse() {
        header('Allow: ' . $this->supportedMethods, true , 501);
    }

    private function methodNotAllowedResponse() {
        header('Allow: ' . $this->supportedMethods, true, 405);
    }

    private function notFoundResponse() {
        header("HTTP/1.1 404 Not Found", true, 404);
    }

    private function noContentResponse() {
        header("HTTP/1.1 204 No Content", true, 204);
    }

    private function internalErrorResponse() {
        header("HTTP/1.1 500 Internal Server Error", true, 500);
    }

    private function badRequestResponse(){
        header("HTTP/1.1 400 Bad Request", true, 400);
    }
}