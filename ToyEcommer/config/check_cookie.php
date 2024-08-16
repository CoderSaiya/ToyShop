<?php
header('Content-Type: application/json');

if (isset($_COOKIE['username'])) {
    echo json_encode(["status" => "exists"]);
} else {
    echo json_encode(["status" => "missing"]);
}