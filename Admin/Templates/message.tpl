<?php 
#################################################################################
##              -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 ##
## --------------------------------------------------------------------------- ##
##  Filename       message.tpl                                                 ##
##  Developed by:  Dzoki                                                       ##
##  License:       TravianX Project                                            ##
##  Copyright:     TravianX (c) 2010-2011. All rights reserved.                ##
##                                                                             ##
#################################################################################
?>
<style>
	.del {width:12px; height:12px; background-image: url(img/admin/icon/del.gif);} 
</style>
<link href="../gpack/travian_default/lang/en/compact.css?f4b7c" rel="stylesheet" type="text/css">
<table id="member" style="width:225px">
  <thead>
    <tr>
        <th colspan="2">IGM/Reports</th>
    </tr>
  </thead> 
    <tr>
        <td>IGM ID</td>
        <td><form action="" method="get"><input type="hidden" name="p" value="message"><input type="text" class="fm" name="nid" value="<?php echo $_GET['nid'];?>"> <input type="image" value="submit" src="../img/admin/b/ok1.gif"></form></td>
    </tr>
    <tr>
        <td>Report ID</td>
        <td><form action="" method="get"><input type="hidden" name="p" value="message"><input type="text" class="fm" name="bid" value="<?php echo $_GET['bid'];?>"> <input type="image" value="submit" src="../img/admin/b/ok1.gif"></form></td>
    </tr>
</table>
<br>

<?php
error_reporting(0);
if (isset($_GET['nid'])) 
{
	if (is_numeric($_GET['nid']))
	{
		include('msg.tpl');
	} 
	else 
	{
		echo '<font color="red">Provided value is not numeric!</font>';
	}
} 
else if (isset($_GET['bid'])) 
{
	if (is_numeric($_GET['bid'])) 
	{
		include('report.tpl');
	} 
	else 
	{
		echo '<font color="red">Provided value is not numeric!</font>';
	}
}
?>

