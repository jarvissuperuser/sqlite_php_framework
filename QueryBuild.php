<?php

/**
 * @author Timothy Tavonga Mugadza
 */
class QueryBuild
{
  /**
   * @var PDO connect
   */
  public $db;
  private $dsn0;
  private $dsn1;
  private $user;
  private $pwd;

  public function __construct()
  {
    try {
      $this->init();
    } catch (Exception $e) {
      $this->db = null;
      echo json_encode([$e,$e->getTraceAsString()]);
      //error_log($e);
    }
  }

  public function setdsn($con = 'mysql', $url = 'localhost') {
    $this->user = "cm9vdA=="; //base64 obfuscicated
    $this->pwd = "cm9vdDEyMzQ=";
    $this->dsn0 = "$con:host=$url;";
    $this->dsn1 = $this->dsn0 . 'name=smart;';
  }

	public function setdsn1($con = 'sqlite:/var/www/html/app.db', $url = 'localhost') {
		$this->user = "cm9vdA=="; //base64 obfuscicated
		$this->pwd = "cm9vdDEyMzQ=";
		$this->dsn0 = "$con";
		$this->dsn1 = $this->dsn0 . '';
	}

  /**
   * @param void $selection
   * @param void $table
   * @param void $what
   * @return string
   */
  public static function slct($selection, $table, $what) {
    // TODO: implement here
    $str = "SELECT ";
    $str .= QueryBuild::arrayJustify($selection,0) . " FROM " . $table;
    if ($what != null)
      $str .= " WHERE $what";
    return $str;
  }

  /**
   * @param string $table string
   * @param string $what string
   * @param string $id string
   * @return string
   */
  public static function update($table, $what, $id) {
    // TODO: implement here
    $str = "UPDATE $table SET ";
    $str .= "$what WHERE $id";
    return $str;
  }

  /**
   * support function for constructor
   * loads db and initiates 
   */
  public function init() {
    // TODO: implement here
    $this->setdsn1();
    if (!file_exists("ready.x")) {
      $this->db = new PDO($this->dsn0, base64_decode($this->user), base64_decode($this->pwd));
      $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = file_get_contents('smart.sql');
      $this->db->exec($sql);
      $fil = fopen("ready.x", 'w');
      fwrite($fil, $this->db->errorcode());
      fclose($fil);
    }
    $this->db = new PDO($this->dsn1, base64_decode($this->user), base64_decode($this->pwd));
		$this->db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
  }

  /**
   * @param void $tble string
   * @param void $cols not array...
   * @param void $vals can be array or string
   * @return string
   */
  public static function insert($tble, $cols, $vals) {
    // TODO: implement here
    $str = "INSERT INTO ";
    $str .= $tble . " (";
    $str .= QueryBuild::arrayJustify($cols,0) . " ) VALUES ( ";
    $str .= QueryBuild::arrayJustify($vals) . ")";
    return $str;
  }

  /**
   * @param void $obj depricate
   * @param integer $mode
   * @return string
   */
  public static function arrayJustify($obj,$mode = 1) {
    // TODO: implement here
    $res = "";
    if (is_array($obj)) {
      $res = " `{$obj[0]}` ";
      if ($mode == 1){
        $res = "'{$obj[0]}'";
      }
      $res .= QueryBuild::Justify($obj,$mode);
    } else {
      $res = $obj;
    }
    return $res;
  }

  /**
   * support function to arrayJustify
   * @param void $obj deprecate
   * @param integer $mode
   * @return string
   */
  public static function Justify($obj,$mode) {
    // TODO: implement here
    $res = "";
    $len = sizeof($obj);
    if ($obj[1] !== NULL) {
      for ($a = 1; $a < $len; $a++) {
        $res .= ($mode == 1)?",'{$obj[$a]}'":",`{$obj[$a]}`";
      }
    }
    return "$res";
  }

  /**
   *@param string $qry Query string
   *@return PDOStatement Description
   */
  public function transaction($qry) {
    // TODO: implement here
    return $this->db->prepare($qry);
  }

}
