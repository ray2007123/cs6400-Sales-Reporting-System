<!DOCTYPE html>
<html>
<head>
    <title>State with Highest Volume Per Category</title>
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

    <h2>Report -> State with Highest Volume Per Category</h2>
    <div>
        <p><a class="btn btn-primary btn-lg" href="index.php" role="button">Back to Main Menu</a></p>
    </div>
      <div class="container">
        <!--YEAR AND MONTH COMBINATIONS-->
        <div class="row">
            <h3>Selct Year and Month: </h3>
        </div>
        <div class="row">
            <div id="message-box"></div>
            <form method="post">
                <select name="month">
                  <option value="">--Please choose a month--</option>
                  <option value="01">01</option>
                  <option value="02">02</option>
                  <option value="03">03</option>
                  <option value="04">04</option>
                  <option value="05">05</option>
                  <option value="06">06</option>
                  <option value="07">07</option>
                  <option value="08">08</option>
                  <option value="09">09</option>
                  <option value="10">10</option>
                  <option value="11">11</option>
                  <option value="12">12</option>
                </select>
                <select name="year">
                  <option value="">--Please choose a year--</option>
                  <option value="2000">2000</option>
                  <option value="2001">2001</option>
                  <option value="2002">2002</option>
                  <option value="2003">2003</option>
                  <option value="2004">2004</option>
                  <option value="2005">2005</option>
                  <option value="2006">2006</option>
                  <option value="2007">2007</option>
                  <option value="2008">2008</option>
                  <option value="2009">2009</option>
                  <option value="2010">2010</option>
                  <option value="2011">2011</option>
                  <option value="2012">2012</option>
                </select>
                <input type="submit" name="submit" value="Run Report"/>
            </form>
        </div>

    <?php
    $setMonth = isset($_POST['month']) ? $_POST['month'] : false;
    $setYear = isset($_POST['year']) ? $_POST['year'] : false;


    if(isset($_POST['submit'])){
      include_once "dbHandler.php";
      $hMonth = mysqli_real_escape_string($dbConnect, $_POST["month"]);
      $hYear = mysqli_real_escape_string($dbConnect, $_POST["year"]);

      echo "State with Highest Volume Per Category "."(mm-YYYY: "."$hMonth"."-"."$hYear".")";

      if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
      }
      $query = "
      SELECT
    Y.category_name,
    Y.max_quantity,
    z.state_name
FROM
    (
    SELECT
        X.category_name AS category_name,
        MAX(X.sumsold) AS max_quantity
    FROM
        (
        SELECT
            producthascategory.category_name AS category_name,
            store.state_name,
            SUM(TRANSACTION.sold_quantity) AS sumsold
        FROM
            producthascategory
        JOIN TRANSACTION ON producthascategory.pid = TRANSACTION.pid
    JOIN store ON TRANSACTION
        .store_id = store.store_id
    WHERE
        MONTH(TRANSACTION.date_time) = '$hMonth' AND YEAR(TRANSACTION.date_time) = '$hYear'
    GROUP BY
        producthascategory.category_name,
        store.state_name
    ) AS X
GROUP BY
    X.category_name
) AS Y
JOIN(
    SELECT
        producthascategory.category_name AS category_name,
        store.state_name,
        SUM(TRANSACTION.sold_quantity) AS sumsold
    FROM
        producthascategory
    JOIN TRANSACTION ON producthascategory.pid = TRANSACTION.pid
JOIN store ON TRANSACTION
    .store_id = store.store_id
WHERE
    MONTH(TRANSACTION.date_time) = '$hMonth' AND YEAR(TRANSACTION.date_time) = '$hYear'
GROUP BY
    producthascategory.category_name,
    store.state_name
) AS z
ON
    Y.category_name = z.category_name AND Y.max_quantity = z.sumsold
ORDER BY
    Y.category_name ASC;";

    $result = mysqli_query($dbConnect, $query);

    echo "<table border='1'>
    <tr>
    <th>Category Name</th>
    <th>State Name</th>
    <th>Sold Quantity</th>
    </tr>";

    while ($row = $result -> fetch_array()) {
        $stateName = $row['state_name'];
        $catName = $row['cate'];
        echo "<tr>";
        echo "<td>" . $row['category_name'] . "</td>";
        echo "<td>" . $row['state_name'] . "</td>";
        echo "<td>" . $row['max_quantity'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";

    mysqli_close($dbConnect);}
  ?>

</body>
</html>
