<?php

include("../includes/config.php");

$visitor = new Visitor();

/* call the right function */
if (isset($_POST['actionType']) == 'edit') {
    $visitor->edit($con);
} elseif (isset($_GET['actionType']) && $_GET['actionType'] == 'remove') {
    $visitor->remove($con);
} else {
    $visitor->add($con);
}

/**
 * Visitor Class
 */
class Visitor
{
    /**
     * Remove a visitor
     */
    public function remove($con)
    {
        /* remove the visitor */
        $sql = 'DELETE FROM visitor WHERE id = "'.$_GET['id'].'"';
        mysqli_query($con, $sql);

        /* redirect */
        header('location: ../index.php/?visitor&success=remove');
    }


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
                WHERE id = "'.$_POST['personId'].'"
            ';
            mysqli_query($con, $sql);

            /* redirect */
            header("location: ../index.php/?visitor&success=edit");
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

            /* redirect */
            header("location: ../index.php/?visitor&success=add");
        }
    }
}
