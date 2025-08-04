<?php

class TokenMap {

    protected array $entry = [];
    protected string $tokenType = '';
    protected array $cursor = ['nothing'];

     function start(): void {
        $this->cursor = &$this->entry;
    }

    function charIsOnTrack(string $char): bool {
        foreach ($this->cursor as $key => $value) {
            if ($this->within($key, $char)) {
                return true;
            }
        }
        return false;
    }

    function walk(string $char): bool {
        foreach ($this->cursor as $key => $value) {
            if ($this->within($key, $char)) {
                $this->cursor = &$value;
                return true;
            }
        }
        return false;
    }

    function type(): string {
        return $this->cursor[0];
    }

    // just a wrapper for clunky strpos

    private function within(string $key, string $char): bool {
        return is_string($key) ? strpos($key, $char) !== false : false;
    }
}
