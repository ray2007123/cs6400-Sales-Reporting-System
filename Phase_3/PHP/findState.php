<!DOCTYPE html>
<html>
<head>
    <title>View Revenue by Year by State</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {box-sizing: border-box}
        body {font-family: "Lato", sans-serif;}

        /* Set height of body and the document to 100% */
        body, html {
          height: 100%;
          margin: 0;
          font-family: Arial;
        }

        /* Style tab links */
        .tablink {
          background-color: #444;
          color: white;
          float: left;
          border: none;
          outline: none;
          cursor: pointer;
          padding: 14px 16px;
          font-size: 17px;
          width: 50%;
        }

        .tablink:hover {
          background-color: #666;
        }

        /* Style the tab content (and add height:100% for full page content) */
        .tabcontent {
          padding: 100px 20px;
          height: 100%;
        }

         /* Style the table content */
        table {
          font-family: arial, sans-serif;
          border-collapse: collapse;
          width: 100%;
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

      <h3>Report -> Store Revenue by Year in Ascending Order </h3>
      <h3>Report -> Store Revenue by Revenue in Descending Order</h3>

    <div>
        <p><a class="btn btn-primary btn-lg" href="reportStoreByYearByState.php" role="button">Back to Find State</a></p>
        <p><a class="btn btn-primary btn-lg" href="index.php" role="button">Back to Main Menu</a></p>
    </div>

    <button class="tablink" onclick="openPage('tab1', this, 'dodgerblue')" id="defaultOpen">Store Revenue by Year in Ascending Order</button>
    <button class="tablink" onclick="openPage('tab2', this, 'dodgerblue')">Store Revenue by Revenue in Descending Order</button>

    <div id="tab1" class="tabcontent">

        <?php
        if(isset($_POST['selectState'])) {

            include_once 'dbHandler.php';

            $selectedState = mysqli_real_escape_string($dbConnect, $_POST['stateName']);
            $query = "
            SELECT
              store.store_id,
              store.street_address,
              store.city_name,
              year(transaction.date_time) AS year,
              SUM(transaction.sold_quantity * IFNULL(discountprice.discount_price, product.retail_price) ) AS revenue
            FROM`store` JOIN transaction ON store.store_id=transaction.store_id
            LEFT JOIN discountprice ON transaction.date_time=discountprice.date_time AND transaction.pid=discountprice.pid
            LEFT JOIN product ON transaction.pid=product.pid
            WHERE store.state_name='$selectedState'
            GROUP BY year(transaction.date_time),store.store_id
            ORDER BY year(transaction.date_time) ASC;";

            $result = mysqli_query($dbConnect, $query);
            $resultNum = mysqli_num_rows($result);
            echo "<table border='1'>
                 <tr>
                    <th>Store ID</th>
                    <th>Store Address</th>
                    <th>City Name</th>
                    <th>Sales Year</th>
                    <th>Total Revenue</th>
                 </tr>";

            while ($row = $result->fetch_array()) {
              echo "<tr>";
              echo "<td>" . $row['store_id'] . "</td>";
              echo "<td>" . $row['street_address'] . "</td>";
              echo "<td>" . $row['city_name'] . "</td>";
              echo "<td>" . $row['year'] . "</td>";
              $row['revenue'] = sprintf("%.2f",$row['revenue']);
              echo "<td>" . $row['revenue']. "</td>";
              echo "</tr>";
            }
            echo "</table>";
            // header("Location: ../reportStoreByYearByState.php");

        } else {

            header("Location: reportStoreByYearByState.php");
            exit();

        }
        ?>


    </div>

    <div id="tab2" class="tabcontent">

        <?php
            //include_once "dbHandler.php";
            if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
            }
            $query2 = "
            SELECT
              store.store_id,
              store.street_address,
              store.city_name,
              year(transaction.date_time) AS year,
              SUM(transaction.sold_quantity * IFNULL(discountprice.discount_price, product.retail_price) ) AS revenue
            FROM store JOIN transaction ON store.store_id=transaction.store_id
            LEFT JOIN discountprice ON transaction.date_time=discountprice.date_time AND transaction.pid=discountprice.pid
            LEFT JOIN product ON transaction.pid=product.pid
            WHERE store.state_name='$selectedState'
            GROUP BY year(transaction.date_time),store.store_id
            ORDER BY revenue DESC;";

            $result = mysqli_query($dbConnect, $query2);

            echo "<table border='1'>
                  <tr>
                  <th>Store ID</th>
                  <th>Store Address</th>
                  <th>City Name</th>
                  <th>Sales Year</th>
                  <th>Total Revenue</th>
                  </tr>";
            while ($row = $result->fetch_array()) {
              echo "<tr>";
              echo "<td>" . $row['store_id'] . "</td>";
              echo "<td>" . $row['street_address'] . "</td>";
              echo "<td>" . $row['city_name'] . "</td>";
              echo "<td>" . $row['year'] . "</td>";
              $row['revenue'] = sprintf("%.2f",$row['revenue']);
              echo "<td>" . $row['revenue']. "</td>";
              echo "</tr>";
            }
            echo "</table>";
            echo(round('TotalRevenue', 2));
            mysqli_close($dbConnect);
        ?>
    </div>

    <script>
        function openPage(pageName,elmnt,color) {
          var i, tabcontent, tablinks;
          tabcontent = document.getElementsByClassName("tabcontent");
          for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
          }
          tablinks = document.getElementsByClassName("tablink");
          for (i = 0; i < tablinks.length; i++) {
            tablinks[i].style.backgroundColor = "";
          }
          document.getElementById(pageName).style.display = "block";
          elmnt.style.backgroundColor = color;
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>
</body>

</html>
