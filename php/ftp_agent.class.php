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
    $this->conn = new \phpseclib\Net\SFTP(getenv('FTP_HOST'));

    $login_result = $this->conn->login(getenv('FTP_USER'), getenv('FTP_PASS'));
    $this->conn->chdir($this->root_dir);
    // Vérification de la connexion
    if ((!$this->conn) || (!$login_result)) {
      return false;
    } else {
      $this->conn->chdir($this->root_dir);
      return true;
    }
  }

  public function disconnect(){
    $this->conn->close();
  }

  //Folders
  public function mkdir($dir){
    $result = $this->conn->mkdir($this->conn, $dir);
    $this->conn->chmod(0777, $dir);
    if($result) {return true;}
    else {return false;}
  }

  public function dirExists($dir){
    $dir = $dir.'/';
    if ($this->conn->chdir($dir)) {
      return true;
    }else{
      return false;
    }
  }

  public function removeFolder($dir){
    $dir = $dir.'/';
    var_dump($this->root_dir.$dir);
    $this->cleanFolder($dir);
    $result = $this->conn->rmdir($dir);

    if($result) {return true;}
    else {return false;}
  }

  public function cleanFolder($dir){ //remove all files from a folder
    $fichiers = $this->conn->nlist($dir);
    var_dump($fichiers);

    foreach($fichiers as &$fichier){
      $this->removeFile($dir.$fichier);
    }
  }

  //files
  public function fileExists($fileName,$dir){
    $contents_on_server = $this->conn->nlist($dir);
    if(in_array($fileName,$contents_on_server)){
      return true;
    }else{
      return false;
    }
  }

  public function removeFile($dir){
    $result = $this->conn->delete($dir);
    if($result) {return true;}
    else {return false;}
  }

  public function sendFile($fileDir,$newDir){
    $upload = $this->conn->put($fileDir, $newDir);

    // Vérification du status du chargement
    if (!$upload) {
        return false;
    } else {
        return true;
    }
  }

  public function downloadFile($fileDir,$newDir){
    $result = $this->conn->get($fileDir, $newDir);
    if($result) {return true;}
    else {return false;}
  }

  public function listFiles($fileDir)
  {
      return $this->conn->nlist($fileDir);
  }

}



 ?>
