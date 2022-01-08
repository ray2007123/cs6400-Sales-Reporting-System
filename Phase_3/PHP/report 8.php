<!DOCTYPE html>
<html>
<head>
    <title>TEAM17-Category Report</title>
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

    <h2>Report -> Category</h2>
    <div>
        <p><a class="btn btn-primary btn-lg" href="index.php" role="button">Back to Main Menu</a></p>
    </div>


    <?php
        include_once "dbHandler.php";
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
        $query = "SELECT
          ProductHasCategory.category_name AS Category,
          Store.restaurant AS Store_Type,
          SUM(TRANSACTION.sold_quantity) AS Q_Sold
        FROM TRANSACTION
        INNER JOIN Store ON TRANSACTION.store_id = Store.store_id INNER JOIN ProductHasCategory ON TRANSACTION.pid = ProductHasCategory.pid
        GROUP BY
          Category,
          Store_Type
        ORDER BY
          Category ASC";
        $result = mysqli_query($dbConnect, $query);
        echo "<table border='1'>
              <tr>
                <th>Category</th>
                <th>Store Type</th>
                <th>Quantity Sold</th>
              </tr>";
        while ($row = $result->fetch_array())
        {
            echo "<tr>";
            echo "<td>" . $row['Category'] . "</td>";
            echo "<td>" . $row['Store_Type'] . "</td>";
            echo "<td>" . $row['Q_Sold'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        mysqli_close($dbConnect);
    ?>

</body>
</html>
