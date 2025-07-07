<?php
session_start();
$username = $_POST["username"];
$_SESSION["username"] = $username;
echo "Hello,{$_SESSION["username"]}";

//Array as storage
$array = match (true) {
    !isset($_SESSION["todolist"]) => $_SESSION["todolist"] = [],
    default => $_SESSION["todolist"],
};

//connecting with database
try {
    $pdo = new PDO('mysql:host=localhost;dbname=todolist', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}

//add a task
if (isset($_POST["add"])) {
    if (isset($_POST["task"]) && !empty($_POST["task"])) {
        $task = htmlspecialchars($_POST["task"]);
        $_SESSION['todolist'][] = $task;
    }
}

//remove a task
if (isset($_GET['remove'])) {
    $index = (int) $_GET['remove'];
    if (isset($_SESSION['todolist'][$index])) {
        unset($_SESSION['todolist'][$index]);
        $_SESSION['todolist'] = array_values($_SESSION['todolist']);
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo list</title>
</head>

<body>
    <h2><u> To-do list</u></h2>
    <form action="todo_list.php" method="post">
        <label>Add a task:</label>
        <input type="text" name="task">
        <input  type="submit" name="add" value="Add"><br>
        <ul>
            <?php foreach ($_SESSION["todolist"] as $index => $task): ?>
                <li>
                    <?php echo htmlspecialchars($task); ?>
                    <a href="todo_list.php?remove=<?php echo $index ?> ">Delete</a>
                </li>
            <?php endforeach; ?>
        </ul>
        Push the table to database: <br>
        <input type="submit" name="push" value="push to database"><br><br>
    </form>
    <form action="index.php" method="post">
        <input type="submit" name="logout" value="Logout">
    </form>
</body>

</html>
<?php
// Insert tasks from session into Database
if (isset($_POST["push"])) {
    if (isset($_SESSION['todolist']) && is_array($_SESSION['todolist'])) {
        $stmt = $pdo->prepare("INSERT INTO Task (Task) VALUES (:Task)");

        foreach ($_SESSION['todolist'] as $task) {
            if (!empty($task)) {
                $stmt->execute([':Task' => $task]);
            }
        }
        echo "Data is pushed to database";
    }
}

if (isset($_POST["logout"])) {
    header("Location: http://localhost/demo/index.php");
}
?>