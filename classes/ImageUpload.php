<?php
define ("MAX_SIZE", "2000");
class ImageUpload {
    public static function upload() {
        $image = $_FILES['image']['name'];
        if($image) {
            $filename = stripslashes($_FILES['image']['name']);
            $extension = self::getExtension($filename);
            $extension = strtolower($extension);

            if(($extension != "jpg") && ($extension != "jpeg") && ($extension !="png") && ($extension != "gif")) {
                //echo "<script>alert('WRONG FILE EXTENSION');</script>";
                return "error";
            } else {
                $size = filesize($_FILES['image']['tmp_name']);
                if($size > MAX_SIZE * 1024) {
                   return "error";
                }

                $image_name = Hash::unique() . '.' . $extension;
                $newname = "images/users/" . $image_name;
                $copied = copy($_FILES['image']['tmp_name'], $newname);
                if(!$copied) {
                    //echo "<script>alert('SOMETHING WENT WRONG');</script>";
                    return "error";
                } else {
                    return $image_name;
                }
            }
        } else {
            return NULL;
        }
    }

    private static function getExtension($str) {
        $i = strrpos($str,".");
        if (!$i) { return ""; }
        $l = strlen($str) - $i;
        $ext = substr($str,$i+1,$l);
        return $ext;
    }
}
 ?>