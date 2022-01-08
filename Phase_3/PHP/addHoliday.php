<?php


    if (isset($_POST['addHoliday'])) {

        include_once 'dbHandler.php';

        $hName = mysqli_real_escape_string($dbConnect, $_POST['hName']);
        $hDate = mysqli_real_escape_string($dbConnect, $_POST['hDate']);

        // Check any empty input;
        if (empty($hName) || empty($hDate)) {
            header("Location: editHoliday.php?holiday=empty");
            exit();
        } else {

            if (!validateDate($hDate)) {
                header("Location: editHoliday.php?holiday=invalidDate");
                exit();
            } else {

                // Check if the same date already existed in the holiday table;
                $sql = "SELECT * FROM holiday WHERE DATE(date_time) = '$hDate';";
                $result = mysqli_query($dbConnect, $sql);
                $resultNum = mysqli_num_rows($result);

                if ($resultNum > 0) {
                    // If holiday table has the same date;
                    $sqlUpdate = "UPDATE holiday SET holiday_name = '$hName' WHERE DATE(date_time) = '$hDate';";
                    mysqli_query($dbConnect, $sqlUpdate);
                    header("Location: editHoliday.php?holiday=success_update");
                } else {
                    // Insert the data into database;
                    $sqlAddHoliday = "INSERT INTO holiday(holiday_name, date_time) VALUES ('$hName', '$hDate');";
                    mysqli_query($dbConnect, $sqlAddHoliday);
                    header("Location: editHoliday.php?holiday=success_add");
                }

            }

        }

        mysqli_close($dbConnect);

    } else {

        header("Location: editHoliday.php");
        exit();

    }

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }

    // var_dump(validateDate('2013-13-01'));  // false
    // var_dump(validateDate('20132-13-01')); // false
    // var_dump(validateDate('2013-11-32'));  // false
    // var_dump(validateDate('2012-2-25'));   // false
    // var_dump(validateDate('2013-12-01'));  // true
    // var_dump(validateDate('1970-12-01'));  // true
    // var_dump(validateDate('2012-02-29'));  // true
    // var_dump(validateDate('2012', 'Y'));   // true
    // var_dump(validateDate('12012', 'Y'));  // false


?>
