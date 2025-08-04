<?php

class Stats {

    private array $x = [];
    private float $mx = 0;
    private $y = [];
    private float $my = 0;
    private int $n = 0;
    private float $b0;
    private float $b1;
    private bool $inError = false;
    private string $errorMessage;

    function clear() {
        $this->mx = 0;
        $this->my = 0;
        $this->x = [];
        $this->y = [];
        $this->inError = false;
        $this->errorMessage = '';
    }

    function setDataPairs($x, $y) {
        $this->clear();

        if (sizeof($x) == sizeof($y)) {
            $this->x = $x;
            $this->y = $y;
            $this->n = sizeof($x);
            $this->mx = $this::mean($this->x);
            $this->my = $this::mean($this->y);
            $this->regress();
        } else {
            die('x and y arrays are not of equal size');
        }
    }

    function n() {
        return $this->n;
    }

    function getPair($i) {
        return [$this->x[$i], $this->y[$i]];
    }

    static function mean($vs) {
        $sum = 0;
        $n = 0;
        foreach ($vs as $v) {
            $sum += $v;
            $n++;
        }
        return $sum / $n;
    }

    private function regress() {

        $sumTop = 0;
        $sumBottom = 0;
        for ($i = 0; $i < $this->n; $i++) {
            $sumTop += ($this->x[$i] - $this->mx) * ($this->y[$i] - $this->my);
            $sumBottom += ($this->x[$i] - $this->mx) ** 2;
        }
        $this->b1 = $sumTop / $sumBottom;
        $this->b0 = $this->my - $this->b1 * $this->mx;
    }

    public function Y($x) {
        return $this->b0 + $this->b1 * $x;
    }

    public function coeffs() {
        return [$this->b0, $this->b1];
    }
}
