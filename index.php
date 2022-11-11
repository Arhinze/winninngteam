<?php

include_once($_SERVER["DOCUMENT_ROOT"]."/php/connection.php");

//ini_set("display_errors", 1);

include_once($_SERVER["DOCUMENT_ROOT"]."/classes/IndexSegments.php");

IndexSegments::main();

//Index_Segments::trunk();
//Index_Segments::side();  
//IndexSegments::footer();