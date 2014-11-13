<?php

session_start();
if (! isset($_SESSION['username'])) {
    /* redirect user if not logged in */
    header("location:login.php");
}

include('includes/config.php');

/* set the current page active in navbar */
$summary = $home = null;
$page = getenv('REQUEST_URI');
if (strpos($page, "summary.php")) {
    $page = 'summary.php';
    $summary = 'active';
} else {
    $page = 'index.php';
    $home = 'active';
}

/* get tables */
$sql = 'SHOW TABLES';
$allTables = mysqli_query($con, $sql);

/* tables to exclude */
$exclude = array('login', 'mapping');
$tables  = array();
while($table = $allTables->fetch_assoc()) {
    if (! in_array($table['Tables_in_visit_anderson'], $exclude)) {
        $tables[] = $table['Tables_in_visit_anderson'];
    }
}

/* get current table */
$url          = $_SERVER['HTTP_HOST']."".$_SERVER['REQUEST_URI'];
$explode      = explode('?', $url); // seperate before/after ? is found
$currentTable = end($explode); // get the part after the ?
if (sizeof($explode) == 1) {
    $currentTable = ''; // there is no table selected
} else {
    /* a table is selected, only get the table name */
    $explode2     = explode('&', $currentTable); // looking for url parameters
    $currentTable = $explode2[0];
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Visit Anderson</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
    <link href="css/custom.css" rel="stylesheet" type="text/css" />
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="js/jQuery-Multiple-Select/css/bootstrap-multiselect.css" type="text/css">
    <script src="js/jquery.js" ></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootstrap-datetimepicker.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/jQuery-Multiple-Select/js/bootstrap-multiselect.js"></script>
    <script src="http://code.highcharts.com/highcharts.js"></script>
</head>
<body>

<!-- main navbar -->
<nav class="navbar navbar-green navbar-fixed-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header" style="width: 200px;">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="index.php" style="font-size: 20px;">Visit Anderson</a>
    </div>

    <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
            <li class="<?php echo $home; ?>">
                <a href="index.php"><i class="fa fa-database"></i> Content</a>
            </li>
            <li class="<?php echo $summary; ?>">
                <a href="summary.php"><i class="fa fa-bar-chart"></i> Summary</a>
            </li>
        </ul>
        <ul class="nav navbar-nav pull-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    Logged in as <?php echo $_SESSION['username']; ?> <span class="caret"></span>
                </a>
                <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- sidenav, contains table names -->
<nav style="width: 250px;">
    <div class="navbar-default sidebar">
        <div class="sidebar-nav navbar-collapse">
            <p class="sidebar-title">Tables</p>
            <ul class="nav tabs" id="side-menu">
                <?php foreach ($tables as $table): ?>
                    <hr>
                    <li class="tab-content <?php if($table == $currentTable){echo 'active';} ?>">
                        <a href="<?php echo $page.'?'.$table; ?>">
                            <?php echo ucwords($table); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <hr>
            </ul>
        </div>
    </div>
</nav>
