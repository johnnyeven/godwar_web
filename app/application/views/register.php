<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>注册</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="<?php echo site_url("register/submit"); ?>">
  <table width="500" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td width="50%" align="right">账号</td>
      <td width="50%"><label for="account_name"></label>
      <input type="text" name="account_name" id="account_name" /></td>
    </tr>
    <tr>
      <td align="right">密码</td>
      <td><input type="text" name="account_pass" id="account_pass" /></td>
    </tr>
    <tr>
      <td align="right">邮箱</td>
      <td><input type="text" name="account_email" id="account_email" /></td>
    </tr>
    <tr>
      <td><a href="login">登录</a></td>
      <td><input type="submit" name="button" id="button" value="提交" /></td>
    </tr>
  </table>
</form>
</body>
</html>