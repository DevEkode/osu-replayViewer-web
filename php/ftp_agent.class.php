<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/startup.php';

class ftp_agent{

  private $conn;
  private $root_dir;

  public function __construct()
  {

  }

  //Connect to ftp server
  public function connect(){
    $this->root_dir = getenv('FTP_DIR');
    $this->conn = ftp_connect(getenv('FTP_HOST'));

    $login_result = ftp_login($this->conn, getenv('FTP_USER'), getenv('FTP_PASS'));
    ftp_pasv($this->conn, true);

    // Vérification de la connexion
    if ((!$this->conn) || (!$login_result)) {
      return false;
    } else {
      ftp_chdir($this->conn, $this->root_dir);
      return true;
    }
  }

  public function disconnect(){
    ftp_close($this->conn);
  }

  //Folders
  public function mkdir($dir){
    $result = ftp_mkdir($this->conn, $dir);
    ftp_chmod($this->conn, 0777, $dir);
    if($result) {return true;}
    else {return false;}
  }

  public function dirExists($dir){
    $dir = $dir.'/';
    if (ftp_chdir($this->conn, $dir)) {
      return true;
    }else{
      return false;
    }
  }

  public function removeFolder($dir){
    $dir = $dir.'/';
    var_dump($this->root_dir.$dir);
    $this->cleanFolder($dir);
    $result = ftp_rmdir($this->conn, $dir);

    if($result) {return true;}
    else {return false;}
  }

  public function cleanFolder($dir){ //remove all files from a folder
    $fichiers = ftp_nlist($this->conn, $dir);
    var_dump($fichiers);

    foreach($fichiers as &$fichier){
      $this->removeFile($dir.$fichier);
    }
  }

  //files
  public function fileExists($fileName,$dir){
    $contents_on_server = ftp_nlist($this->conn, $dir);
    if(in_array($fileName,$contents_on_server)){
      return true;
    }else{
      return false;
    }
  }

  public function removeFile($dir){
    $result = ftp_delete($this->conn, $dir);
    if($result) {return true;}
    else {return false;}
  }

  public function sendFile($fileDir,$newDir){
    $upload = ftp_put($this->conn, $newDir, $fileDir, FTP_BINARY);

    // Vérification du status du chargement
    if (!$upload) {
        return false;
    } else {
        return true;
    }
  }

  public function downloadFile($fileDir,$newDir){
    $result = ftp_get($this->conn, $newDir, $fileDir, FTP_BINARY);
    if($result) {return true;}
    else {return false;}
  }

  public function listFiles($fileDir)
  {
    return ftp_mlsd($this->conn, $fileDir);
  }

}



 ?>
