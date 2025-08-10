<?php


class TokenTypes{
    const ATYPICAL = 999;
    const NULL = 1000;
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
    
    const SWAPS = [
        '*'=>'x'
    ];
    

    public string $token = '';
    public int $type = TokenTypes::ATYPICAL;
    public string $prettyToken = '';

    function __construct(string $token, int $type) {
        $this->token = $token;
        $this->type = $type;
        $this->prettyMap();
    }
    
    function isAcceptableType(array $types):bool{
        return in_array($this->type, $types);
    }
    
    function isAcceptableToken(array $tokens):bool{
        return in_array($this->token, $tokens);
    }
    
    function prettyMap():void{
        if (isset(self::SWAPS[$this->token])){
            $this->prettyToken = self::SWAPS[$this->token];
        } else {
            $this->prettyToken = $this->token;
        }
    }
        
    
    function echo(){
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
        
        

        $this->token = $token === '' ? new Token('', TokenTypes::NULL) : new Token($token, $this->map->type());
    }
    
    function token():Token{
        return $this->token;
    }
    
    
}
