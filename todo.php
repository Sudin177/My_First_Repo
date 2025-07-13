<?php
require "db.php";
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: http://localhost/demo/index.php");
    exit();
}
$username = htmlspecialchars($_SESSION['username']);
if (!isset($_SESSION['todolist'][$username])) {
    $_SESSION['todolist'][$username] = [];
}
setcookie("username",$username,time() + 3600,"/");
//Adding a task
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add']))
{
    $task = trim($_POST['task']);
    if($task !=='')
    {
        $_SESSION['todolist'][$username][] = $task;
    }
}

//removing a task
if(isset($_GET['delete']))
{
    $index =(int)$_GET['delete'];
    if(isset($_SESSION['todolist'][$username][$index]))
    {
        unset($_SESSION['todolist'][$username][$index]);
        $_SESSION['todolist'][$username]=array_values($_SESSION['todolist'][$username]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php if(isset($_COOKIE['username']))
    {
        echo "Hello " . $_COOKIE["username"];
    }?>
    <h2>To-do List</h2>
    <form method="post" action="todo.php">
        <label for="task">Add a task:</label><br>
        <input type="text" name="task" id="task" >
        <input type="submit" name="add" value="Add">
    </form>
    <ul>
    <?php foreach ($_SESSION['todolist'][$username] as $index=> $todo):?>
        <li>
        <?php echo htmlspecialchars($todo);?>
        <a href="todo.php?delete=<?php echo $index?>">Delete</a><br>
        </li>
        <?php endforeach;?>
        </ul>
        <form action="todo.php" method="post">
        <input type="submit" name="push" value="Push to database"><br><br>
        <input type="submit" name="logout" value="Logout">
        </form>
</body>
</html>
<?php 
if(isset($_POST['push']))
{
    if(!empty($_SESSION['todolist'][$username]))
    {
        $stmt =$pdo->prepare("INSERT INTO Tasks(Username ,task) VALUES (:username ,:task) ");
        foreach($_SESSION['todolist'][$username] as $task)
        {
            if (!isset($_SESSION['pushed data'][$username]) || !is_array($_SESSION['pushed data'][$username])) {
                $_SESSION['pushed data'][$username] = [];
            }
            if(!in_array($task, $_SESSION['pushed data'][$username]))
            {
                $stmt->execute([':username' => $username ,':task' => $task]);
                $_SESSION['pushed data'][$username][]=$task;
            }
        }
        echo"Task pushed to database";
    }
}
if(isset($_POST['logout']))
{
    unset($_SESSION['username']);
    header("Location: http://localhost/demo/index.php");
    exit();
}
?>
