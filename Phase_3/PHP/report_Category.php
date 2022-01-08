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
            phc.category_name AS category_name,
            COUNT(p.pid) AS product_count,
            MIN(p.retail_price) AS min_price,
            AVG(p.retail_price) AS avg_price,
            MAX(p.retail_price) AS max_price
            FROM product AS p
            INNER JOIN producthascategory AS phc ON phc.pid  = p.pid GROUP BY category_name
            ORDER BY category_name ASC";
        $result = mysqli_query($dbConnect, $query);
        echo "<table border='1'>
              <tr>
                <th>Category Name</th>
                <th>Product Count</th>
                <th>MIN Price</th>
                <th>MAX Price</th>
                <th>AVE Price</th>
              </tr>";
        while ($row = $result->fetch_array())
        {
            echo "<tr>";
            echo "<td>" . $row['category_name'] . "</td>";
            echo "<td>" . $row['product_count'] . "</td>";
            echo "<td>" . $row['min_price'] . "</td>";
            echo "<td>" . $row['max_price'] . "</td>";
            $row['avg_price'] = sprintf("%.2f",$row['avg_price']);
            echo "<td>" . $row['avg_price'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        mysqli_close($dbConnect);
    ?>

</body>
</html>
