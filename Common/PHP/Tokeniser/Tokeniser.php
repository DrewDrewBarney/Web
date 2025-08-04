<?php

class Token {

    public string $token = '';
    public string $type = '';

    function __construct(string $token, string $type) {
        $this->token = $token;
        $this->type = $type;
    }
}

class Tokeniser {

    private ?TokenMap $map = null;
    private ?StringWalker $text = null;
    private string $chr = '';

    function __construct(string $text, TokenMap $map) {
        $this->text = new StringWalker($text);
        $this->map = $map;
    }

    function prime(): void {
        $this->text->start();
        $this->chr = $this->text->get();
    }
    
    function atEnd():bool{
        return $this->text->atEnd();
    }

    function getToken(): Token {

        $token = '';
        $this->map->start();

        // gobble up any unrecognised characters which are considered as whitespace
        while (!$this->map->charIsOnTrack($this->chr) && !$this->text->atEnd()) {
            $this->chr = $this->text->get();
        }

        while ($this->map->walk($this->chr)) {
            $token .= $this->chr;
            $this->chr = $this->text->get();
        };
        return new Token($token, $this->map->type());
    }
}
