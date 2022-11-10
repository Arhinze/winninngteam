<?php

Class Transactions{
    private $amt;
    private $obj;
  
    /*
    public function __construct($amt){
        $this->amt = $amt ;
    }
    */

    public function rate($amt){
        $r = "";
        if(($amt >= 100) && ($amt < 5000)){
            //$r = 1.5;
            $r = 2;
        } else if (($amt >= 5000) && ($amt < 10000)){
            //$r = 2;
            $r = 2.5;
        } else if (($amt >= 10000) && ($amt < 20000)){
            $r = 3;
        } else if ($amt >= 20000){
            $r = 4;
        }

        return $r;
    }

    public function num_of_days($amt){
        $d = 0;
        if(($amt >= 100) && ($amt < 5000)){
            $d = 7;
        } else if (($amt >= 5000) && ($amt < 10000)){
            $d = 7;
        } else if (($amt >= 10000) && ($amt < 20000)){
            //$d = 6;
            $d = 7;
        } else if ($amt >= 20000){
            //$d = 30;
            $d = 7;
        }

        return $d;
    }

    public function inject($obj){
        $obj = $this->obj;
    }
}

$transactions = new Transactions;

?>