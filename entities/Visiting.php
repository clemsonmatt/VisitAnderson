<?php

include("../includes/config.php");

$visiting = new Visiting();

/* call the right function */
if ($_POST['actionType'] == 'edit') {
    $visiting->edit($con);
} else {
    $visiting->add($con);
}

/**
 * Visitor Class
 */
class Visiting
{
    /**
     * Edit an existing visitor
     */
    public function edit($con)
    {
        /* deny if fname, lname, and email are null */
        if ($_POST['first_name'] == "" && $_POST['last_name'] == "" && $_POST['email'] == "") {
            header("location: ../index.php/?visitor&error=1");
        }
        else {
            $sql = 'UPDATE visitor SET
                `first_name`   = "'.$_POST['first_name'].'",
                `last_name`    = "'.$_POST['last_name'].'",
                `address`      = "'.$_POST['address'].'",
                `city`         = "'.$_POST['city'].'",
                `state`        = "'.$_POST['state'].'",
                `zip`          = "'.$_POST['zip'].'",
                `home_phone`   = "'.$_POST['home_phone'].'",
                `mobile_phone` = "'.$_POST['mobile_phone'].'",
                `work_phone`   = "'.$_POST['work_phone'].'",
                `email`        = "'.$_POST['email'].'",
                `created_at`   = "'.time().'",
                `updated_at`   = "'.time().'"
                WHERE id = "'.$_POST['visitorId'].'"
            ';
            //mysqli_query($con, $sql);

            /* deny if visiting info is null */
            if ($_POST['reason'] == "" && $_POST['visit_date'] == "" && $_POST['visit_date_start'] == "" && $_POST['interests'] == "") {
                header("location: ../index.php/?person&success=edit");
            }
            else {
                /* now add the visiting info */
                $sql = "UPDATE visiting SET
                    `reason`           = '".$_POST['reason']."',
                    `interests`        = '".serialize(explode(',', $_POST['interests']))."',
                    `visit_date`       = '".$_POST['visit_date']."',
                    `visit_date_start` = '".$_POST['visit_date_start']."',
                    `visit_date_end`   = '".$_POST['visit_date_end']."',
                    `created_at`       = '".time()."',
                    `updated_at`       = '".time()."'
                    WHERE id           = '".$_POST['visitingId']."'
                ";
                echo $sql;
                die();
                mysqli_query($con, $sql);

                /* redirect */
                header("location: ../index.php/?visitor&success=edit");
            }
        }
    }

    /**
     * Add a new visitor
     */
    public function add($con)
    {
        /* deny if fname, lname, and email are null */
        if ($_POST['first_name'] == "" && $_POST['last_name'] == "" && $_POST['email'] == "") {
            header("location: ../index.php/?visitor&error=1");
        }
        else {
            /* first add the visitor */
            $sql = 'INSERT INTO visitor (
                `first_name`,
                `last_name`,
                `address`,
                `city`,
                `state`,
                `zip`,
                `home_phone`,
                `mobile_phone`,
                `work_phone`,
                `email`,
                `created_at`,
                `updated_at`
            ) VALUES (
                "'.$_POST['first_name'].'",
                "'.$_POST['last_name'].'",
                "'.$_POST['address'].'",
                "'.$_POST['city'].'",
                "'.$_POST['state'].'",
                "'.$_POST['zip'].'",
                "'.$_POST['home_phone'].'",
                "'.$_POST['mobile_phone'].'",
                "'.$_POST['work_phone'].'",
                "'.$_POST['email'].'",
                "'.time().'",
                "'.time().'"
            )';
            mysqli_query($con, $sql);
            $visitorId = mysqli_insert_id($con);

            /* deny if visiting info is null */
            if ($_POST['reason'] == "" && $_POST['visit_date'] == "" && $_POST['visit_date_start'] == "" && $_POST['interests'] == "") {
                header("location: ../index.php/?person&success=add");
            }
            else {
                /* now add the visiting info */
                $sql = "INSERT INTO visiting (
                    `reason`,
                    `interests`,
                    `visit_date`,
                    `visit_date_start`,
                    `visit_date_end`,
                    `created_at`,
                    `updated_at`
                ) VALUES (
                    '".$_POST['reason']."',
                    '".serialize(explode(',', $_POST['interests']))."',
                    '".$_POST['visit_date']."',
                    '".date('Y-m-d', strtotime($_POST['visit_date_start']))."',
                    '".date('Y-m-d', strtotime($_POST['visit_date_end']))."',
                    '".time()."',
                    '".time()."'
                )";
                mysqli_query($con, $sql);
                $visitingId = mysqli_insert_id($con);

                /* map the person to the visiting table */
                $sql = 'INSERT INTO mapping (
                    `person_id`,
                    `table_name`,
                    `table_id`
                ) VALUES (
                    "'.$visitorId.'",
                    "visiting",
                    "'.$visitingId.'"
                )';
                mysqli_query($con, $sql);

                /* redirect */
                header("location: ../index.php/?visiting&success=add");
            }
        }
    }
}
