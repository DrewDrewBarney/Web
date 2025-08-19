<?php

class TokenMap {
    
    //
    // TOKEN TYPES
    //
    const NULL = 'NULL';
    const NO_TYPE = 'NO_TYPE';

    protected ?TokenMapNode $entry = null;
    protected ?TokenMapNode $cursor = null;
    protected $tokenType = self::NO_TYPE;

    function __construct() {
        $this->entry = new TokenMapNode(self::NO_TYPE);
        $this->start();
    }

    function start(): void {
        $this->cursor = $this->entry;
    }

    function onTrack(string $char): bool {
        return $this->cursor->onTrack($char);
    }

    function step(string $char): bool {
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
