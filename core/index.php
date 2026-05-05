<?php 

$cores = scandir(__DIR__);
foreach ($cores as $core) {
    if(preg_match('/.php/', $core, $matches)) {
        if ($core != 'index.php') {
            require_once "core/$core";
        }
    }
}

