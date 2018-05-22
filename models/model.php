<?php
/**
 * Created by PhpStorm.
 * User: timothy
 * Date: 2018/05/19
 * Time: 4:49 PM
 */

class BaseModel {


	/**
	 * @var array
	 */
	public $tbls;

	/**
	 * @var array
	 */
	public $cols;

	/**
	 * @var array
	 */
	public $muted;
	/**
	 * @var QueryBuild
	 */
	public $db;

	/**
	 * @param void $values
	 * @param void $cols
	 * @return boolean
	 */
	protected function add_to_db($values,$cols)
	{
		$db = $this->db;
		$qry = QueryBuild::insert($this->tbls[$cols],$this->cols[$cols],$values);
		$db->transaction($qry)->execute();
		return true;
	}

	protected function update_db($values,$cols,$id)
	{
		$db = $this->db;
		$qry = QueryBuild::update($this->tbls[$cols],$values,$id);
		$db->transaction($qry)->execute();
		return true;
	}

	protected function get_from_db($what,$start,$limit,$pointer)
	{
		$db = $this->db;
		$qry = QueryBuild::slct("*",$this->tbls[$pointer],
			"$what limit $limit offset $start");
		$st = $db->transaction($qry);
		$st->execute();
		return $st->fetchAll(PDO::FETCH_ASSOC);
	}

	protected function record_check($pointer, $target){
		$db = $this->db;
		$selection = "count(*) as c";
		$tbl = $this->tbls[$pointer];//table to query
		$email = filter_input(INPUT_POST,"$target",FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$what = "$target='$email'";//or phone='$phone'";
		$qry = $db->slct($selection, $tbl, $what);
		$st = $db->db->query($qry);
		return [$st->fetchObject()->c,$st];
	}
	
	protected function mute(&$arr){
		foreach($this->muted as $d)
			$a[$d] = "";
	}
	
	protected function silence($arr){
		$newArray = [];
		foreach($arr as $d){
			$val = true;
			foreach($this->muted as $m){
				if ($m == $d){
					$val = false;
					break;
				}
			}
			if ($val) array_push($newArray,$d);
		}
	}
}
