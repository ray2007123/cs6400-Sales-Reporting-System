<!DOCTYPE html>
<html>
<head>
    <title>Actual VS Predicted Revenue(Couches and Sofas)</title>
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

    <h2>Report -> Actual VS Predicted Revenue(Couches and Sofas)</h2>
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
       set1.pid,
       set1.name,
       set1.retailprice,
       set1.totalsold,
       set2.discountsold,
       set1.totalsold-set2.discountsold as retailprice_sold,
       set1.totalsold * set1.retailprice AS actual_revenue,
       set1.totalsold * set1.retailprice * 0.75 AS pre_revenue,
       set3.difference
       FROM (
           SELECT transaction.pid AS pid,
           product.product_name AS name,
           SUM(transaction.sold_quantity) AS totalsold,
           product.retail_price AS retailprice
           FROM transaction
               JOIN producthascategory AS pc ON transaction.pid = pc.pid
               JOIN product ON transaction.pid = product.pid
           WHERE pc.category_name = 'Couches and Sofas'
           GROUP BY transaction.pid) AS set1
       JOIN(
           SELECT transaction.pid AS pid,
           SUM(transaction.sold_quantity) AS discountsold

           FROM transaction
               JOIN producthascategory AS pc ON transaction.pid = pc.pid
               JOIN discountprice ON transaction.pid = discountprice.pid AND transaction.date_time = discountprice.date_time
           WHERE pc.category_name = 'Couches and Sofas'
           GROUP BY transaction.pid) AS set2
       ON set1.pid = set2.pid
       JOIN(
           SELECT
               x.pid AS pid,
               x.ar - x.pr AS difference
           FROM (
               SELECT transaction.pid AS pid,
               SUM(
                   product.retail_price * transaction.sold_quantity * 0.75
               ) AS pr,
               SUM(
                   transaction.sold_quantity * discountprice.discount_price
               ) AS ar

               FROM transaction
               JOIN product ON transaction.pid = product.pid
               JOIN producthascategory AS pc
               ON transaction.pid = pc.pid
               JOIN discountprice ON transaction.pid = discountprice.pid AND transaction.date_time = discountprice.date_time
               WHERE pc.category_name = 'Couches and Sofas'
               GROUP BY transaction.pid) AS x
               HAVING difference > 5000 OR Difference <-5000) AS set3 ON set1.pid = set3.pid
       ORDER BY difference DESC;";
    $result = mysqli_query($dbConnect, $query);
    echo "<table border='1'>
          <tr>
          <th>Product ID</th>
          <th>Product Name</th>
          <th>Retail Price</th>
          <th>Total Sold</th>
          <th>Discount Sold</th>
          <th>Sold by Retail Price</th>
          <th>Actual Revenue</th>
          <th>Predicted Revenue</th>
          <th>Difference</th>
          </tr>";
    while ($row = $result->fetch_array())
    {
      echo "<tr>";
      echo "<td>" . $row['pid'] . "</td>";
      echo "<td>" . $row['name'] . "</td>";
      echo "<td>" . $row['retailprice'] . "</td>";
      echo "<td>" . $row['totalsold'] . "</td>";
      echo "<td>" . $row['discountsold'] . "</td>";
      echo "<td>" . $row['retailprice_sold'] . "</td>";
      $row['actual_revenue'] = sprintf("%.2f",$row['actual_revenue']);
      echo "<td>" . $row['actual_revenue'] . "</td>";
      $row['pre_revenue'] = sprintf("%.2f",$row['pre_revenue']);
      echo "<td>" . $row['pre_revenue'] . "</td>";
      $row['difference'] = sprintf("%.2f",$row['difference']);
      echo "<td>" . $row['difference']. "</td>";
      echo "</tr>";
    }
    echo "</table>";
    mysqli_close($dbConnect);
  ?>

</body>
</html>
