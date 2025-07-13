<?php
session_start();
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['username']))
{
    $_SESSION['username'] = $_POST['username'];
}
if(isset($_SESSION['username']))
{
    header("Location: todo.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-do list</title>
</head>
<body>
    <form action="index.php" method="post">
        <label for="user">Username:</label>
        <input type="text" name="username" id="user" required><br>
        <input type="submit" name="login" value="Login">
    </form>
</body>
</html>
