<?php
error_reporting(E_ALL);
$db = 'test';  // old db
$db_2 = "test2"; //new db 

$MYSQL_HOST="localhost";
$MYSQL_USER="root";
$MYSQL_PASS="";

 
$conn = mysql_connect($MYSQL_HOST,$MYSQL_USER,$MYSQL_PASS);
$sql_tb2 = "select TABLE_NAME from `information_schema`.`TABLES` where TABLE_SCHEMA = '$db_2' ";
$res_db2=mysql_query($sql_tb2);
while($row = mysql_fetch_array($res_db2))
{
	$sql_insert = tbcmp($db,$row["TABLE_NAME"],$db_2,$row["TABLE_NAME"]);
	echo "<br/>\r\n";
	echo $sql_insert . ";";
}
mysql_close($conn);

function tbcmp($db,$tb,$db_2,$tb_2)
{
	$sql = "select `COLUMN_NAME` from `information_schema`.`COLUMNS` where `TABLE_SCHEMA` = '$db_2'	and `TABLE_NAME` = '$tb_2'  ";
	//echo $sql;
	$res = mysql_query($sql);
	 
	$sql_col = "";
	while($row = mysql_fetch_assoc($res))
	{
		
		$sql_cmp = "select COLUMN_NAME from information_schema.COLUMNS where `TABLE_SCHEMA` = '$db'	and `TABLE_NAME` = '$tb' and COLUMN_NAME ='$row[COLUMN_NAME]' ";
		//echo $sql_cmp;
		//echo "<br/>\r\n";
		$result = mysql_query($sql_cmp);
		if(mysql_num_rows($result) != 0 )
		{
			$sql_col .= "`".$row["COLUMN_NAME"]."`,"  ; 
		}else{

			//echo $row["COLUMN_NAME"] . " not exists in old table = `$db`.`$tb` ";
			//echo "<br/>\r\n";
		}



	}
	$sql_col = rtrim($sql_col,",");

	$sql_new = "insert into `$db_2`.`$tb_2` (" . $sql_col .")   select  " . $sql_col . " from `$db`.`$tb`";

	return  $sql_new;
	 

}


