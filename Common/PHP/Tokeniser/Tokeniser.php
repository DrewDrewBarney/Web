<?php



class Tokeniser {

    private ?StringWalker $text = null;
    private ?TokenMap $map = null;
    private string $chr = '';
    private ?MathToken $token = null;

    function __construct(string $text, TokenMap $map) {
        $this->text = new StringWalker($text);
        $this->map = $map;
        $this->reset();
    }

    function reset(): void {
        $this->text->start();
        $this->map->start();
        $this->token = new MathToken('', Token::NULL);
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

        $this->token = $token === '' ? new MathToken('Token is null', Token::NULL) : new MathToken($token, $this->map->type());
    }

   
    function token(): ?MathToken {
        return $this->token;
    }
}
