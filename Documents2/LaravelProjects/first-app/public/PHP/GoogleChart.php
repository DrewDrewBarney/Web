<!DOCTYPE html>

<?php
include_once 'all.php';

class GoogleChart {

    private string $chartID;
    private int $width;
    private int $height;
    private string $title;
    private string $xLabel;
    private string $yLabel;
    private array $data;
    private int $size;

    function __construct(string $chartID = 'myChart', int $width = 600, int $height = 600) {
        $this->chartID = $chartID;
        $this->width = $width;
        $this->height = $height;
        $this->title = 'Google Chart';
        $this->xLabel = 'x axis';
        $this->yLabel = 'y axis';
        $this->data = [];
        $this->size = 0;
        //$this->spoofData();
    }

    private function spoofData() {
        for ($x = 0; $x < 10; $x++) {
            $y = $x ** 2;
            $this->addPair($x, $y);
        }
    }

    private function toString() {
        $result = '';
        for ($i = 0; $i < sizeof($this->data); $i++) {
            $pair = $this->data[$i];
            $x = $pair[0];
            $y = $pair[1];
            $result .= "[$x, $y],";
        }
        return $result;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setXlabel($xLabel) {
        $this->xLabel = $xLabel;
    }

    function setYlabel($yLabel) {
        $this->yLabel = $yLabel;
    }

    function addPair($pair) {
        $this->data[] = $pair;
        $this->size++;
    }

    function obtainAverages() {
        $n = 0;
        $sumX = 0;
        $sumY = 0;
        $mx = 0;
        $my = 0;

        foreach ($this->data as list($x, $y)) {
            $sumX += $x;
            $sumY += $y;
            $n++;
        }

        if ($n) {
            $mx = $sumX / $n;
            $my = $sumY / $n;
        }

        return[$mx, $my];
    }

    function obtainCoefficients($mx, $my) {
        $sumXY = 0;
        $sumXX = 0;
        foreach ($this->data as list($x, $y)) {
            $sumXY += ($x - $mx) * ($y - $my);
            $sumXX += ($x - $mx) ** 2;
        }
        if ($sumXX){
            $b1 = $sumXY / $sumXX;
            $b0 = $my - $b1 * $mx;
            return [$b0, $b1];
        } else {
            return [0,0];
        }
    }
    
    
    function obtainR2($my, $b0, $b1){
        $SSR = 0;
        $SST = 0;
        foreach ($this->data as list($x, $y)){
            $f = $b0 + $b1 * $x;
            $SSR += ($y - $f)**2;
            $SST += ($y - $my)**2;
        }
        if ($SST){
            return 1 - $SSR/$SST;
        } else {
            return 0;
        }
    }

    function regress() {
        // calculate the means
        list($mx, $my) = $this->obtainAverages();
        // calculate the coefficients
        list($b0, $b1) = $this->obtainCoefficients($mx, $my);
        // get R**2
        $R2 = $this->obtainR2($my, $b0, $b1);
        return [$b0, $b1, $R2];
    }

    function makeHeadScript(): Tag {
        return Tag::make('script', '', ['type' => 'text/javascript', 'src' => 'https://www.gstatic.com/charts/loader.js']);
    }

    private function makeChartScript(): Tag {

        $scriptString = "google.charts.load('current', {packages: ['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                // Set Data
                const data = google.visualization.arrayToDataTable([ ['$this->xLabel', '$this->yLabel']," .
                $this->toString() .
                "]);
                // Set Options
                const options = {
                    title: '$this->title',
                    hAxis: {title: '$this->xLabel'},
                    vAxis: {title: '$this->yLabel'},
                    pointSize: 1,
                    legend: 'none',
                    trendlines: { 0: 
                        { 
                        type: 'linear',
                        showR2: true,
                        color: 'red',
                        lineWidth: 5,
                        opacity: 0.5,
                        visibleInLegend: true
                        } 
                    }  
                };
                // Draw
                const chart = new google.visualization.ScatterChart(document.getElementById('$this->chartID'));
                chart.draw(data, options);
            }";

        $script = Tag::make('script', $scriptString);

        return $script;
    }

    private function makeChartdiv(): Tag {
        $chartStyle = 'width: ' . $this->width . 'px; ' .
                'max-width: ' . $this->width . 'px; ' .
                'height: ' . $this->height . 'px; ' .
                'display: block; margin: 0 auto;'
        ;

        return Tag::make('div', '', ['id' => $this->chartID, 'style' => $chartStyle]);
    }

    function makeChart(): Tag {
        $div = $this->makeChartdiv();
        if ($this->size) {
            $div->addChild($this->makeChartScript());
        } else {
            $div->makeChild('p', 'TCX does not seem to contain the relevant metrics for plotting. ', ['class' => 'error']);
        }
        return $div;
    }
}
