<?php

http_response_code(201);

header('Some-Header: some_value');
header('One-More-Header: another_value');
$a = 1;

echo 'Hello from PHP2';