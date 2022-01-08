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

    <h2>Settings -> Holidays</h2>
    <div>
        <p><a class="btn btn-primary btn-lg" href="index.php" role="button">Back to Main Menu</a></p>
    </div>

    <?php

        $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if (strpos($fullUrl, "holiday=empty") == true) {
            echo "<p style=\"color:Red;\">WARNING: Find empty input(s);</p>";
        } elseif (strpos($fullUrl, "holiday=invalidDate") == true) {
            echo "<p  style=\"color:Tomato;\">ERROR: Invalid date input, use YYYY-mm-dd format;</p>";
        } elseif (strpos($fullUrl, "holiday=success_update") == true) {
            echo "<p style=\"color:DodgerBlue;\">INFO: Successfully update an existing holiday;</p>";
        } elseif (strpos($fullUrl, "holiday=success_add") == true) {
            echo "<p style=\"color:DodgerBlue;\">INFO: Successfully add a holiday;</p>";
        } else {
            echo "<p style=\"color:DodgerBlue;\">INFO: View and Add holiday;</p>";
        }

    ?>


    <div class="tab">
      <button class="tablinks" onclick="openTab(event, 'view')" id="defaultOpen">View Holidays</button>
      <button class="tablinks" onclick="openTab(event, 'add')">Add Holidays</button>
    </div>


    <div id="view" class="tabcontent">
      <h3>View Holidays</h3>
      <p>List of existing holidays: </p>

        <form>

            <?php
              $sql = "SELECT * FROM holiday;";
              $result = mysqli_query($dbConnect, $sql);
              $resultNum = mysqli_num_rows($result);
            ?>

            <table>
              <tr>
                <th>Holiday Name</th>
                <th>Date Time</th>
              </tr>

              <?php
                  if ($resultNum > 0) {
                      while ($row = mysqli_fetch_assoc($result)) {
                          $holidayName = $row['holiday_name'];
                          $holidayDate = $row['date_time'];
                          echo "<tr>";
                          echo "<td>$holidayName</td>";
                          echo "<td>$holidayDate</td>";
                          echo "</tr>";
                      }
                  }
              ?>
            </table>

        </form>

    </div>


    <div id="add" class="tabcontent">
      <h3>Add Holiday</h3>
      <p>Enter a date (YYYY-mm-dd) and name for the holoday: </p>

        <form action="addHoliday.php" method="POST">
            <input type='text' name='hName' placeholder='Holiday Name'>
            <br>
            <input type='text' name='hDate' placeholder='Date'>
            <br>
            <button type='addHoliday' name='addHoliday'>Add Holiday</button>
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
