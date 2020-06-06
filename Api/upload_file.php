<?php
class UploadFile {
    public static function exec() {
        $name= $_FILES['file']['name'];

        $tmp_name= $_FILES['file']['tmp_name'];

        $tabName = explode(".", $name);

        $fileExtension= end($tabName);
        $fileExtension= strtolower($fileExtension);


        if (isset($name)) {
            $path = $_SERVER['DOCUMENT_ROOT']."/videos/";
            if (!empty($name)){
                if ($fileExtension !== "mp4" && $fileExtension !== "ogg" && $fileExtension !== "webm"){
                    die();
                }
                else{
                    $date = date('Y-m-d', time());
                    $value = rand(0, 10000);
                    $fileName = $date.$value.$name;
                    if (move_uploaded_file($tmp_name, $path.$fileName)) {
                        $videoPath = "videos/$fileName";
                        return $videoPath;
                    }
                    else{
                        header("Location : /home");
                        die();
                    }
                }
            }
            else{
                die();
            }
        }
    }
}
