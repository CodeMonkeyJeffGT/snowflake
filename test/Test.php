<?php

include '../src/Snowflake.php';

$serialId = Snowflake::generateId();
var_dump($serialId);
printf("%64b\n", $serialId);
echo Snowflake::UNIX_MOVE . PHP_EOL . Snowflake::MACHINE_ID_MOVE . PHP_EOL . Snowflake::NUM_MOVE . PHP_EOL . Snowflake::PROCESS_ID_MOVE;