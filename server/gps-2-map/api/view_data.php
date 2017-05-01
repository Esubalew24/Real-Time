<?php
    // Get database connection
    include('database.php');
?>

<html>
<head>
    <title>GPS-2-MAP - TEST</title>
    <style type="text/css">
        .table_titles, .table_cells {
                padding-right: 20px;
                padding-left: 20px;
                color: #000;
        }
        .table_titles {
            color: #FFF;
            background-color: #666;
        }
        .table_cells {
            background-color: #FAFAFA;
        }
        table {
            border: 2px solid #333;
        }
        body { font-family: "Trebuchet MS", Arial; }
    </style>
</head>

<body>
<h1>GPS-2-MAP - TEST</h1>
<h2>Shows GPS-data from database, that is pushed from Arduino to server</h2>
<a href="clear_database.php">Clear data</a>
<table border="0" cellspacing="0" cellpadding="5">
	<tr>
		<td class="table_titles">Device ID</td>
        <td class="table_titles">Date and time</td>
        <td class="table_titles">Latitude</td>
        <td class="table_titles">Longitude</td>
     </tr>
<?php


$result = mysqli_query($db_connection,"SELECT * FROM data ORDER BY date_and_time");

//$result = myssqli_query($db_connection,"SELECT * FROM data WHERE device_id = '".$_GET["device_id"]."'");


while($row = mysqli_fetch_array($result))
{
echo "<tr>";
echo "<td class='table_cells'>" . $row['device_id'] . "</td>";
echo "<td class='table_cells'>" . $row['date_and_time'] . "</td>";
echo "<td class='table_cells'>" . $row['latitude'] . "</td>";
echo "<td class='table_cells'>" . $row['longitude'] . "</td>";
echo "</tr>";
}
echo "</table>";

mysqli_close($db_connection);
?>
    </table>
    </body>
</html>