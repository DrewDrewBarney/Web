<?php

//include_once '../../Common/PHP/all.php';

class GoogleChart {

    //private int $size = 0;
    private string $chartID;
    private int $width;
    private int $height;
    private string $title;
    private array $hAxisAttributes;
    private array $vAxisAttributes;
    private array $data;
    // whole chart attributes
    private string $style; // linear or curve
    private array $chartStyle = [];
    // just series attributes
    private array $chartStyles = [];
    private array $trendlines = [];
    private array $tableLabels;

    function __construct(Tag $head, string $chartID = 'myChart', int $width = 50, int $height = 50) {
        $this->chartID = $chartID;
        $this->width = $width;
        $this->height = $height;
        $this->title = 'Google Chart';
        $this->hAxisAttributes = ['title' => "'x-axis'"];
        $this->vAxisAttributes = ['title' => "'y-axis'"];
        $this->data = [];
        $this->trendlines = [];

        $this->style = 'scatter';
        $this->chartStyle = [
            'legend' => '"none"'
        ];

        $this->tableLabels = ["'x'", "'y'"];

        $head->makeChild('script', '', ['type' => 'text/javascript', 'src' => 'https://www.gstatic.com/charts/loader.js']);
    }

    public function spoofData() {
        for ($x = 0; $x < 10; $x++) {
            $y = $x ** 2;
            $this->addPair([$x, $y]);
        }
    }

    private function toString() {
        $result = '[';
        foreach ($this->tableLabels as $label) {
            $result .= $label . ',';
        }
        $result .= '],';

        foreach ($this->data as $values) {
            $result .= '[';
            foreach ($values as $value) {
                $result .= $value . ',';
            }
            $result .= '],';
        }
        return $result;
    }

    function setChartStyle(array $options) {
        foreach ($options as $key => $value) {
            $this->chartStyle[$key] = $value;
        }
    }

    function getChartStyle(): string {
        $result = '';
        foreach ($this->chartStyle as $key => $value) {
            $result .= $key . ':' . $value . ", \n";
        }
        return $result;
    }

    function addSeriesStyle(array $options) {
        $style = [];
        foreach ($options as $key => $value) {
            $style[$key] = $value;
        }
        $this->chartStyles[] = $style;
    }

