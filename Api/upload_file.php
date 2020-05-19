<?php
$name= $_FILES['file']['name'];

$tmp_name= $_FILES['file']['tmp_name'];

print_r($_FILES);
// $position= strpos($name, ".");
//
// $fileextension= substr($name, $position + 1);
//
// $fileextension= strtolower($fileextension);


// if (isset($name)) {
//
//     $path= $_SERVER['DOCUMENT_ROOT']."/videos/";
//     if (empty($name))
//     {
//         echo "Please choose a file";
//     }
//     else if (!empty($name)){
//         if ($fileextension !== "mp4")
//         {
//             echo "The file extension must be .mp4, .ogg, or .webm in order to be uploaded";
//         }
//
//
//         else if (($fileextension == "mp4"))
//         {
//             if (move_uploaded_file($tmp_name, $path.$name)) {
//                 echo 'Uploaded!';
//             }
//         }
//     }
// }
