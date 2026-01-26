<?php
$mysqlserver = "localhost";
$mysqluser = "root";
$mysqlpass = "";
$mysqldb = "fbcs.nl_licenties_PHP";

$conn = new mysqli($mysqlserver, $mysqluser, $mysqlpass, $mysqldb);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    die("❌ Connection failed: " . $conn->connect_error);
}

echo "✅ Database connection successful!<br><br>";

// Simple validation query
$result = $conn->query("SELECT 1");
if ($result) {
    echo "✅ Test query successful!<br><br>";
} else {
    http_response_code(500);
    die("❌ Test query failed.");
}

// List tables
echo "📋 Tables in database <strong>{$mysqldb}</strong>:<br>";

$tables = $conn->query("SHOW TABLES");

if ($tables && $tables->num_rows > 0) {
    echo "<ul>";
    while ($row = $tables->fetch_array()) {
        echo "<li>" . htmlspecialchars($row[0]) . "</li>";
    }
    echo "</ul>";
} else {
    echo "❌ No tables found or unable to list tables.";
}

$conn->close();
?>
