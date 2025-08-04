<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define('WHITESPACE', chr(8) . chr(10) . chr(13) . chr(32));
define('ALPHA', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');
define('NATURAL_NUMBER', '0123456789');
define('ALPHA_NUMERIC', ALPHA . NATURAL_NUMBER);
define('NATURAL_PLUS_POINT', NATURAL_NUMBER . '.');
define('ONE_CHAR_SYMBOLS', '[({})]+-*/^,;:');
define('OBLIGATORY_TOKEN', true);
define('OPTIONAL_TOKEN', false);



// implementation of something like an enum using a class
// and static members



abstract class eTokenTypes {

    const WHITESPACE = 0;
    const NATURAL_NUMBER = 1;
    const DECIMAL_NUMBER = 2;
    const ALPHA_NUMERIC = 4;
    const SINGLE_CHAR_SYMBOL = 5;
    const UNKNOWN = 7;
    const TERMINATOR = 8;

}



class CharSet {

    protected $mChars;

    public function __construct(string $chars) {
        $this->mChars = $chars;
    }

    public function contains(string $char) {
        return strpos($this->mChars, $char) !== false;
    }

}



class Tokeniser {

    protected $mText;
    protected $mCursor;
    protected $mChar;
    protected $mToken;
    protected $mTokenType;
    protected $mError;

    protected $mWhitespace;
    protected $mAlpha;
    protected $mNatural;
    protected $mNaturalPlusPoint;
    protected $mAlphaNumeric;

    function __construct($text) {
        $this->mText = $text . chr(0); //add a terminal zero like a C string
        $this->mCursor = 0;
        $this->mError = null;

        $this->mWhitespace = new CharSet(WHITESPACE);
        $this->mAlpha = new CharSet(ALPHA);
        $this->mNaturalNumber = new CharSet(NATURAL_NUMBER);
        $this->mAlphaNumeric = new CharSet(ALPHA_NUMERIC);
        $this->mNaturalPlusPoint = new CharSet(NATURAL_PLUS_POINT);
        $this->mSymbolic = new CharSet(ONE_CHAR_SYMBOLS); // we only use simple single char symbols in this app
    }

    function getChar() {
        $this->mChar = substr($this->mText, $this->mCursor, 1);
        if ($this->mChar !== chr(0)) { // only increments if not a zero value (like a C zero terminated string
            $this->mCursor++;
        }
    }

    function EOT() {
        return $this->mChar === chr(0);
    }
    
    function error() {
        return $this->mError;
    }

    function raiseError($message) {
        $this->mError = $message;
    }

    function getToken() {

        $this->mToken = '';
        $this->mTokenType = eTokenTypes::UNKNOWN;
        
        if ($this->error()) return;

        // gobble up whitespace
        while ($this->mWhitespace->contains($this->mChar)) {
            $this->getChar();
        }

        if ($this->mAlpha->contains($this->mChar)) {
            $this->mTokenType = eTokenTypes::ALPHA_NUMERIC;
            $this->mToken = $this->mChar;
            $this->getChar();
            while ($this->mAlphaNumeric->contains($this->mChar)) {
                $this->mToken .= $this->mChar;
                $this->getChar();
            }
        } else if ($this->mNaturalNumber->contains($this->mChar)) {
            $this->mTokenType = eTokenTypes::NATURAL_NUMBER;
            $this->mToken = $this->mChar;
            $this->getChar();
            while ($this->mNaturalPlusPoint->contains($this->mChar)) {
                $this->mTokenType = $this->mChar === '.' ? eTokenTypes::DECIMAL_NUMBER : $this->mTokenType;
                $this->mToken .= $this->mChar;
                $this->getChar();
            }
        } else if ($this->mSymbolic->contains($this->mChar)) {
            $this->mTokenType = eTokenTypes::SINGLE_CHAR_SYMBOL;
            $this->mToken = $this->mChar;
            $this->getChar();
        } else {
            $this->getChar();
        }
        
        
        return $this->mTokenType . ' ' . $this->mToken . ' <br>';
    }

}





