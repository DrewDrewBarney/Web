<?php

//include_once '../../Common/PHP/all.php';

class FitnessFileProcessor { // VIRTUAL CLASS

    protected $filePath = '';
    protected $data = [
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

    // GET THE DATA FROM THE FILE
    function process(): void {
        // fills the data arrays with values
    }

    function setError(string $msg): void {
        $this->error = $msg;
    }

    function inError(): bool {
        return $this->error !== '';
    }

    // MOVE THROUGH THE DATA
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
}

class TCXprocessor extends FitnessFileProcessor {

    function __construct($filePath) {
        parent::__construct($filePath);
    }

    function process(): void {
        if ($this->filePath) {
            try {
                $myXMLData = file_get_contents($this->filePath); // or die("Error: Cannot load file $this->filePath");
                $xml = simplexml_load_string(trim($myXMLData)); // or die("Error: Cannot create object");
                $this->processXML($xml);
            } catch (\Throwable $e) {
                $this->setError('unable to process file');
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
        //$this->data['Alts'][] = floatval((string) $trackpoint->AltitudeMeters);
        $this->data['Dists'][] = floatval((string) $trackpoint->DistanceMeters);

        $ns3 = $trackpoint->Extensions->children('ns3', true);

        $this->data['Speeds'][] = floatval((string) $ns3->TPX->Speed);
        $this->data['Cads'][] = floatval((string) $ns3->TPX->RunCadence);
        $this->data['Watts'][] = floatval((string) $ns3->TPX->Watts);
    }
}

class FITprocessor extends FitnessFileProcessor {

    function __construct($filePath) {

        parent::__construct($filePath);
    }

    function process(): void {

        $parser = new ParseFit($this->filePath);
        $tables = $parser->parse();
        if ($tables) {
            $records = isset($tables['record']) ? $tables['record'] : null;
            if ($records) {
                foreach ($records as $record) {

                    $this->data['HRs'][] = floatval($record['heart_rate']);
                    //$this->data['Alts'][] = floatval($record['enhanced_altitude']);
                    $this->data['Dists'][] = floatval($record['distance']);
                    $this->data['Speeds'][] = floatval($record['enhanced_speed']) / 1000;
                    $this->data['Cads'][] = floatval($record['cadence']);
                    $this->data['Watts'][] = floatval($record['power']);
                }
            }
        }
    }
}
