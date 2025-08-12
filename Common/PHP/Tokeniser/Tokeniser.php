<?php

class Token {

    const SWAPS = [
        '*' => 'x',
    ];

    public string $token = '';
    public int $type = TokenTypes::ATYPICAL;
    public string $prettyToken = '';

    function __construct(string $token, int $type) {
        $this->token = $token;
        $this->type = $type;
        $this->prettyMap();
    }

    function prettyMap(): void {
        
        if (substr($this->token, 0, 1) === '"' && substr($this->token, -1, 1) === '"'){ // token is a string
            $this->prettyToken = substr($this->token, 1, strlen($this->token) - 2);
        } else if (isset(self::SWAPS[$this->token])) {
            $this->prettyToken = self::SWAPS[$this->token];
        } else {
            $this->prettyToken = $this->token;
        }
    }

    function echo() {
        echo '<h3>' . $this->token . ' </h3> ' . TokenTypes::typeString($this->type) . '<br>';
    }
}

class Tokeniser {

    private ?StringWalker $text = null;
    private ?TokenMap $map = null;
    private string $chr = '';
    private ?Token $token = null;

    function __construct(string $text, TokenMap $map) {
        $this->text = new StringWalker($text);
        $this->map = $map;
        $this->reset();
    }

    function reset(): void {
        $this->text->start();
        $this->map->start();
        $this->token = new Token('', TokenTypes::NULL);
        $this->chr = $this->text->get();
    }

    function get(): void {

        $token = '';
        $this->map->start();

        // gobble up any unrecognised characters which are considered as whitespace
        while (!($this->map->onTrack($this->chr) || $this->text->atEnd())) {
            $this->chr = $this->text->get();
        }

        while ($this->map->step($this->chr)) {
            $token .= $this->chr;
            $this->chr = $this->text->get();
        };

        $this->token = $token === '' ? new Token('Token is null', TokenTypes::NULL) : new Token($token, $this->map->type());
    }

   
    function token(): ?Token {
        return $this->token;
    }
}
