<?php
    include_once 'dbHandler.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Settings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {box-sizing: border-box}
        body {font-family: "Lato", sans-serif;}

        /* Style the tab */
        .tab {
          float: left;
          border: 1px solid #ccc;
          background-color: #f1f1f1;
          width: 30%;
          height: 600px;
        }

        /* Style the buttons inside the tab */
        .tab button {
          display: block;
          background-color: inherit;
          color: black;
          padding: 22px 16px;
          width: 100%;
          border: none;
          outline: none;
          text-align: left;
          cursor: pointer;
          transition: 0.3s;
          font-size: 17px;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
          background-color: #ddd;
        }

        /* Create an active/current "tab button" class */
        .tab button.active {
          background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
          float: left;
          padding: 0px 12px;
          border: 1px solid #ccc;
          width: 70%;
          border-left: none;
          height: 600px;
        }

        /* Style the table content */
        table {
          font-family: arial, sans-serif;
          border-collapse: collapse;
          width: 100%;
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

<body>

    <h2>Settings -> City's Population</h2>
    <div>
        <p><a class="btn btn-primary btn-lg" href="index.php" role="button">Back to Main Menu</a></p>
    </div>


    <?php

        $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if (strpos($fullUrl, "population=empty") == true) {
            echo "<p style=\"color:Orange;\">WARNING: Find empty population input(s);</p>";
        } elseif (strpos($fullUrl, "population=success") == true) {
            echo "<p style=\"color:DodgerBlue;\">INFO: Successfully set a new population;</p>";
        } elseif (strpos($fullUrl, "population=invalid") == true) {
            echo "<p style=\"color:Tomato;\">ERROR: Invalid combination of state and city;</p>";
        } else {
            echo "<p style=\"color:DodgerBlue;\">INFO: View and Set City Population;</p>";
        }

    ?>


    <div class="tab">
      <button class="tablinks" onclick="openTab(event, 'view')" id="defaultOpen">View City Population</button>
      <button class="tablinks" onclick="openTab(event, 'set')">Set City Population</button>
    </div>


    <div id="view" class="tabcontent" >
      <h3>View the pupulation</h3>
      <p>Population for each city and state</p>

        <div style="height:500px; overflow-y: scroll;">

            <?php
              $sql = "SELECT * FROM city;";
              $result = mysqli_query($dbConnect, $sql);
              $resultNum = mysqli_num_rows($result);
            ?>

            <table>
              <tr>
                <th>City_Name</th>
                <th>State_Name</th>
                <th>Population</th>
              </tr>

              <?php
                  if ($resultNum > 0) {
                      while ($row = mysqli_fetch_assoc($result)) {
                          $cityName = $row['city_name'];
                          $stateName = $row['state_name'];
                          $popNum = $row['population'];
                          echo "<tr>";
                          echo "<td>$cityName</td>";
                          echo "<td>$stateName</td>";
                          echo "<td>$popNum</td>";
                          echo "</tr>";
                      }
                  }
              ?>
            </table>

        </div>

    </div>


    <div id="set" class="tabcontent">
      <h3>Set Population</h3>
      <p>Select a city and state: </p>

        <form action="setPopulation.php" method="POST">
            <?php
              $sqlPop1 = "SELECT DISTINCT city_name FROM city;";
              $resultPop1 = mysqli_query($dbConnect, $sqlPop1);
              $resultNumPop1 = mysqli_num_rows($resultPop1);
            ?>

            <select name="selectCity">
              <?php
                  if ($resultNumPop1 > 0) {
                      while ($rowPop1 = mysqli_fetch_assoc($resultPop1)) {
                          $cityName = $rowPop1['city_name'];
                          echo "<option value='$cityName'>$cityName</option>";
                      }
                  }
              ?>
            </select>

            <?php
              $sqlPop2 = "SELECT DISTINCT state_name FROM city;";
              $resultPop2 = mysqli_query($dbConnect, $sqlPop2);
              $resultNumPop2 = mysqli_num_rows($resultPop2);
            ?>

            <select name="selectState" style="width: 50px;">
              <?php
                  if ($resultNumPop2 > 0) {
                      while ($rowPop2 = mysqli_fetch_assoc($resultPop2)) {
                          $stateName = $rowPop2['state_name'];
                          echo "<option value='$stateName'>$stateName</option>";
                      }
                  }
              ?>
            </select>
            <br>

            <input type='text' name='newPop' placeholder='New Population'>
            <br>

            <button type='setPopulation' name="setPopulation">Set</button>
        </form>

    </div>

    <script>
        function openTab(evt, tabName) {
          var i, tabcontent, tablinks;
          tabcontent = document.getElementsByClassName("tabcontent");
          for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
          }
          tablinks = document.getElementsByClassName("tablinks");
          for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
          }
          document.getElementById(tabName).style.display = "block";
          evt.currentTarget.className += " active";
        }

        // Get the element with id="defaultOpen" and click on it
        document.getElementById("defaultOpen").click();
    </script>

</body>
</html>
