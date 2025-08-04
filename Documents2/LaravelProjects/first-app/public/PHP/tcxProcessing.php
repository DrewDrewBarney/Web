<?php

include_once 'all.php';

class TCXprocessor {

    private $filePath = [];
    private $data = [
        'HRs' => [],
        'Alts' => [],
        'Dists' => [],
        'Speeds' => [],
        'Cads' => [],
        'Watts' => []
    ];
    private int $cursor = 0;
    private string $error = '';

    function __construct($filePath) {
        $this->filePath = $filePath;
    }

    function run() {
        if ($this->filePath) {
            try {
                $myXMLData = file_get_contents($this->filePath); // or die("Error: Cannot load file $this->filePath");
                $xml = simplexml_load_string(trim($myXMLData)); // or die("Error: Cannot create object");
                $this->processXML($xml);
            } catch (\Throwable $e) {
                $this->error = 'unable to process file';
            }
        }
    }

    private function processXML(SimpleXMLElement $xml) {
        foreach ($xml->Activities->Activity as $activity) {
            if ($activity->attributes()->Sport == 'Running') {
                $this->processActivity($activity);
                break;
            }
        }
    }

    private function processActivity(SimpleXMLElement $activity) {
        foreach ($activity->Lap as $lap) {
            $this->processLap($lap);
        }
    }

    function processLap(SimpleXMLElement $lap) {
        foreach ($lap->Track->Trackpoint as $trackpoint) {
            $this->processTrackPoint($trackpoint);
        }
    }

    function processTrackPoint(SimpleXMLElement $trackpoint) {

        $this->data['HRs'][] = floatval((string) $trackpoint->HeartRateBpm->Value);
        $this->data['Alts'][] = floatval((string) $trackpoint->AltitudeMeters);
        $this->data['Dists'][] = floatval((string) $trackpoint->DistanceMeters);

        $ns3 = $trackpoint->Extensions->children('ns3', true);

        $this->data['Speeds'][] = floatval((string) $ns3->TPX->Speed);
        $this->data['Cads'][] = floatval((string) $ns3->TPX->RunCadence);
        $this->data['Watts'][] = floatval((string) $ns3->TPX->Watts);
    }

    function first() {
        $this->cursor = 0;
    }

    function beyond() {
        return $this->cursor >= sizeof($this->data['HRs']);
    }

    function readPair(string $xKey, $yKey) {
        $xs = $this->data[$xKey];
        $ys = $this->data[$yKey];
        $x = $xs[$this->cursor];
        $y = $ys[$this->cursor];
        $this->cursor++;
        return [$x, $y];
    }

    function makeTags() {
        $div = Tag::make('div');

        $stats = new Stats();

        $stats->setDataPairs($this->data['Speeds'], $this->data['Watts']);
        list($b0, $b1) = $stats->coeffs();

        $div->makeChild('h2', "b0 = $b0, b1 = $b1");

        $plotter = new TablePlotter(200, 200);
        $plotter->setX(0, 5);
        $plotter->setY(0, 700);

        for ($i = 0; $i < $stats->n(); $i++) {
            list($x, $y) = $stats->getPair($i);
            $plotter->plot($x, $y, 'blue');
        }

        for ($x = 0; $x < 5; $x += 0.01) {
            $plotter->plot($x, $stats->Y($x), 'green');
        }


        $div->addChild($plotter->getTags());

        return $div;
    }
}
