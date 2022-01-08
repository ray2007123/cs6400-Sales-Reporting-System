<?php


    if (isset($_POST['setPopulation'])) {

        include_once 'dbHandler.php';

        $cityName = mysqli_real_escape_string($dbConnect, $_POST['selectCity']);
        $stateName = mysqli_real_escape_string($dbConnect, $_POST['selectState']);
        $newPop = mysqli_real_escape_string($dbConnect, $_POST['newPop']);

        // Check any empty input;
        if (empty($cityName) || empty($stateName) || empty($newPop)) {
            header("Location: ../editCityPop.php?population=empty");
            exit();
        } else {
            $sqlCheckPop = "SELECT * FROM city WHERE city_name = '$cityName' AND state_name = '$stateName';";
            $resultCheck = mysqli_query($dbConnect, $sqlCheckPop);
            $resultCheckNum = mysqli_num_rows($resultCheck);

            if ($resultCheckNum > 0) {
                $sqlSetPop = "UPDATE city SET population = '$newPop' WHERE city_name = '$cityName' AND state_name = '$stateName';";
                mysqli_query($dbConnect, $sqlSetPop);
                header("Location: editCityPop.php?population=success");
            } else {
                header("Location: editCityPop.php?population=invalid");
                exit();
            }
        }

        mysqli_close($dbConnect);

    } else {

        header("Location: ../editCityPop.php");
        exit();

    }

?>
