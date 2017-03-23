<?php
require_once "../vendor/autoload.php";
require_once "../src/DrTeam/PCRecruiter/PCRecruiter.php";

$methods = get_class_methods(new \DrTeam\PCRecruiter\PCRecruiter());
foreach ($methods as $method) echo $method."\n";