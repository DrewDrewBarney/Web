<?php


class TokenTypes{
    const ATYPICAL = 999;
    const NUMBER = 0;
    const DECIMAL = 1;
    const ALPHA = 2;
    const ALPHANUMERIC = 3;
    const OPERATOR = 4;
    const BRACE = 5;
    const PUNCT = 6;
    
    const MAP = [
        self::ATYPICAL => 'atypical',
        self::NUMBER => 'number',
        self::DECIMAL => 'decimal',
        self::ALPHA => 'alpha',
        self::ALPHANUMERIC => 'alphanumeric',
        self::OPERATOR => 'operator',
        self::BRACE => 'brace',
        self::PUNCT => 'punctuation'
    ];
    
    static function typeString(int $type):string{
        return isset(self::MAP[$type]) ? self::MAP[$type] : self::MAP[self::ATYPICAL];
    }
}

class Token {

    public string $token = '';
    public int $type = TokenTypes::ATYPICAL;

    function __construct(string $token, int $type) {
        $this->token = $token;
        $this->type = $type;
    }
    
    function echo(){
        echo '<h3>' . $this->token . ' </h3> ' . TokenTypes::typeString($this->type) . '<br>';
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
        $this->map->start();
        $this->chr = $this->text->get();
    }

    function getToken(): ?Token {

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

        return $token === '' ? null : new Token($token, $this->map->type());
    }
}
