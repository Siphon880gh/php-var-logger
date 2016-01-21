<?php
/*!/////////////////////////
 * RAPID DEBUG (PHP ONLY)
 * Debug in PHP with a simple syntax. Logs to a file so you aren't 
 * forced to break the website layout. Furthermore, AJAX calls
 * can easily be debugged.
 *
 * By Weng Fei Fung
 *
 * Date: 2015-04-28 T16:19Z
 * Version: 1
 */


////////////////////////////
// SECTION 1: Optional Init

$rdebug_filePtr = null;
$rdebug_tab = "";
$rdebug_utc_num = null;

function rdebug_utc($offset = null) {
    if(is_null($offset)) $offset = isset($_COOKIE['UTC'])?intval($_COOKIE['UTC']):-8;
    $rdebug_utc_num=$offset;
    /* At client side, you could:
       var nUTC=((new Date()).getTimezoneOffset() / -60);
       document.cookie = "UTC=" + nUTC + ";";
    */
    
    $is_DST = FALSE; // observing daylight savings?
    $timezone_name = timezone_name_from_abbr('', $offset * 3600, $is_DST);
    date_default_timezone_set($timezone_name);
}
	
function rdebug_new($fn="`RESULTS.log") {
	global $rdebug_filePtr;
	
	//empty any existing file
	$rdebug_filePtr = fopen($fn, "w");
	fwrite($rdebug_filePtr, "");
	fclose($rdebug_filePtr);
	
	//start appending future logs
	$rdebug_filePtr = fopen($fn, "a");
}

function rdebug_append($fn="RESULTS.log") {
	global $rdebug_filePtr;
	$rdebug_filePtr = fopen($fn, "a");
}

////////////////////////
// SECTION 2: Functions

function rdebug_stamp() {
    global $rdebug_utc_num;
    if(is_null($rdebug_utc_num)) rdebug_utc();
    $stamp="";
    $words = preg_split('//', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', -1);
    $nums = preg_split('//', '0123456789', -1);
    $wordsF="";
    $numsF="";
    shuffle($words);
    shuffle($nums);
    foreach($words as $word) $wordsF.= $word;
    foreach($nums as $num) $numsF.= $num;
    $stamp.= substr($wordsF, 0, rand(2, 3));
    $stamp.= " ";
    $stamp.= substr($numsF, 0, rand(1, 3));
    $stamp.= "\n";
    $strTime = date('m-d-Y g:i a');
    if($strTime) $stamp.=$strTime."\n";
    rdebug($stamp);
} // rdebug_stamp

function rdebug($var, $strA=null, $strB=null, $strC=null) {
	global $rdebug_filePtr, $rdebug_tab;
    if($rdebug_filePtr==null) rdebug_new();
	$arr = array($strA, $strB, $strC);
    $str = "";
	
	foreach($arr as $val) {
		if (gettype($val)=="boolean" && $val==true) 
			$var=var_export($var, true);
	}
    
	try {
        $str .= $var;
    } catch (Exception $e) {
        fwrite($rdebug_filePtr, "Rdebug: You passed an array into rdebug but it only takes in data types like string, boolean, int, float, etc that are not multidimensional. Consider using rdebug\'s var export by passing true as one of the parameters after the variable.\n");
    }
    
	foreach($arr as $val) {
        if (gettype($val)=="boolean") continue;
		if(is_null($val)) continue;
		if($val[0]=='^') $str=substr($val, 1)." ".$str;
		else if($val[strlen($val)-1]=='$') $str=$str." ".substr($val,0, strlen($val)-1);
		else $str=$val." ".$str;
	}
	fwrite($rdebug_filePtr, $rdebug_tab . $str . "\n\n");
}

function rdebug_group($str) {
    global $rdebug_tab, $rdebug_filePtr;
    if($rdebug_filePtr==null) rdebug_new();
    $rdebug_tab = "\t";
    fwrite($rdebug_filePtr, "**" . $str . "**\n");
}

function rdebug_groupEnd() {
    global $rdebug_tab;
    $rdebug_tab = "";
}

function rdebug_ajax() {
    rdebug_group("Session Variables");
        rdebug($_SESSION, "Session variables are:", true);
    rdebug_groupEnd();
    
    rdebug_group("Get Parameters");
        rdebug($_GET, "Get parameters are:", true);
    rdebug_groupEnd();
    
    rdebug_group("Post Parameters");
        rdebug($_POST, "Post parameters are:", true);
    rdebug_groupEnd();
    
    rdebug_group("Put/Patch/Update/Delete Parameters");
    	$arr = array();
        if (($stream = fopen('php://input', "r")) !== FALSE) {
           $str_prm = stream_get_contents($stream);
           parse_str($str_prm, $arr);
        }
        rdebug($arr, "These are the parameter(s):", true);
    rdebug_groupEnd();

} // rdebug_ajax

function rdebug_vars() { // pass true to see console too
    $arr = $_SERVER; // which includes GET, POST, cookies
    if (($stream = fopen('php://input', "r")) !== FALSE) { // which includes PUT, PATCH, UPDATE, DELETE
        $arr_ = array();
        $str_prm = stream_get_contents($stream);
        parse_str($str_prm, $arr_);
        $arr = array_merge($arr, $arr_);
    }
    if(!isset($_SESSION)) $_SESSION = array();
    $arr = array_merge($arr, $_SESSION, $GLOBALS, get_defined_vars()); // session, global, and local variables
    
    $strQ = json_encode(str_replace(array("\n", "\r"), "", (str_replace(array("\""), "\\\"", @var_export($arr, true)))));
    $strQ = str_replace(array("\""), "\\\"", $strQ);
    $strQ = str_replace(array("'"), "\\\"", $strQ);
    $strQ = str_replace(array("=>"), ":", $strQ);
    $strQ = str_replace(array("array ( "), "{", $strQ);
    if($strQ[0]=='\"') $strQ = substr($strQ, 1);
    $strQ = substr($strQ, 2);
    $strQ = rtrim($strQ, "\\\"");
    
    if(func_num_args()>0) {
        if(func_get_arg(0)==true) {
            $expl = "%cHere are all server variables, session variables, method parameters (eg. \$_POST[\"someVar\"]), global variables, and local variables. This is incorrect JSON due to how PHP parses its internal representations. To add spacing and tabs, validate the following text at JSON Lint:   %c";
            $str = "<script>$(function() { console.log(\"";
            $str.= $expl . $strQ . "\", 'font-weight:600', 'font-weight:normal'); });</script>"; 
            echo $str;
        }
    }
    
    rdebug_group("Server variables, session variables, method parameters (eg. \$_POST[\"someVar\"]), global variables, and local variables");
        rdebug($strQ, "To add spacing and tabs, validate the following text at JSON Lint (without the enclosing quotes):\n", true);
    rdebug_groupEnd();
}

function rdebug_string_path() { // pass true to see console too
    global $rdebug_filePtr;
    $meta_data = stream_get_meta_data($rdebug_filePtr);
    $filename = $meta_data["uri"];
    $str ="RDEBUG: FROM " . "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" .  ", SAVE LOG AS " . $filename;
    
    
    if(func_num_args()>0) {
        if(func_get_arg(0)==true) {
            echo "<script>$(function() { console.info(\"" . $str . "\"); });</script>";
        }
    }
    
    return $str;
}

?>