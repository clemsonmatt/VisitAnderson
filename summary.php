<?php

include('includes/header.php');

?>

<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <br><br>
            <!-- page title -->
            <legend>
               <h3>Summary</h3>
            </legend>
        </div>
    </div>

<?php 
    include 'includes/charts.php';
    $charts = new Charts();

    switch($currentTable) {
    case 'moving':
       break;
    case 'visiting':
        $charts->visitingPieChart();
        $charts->visitingBarChart();
        break;
    case 'visitor':
        $charts->personPieChart();
        break;
    default:
        include 'includes/query.php';
        // $summary = new SummaryPage();
        // $summary->queryForm();
    }
?>

</div>