<?php

//connecting to a database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Todo_List";

//connection
$conn = new mysqli($servername, $username, $password, $dbname);

//checking connection
if ($conn->connect_error) {
    die("Sorry, We failed to connect: " . $conn->connect_error);
}
//Add task
if (isset($_POST["submit"])) {
    if (isset($_POST["task"]) && !empty($_POST["task"])) {
        $task = $_POST["task"];
        $conn->query("INSERT INTO Task (Tasks) VALUES ('$task')");
    }
}
//Delete task
if (isset($_GET["delete"])) {
    $id = (int)$_GET["delete"];
    $conn->query("DELETE FROM Task WHERE id='$id'");
}
//View task
if (isset($_GET["view"])) {
    $id = (int)$_GET["view"];
    $conn->query("UPDATE Task SET status= 'Viewed' WHERE id='$id'");
}
$result = $conn->query("SELECT id, Tasks, Status FROM Task ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h3><u>To-do list</u></h3>
    <form action="index.php" method="post">
        <label>Add a Task:</label><br>
        <input type="text" name="task">
        <input type="submit" value="Add" name="submit"><br>
    </form>
    <ul>
        <?php while ($row = $result->fetch_assoc()): ?>
            <li><?php echo $row["Tasks"]; ?>
                <a href="index.php?view=<?php echo $row['id']; ?>">View</a>
                <a href="index.php?delete=<?php echo $row['id']; ?>">Delete </a>
                (<?php echo $row["Status"]; ?>) <br>
            </li>
        <?php endwhile ?>
    </ul>

</body>

</html>