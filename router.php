<?php
//autoload Segments.php 
/*
    function connect(){
        include "Segments.php";
    }

    spl_autoload_register("connect");
*/

include_once($_SERVER["DOCUMENT_ROOT"]."/views/Segments.php");

class Route{
    private $request;
    private $pages = [
        "about",
        "contact",
        "privacy-policy",
        "login",
        "sign-up"
    ];
    private $pdo;

    public function __construct($request){
        $this->request = $request;
    }

    public function inject($obj){
        $this->pdo = $obj;
    }

    private function resolve($req){
        Segments::header(); 

        $request = ltrim($req, "/");
         //if index page is requested
        if($request == "" || $request == "/"){   
           // Segments::trunk();
           // Segments::side();     
        }else if(in_array($request, $this->pages)){   
            include("views/$request.php");    
        } else {
            echo "<div class='main'>",
            $this->get_post($request),"</div>";
        }

        Segments::footer();
    }

    private function get_post($req){
        $stmt = $this->pdo->prepare("SELECT * FROM investors WHERE username=?");
        $stmt->execute([$req]);

        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        if(count($data)==0){
            (include_once("views/404.php"));
        } else {
            foreach ($data as $d){
               return $d->username;
            }
        }       
    }

    public function __destruct(){
        $this->resolve($this->request);
    }
}

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");

$request = $_SERVER["REQUEST_URI"];
$route = new Route($request);
$route->inject($pdo);