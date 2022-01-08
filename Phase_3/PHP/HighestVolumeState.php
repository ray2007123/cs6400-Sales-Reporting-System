<html>
<head>
<title>Report 5 - View State with Highest Volume for each Category</title>
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
          border: 1px solid #dddddd;
          text-align: left;
          padding: 8px;
        }
        
        tr:nth-child(even) {
          background-color: #dddddd;
        }
    </style>


</head>
       <h2>State with Highest Volume for each Category</h2>
<div>
<p><a class="btn btn-primary btn-lg" href="index.php" role="button">Go Back to Main Menu</a></p>
</div>
      <div class="container">
        <!--COMBINATION OF YEAR AND MONTH-->
        <div class="row">
            <h3>Select Year and Month: </h3>
        </div>
        <div class="row">
            <div id="message-box"></div>
            <form method="post">
                <select name="month">
                  <option value="">--Please select a month--</option>
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
                  <option value="">--Please select a year--</option>
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
      include_once "includes/dbHandler.php"; 
      $hMonth = mysqli_real_escape_string($dbConnect, $_POST["month"]);
      $hYear = mysqli_real_escape_string($dbConnect, $_POST["year"]);
      
      echo "State with Highest Volume for each Category "."(mm-YYYY: "."$hMonth"."-"."$hYear".")";

      if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
      }


      $sqlRunReport5 = "CREATE VIEW JoinedTable AS
SELECT
    producthascategory.category_name AS category_name,
    transaction.pid AS pid,
    store.state_name AS state_name,
    transaction.sold_quantity AS sold_quantity,
    transaction.date_time AS date
FROM transaction
INNER JOIN producthascategory ON transaction.pid = producthascategory.pid
INNER JOIN store ON Store.store_id = transaction.store_id;



CREATE VIEW filterByYearAndMonth AS
SELECT
    category_name,
    state_name,
    SUM(sold_quantity) AS sum_quantity
FROM JoinedTable
WHERE YEAR(date) = '$hYear' AND MONTH(date)= '$hMonth'
GROUP BY category_name, state_name;



SELECT
    filter.category_name AS category_name,
    filter.state_name AS state_name,
    filter.sum_quantity AS max_quantity
FROM filterByYearAndMonth AS filter
WHERE (category_name, sum_quantity) IN ( 
    SELECT category_name, MAX(sum_quantity)
    FROM filterByYearAndMonth
    GROUP BY category_name
      )ORDER BY category_name ASC;";


    $result = mysqli_query($dbConnect, $sqlRunReport5);

    echo "<table border='1'>
    <tr>
    <th>Category</th>
    <th>State</th>
    <th>Units Sold</th> 
    <th>View Details</th>
    </tr>";


    while ($row = $result->fetch_array()) {
        $stateName = $row['state_name']; 
        $catName = $row['cate']; 
        echo "<tr>";
        echo "<td>" . $row['cate'] . "</td>";
        echo "<td>" . $row['state_name'] . "</td>";
        echo "<td>" . $row['maxsold'] . "</td>";
        echo "<td> <a href=\"includes/viewStateDetails.php?viewDetails=yes&stateName=$stateName&hMonth=$hMonth&hYear=$hYear&catName=$catName\" role=\"button\">View Details</a> </td>";
        echo "</tr>";
    }
    echo "</table>";
    mysqli_close($dbConnect);}
  ?>



<body>


</html> 
