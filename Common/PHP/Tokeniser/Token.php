<?php

class Token {

    //
    // TOKEN TYPES
    //

    const NULL = 'NULL';
    const NO_TYPE = 'NO_TYPE';

    public array $SWAPS = [];
    public string $token = '';
    public string $type = self::NO_TYPE;
    public string $prettyToken = '';

    public function __construct(string $token, string $type) {
        $this->token = $token;
        $this->type = $type;
        $this->prettyMap();
    }

    protected function prettyMap(): void {

        if (substr($this->token, 0, 1) === '"' && substr($this->token, -1, 1) === '"') { // token is a string
            $this->prettyToken = substr($this->token, 1, strlen($this->token) - 2);
        } else if (isset(self::SWAPS[$this->token])) {
            $this->prettyToken = $this->SWAPS[$this->token];
        } else {
            $this->prettyToken = $this->token;
        }
    }

    public function isOfTypes(array $types): bool {
        return in_array($this->type, $types);
    }

    public function isOf(array $tokens): bool {
        return in_array($this->token, $tokens);
    }

    public function echo() {
        echo '<h3>' . $this->token . ' </h3> ' . TokenTypes::typeString($this->type) . '<br>';
    }
}
