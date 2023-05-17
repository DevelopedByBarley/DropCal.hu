<?php

/*
  ?How to use?
  required_once app/helpers/FileSaver.php;
  $fileSaver = new FileSaver();
  $fileSaver->saver($_FILE["files"], "/path", null || "asdwasdasd.jpg" || [
    asdsadadasd.jpg, asdasds.jpg
  ]) 
*/

class FileSaver
{

  public function saver($files, $path, $prevImages)
  {

    $this->unlinkPrevImages($prevImages, $path);

    if (empty($files["name"])) return false;;
    if (is_array($files["name"])) {
      return $this->saveMultipleFiles($files, $path);
    }
    return $this->save($files, $path);
  }

  private function saveMultipleFiles($files, $path)
  {
    $ret = [];
    $fileNames = [];

    foreach ($files as $fieldName => $fields) {

      foreach ($fields as $index => $field) {
        $ret[$index][$fieldName] = $fields[$index];
      }
    }

    foreach ($ret as $file) {
      $fileName =  $this->save($file, $path);
      $fileNames[] = $fileName;
    }

    return $fileNames;
  }

  private function save($file, $path)
  {

    $whiteList = [IMAGETYPE_JPEG, IMAGETYPE_GIF, IMAGETYPE_PNG];
    if (!in_array(exif_imagetype($file["tmp_name"]), $whiteList)) return false;

    $rand = uniqid(rand(), true);
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $originalFileName = $rand . '.' . $ext;
    $directoryPath = "./public/assets/$path/";

    $destination = $directoryPath . $originalFileName;
    move_uploaded_file($file["tmp_name"], $destination);
    return $originalFileName;
  }

  private function unlinkPrevImages($prevImages, $path)
  {
    if (isset($prevImages)) {
      if (is_array($prevImages)) {
        foreach ($prevImages as $images) {
          var_dump($images);
          unlink("./public/assets/$path/" . $images);
        }
        exit;
      } else {
        unlink("./public/assets/$path/" . $prevImages);
      }
    };
  }
}
