<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>

<body>
<form action="<?php echo site_url("import/base_config_submit"); ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table width="500" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td width="50%" align="right">base_config</td>
      <td width="50%"><label for="baseConfig"></label>
      <input type="file" name="baseConfig" id="baseConfig" /></td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td><input type="submit" name="button" id="button" value="提交" /></td>
    </tr>
  </table>
</form>
<form action="<?php echo site_url("import/race_config_submit"); ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table width="500" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td width="50%" align="right">race_config</td>
      <td width="50%"><label for="raceConfig"></label>
      <input type="file" name="raceConfig" id="raceConfig" /></td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td><input type="submit" name="button" id="button" value="提交" /></td>
    </tr>
  </table>
</form>
<form action="<?php echo site_url("import/exp_config_submit"); ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table width="500" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td width="50%" align="right">exp_config</td>
      <td width="50%"><label for="expConfig"></label>
      <input type="file" name="expConfig" id="expConfig" /></td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td><input type="submit" name="button" id="button" value="提交" /></td>
    </tr>
  </table>
</form>
<form action="<?php echo site_url("import/monster_config_submit"); ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table width="500" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td width="50%" align="right">monster_config</td>
      <td width="50%"><label for="monsterConfig"></label>
      <input type="file" name="monsterConfig" id="monsterConfig" /></td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td><input type="submit" name="button" id="button" value="提交" /></td>
    </tr>
  </table>
</form>
<form action="<?php echo site_url("import/map_config_submit"); ?>" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <table width="500" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td width="50%" align="right">map_config</td>
      <td width="50%"><label for="mapConfig"></label>
      <input type="file" name="mapConfig" id="mapConfig" /></td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td><input type="submit" name="button" id="button" value="提交" /></td>
    </tr>
  </table>
</form>
</body>
</html>