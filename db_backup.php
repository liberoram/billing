 
<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "newhotel";
$backupFile = __DIR__ . "/backup_" . date("Y-m-d_H-i-s") . ".sql";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$tables = $conn->query("SHOW TABLES");
$backup = "";

while ($row = $tables->fetch_row()) {
    $table = $row[0];
    $createTable = $conn->query("SHOW CREATE TABLE `$table`")->fetch_row()[1];
    $backup .= $createTable . ";\n\n";

    $rows = $conn->query("SELECT * FROM `$table`");
    while ($data = $rows->fetch_assoc()) {
        $columns = implode("`, `", array_keys($data));
        $values = implode("', '", array_map([$conn, 'real_escape_string'], array_values($data)));
        $backup .= "INSERT INTO `$table` (`$columns`) VALUES ('$values');\n";
    }
    $backup .= "\n\n";
}

file_put_contents($backupFile, $backup);
echo "Backup successful. File saved to: $backupFile";
$conn->close();
?>
