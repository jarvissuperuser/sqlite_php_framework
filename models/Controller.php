<?php
/**
 * Created by PhpStorm.
 * User: timothy
 * Date: 2018/05/19
 * Time: 3:35 PM
 */


/**
 * @author timothy
 */
class Controller
{
	/**
	 *
	 */
	public function __construct()
	{
	}
	/**
	 * @param array $vals
	 * @param array $cols
	 * @return array
	 */
	public static function valuate($vals,$cols){
		foreach($cols as $d){
			array_push($vals,filter_input(INPUT_POST, $d,
				FILTER_SANITIZE_FULL_SPECIAL_CHARS)==null?"":filter_input(INPUT_POST, $d,
				FILTER_SANITIZE_FULL_SPECIAL_CHARS));
		}
		return $vals;
	}
	/**
	 * @param void
     * @return string
     */
	public static function valuesToString($cols){
		$what ="";
		foreach($cols as $d){
			$what .= $d . "='".filter_input(INPUT_POST, $d,
					FILTER_SANITIZE_FULL_SPECIAL_CHARS)."',";
		}
		return substr($what,0 ,strlen($what)-1);
	}
	public static function setCols($cols){
		$what = [];
		foreach($cols as $d){
			$input = filter_input(INPUT_POST,$d,FILTER_SANITIZE_SPECIAL_CHARS);
			if ($input!=null ||$input!="")
				array_push($what,$d);
		}
		return $what;
	}
	public static function input($var,$default,$type){
		return  (filter_input(INPUT_POST,$var,$type)!=""or
		filter_input(INPUT_POST,$var,$type)!=null)
		?filter_input(INPUT_POST,$var,$type):$default;
	}

}
