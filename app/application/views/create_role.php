<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>创建角色</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="<?php echo site_url("create_role/submit"); ?>">
  <table width="500" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td width="50%" align="right">角色名</td>
      <td width="50%"><label for="role_name"></label>
      <input type="text" name="role_name" id="role_name" /></td>
    </tr>
    <tr>
      <td align="right">种族</td>
      <td><label for=""></label>
        <select name="role_race" id="role_race">
          <option value="" selected="selected">未选择</option>
          <option value="01001">人类</option>
          <option value="01002">天使</option>
          <option value="01003">恶魔</option>
          <option value="01004">精灵</option>
          <option value="01005">亡灵</option>
          <option value="01006">泰坦</option>
      </select></td>
    </tr>
    <tr>
      <td align="right">&nbsp;</td>
      <td><input type="submit" name="button" id="button" value="提交" /></td>
    </tr>
  </table>
</form>
</body>
</html>