<?php
/*
* 预防数据库攻击
*/
function check_input($value)
{
	// 去除斜杠
	if (get_magic_quotes_gpc())
	{
		$value = stripslashes($value);
	}
	// 如果不是数字则加引号
	if (!is_numeric($value))
	{
		$value = "'" . mysql_real_escape_string($value) . "'";
	}
	return $value;
}

?>