<?php
/**
 * Created by PhpStorm.
 * User: timothy
 * Date: 2018/05/19
 * Time: 2:43 PM
 */

class User extends BaseModel
{
	/**
	 * @param QueryBuild $db
	 */
	public function __construct($db)
	{
		$this->db = $db;
		$this->tbls = ["user","user_details","user_list"];
		$this->cols = [["name","middle_name","surname","type","flag"],
			["uid","date_of_birth","national_id",
			"nationality","gender","flag","cell","tel","email","password"]];
	}
	public function add()
	{
		// TODO: test Cases
		$db = $this->db;
		$user_reg = $this->record_check(1,"email");
		if ($user_reg[0]>0) throw new Exception("User Exists");
		$vls = Controller::valuate([],$this->cols[0]);
		$vls2 = Controller::valuate([],$this->cols[1]);
		$vls2[sizeof($vls2) - 1] = hash("SHA256", filter_input(INPUT_POST,"password",
			FILTER_SANITIZE_SPECIAL_CHARS));
		if ($this->add_to_db($vls,0)) {
			$vls2[0] = $db->db->lastInsertId("id");
			$this->add_to_db($vls2, 1);
			array_pop($vls2);
			echo json_encode([$vls, $vls2]);
		} else{
			echo json_encode(["error"=>"failed user add"]);
		}
	}

	/**
	 *
	 */
	public function get()
	{
		// TODO: Test Cases
		$pntr = filter_input(INPUT_POST,"userid");
		$what = "id=$pntr";
		$data=$this->get_from_db($what,0,1,0);
		echo json_encode([$data]);
	}

	/**
	 * @param integer $table_pointer
	 * @throws Exception
	 * @return array
	 */
	public function update($table_pointer)
	{
		// TODO: implement here

		$setCol = Controller::setCols($this->cols[$table_pointer]);
		$values = Controller::valuesToString($setCol);
		$id = filter_input(INPUT_POST,"userid");
		if (sizeof($setCol)==0)throw new Exception("No data Set");
		$qry = QueryBuild::update($this->tbls[$table_pointer],$values,"id=$id");
		$this->db->transaction($qry)->execute();
		echo ["message"=>"details updated","success"=>true];
	}

	public function search(){
		$p = filter_input(INPUT_POST,"query");
		$tp = filter_input(INPUT_POST,"type");
		$start = (filter_input(INPUT_POST,"start",FILTER_SANITIZE_NUMBER_INT)!=""or
		filter_input(INPUT_POST,"start",FILTER_SANITIZE_NUMBER_INT)!=null)
		?filter_input(INPUT_POST,"start",FILTER_SANITIZE_NUMBER_INT):0;
		$limit = (filter_input(INPUT_POST,"limit",FILTER_SANITIZE_NUMBER_INT)!=""or
			filter_input(INPUT_POST,"limit",FILTER_SANITIZE_NUMBER_INT)!=null)
			?filter_input(INPUT_POST,"limit",FILTER_SANITIZE_NUMBER_INT):10;
		$what = "(fullname LIKE '%$p%') AND type='$tp' ";
		$data = $this->get_from_db($what, $start, $limit,2);
		$final = [];
		foreach ($data  as $record){
			$record['password'] = "*";
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
