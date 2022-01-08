<!DOCTYPE html>
<html>
<head>
    <title>TEAM17-ChildCare Sales Volume Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {box-sizing: border-box}
        body {font-family: "Lato", sans-serif;}
        /* Style the table content */
        table {
          font-family: arial, sans-serif;
          border-collapse: collapse;
          width: 90%;
        }
        td, th {
          border: 1px solid #ffffff;
          text-align: left;
          padding: 8px;
        }
        tr:nth-child(even) {
          background-color: #ffffff;
        }
    </style>
</head>

<body>

    <h2>Report -> ChildCare Sales Volume</h2>
    <div>
        <p><a class="btn btn-primary btn-lg" href="index.php" role="button">Back to Main Menu</a></p>
    </div>


    <?php
        include_once "dbHandler.php";
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
        $createTemp = "CREATE VIEW temp AS SELECT YEAR(transaction.date_time) AS year, MONTH(transaction.date_time) AS month, transaction.store_id, SUM(transaction.sold_quantity * p.retail_price) AS volumn, childcare.time_limit AS time_limit FROM transaction LEFT OUTER JOIN childcare ON childcare.store_id = transaction.store_id JOIN product AS p ON p.pid = transaction.pid GROUP BY YEAR(transaction.date_time), MONTH(transaction.date_time), transaction.store_id, time_limit ORDER BY YEAR(transaction.date_time) DESC, MONTH(transaction.date_time) DESC";
        $query = " SELECT year, month,  CAST(SUM(CASE WHEN time_limit <= 30 THEN volumn ELSE 0 END) AS decimal(38, 2)) AS 'Max 30 Mins', CAST(SUM(CASE WHEN time_limit > 30 AND time_limit <= 45 THEN volumn ELSE 0 END) AS decimal(38, 2)) AS 'Max 45 Mins', CAST(SUM(CASE WHEN time_limit > 45 AND time_limit <= 60 THEN volumn ELSE 0 END) AS decimal(38, 2)) AS 'Max 60 Mins', CAST(SUM(CASE WHEN time_limit is NULL THEN volumn ELSE 0 END) AS decimal(38, 2)) AS 'No Childcare' FROM `temp` GROUP BY year, month ORDER BY year DESC, month DESC LIMIT 12";
        mysqli_query($dbConnect, $createTemp);
        $result = mysqli_query($dbConnect, $query);

        echo "<table border='1'>
              <tr>
                <th>Year</th>
                <th>Month</th>
                <th>Max 30 mins</th>
                <th>Max 45 mins</th>
                <th>Max 60 mins</th>
                <th>No Childcare</th>
              </tr>";
        while ($row = $result->fetch_array())
        {
            echo "<tr>";
            echo "<td>" . $row['year'] . "</td>";
            echo "<td>" . $row['month'] . "</td>";
            echo "<td>" . $row['Max 30 Mins'] . "</td>";
            echo "<td>" . $row['Max 45 Mins'] . "</td>";
            echo "<td>" . $row['Max 60 Mins'] . "</td>";
            echo "<td>" . $row['No Childcare'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        mysqli_close($dbConnect);
    ?>

</body>
</html>
