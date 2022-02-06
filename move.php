<?php
// CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Accept, X-Custom-Header, Upgrade-Insecure-Requests");

$tmpPath = str_replace("\\", "/", getcwd() . "/" . "data_tmp");
$path = str_replace("\\", "/", getcwd() . "/" . "data");

$json = file_get_contents('php://input');
$moveData = json_decode($json, true);

$fileNames = array();

if (!file_exists($path)) {
    mkdir($path, 0644);
}

foreach ($moveData as $k => $v) {
    // $f = json_decode($v, true);
    $f = $v;
    $fname = pathinfo($f["name"], PATHINFO_FILENAME);
    $fext = strtolower(pathinfo($f["name"], PATHINFO_EXTENSION));
    // $filename = $fname . '_' . uniqid() . '.' . $fext;
    $dbname = $fname . '-' . uniqid() . '.' . $fext;
    rename($tmpPath . "/" .  $f["tmp_name"], $path . "/" . $dbname);

    $fileNames[] = array(
        "filename" => $f["name"],
        "dbname" => $dbname,
        "type" => $f["type"]
    );
}

$result = array(
    "result" => "done",
    // "info" => $fileNames
);

echo json_encode($result, JSON_UNESCAPED_UNICODE);
