<?php
class Charts {
    public function personPieChart() {
        include('includes/config.php');

    $sql = 'SELECT state, count(state) AS count
            FROM visitor
            GROUP BY state';

    $result = mysqli_query($con, $sql);

    $data = array();
    while ($row = mysqli_fetch_array($result)) {
        $state = $row['state'];
        $count = intval($row['count']);
        $item = array($state, $count);

        array_push($data, $item);
    }

    $data = json_encode($data);
?>
        <div id="pie-chart-locality"></div>
        <script>
        $(function() {
            $('#pie-chart-locality').highcharts({
                    title: {
                        text: 'State Locality'
                            },
                        series: [{
                            type: 'pie',
                                data: <?php echo $data ?>
                        }]
                        });
        });
        </script>
<?php
    }
    
    public function visitingBarChart() {
        include('includes/config.php');
        $sql = 'SELECT reason, count(reason) AS count, visit_date
            FROM visiting
            GROUP BY reason, visit_date';
        
        $result = mysqli_query($con, $sql);
        
        $data = array();
        while ($row = mysqli_fetch_array($result)) {
            if (!array_key_exists(ucwords(str_replace("_", " ", $row['reason'])), $data)) {
                $data[ucwords(str_replace("_", " ", $row['reason']))] = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
            }
            $reason = ucwords(str_replace("_", " ", $row['reason']));
            $month = strtotime($row['visit_date']);
            $index = intval(date('m', $month)) - 1;
            $count = intval($row['count']);
            
            $data[$reason][$index] = $count;
        }
        
        $out = '';
        foreach($data as $key => $value)
            $out = $out . '{ name: \'' . $key . '\', data: ' . json_encode($value) . ' }, ';
        $out = substr($out, 0, strlen($out) - 2);        
?>
        <div id='bar-chart-reason-month'></div>
        <script>
        $(function() {
            $('#bar-chart-reason-month').highcharts({
                    chart: {
                        type: 'column'
                            },
                        title: {
                        text: 'Reason for Visit by Month'
                            },
                        xAxis: {
                        title: {
                            text: 'Month'
                                },
                            categories: [
                                'January',
                                'February',
                                'March',
                                'April',
                                'May',
                                'June',
                                'July',
                                'August',
                                'September',
                                'October',
                                'November',
                                'December'
                            ]
                            },
                        yAxis: {
                        min: 0,
                            allowDecimals: false,
                            title: {
                            text: 'Visitors'
                                }
                    },
                        series: [
                           <?php echo $out ?>
                        ]
                        });
        });
        </script>
<?php
    }

    public function visitingPieChart() {
        include('includes/config.php');

        $sql = 'SELECT reason, count(reason) AS count
            FROM visiting
            GROUP BY reason';

        $result = mysqli_query($con, $sql);

        $data = array();
        while ($row = mysqli_fetch_array($result)) {
            $reason = ucwords(str_replace("_", " ", $row['reason']));
            $count = intval($row['count']);
            $item = array($reason, $count);

            array_push($data, $item);
        }
        $data = json_encode($data);

?>
        <div id="pie-chart-reason"></div>
        <script>
        $(function() {
            $('#pie-chart-reason').highcharts({
                    title: {
                        text: 'Reason for Visit'
                            },
                        series: [{
                            name: 'Planned visits',
                                type: 'pie',
                                data: <?php echo $data ?>
                        }]
                        });
        });
        </script>
<?php
    }
};
?>