    function getSeriesStyleString() {
        $result = "series : {\n";
        $i = 0;
        foreach ($this->chartStyles as $style) {
            $result .= $i . ': {';
            foreach ($style as $key => $value) {
                $result .= "$key : $value, ";
            }
            $i++;
            $result .= "} ,\n";
        }
        $result .= '}';
        return $result;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setHAxis(array $attributes) {
        foreach ($attributes as $key => $value) {
            $this->hAxisAttributes[$key] = $value;
        }
    }

    function getHAxis(): string {
        $result = '{';
        foreach ($this->hAxisAttributes as $key => $value) {
            $result .= "$key : $value ,";
        }
        $result .= '}';
        return $result;
    }

    function setVAxis(array $attributes) {
        foreach ($attributes as $key => $value) {
            $this->vAxisAttributes[$key] = $value;
        }
    }

    function getVAxis(): string {
        $result = '{';
        foreach ($this->vAxisAttributes as $key => $value) {
            $result .= "$key : $value ,";
        }
        $result .= '}';
        return $result;
    }

    function addTrendine(array $options = []) {

        $type = array_key_exists('type', $options) ? $options['type'] : 'linear'; // defaults to linear trendline
        $showR2 = array_key_exists('showR2', $options) ? $options['showR2'] : 'true'; // defaults to linear trendline
        $color = array_key_exists('color', $options) ? $options['color'] : 'red'; // defaults to linear trendline
        $lineWidth = array_key_exists('lineWidth', $options) ? $options['lineWidth'] : '3'; // defaults to linear trendline
        $pointSize = array_key_exists('pointSize', $options) ? $options['pointSize'] : '0'; // defaults to linear trendline
        $opacity = array_key_exists('opacity', $options) ? $options['opacity'] : '1.0'; // defaults to linear trendline
        $visibleInLegend = array_key_exists('visibleInLegend', $options) ? $options['visibleInLegend'] : 'false'; // defaults to linear trendline


        $count = count($this->trendlines);
        $this->trendlines[] = "$count: 
            { 
            type: '$type',
            showR2: $showR2,
            color: '$color',
            lineWidth: $lineWidth,
            pointSize: $pointSize,
            opacity: $opacity,
            visibleInLegend: $visibleInLegend
            }";
    }

    private function getTrendlinesString() {
        $result = "";
        if (count($this->trendlines)) {
            $result = 'trendlines: {';
            foreach ($this->trendlines as $trendline) {
                $result .= $trendline;
            }
            $result .= '},';
        }
        return $result;
    }

    function setTableLabels(array $labels) {
        $this->tableLabels = $labels;
    }

    function addPair(array $pair) {
        $this->data[] = $pair;
        //$this->size++;
    }
    
    function size():int{
        return sizeof($this->data);
    }

    function obtainAverages() {
        $n = 0;
        $sumX = 0;
        $sumY = 0;
        $mx = null;
        $my = null;

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
        if ($sumXX) {
            $b1 = $sumXY / $sumXX;
            $b0 = $my - $b1 * $mx;
            return [$b0, $b1];
        } else {
            return [null, null];
        }
    }

    function obtainR2($my, $b0, $b1) {
        $SSR = 0;
        $SST = 0;
        foreach ($this->data as list($x, $y)) {
            $f = $b0 + $b1 * $x;
            $SSR += ($y - $f) ** 2;
            $SST += ($y - $my) ** 2;
        }
        if ($SST) {
            return 1 - $SSR / $SST;
        } else {
            return null;
        }
    }

    function regress() {
        // calculate the means
        list($mx, $my) = $this->obtainAverages();
        // calculate the coefficients
        list($b0, $b1) = $this->obtainCoefficients($mx, $my);
        // get R**2
        if ($my && $b0 && $b1) {
            $R2 = $this->obtainR2($my, $b0, $b1);
            return [$b0, $b1, $R2];
        } else {
            return [null, null, null];
        }
    }

    private function getChartOfStyle(string $chartStyle) {
        switch ($chartStyle) {
            case 'line':
                return "new google.visualization.LineChart(document.getElementById('$this->chartID'))";

            default:
                return "new google.visualization.ScatterChart(document.getElementById('$this->chartID'))";
        }
    }

    private function makeChartScript(): Tag {
        //$this->lineWidth = $this->style == 'scatter' ? '0' : $this->lineWidth;

        $chart = $this->getChartOfStyle('line');
        $hAxis = $this->getHAxis();
        $vAxis = $this->getVAxis();
        $chartStyle = $this->getChartStyle();
        $seriesStyles = $this->getSeriesStyleString();

        $trendlinesString = $this->getTrendlinesString();

        $scriptString = "google.charts.load('current', {packages: ['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                // Set Data
                const data = google.visualization.arrayToDataTable([" . $this->toString() . "]);
                
                    
                // Set Options
                const options = {
                    title: '$this->title',
                    hAxis: $hAxis ,
                    vAxis: $vAxis ,
                    $chartStyle 
                    $seriesStyles ,
                    $trendlinesString
                };
                
                // Draw
                var chart = $chart;
                chart.draw(data,options);
                
            }";

        $script = Tag::make('script', $scriptString);

        return $script;
    }

    private function makeChartdiv(): Tag {
        $chartStyle = 'width: ' . $this->width . 'vw; ' .
                'max-width: ' . $this->width . 'vh; ' .
                'height: ' . $this->height . 'vh;' .
                'display: block; margin: 0 auto;'
        ;

        //. $this->height . ' vh; ' .

        return Tag::make('div', '', ['id' => $this->chartID, 'style' => $chartStyle]);
    }

    function makeChart(): Tag {
        $div = null;
        if (count($this->data)) {
            $div = $this->makeChartdiv();
            $div->addChild($this->makeChartScript());
        } else {     
            $div = Tag::make('div', 'No required data! ', ['class' => 'error']);
        }
        return $div;
    }
}
