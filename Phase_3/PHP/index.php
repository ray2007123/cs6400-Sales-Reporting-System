<!DOCTYPE html>
<html>
<head>
    <title>LEOFURN Sales Reporting System-CS6400-TEAM017</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {box-sizing: border-box}
        body {font-family: "Lato", Helvetica;}

        .header {
          overflow: hidden;
          background-color: #d1fcb8;
          padding: 30px 15px;
        }

        .header a {
          float: left;
          color: black;
          text-align: center;
          padding: 20px;
          text-decoration: none;
          font-size: 18px;
          line-height: 30px;
          border-radius: 4px;
        }

        .header a.logo {
          font-size: 39px;
          font-weight: bold;
          color: #29689e;

        }

        .header a:hover {
          background-color: #f7fac0;
          color: black;
        }

        .header a.active {
          background-color: #38a629;
          font-weight: bold;
          font-size: 26px;
          color: black;
        }

        .header-right {
          float: right;
        }

        /* Style the tab */
        .tab {
          float: left;
          border: 1px solid #ccc;
          background-color: #f7fac0;
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
        #nav {
         margin: 50px auto;
         height: 40px;
         background-color: #690;
         }
         #nav ul {
         list-style: none;
         margin-left: 50px;
         }
         #nav li {
         display: inline;
         font-size: 27px;
         }
         #nav a {
         line-height: 40px;
         color: #fff;
         text-decoration: none;
         padding: 20px 20px;
         }
         #nav a:hover {
         background-color: #060;
         }
    </style>
</head>

<body>

    <div class="header">
      <a href="index.php" class="logo">
        <img alt="GT-OMSCS" src="icon.png" style="width:42px;height:42px;"></img>
      </a>
      <a href="index.php" class="logo">LEOFURN Sales Reporting System-CS6400-TEAM017 </a>
      <div class="header-right">
        <a class="active" href="index.php">Main Menu</a>
      </div>
    </div>
    <ul id="nav">
      <li> <a class="tablinks" onclick="openTab(event, 'counts')" id="defaultOpen">Counts</a></li>
      <li> <a class="tablinks" onclick="openTab(event, 'reports')" id="defaultOpen">Reports</a></li>
      <li> <a class="tablinks" onclick="openTab(event, 'settings')" id="defaultOpen">Settings</a></li>
     </ul>

    <div id="counts" class="tabcontent">
      <h3>Counts</h3>
      <p>View statistics of Stores, Products, Services and Campaigns </p>

        <div>
            <p><a class="btn btn-primary btn-lg" href="countStore.php" role="button">Stores</a><p/>
            <p><a class="btn btn-primary btn-lg" href="StoresOfferFood.php" role="button">Stores offer food</a><p/>
            <p><a class="btn btn-primary btn-lg" href="StoresOfferChildcare.php" role="button">Stores offer childcare</a><p/>
            <p><a class="btn btn-primary btn-lg" href="countProducts.php" role="button">Products</a></p>
            <p><a class="btn btn-primary btn-lg" href="DistinctAdvertisingCampaigns.php" role="button">Distinct advertising campaigns</a></p>
        </div>

    </div>

    <div id="reports" class="tabcontent">
      <h3>Reports</h3>
      <p>View detailed reports</p>

        <div>
            <p><a class="btn btn-primary btn-lg" href="report_Category.php" role="button">Category Report</a></p>
            <p><a class="btn btn-primary btn-lg" href="reportActVsPred.php" role="button">Actual VS Predicted Revenue(Couches and Sofas)</a></p>
            <p><a class="btn btn-primary btn-lg" href="reportStoreByYearByState.php" role="button">Store Revenue by Year by State</a></p>
            <p><a class="btn btn-primary btn-lg" href="Outdoor_Furniture.php" role="button">Outdoor Furniture on Groundhog Day</a></p>
            <p><a class="btn btn-primary btn-lg" href="reportHighestState.php" role="button">State with Highest Volume for each Category</a></p>
            <p><a class="btn btn-primary btn-lg" href="reportRevByPop.php" role="button">Revenue by Population</a></p>
            <p><a class="btn btn-primary btn-lg" href="report 7.php" role="button">Childcare Sales Volume</a></p>
            <p><a class="btn btn-primary btn-lg" href="report 8.php" role="button">Restaurant Impact on Category Sales</a></p>
            <p><a class="btn btn-primary btn-lg" href="report_9.php" role="button">Advertising Campaign Analysis</a></p>
        </div>

    </div>

    <div id="settings" class="tabcontent">
      <h3>Settings</h3>
      <p>View and Edit Holidays and City Populations </p>

        <div>
            <p><a class="btn btn-primary btn-lg" href="editHoliday.php" role="button">View and Add Holidays</a></p>
            <p><a class="btn btn-primary btn-lg" href="editCityPop.php" role="button">View and Edit City's Population</a></p>
        </div>

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
