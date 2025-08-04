<?php

class MathTokenMap extends TokenMap {

    //
    // STRINGS DEFINING PRIMITIVE CHAR SETS
    //
    protected string $alphaChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    protected string $numberChars = "0123456789";
    protected string $alphaNumericChars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    protected string $punctChars = ".,;:";
    protected string $opChars1 = "+-*/^!";
    protected string $braceChars = "()[]{}";
    //
    // ASSOCIATIVE ARRAYS WITH ALLOWED TRANSITIONS
    //
    protected array $entry = [];
    protected array $alphaNumeric = [];
    protected array $number = [];
    protected array $decimal = [];
    protected array $opEnd = [];
    protected array $less = [];
    protected array $greater = [];
    protected array $equals = [];
    protected array $punctEnd = [];
    protected array $braceEnd = [];

    function __construct() {

        // ALPHANUMERIC

        $this->alphaNumeric = [
            'alphanumeric',
            $this->alphaNumericChars => &$this->alphaNumeric
        ];

        // NUMBERS

        $this->number = [
            'number',
            $this->numberChars => &$this->number,
            '.' => &$this->decimal
        ];

        $this->decimal = [
            'decimal',
            $this->numberChars => &$this->decimal
        ];

        // OPERATORS

        $this->opEnd = [
            'operator'
        ];

        $this->less = [
            'operator',
            '=' => $this->opEnd,
            '>' => $this->opEnd,
        ];

        $this->greater = [
            'operator',
            '=' => $this->opEnd,
        ];

        $this->equals = [
            'operator',
            '=' => $this->opEnd
        ];

        // PUNCTUATION

        $this->punctEnd = [
            'punctuation'
        ];

        // BRACES

        $this->braceEnd = [
            'brace'
        ];

        // ENTRY

        $this->entry = [
            $this->alphaChars => &$this->alphaNumeric,
            $this->numberChars => &$this->number,
            $this->punctChars => &$this->punctEnd,
            $this->braceChars => &$this->braceEnd,
            $this->opChars1 => &$this->opEnd,
            '<' => &$this->less,
            '>' => &$this->greater,
            '=' => &$this->equals
        ];
    }
}
