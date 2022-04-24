<?php
    $memcache = new Memcache();
    $memcache->connect("localhost", 11211);
    $array = array("Ciao"=>2, "wow"=>10);

    $memcache->set('8e0sjc8cdasf8otd30qu05qphl', $array, false, 20) or die("Unable to set");