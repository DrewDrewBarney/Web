<?php

class TokenMap {

    protected ?TokenMapNode $entry = null;
    protected ?TokenMapNode $cursor = null;
    protected $tokenType = 'atypical';

    function __construct() {
        $this->entry = new TokenMapNode('atypical');
        $this->start();
    }

    function start(): void {
        $this->cursor = &$this->entry;
    }

    function charIsOnTrack(string $char): bool {
        return $this->cursor->onTrack($char);
    }

    function walk(string $char): bool {
        if ($this->cursor) {
            $this->cursor = $this->cursor->next($char);
            if ($this->cursor) {
                // get token type from the last non-null cursor
                $this->tokenType = $this->cursor->tokenType();
                return true;
            }
        }
        return false;
    }

    function type(): string {
        return $this->tokenType;
    }
}
