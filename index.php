<?php
include('includes/header.php');

/* database calls */
if ($currentTable == 'visitor') { // visitor table
    //$sql = 'SELECT * FROM visitor';
    if (isset($_GET['pageno'])) {
        $pageno = $_GET['pageno'];
    } else {
        $pageno = 1;
    }
    
    $query = "SELECT count(*) FROM visitor";
    $result = mysqli_query($con, $query);
    $query_data = mysqli_fetch_row($result);
    $numrows = $query_data[0];
    $rows_per_page = 200;
    
    $lastpage = ceil($numrows/$rows_per_page);
    $pageno = (int)$pageno;
    if ($pageno > $lastpage) {
        $pageno = $lastpage;
    } // if
    if ($pageno < 1) {
        $pageno = 1;
    }
    $limit = 'LIMIT ' .($pageno - 1) * $rows_per_page .',' .$rows_per_page;
    $sql = "SELECT * FROM visitor $limit";
}
elseif ($currentTable == 'visiting') { // visiting table
    /* get from mapping table then join visitor and visiting table */
    $sql = 'SELECT visitor.*,
                   visiting.reason, visiting.interests, visiting.visit_date, visiting.visit_date_start, visiting.visit_date_end, visiting.updated_at,
                   visiting.id AS visiting_id
            FROM mapping
            INNER JOIN visitor ON mapping.person_id = visitor.id
            INNER JOIN visiting ON mapping.table_id = visiting.id';
}
elseif ($currentTable == 'interests') {
    $sql = 'SELECT * FROM interests';
}
$queryResult = mysqli_query($con, $sql);

?>

<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <br><br>
            <!-- success and error messages -->
            <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <strong>ERROR:</strong> First Name, Last Name, and Email cannot all be blank
                </div>
            <?php elseif (isset($_GET['error']) && $_GET['error'] == 'upload'): ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <strong>ERROR:</strong> The file was not uploaded
                </div>
            <?php elseif (isset($_GET['success']) && $_GET['success'] == "add"): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <strong>Success!</strong> The visitor was successfully added.
                </div>
            <?php elseif (isset($_GET['success']) && $_GET['success'] == "edit"): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <strong>Success!</strong> The visitor was successfully edited.
                </div>
            <?php elseif (isset($_GET['success']) && $_GET['success'] == "upload"): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <strong>Success!</strong> The file was uploaded.
                </div>
            <?php elseif (isset($_GET['success']) && $_GET['success'] == "remove"): ?>
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <strong>Success!</strong> The visitor was removed.
                </div>
            <?php endif; ?>

            <a id="top"></a>

            <!-- page title -->
            <legend>
                <?php if (sizeof($explode) > 1): ?>
                    <button class="pull-right btn btn-green btn-sm" id="add-row">
                        <i class="fa fa-plus"></i> Add
                    </button>
                <?php endif; ?>
                <!-- <button class="pull-right btn btn-default btn-sm" style="margin-right: 10px;">
                    <i class="fa fa-file"></i> Add From File
                </button> -->
                <form action="upload.php" method="post" enctype="multipart/form-data">
                    <div class="btn-group pull-right">
                        <span id="upload-file" class="btn btn-default btn-sm btn-file">
                            <i class="fa fa-file"></i> Add From File <input name="file" type="file">
                        </span>
                        <button id="upload-button" type="submit" name="submit" class="btn btn-primary btn-sm" style="margin-right: 10px;">
                            <i class="fa fa-upload"></i> Upload
                        </button>
                    </div>
                </form>
                <!-- <form action="upload.php" method="post" enctype="multipart/form-data">
                    <button class="pull-right btn btn-default btn-sm" id="add-row">
                        <input type="submit" name="submit" value="Submit">
                    </button>
                    <button class="pull-right btn btn-default btn-sm" style="margin-right: 10px;">
                        <input type="file" name="file" >
                    </button>
                </form> -->
                <input type="hidden" id="table-name" value="<?php echo $currentTable; ?>">
                <h3><?php echo ucwords($currentTable); ?> Table</h3>
            </legend>

            <!-- page data -->
            <div class="row">
                <div class="col-lg-12">
                    <?php if (sizeof($explode) > 1): ?>
                        <!-- a table is chosen in url -->
                        <?php
                        include('includes/tables.php');
                        $table = new TableClass(); // instance of the table class
                        /* get the right table */
                        switch ($currentTable) {
                            case 'visitor':
                                $table->visitorTable($queryResult, $pageno, $lastpage);
                                break;
                            case 'visiting':
                                $table->visitingTable($queryResult, $con);
                                break;
                            case 'interests':
                                $table->interestsTable($queryResult);
                                break;
                        }
                        ?>
                    <?php else: ?>
                        <!-- no table provided in url -->
                        <table class="table table-striped table-condensed table-hover">
                            <?php foreach ($tables as $table) {
                                echo '
                                    <tr>
                                        <td>
                                            <a href="'.$page.'?'.$table.'">
                                                '.ucwords($table).'
                                            </a>
                                        </td>
                                    </tr>';
                            } ?>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>
