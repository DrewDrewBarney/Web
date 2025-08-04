<?php

include_once 'all.php';

class TablePlotter {

    private $width = 200;
    private $height = 200;
    private $table;
    private $x1;
    private $x2;
    private $y1;
    private $y2;

    function __construct($height, $width) {
        $this->height = $height;
        $this->width = $width;
        $this->x1 = 0;
        $this->x2 = $this->width - 1;
        $this->y1 = 0;
        $this->y2 = $this->height - 1;
        $this->makeCanvas();
    }

    function setY($low, $high) {
        $this->y1 = $low;
        $this->y2 = $high;
    }

    function setX($low, $high) {
        $this->x1 = $low;
        $this->x2 = $high;
    }

    private function makeCanvas() {
        $this->table = Tag::make('table', '', ['class' => 'tablePlotter']);
        for ($row = 0; $row < $this->height; $row++) {
            $tr = $this->table->makeChild('tr', '', ['class' => 'tablePlotter']);
            for ($col = 0; $col < $this->width; $col++) {
                $tr->makeChild('td', '', ['class' => 'tablePlotter']);
            }
        }
    }

    private function clip($row, $col) {
        if ($row < 0)
            $row = 0;
        if ($row > $this->height - 1)
            $row = $this->height - 1;
        if ($col < 0)
            $col = 0;
        if ($col > $this->width - 1)
            $col = $this->width - 1;
        return [$row, $col];
    }

    private function map($x, $y) {
        $row = ($this->height - 1) * ($this->y2 - $y) / ($this->y2 - $this->y1);
        $col = ($this->width - 1) * ($x - $this->x1) / ($this->x2 - $this->x1);
        list($crow, $ccol) = $this->clip($row, $col);
        return [$crow, $ccol];
    }

    private function plotRowCol($row, $col, $color = 'black') {
        $icol = (int) round($col);
        $irow = (int) round($row);
        $this->table->children()[$irow]->children()[$icol]->setAttributes(['style' => "background-color: $color;"]);
    }

    function plot($x, $y, $color = 'black') {
        list($row, $col) = $this->map($x, $y);
        $this->plotRowCol($row, $col, $color);
    }
    

   

    function getTags() {
        //$this->plot(0,0);
        return $this->table;
    }
}
