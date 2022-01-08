<!DOCTYPE html>
<html>
<head>
    <title>TEAM17-Advertising Campaign Analysis Report</title>
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

    <h2>Report -> Advertising Campaign Analysis</h2>
    <div>
        <p><a class="btn btn-primary btn-lg" href="index.php" role="button">Back to Main Menu</a></p>
    </div>


    <?php
        include_once "dbHandler.php";
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
        $createSalesDuringCampaign = "CREATE VIEW SalesDuringCampaign AS(SELECT DISTINCT discount_sales_date, temp.pid, p.product_name, sold_quantity, discount_price FROM(SELECT d.date_time AS discount_sales_date, s.pid, s.sold_quantity, d.discount_price FROM discountprice d JOIN transaction s ON d.pid = s.pid AND d.date_time = s.date_time) temp JOIN campaign ac ON ac.date_time = temp.discount_sales_date JOIN product p ON p.pid = temp.pid)";

        $createAllDiscountSales = "CREATE VIEW AllDiscountSales AS(SELECT d.date_time AS discount_sales_date, s.pid, p.product_name, sold_quantity, d.discount_price FROM discountprice d JOIN transaction s ON d.pid = s.pid AND d.date_time = s.date_time JOIN product p ON p.pid = s.pid)";

        $createSalesOutsideCampaign = "CREATE VIEW SalesOutsideCampaign AS(SELECT AllDiscountSales.* FROM AllDiscountSales LEFT JOIN SalesDuringCampaign ON AllDiscountSales.discount_sales_date=SalesDuringCampaign.discount_sales_date AND AllDiscountSales.pid=SalesDuringCampaign.pid WHERE SalesDuringCampaign.discount_sales_date IS NULL);";

        $createCampaignAnalysis = "CREATE VIEW CampaignAnalysis AS(SELECT temp_out_ads.pid AS ProductID, temp_out_ads.product_name AS ProductName, SoldDuringCampaign, SoldOutsideCampaign, SoldDuringCampaign - SoldOutsideCampaign AS Difference FROM(SELECT pid, product_name, SUM(sold_quantity) AS SoldOutsideCampaign FROM SalesOutsideCampaign GROUP BY pid, product_name) temp_out_ads JOIN(SELECT pid, product_name, SUM(sold_quantity) AS SoldDuringCampaign FROM SalesDuringCampaign GROUP BY pid, product_name) temp_during_ads ON temp_during_ads.pid = temp_out_ads.pid)";

        $query = "SELECT * FROM (SELECT * FROM CampaignAnalysis ORDER BY Difference DESC LIMIT 10) top10 UNION SELECT * FROM (SELECT * FROM CampaignAnalysis ORDER BY Difference ASC LIMIT 10) bottom10 ORDER BY Difference DESC";

        mysqli_query($dbConnect, $createSalesDuringCampaign);
        mysqli_query($dbConnect, $createAllDiscountSales);
        mysqli_query($dbConnect, $createSalesOutsideCampaign);
        mysqli_query($dbConnect, $createCampaignAnalysis);

        $result = mysqli_query($dbConnect, $query);

        echo "<table border='1'>
              <tr>
                <th>ProductID</th>
                <th>ProductName</th>
                <th>SoldDuringCampaign</th>
                <th>SoldOutsideCampaign</th>
                <th>Difference</th>
              </tr>";
        while ($row = $result->fetch_array())
        {
            echo "<tr>";
            echo "<td>" . $row['ProductID'] . "</td>";
            echo "<td>" . $row['ProductName'] . "</td>";
            echo "<td>" . $row['SoldDuringCampaign'] . "</td>";
            echo "<td>" . $row['SoldOutsideCampaign'] . "</td>";
            echo "<td>" . $row['Difference'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        mysqli_close($dbConnect);
    ?>

</body>
</html>
