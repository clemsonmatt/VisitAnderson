<?php

include('includes/config.php');

if (isset($_POST['submit'])) {
    // get the csv file
    $file = $_FILES['file']['tmp_name'];

    if ($file != null) {
        $handle = fopen($file, "r");
     
        $counter    = 0;
        $csvHeaders = array();

        while (($fileop = fgetcsv($handle, 1000, ",")) !== false) {

            /* first read the column headers */
            if ($counter == 0) {
                $counter++;
                /* read in the headers */
                foreach ($fileop as $col) {
                    $csvHeaders[] = $col;
                }
            }
            /* now insert the values for each column */
            else {
                /* build the sql statement */
                $sql = "INSERT INTO visitor (";
                foreach ($csvHeaders as $header) {  // col names
                    $sql = $sql . "`$header`, ";
                }
                $sql = $sql . "`created_at`, `updated_at`"; // created_at, updated_at
                $sql = $sql . ") VALUES (";
                foreach ($fileop as $value) {   // col values
                    $sql = $sql . "'$value',";
                }
                $sql = $sql . "'".time()."', '".time()."'"; // created_at, updated_at
                $sql = $sql . ")";

                // echo $sql;
                // die();
                mysqli_query($con, $sql);
            }

        }
        fclose($handle);

        // redirect
        header('Location: index.php?visitor&success=upload');
    } else {
        /* file was null, redirect */
        header('Location: index.php?visitor&error=upload');
    }
}
