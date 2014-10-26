<?php
include('includes/header.php');

/* database calls */
if ($currentTable == 'person') { // person table
    $sql = 'SELECT * FROM person';
}
elseif ($currentTable == 'visiting') { // visiting table
    /* get from mapping table then join person and visiting table */
    $sql = 'SELECT person.first_name, person.last_name, person.email, person.id,
                   visiting.reason, visiting.visit_date, visiting.updated_at 
            FROM mapping 
            INNER JOIN person ON mapping.person_id = person.id
            INNER JOIN visiting ON mapping.table_id = visiting.id';
}
elseif ($currentTable == 'intrests') {
    $sql = 'SELECT * FROM intrests';
}
$queryResult = mysqli_query($con, $sql);

?>

<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <br><br>
            <!-- success and error messages -->
            <?php if (isset($_GET['error']) == 1): ?>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <strong>ERROR:</strong> First Name, Last Name, and Email cannot all be blank
                </div>
            <?php elseif (isset($_GET['success']) == "add"): ?>
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <strong>Success!</strong> The person was successfully added.
                </div>
            <?php endif; ?>

            <a id="top"></a>

            <!-- page title -->
            <legend>
                <button class="pull-right btn btn-green btn-sm" id="add-row">
                    <i class="fa fa-plus"></i> Add
                </button>
                <button class="pull-right btn btn-default btn-sm" style="margin-right: 10px;">
                    <i class="fa fa-file"></i> Add From File
                </button>
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
                            case 'person':
                                $table->personTable($queryResult);
                                break;
                            case 'visiting':
                                $table->visitingTable($queryResult);
                                break;
                            case 'intrests':
                                $table->intrestsTable($queryResult);
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
