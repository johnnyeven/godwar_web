<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php echo $meta_data; ?>
<title>无标题文档</title>
</head>

<body>
<p><?php echo $return_content; ?></p>
<p>type: <?php echo $type; ?></p>
<p>info: <?php echo $info; ?></p>
<p>message: <?php echo urldecode($message); ?></p>
<p>redirect: <?php echo $redirect; ?></p>
<p>autoRedirect: <?php echo $autoRedirect; ?></p>
<p>autoDelay: <?php echo $autoDelay; ?></p>
</body>
</html>