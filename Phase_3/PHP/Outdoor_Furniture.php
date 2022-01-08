<html>
<head>
    <title>Report 4 -> Outdoor Furniture on Groundhog Day</title>
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

<body>

    <h2>Outdoor Furniture on Groundhog Day</h2>
    <div>
        <p><a class="btn btn-primary btn-lg" href="index.php" role="button">Go Back to Main Menu</a></p>
    </div>

    <?php
    include_once "dbHandler.php";
    if (mysqli_connect_errno()) {
      printf("Connect failed: %s\n", mysqli_connect_error());
      exit();
    }
    $sqlQuery = " SELECT
    X.year,
    X.total_sold,
    X.ave_sold,
    Y.groundhogsold
FROM
    (
    SELECT
        YEAR(TRANSACTION.date_time) AS YEAR,
        SUM(TRANSACTION.sold_quantity) AS total_sold,
        SUM(TRANSACTION.sold_quantity) / 365 AS ave_sold
    FROM
        product
    JOIN producthascategory ON product.pid = producthascategory.pid
    JOIN TRANSACTION ON product.pid = TRANSACTION.pid
WHERE
    producthascategory.category_name = 'Outdoor Furniture'
GROUP BY
    YEAR
) AS X
LEFT JOIN(
    SELECT
        YEAR(date_time) AS YEAR,
        SUM(sold_quantity) AS groundhogsold
    FROM TRANSACTION
JOIN producthascategory ON TRANSACTION
    .pid = producthascategory.pid
WHERE
    MONTH(date_time) = 02 AND DAY(date_time) = 02 AND producthascategory.category_name = 'Outdoor Furniture'
GROUP BY
    YEAR(date_time)
) AS Y
ON
    X.year = Y.year
ORDER BY
    `x`.`year` ASC;";
    $result = mysqli_query($dbConnect, $sqlQuery);
    echo "<table border='1'>
          <tr>
          <th>Year</th>
          <th>Total Sold</th>
          <th>Average Sold</th>
          <th>GroundHog Day Sold</th>
          </tr>";
    while ($row = $result->fetch_array())
    {
      echo "<tr>";
      echo "<td>" . $row['year'] . "</td>";
      echo "<td>" . $row['total_sold'] . "</td>";
      $row['ave_sold'] = sprintf("%.2f",$row['ave_sold']);
      echo "<td>" . $row['ave_sold'] . "</td>";
      echo "<td>" . $row['groundhogsold']. "</td>";
      echo "</tr>";
    }
    echo "</table>";
    mysqli_close($dbConnect);
  ?>

    </body>
</head>
