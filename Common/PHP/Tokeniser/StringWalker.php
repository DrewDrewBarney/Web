<?php

class StringWalker {

    private int $cursor = 0;
    private string $text = '';

    function __construct(string $text) {
        $this->text = $text . chr(0); // zero terminated string
    }
    
    function start():void{
        $this->cursor = 0;
    }

    public function peek(): string {
        return $this->text[$this->cursor];
    }

    public function atEnd(): bool {
        return $this->peek() === chr(0);
    }

    public function advance(): void {
        if (!$this->atEnd()) {
            $this->cursor++;
        }
    }

    public function get(): string {
        $result = $this->peek();
        $this->advance();
        return $result;
    }

    public function test(): void {
        $this->start();
        while (!$this->atEnd()){
            echo $this->get();
        }
    }
}
