<?php

if (!empty($_POST)) {
    require 'config.php';

    $sql = $_POST['sql'];
    $result = mysqli_query($con, $sql);

    $data = array();
    while ($row = mysqli_fetch_array($result)) {
        $count = mysqli_num_fields($result);
        $item = array();
        for ($i = 0; $i < $count; $i++)
            array_push($item, $row[$i]);

        array_push($data, $item);
    }

    echo json_encode($data);
}
?>