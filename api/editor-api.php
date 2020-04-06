<?php
header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers: X-Requested-With, Authorrization, content-type, access-control-allow-origin, access-control-allow-methods, access-control-allow-headers");

define( 'post', json_decode(file_get_contents('php://input'), true) );

var_dump(post);