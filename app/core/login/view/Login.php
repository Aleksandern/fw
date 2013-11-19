<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru">
<head>
<title>Login</title>
</head>
<body>
{login_err}
<form action="" method="post" enctype="multipart/form-data" >

<input type="text" name="login" value="" placeholder="{lang_m_login}" >
<br>
<input type="text" name="passw" value="" placeholder="{lang_m_password}" >
<br>
<input type="checkbox" name="save" id="auth_save" > <label for="auth_save">{lang_m_save_passw}</label>
<br>
<br>
<input type="hidden" name="form" value="" >
<input type="submit" name="submit" value=" {lang_m_submit} ">
</form>
<?php
?>
</body>
</html>
