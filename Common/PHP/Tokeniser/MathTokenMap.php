<?php

class MathTokenMap extends TokenMap {

    //
    // STRINGS DEFINING PRIMITIVE CHAR SETS
    //
    protected string $alphaChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    protected string $numberChars = "0123456789";
    protected string $alphaNumericChars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    protected string $punctChars = ".,;:";
    protected string $opChars1 = "+-*/_^!";
    protected string $braceChars = "()[]{}";
    protected string $stringChars = " 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+-*/_^!()[]{}";
            
    //
    // THE MAP LOCATIONS
    //
    protected ?TokenMapNode $entry = null;
    protected ?TokenMapNode $alphaNumeric = null;
    protected ?TokenMapNode $number = null;
    protected ?TokenMapNode $decimal = null;
    protected ?TokenMapNode $opEnd = null;
    protected ?TokenMapNode $less = null;
    protected ?TokenMapNode $greater = null;
    protected ?TokenMapNode $equals = null;
    protected ?TokenMapNode $punctEnd = null;
    protected ?TokenMapNode $braceEnd = null;
    protected ?TokenMapNode $string = null;
    protected ?TokenMapNode $stringEnd = null;

    const CHAR_SWAPS = [
        '*' => 'x'
    ];

    function __construct() {

        // CONSTRUCT ALL THE MAP LOCATIONS
        //////////////////////////////////
        $this->alphaNumeric = new TokenMapNode(TokenTypes::ALPHANUMERIC);
        $this->number = new TokenMapNode(TokenTypes::NUMBER);
        $this->decimal = new TokenMapNode(TokenTypes::DECIMAL);
        $this->opEnd = new TokenMapNode(TokenTypes::OPERATOR);
        $this->less = $this->opEnd;
        $this->greater = $this->opEnd;
        $this->equals = $this->opEnd;
        $this->punctEnd = new TokenMapNode(TokenTypes::PUNCT);
        $this->braceEnd = new TokenMapNode(TokenTypes::BRACE);
        $this->string = new TokenMapNode(TokenTypes::STRING);
        $this->stringEnd = new TokenMapNode(TokenTypes::STRING);

        // DEFINE HOW THE LOCATIONS LINK TO OTHER LOCATIONS
        // 
        // ALPHANUMERIC

        $this->alphaNumeric->addStep($this->alphaNumericChars, $this->alphaNumeric);

        // NUMBERS

        $this->number->addStep($this->numberChars, $this->number);
        $this->number->addStep('.', $this->decimal);
        $this->decimal->addStep($this->numberChars, $this->decimal);

        // OPERATORS

        $this->less->addStep('=', $this->opEnd);
        $this->less->addStep('>', $this->opEnd);
        $this->greater->addStep('=', $this->opEnd);
        $this->equals->addStep('=', $this->opEnd);

        // PUNCTUATION
        // BRACES
        // STRING
        $this->string->addStep($this->stringChars, $this->string);
        $this->string->addStep('"', $this->stringEnd);
        // ENTRY

        $this->entry = new TokenMapNode('entry');
        $this->entry->addStep($this->alphaChars, $this->alphaNumeric);
        $this->entry->addStep($this->numberChars, $this->number);
        $this->entry->addStep($this->punctChars, $this->punctEnd);
        $this->entry->addStep($this->braceChars, $this->braceEnd);
        $this->entry->addStep($this->opChars1, $this->opEnd);
        $this->entry->addStep('<', $this->less);
        $this->entry->addStep('>', $this->greater);
        $this->entry->addStep('=', $this->equals);
        $this->entry->addStep('"', $this->string);
    }

   
}
