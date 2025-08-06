<?php

class TokenMapNode {

    protected array $validSteps = [];
    protected string $tokenType = 'atypical';

    public function __construct(string $type) {
        $this->validSteps = [];
        $this->tokenType = $type;
    }

    public function addStep(string $set, TokenMapNode &$node) {
        $this->validSteps[$set] = $node;
    }

    public function onTrack(string $char): bool {
        foreach ($this->validSteps as $key => $value) {
            $within = $this->within($char, $key);
            if ($within) {
                return true;
            }
        }
        return false;
    }

    public function &next(string $char): ?TokenMapNode {
        $result = null;
        foreach ($this->validSteps as $key => $value) {
            $within = $this->within($char, $key);
            if ($within) {
                $result = $value;
            }
        }

        return $result;
        // if $char is present in one of the contained sets then it MUST return a valid TokenMapNode reference
        // else it returns null
    }

    public function tokenType(): string {
        return $this->tokenType;
    }

    // just a wrapper for clunky strpos

    private function within(string $char, string $key): bool {
        return is_string($key) ? strpos($key, $char) !== false : false;
    }
}
