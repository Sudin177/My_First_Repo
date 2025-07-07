<?php    
   if (isset($_POST["login"]))
   {
    header("Location: http://localhost/demo/todo_List.php");
   }
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="todo_list.php" method="post">
    <label>Username:</label>
    <input type="text" name="username"><br>
    <button name="login">Login</button>
    </form>
</body>
</html>
