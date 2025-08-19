<?php

include_once 'MathToken.php';
include_once '../Tokeniser/TokenMap.php';
include_once '../Tokeniser/TokenMapNode.php';

class MathTokenMap extends TokenMap {

    //
    // STRINGS DEFINING PRIMITIVE CHAR SETS
    //

    const ALPHA_CHARS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const NUMBER_CHARS = "0123456789";
    const ALPHA_NUMERIC_CHARS = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    const PUNCT_CHARS = ".,;:";
    const OP_CHARS_1 = "*/_^!";
    const OP_CHARS_2 = "+-=<>";
    const BRACE_CHARS = "()[]{}";
    const STRING_CHARS = " 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+-*/_^!()[]{}";

    //
    // THE MAP LOCATIONS
    //

    protected ?TokenMapNode $root = null;

    function __construct() {

        // create the empty root node

        $root = TokenMapNode::make(self::NO_TYPE);

        /*
         * add valid moves based on character sets
         */

        // alpha or alphanumeric

        $alpha = $root->makeChild(self::ALPHA_CHARS, MathToken::ALPHA_NUMERIC); // catch
        $alpha->addChild(self::ALPHA_CHARS, $alpha); // loop in alpha 
        $alphanumeric = $alpha->makeChild(self::NUMBER_CHARS); //branch
        $alphanumeric->addChild(self::ALPHA_NUMERIC_CHARS, $alphanumeric); // loop
        //
        // numbers

        $number = $root->makeChild(self::NUMBER_CHARS, MathToken::NUMBER); // catch
        $number->addChild(self::NUMBER_CHARS, $number); // loop
        $decimal = $number->makeChild('.', MathToken::DECIMAL); // branch
        $decimal->addChild(self::NUMBER_CHARS, $decimal); // loop
        //
        // punctuation

        $root->makeChild(self::PUNCT_CHARS, MathToken::PUNCTUATION); // terminate
        //
        // braces

        $root->makeChild(self::BRACE_CHARS, MathToken::BRACE); // terminate
        //
        // single char operators

        $root->makeChild(self::OP_CHARS_1, MathToken::OPERATOR); // terminate
        //
        // longer operator tokens

        $root->makeChildren(['<', '<=>'], MathToken::OPERATOR); // catch + terminate
        $root->makeChildren(['>', '>='], MathToken::OPERATOR); // catch + terminate
        $root->makeChildren(['+', '-'], MathToken::OPERATOR); // catch + terminate
        $root->makeChildren(['-', '+'], MathToken::OPERATOR); // catch + terminate
        $root->makeChildren(['=', '='], MathToken::OPERATOR); // catch + terminate
        //
        // strings
        $stringSingle = $root->makeChild("'", MathToken::STRING); // catch
        $stringSingle->addChild(self::STRING_CHARS, $stringSingle); // loop
        $stringSingle->makeChild("'"); // terminate

        $stringDouble = $root->makeChild('"', MathToken::STRING); // catch
        $stringDouble->addChild(self::STRING_CHARS, $stringDouble); // loop
        $stringDouble->makeChild('"'); // terminate
        //
        // plug tree into the root

        $this->entry = $root;
        //
        // set the cursor to the root

        $this->start();
    }

    function toString(): string {
        return $this->entry->toString();
    }

    public function render(): void {
        echo '<pre>';
        echo $this->toString();
        echo '</pre>';
    }
}

//$map = new MathTokenMap();
//echo $map->render();
