<html>
<head>
  <title>OpenShift PHP Example</title>
</head>
<body>
<?php
// Connecting, selecting database
$link = mysql_connect(getenv('DATABASE_SERVICE_NAME'), getenv('DATABASE_USER'), getenv('DATABASE_PASSWORD')) or die('Could not connect: ' . mysql_error());
echo 'Connected successfully\n';
try {
    mysql_select_db(getenv('DATABASE_NAME')) or die('Could not select database\n');
} catch (Exception $e) {
    try {
        $query = 'CREATE DATABASE ' . getenv('DATABASE_NAME');
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());

        try {
            $db_selected = mysql_select_db(getenv('DATABASE_NAME'), $link);
        } catch (Exception $ed) {
            echo 'Faled to select db\n'
        }

        $query = 'CREATE TABLE view_counter (views integer)';
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
        $query = 'INSERT INTO view_counter VALUES (0)';
        $result = mysql_query($query) or die('Query failed: ' . mysql_error());
    } catch (Exception $ee) {
       echo 'Could not create database or table';
    }
}

// Performing SQL query
$query = 'SELECT views FROM view_counter';
$result = mysql_query($query) or die('Query failed: ' . mysql_error());

// Printing results in HTML
echo "<table>\n";
while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    echo "\t<tr>\n";
    foreach ($line as $col_value) {
        echo "\t\t<td>$col_value</td>\n";
    }
    echo "\t</tr>\n";
}
echo "</table>\n";

$query = 'UPDATE view_counter SET views=views+1';
$result = mysql_query($query) or die('Query failed: ' . mysql_error());


mysql_free_result($result);

// Closing connection
mysql_close($link);
?>
</body>
</html>
