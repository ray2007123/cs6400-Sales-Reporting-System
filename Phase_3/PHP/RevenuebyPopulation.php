<html>
<head>
<title>Report6 - Revenue by Population</title>
<meta name = "viewport" content = "Width = device-width, initial-scale = 1">
<style>

</style>
</head>

<body>
   <h2>Revenue By Population & By Year</h2>
    <div>
        <p><a class="btn btn-primary btn-lg" href="index.php" role="button">Back to Main Menu</a></p>
    </div>
    
    <button class="tablink" onclick="openPage('tab1', this, 'dodgerblue')" id="defaultOpen">Revenue By Population</button>
    <button class="tablink" onclick="openPage('tab2', this, 'dodgerblue')">Revenue By Year</button>

    <div id="tab1" class="tabcontent">
        <?php
            include_once "includes/dbHandler.php"; 
            if (mysqli_connect_errno()) {
              printf("Connect failed: %s\n", mysqli_connect_error());
              exit();
            }

            $sqlByPopulation = "SELECT 
            x.Population_Category,
            SUM(CASE WHEN y.year=2000 THEN y.quantity * y.price ELSE 0 END) AS '2000',
            SUM(CASE WHEN y.year=2001 THEN y.quantity * y.price ELSE 0 END) AS '2001',
            SUM(CASE WHEN y.year=2002 THEN y.quantity * y.price ELSE 0 END) AS '2002',
            SUM(CASE WHEN y.year=2003 THEN y.quantity * y.price ELSE 0 END) AS '2003',
            SUM(CASE WHEN y.year=2004 THEN y.quantity * y.price ELSE 0 END) AS '2004',
            SUM(CASE WHEN y.year=2005 THEN y.quantity * y.price ELSE 0 END) AS '2005',
            SUM(CASE WHEN y.year=2006 THEN y.quantity * y.price ELSE 0 END) AS '2006',
            SUM(CASE WHEN y.year=2007 THEN y.quantity * y.price ELSE 0 END) AS '2007',
            SUM(CASE WHEN y.year=2008 THEN y.quantity * y.price ELSE 0 END) AS '2008',
            SUM(CASE WHEN y.year=2009 THEN y.quantity * y.price ELSE 0 END) AS '2009',
            SUM(CASE WHEN y.year=2010 THEN y.quantity * y.price ELSE 0 END) AS '2010',
            SUM(CASE WHEN y.year=2011 THEN y.quantity * y.price ELSE 0 END) AS '2011',
            SUM(CASE WHEN y.year=2012 THEN y.quantity * y.price ELSE 0 END) AS '2012'
            FROM
            (SELECT store.store_id, populationcategory.Population_Category FROM store 
            JOIN populationcategory ON store.city_name = populationcategory.city_name AND 
            store.state_name = populationcategory.state_name) AS x
            JOIN 
            (SELECT transaction.store_id,transaction.pid,year(transaction.date_time)AS year,
            transaction.sold_quantity AS quantity, IFNULL(DiscountPrice.discount_price, product.retail_price) AS price
            FROM transaction LEFT JOIN DiscountPrice
            ON transaction.pid=DiscountPrice.pid AND transaction.date_time=DiscountPrice.date_time
            LEFT JOIN product ON transaction.pid = product.pid) AS y 
            ON x.store_id = y.store_id
            GROUP BY x.Population_Category
            ORDER BY find_in_set(x.Population_Category,'Small,Medium,Large,Extra Large');"; 
            
            $resultByPop = mysqli_query($dbConnect, $sqlByPopulation);

            echo "<table border='1'>
            <tr>
            <th>Population Group</th>
            <th>2000</th>
            <th>2001</th>
            <th>2002</th>
            <th>2003</th>
            <th>2004</th>
            <th>2005</th>
            <th>2006</th>
            <th>2007</th>
            <th>2008</th>
            <th>2009</th>
            <th>2010</th>
            <th>2011</th>
            <th>2012</th>
            </tr>";

      $lineNum = 1; 
      while ($row = $resultByPop->fetch_array()) {
          echo "<tr>";
          if ($lineNum == 1) {echo "<td>Small</td>";}                 
          if ($lineNum == 2) {echo "<td>Medium</td>";}                 
          if ($lineNum == 3) {echo "<td>Large</td>";}                 
          if ($lineNum == 4) {echo "<td>Extra Large</td>";}
          $row['2000'] = sprintf("%.2f",$row['2000']);
          echo "<td>" . $row['2000'] . "</td>";
          $row['2001'] = sprintf("%.2f",$row['2001']);
          echo "<td>" . $row['2001'] . "</td>";
          $row['2002'] = sprintf("%.2f",$row['2002']);
          echo "<td>" . $row['2002'] . "</td>";
          $row['2003'] = sprintf("%.2f",$row['2003']);
          echo "<td>" . $row['2003'] . "</td>";
          $row['2004'] = sprintf("%.2f",$row['2004']);
          echo "<td>" . $row['2004'] . "</td>";
          $row['2005'] = sprintf("%.2f",$row['2005']);
          echo "<td>" . $row['2005'] . "</td>";
          $row['2006'] = sprintf("%.2f",$row['2006']);
          echo "<td>" . $row['2006'] . "</td>";
          $row['2007'] = sprintf("%.2f",$row['2007']);
          echo "<td>" . $row['2007'] . "</td>";
          $row['2008'] = sprintf("%.2f",$row['2008']);
          echo "<td>" . $row['2008'] . "</td>";
          $row['2009'] = sprintf("%.2f",$row['2009']);
          echo "<td>" . $row['2009'] . "</td>";
          $row['2010'] = sprintf("%.2f",$row['2010']);
          echo "<td>" . $row['2010'] . "</td>";
          $row['2011'] = sprintf("%.2f",$row['2011']);
          echo "<td>" . $row['2011'] . "</td>";
          $row['2012'] = sprintf("%.2f",$row['2012']);
          echo "<td>" . $row['2012'] . "</td>";
          echo "</tr>";
          $lineNum = $lineNum + 1; 
      }
      echo "</table>";
         ?>

    </div>  


    <div id="tab2" class="tabcontent">
        <?php  
            include_once "includes/dbHandler.php"; 
            if (mysqli_connect_errno()) {
              printf("Connect failed: %s\n", mysqli_connect_error());
              exit();
            }

    $sqlByYear = "SELECT y.year,
    SUM(CASE WHEN x.Population_Category= 'Small' THEN y.quantity * y.price ELSE 0 END) AS 'Small',
    SUM(CASE WHEN x.Population_Category= 'Medium' THEN y.quantity * y.price ELSE 0 END) AS 'Medium',
    SUM(CASE WHEN x.Population_Category= 'Large' THEN y.quantity * y.price ELSE 0 END) AS 'Large',
    SUM(CASE WHEN x.Population_Category= 'Extra Large' THEN y.quantity * y.price ELSE 0 END) AS 'Extra Large'
FROM
    (SELECT store.store_id, 
    populationcategory.Population_Category 
        FROM store JOIN populationcategory 
            ON store.city_name = populationcategory.city_name AND store.state_name = populationcategory.state_name
    ) AS x 
    JOIN
    (SELECT transaction.store_id, 
            transaction.pid, 
            year(transaction.date_time) AS year, 
            transaction.sold_quantity AS quantity, 
            IFNULL(DiscountPrice.discount_price, product.retail_price) AS price
        FROM transaction LEFT JOIN DiscountPrice
            ON transaction.pid=DiscountPrice.pid AND transaction.date_time=DiscountPrice.date_time
        LEFT JOIN product 
            ON transaction.pid = product.pid
    ) AS y 
    ON x.store_id = y.store_id
GROUP BY y.year
ORDER BY y.year ASC;";

$resultByYear = mysqli_query($dbConnect, $sqlByYear);
    ?>

   </body>
</html>
