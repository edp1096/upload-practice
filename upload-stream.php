<?php
// CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Accept, X-Custom-Header, Upgrade-Insecure-Requests");

$dataTmpPath = "data_tmp";

$json = file_get_contents('php://input');
$fileData = json_decode($json, true);
$fileContent = base64_decode($fileData["content"]);

if (!file_exists(getcwd() . "/" . $dataTmpPath)) {
    mkdir(getcwd() . "/" . $dataTmpPath, 0644);
}

if ($fileData["name"] != null and $fileData["type"] != null) {
    $fname = pathinfo($fileData["name"], PATHINFO_FILENAME);
    $fext = strtolower(pathinfo($fileData["name"], PATHINFO_EXTENSION));
    $filenameTMP = $fname . '_' . uniqid() . '.' . $fext;

    file_put_contents(getcwd() . "/" . $dataTmpPath . "/" . $filenameTMP, $fileContent);
}

$result = array(
    "status" => "done",
    "type" => $fileData["type"],
    "name" => $fileData["name"],
    "tmp_name" => $filenameTMP
);

echo json_encode($result);
