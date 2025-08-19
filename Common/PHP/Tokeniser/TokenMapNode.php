<?php

class TokenMapNode {

    protected array $validSteps = [];
    protected string $tokenType = TokenMap::NO_TYPE;

    public function __construct(string $type) {
        $this->validSteps = [];
        $this->tokenType = $type;
    }

    static public function make(string $type): TokenMapNode {
        return new TokenMapNode($type);
    }

    private function check(string $newSet): void {
        // check that all set intersections are empty
        // throw an exception if they are not

        foreach (str_split($newSet) as $char) {
            foreach ($this->validSteps as $charSet => $value) {
                if (strpos($charSet, $char) !== false) {
                    throw new Exception('Overlapping character set on TokenMapNode->check');
                }
            }
        }
    }

    public function addChild(string $setOfChars, TokenMapNode $node): TokenMapNode {
        $this->check($setOfChars);
        $this->validSteps[$setOfChars] = $node;
        return $node;
    }

    public function makeChild(string $setOfChars, string $type = ''): TokenMapNode {
        $type = $type === '' ? $this->tokenType : $type;
        return $this->addChild($setOfChars, new TokenMapNode($type));
    }

    public function makeChildren(array $setsOfChars, string $type): TokenMapNode {
        $current = $this;
        foreach ($setsOfChars as $setOfChars) {
            $current = $current->makeChild($setOfChars, $type);
        }
        return $this;
    }

    public function onTrack(string $char): bool {
        foreach ($this->validSteps as $key => $value) {
            if ($this->within($char, $key))
                return true;
        }
        return false;
    }

    public function next(string $char): ?TokenMapNode {
        $result = null;
        foreach ($this->validSteps as $key => $value) {
            if ($this->within($char, $key))
                return $value;
        }

        return null;
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

    // View the tree
    ////////////////
    //
    public function toString(string $tab = ''): string {
        $result = $tab . $this->tokenType() . "\n";

        if (strlen($tab) < 6) {
            foreach ($this->validSteps as $key => $value) {
                if ($key != null && $value != null) {
                    $result .= $tab . substr($key, 0, 10) . "\n";
                    $result .= $value->toString('  ' . $tab);
                }
            }
        }
        return $result;
    }
}
