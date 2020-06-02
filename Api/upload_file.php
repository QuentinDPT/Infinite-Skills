<?php
$name= $_FILES['file']['name'];

$tmp_name= $_FILES['file']['tmp_name'];

print_r($_FILES);
print_r($_POST);

$tabName = explode(".", $name);

$fileExtension= end($tabName);
$fileExtension= strtolower($fileExtension);


if (isset($name)) {

    $path= $_SERVER['DOCUMENT_ROOT']."/videos/";
    if (!empty($name)){
        if ($fileExtension !== "mp4" && $fileExtension !== "ogg" && $fileExtension !== "webm")
        {
            die();
        }
        else{
            $date = date('Y-m-d', time());
            $value = rand(0, 10000);
            if (move_uploaded_file($tmp_name, $path.$date.$value.$name)) {
                echo 'Uploaded!';
            }
        }
    }else{
        die();
    }
}
