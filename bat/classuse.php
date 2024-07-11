<?php
require_once("classa.php");

$start_time = date("Y-m-d H:i:s");
echo "PHP Script Start: $start_time\n";

$a = new ClassA();

echo "start\n";
$a->getValue();
echo "end\n";

$end_time = date("Y-m-d H:i:s");
echo "PHP Script End: $end_time\n";
?>
