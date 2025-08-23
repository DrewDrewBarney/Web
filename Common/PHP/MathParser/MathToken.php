<?php

class MathToken extends Token {

    //
    // TOKEN TYPES
    //
    //const ALPHA = 'ALPHA';
    const ALPHA_NUMERIC = 'ALPHA_NUMERIC';
    const NUMBER = 'NUMERIC';
    //const DECIMAL = 'DECIMAL';
    const PUNCTUATION = 'PUNCTUATION';
    const OPERATOR = 'OPERATOR';
    const BRACE = 'BRACE';
    const STRING = 'STRING';
    //
    //
    // MAP ALPHA OPERATORS TO OPERATOR TYPE
    //
    //
    const ALPHA_OP_MAP = [
        'sqrt' => self::OPERATOR
    ];
    //
    //
    //
    //
    //
    // UTF-8 replacements for the SWAPS map
    //
    const PLUS_MINUS = "\u{00B1}";
    const TIMES = "\u{22C5}";
    const DIVIDE = "\u{00F7}";
    const LESS_EQ = "\u{2264}";
    const GREATER_EQ = "\u{2265}";
    const NOT_EQUAL = "\u{2260}";
    const MUCH_LESS_THAN = "\u{226A}";
    const MUCH_GREATER_THAN = "\u{226B}";
    

    public string $token = '';
    public string $type = TokenMap::NO_TYPE;
    public string $prettyToken = '';

    function __construct(string $token, string $type) {
        $this->SWAPS = [
            '*' => self::TIMES,
            '+-' => self::PLUS_MINUS,
            '/' => self::DIVIDE,
            '<=' => self::LESS_EQ,
            '>=' => self::GREATER_EQ,
            '<>' => self::NOT_EQUAL,
            '<<' => self::MUCH_LESS_THAN,
            '>>' => self::MUCH_GREATER_THAN
        ];
        $this->token = $token;
        $this->type =  isset(self::ALPHA_OP_MAP[$this->token]) ? self::ALPHA_OP_MAP[$this->token]: $type;
        $this->prettyMap();
    }

    function prettyMap(): void {

        if (substr($this->token, 0, 1) === "'" && substr($this->token, -1, 1) === "'") { // token is a string
            $this->prettyToken = substr($this->token, 1, strlen($this->token) - 2);
        } else if (isset($this->SWAPS[$this->token])) {
            $this->prettyToken = $this->SWAPS[$this->token];
        } else {
            $this->prettyToken = $this->token;
        }
    }

    function echo() {
        echo '<h3>' . $this->token . ' </h3> ' . $this->type . '<br>';
    }
}
