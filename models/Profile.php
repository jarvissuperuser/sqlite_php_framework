<?php
/**
 * Created by PhpStorm.
 * User: timothy
 * Date: 2018/05/19
 * Time: 2:43 PM
 */

class Profile extends BaseModel
{
	/**
	 * @param QueryBuild $db
	 */
	public function __construct($db)
	{
		$this->db = $db;
		$this->tbls = ["user_profile","user_relationship"];
		$this->cols = [["type","uid","institution"],
			["uid1","uid2","relationship"]];
	}
	public function add()
	{
		// TODO: test Cases
		$db = $this->db;
		$vls = Controller::valuate([],$this->cols[0]);
		$vls2 = Controller::valuate([],$this->cols[1]);
		if ($this->add_to_db($vls,0)) {
			$this->add_to_db($vls2, 1);
			array_pop($vls2);
			echo json_encode([$vls, $vls2]);
		} else{
			echo json_encode(["error"=>"failed user_profile add"]);
		}
	}

	/**
	 *
	 */
	public function get()
	{
		// TODO: Test Cases
		$pntr = filter_input(INPUT_POST,"profileid");
		$what = "uid=$pntr";
		$data=$this->get_from_db($what,0,1,0);
		echo json_encode($data);
	}

	/**
	 * @param integer $table_pointer
	 * @throws Exception
	 * @return array
	 */
	public function update($table_pointer)
	{
		// TODO: TestCases

		$setCol = Controller::setCols($this->cols[$table_pointer]);
		$values = Controller::valuesToString($setCol);
		$id = filter_input(INPUT_POST,"userid",FILTER_SANITIZE_NUMBER_INT);
		if (sizeof($setCol)==0)throw new Exception("No data Set");
		$qry = QueryBuild::update($this->tbls[$table_pointer],$values,"id=$id");
		$this->db->transaction($qry)->execute();
		return ["message"=>"details updated","success"=>true];
	}

	public function update_all(){
		$this->update(0);
		$results = $this->update(1);
		echo json_encode([$results]);

	}

	public function search(){
		$p = filter_input(INPUT_POST,"query");
		$tp = filter_input(INPUT_POST,"type");
		$start = Controller::input("start",0,FILTER_SANITIZE_NUMBER_INT);
		$limit = Controller::input("limit",10,FILTER_SANITIZE_NUMBER_INT);
		$what = "(fullname LIKE '%$p%') AND type='$tp' ";
		$data = $this->get_from_db($what, $start, $limit,2);
		$final = [];
		foreach ($data  as $record){
			$record['pass_key'] = "*";
			array_push($final,$record);
		}
		echo json_encode($final);
	}

	/**
	 *
	 */
	public function remove()
	{
		// TODO: implement here
	}

	/**
	 * @param integer $start
	 * @param integer $count
	 */
	public function get_multiple($start, $count)
	{
		// TODO: implement here
	}




}
