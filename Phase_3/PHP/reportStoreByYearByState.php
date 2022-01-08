<?php
    include_once 'dbHandler.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Report-storebyYbyS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {box-sizing: border-box}
        body {font-family: "Lato", sans-serif;}
        /* Style the tab */
        .tab {
          float: left;
          border: 1px solid #ccc;
          background-color: #ffffff;
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
          background-color: #ffffff;
        }
        /* Create an active/current "tab button" class */
        .tab button.active {
          background-color: #ffffff;
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
    </style>
</head>


<body>

    <h2>Report -> Store Revenue by State by Year</h2>
    <div>
        <p><a class="btn btn-primary btn-lg" href="index.php" role="button">Back to Main Menu</a></p>
    </div>


    <div class="tab">
      <button class="tablinks" onclick="openTab(event, 'find')" id="defaultOpen">Select</button>
    </div>


    <div id="find" class="tabcontent">
      <h2>Select a State:</h2>

        <form action="findState.php" method="POST">

            <?php
              $sql = "SELECT DISTINCT state_name FROM city;";
              $result = mysqli_query($dbConnect, $sql);
              $resultNum = mysqli_num_rows($result);
            ?>

            <select name="stateName">
              <?php
                  if ($resultNum > 0) {
                      while ($row = mysqli_fetch_assoc($result)) {
                          $state = $row['state_name'];
                          echo "<option value='$state'>$state</option>";
                      }
                  }
              ?>
            </select>
            <button type='selectState' name="selectState">Select</button>

        </form>

        <?php
            if(isset($_GET['find'])) {
                $findCheck = $_GET['find'];
                if ($findCheck == "success") {
                    echo "<p> Find the selected state </p>";
                }
            }
        ?>
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
