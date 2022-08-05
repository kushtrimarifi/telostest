<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Amount of Concurrent Events Chart</title>
</head>

<body>
    <center>
        <div style="max-width: 60%; margin-top: 50px !important;">
            <?php
            use Hisune\EchartsPHP\Doc\IDE\Series;
            header('Content-Type: text/html; charset=utf-8');
            require_once('./vendor/autoload.php');
            require_once('Events.php');

            $time_start = microtime(true);

            $events = new Events();
            try {
                $events->getEvents();
            } catch (Exception $ex) {
                echo 'Error: ' . $ex->getMessage();
            }


            $datapoints = $events->labels;
            $datas[] = $events->points;
            $title = 'Amount of Concurrent Events Chart';
            $legendtitle = 'Amount of Events';
            $chart = new Hisune\EchartsPHP\ECharts();
            $xAxis = new Hisune\EchartsPHP\Doc\IDE\XAxis();
            $yAxis = new Hisune\EchartsPHP\Doc\IDE\YAxis();

            $title && $chart->title->text = $title;
            $chart->color = '#91c7ae';
            $chart->tooltip->trigger = 'axis';
            $chart->toolbox->show = true;
            $chart->toolbox->feature->dataZoom->yAxisIndex = 'none';
            $chart->toolbox->feature->dataView->readOnly = false;
            $chart->toolbox->feature->magicType->type = ['line', 'bar'];
            $chart->toolbox->feature->saveAsImage = [];
            $xAxis->type = 'category';
            $xAxis->boundaryGap = false;
            $xAxis->data = $datapoints;
            $chart->dataZoom->type = 'slider';
            foreach ($datas as $serie) {
                $series = new Series();
                $chart->legend->data[] = $legendtitle;
                $series->name = $legendtitle;
                $series->data = $serie;
                $series->type = isset($ser['type']) ?: 'bar';
                $series->large = 'true';
                $chart->addSeries($series);
            }

            $chart->addXAxis($xAxis);
            $chart->addYAxis($yAxis);

            echo $chart->render(uniqid());
            ?>
        </div>
    </center>
</body>

</html>