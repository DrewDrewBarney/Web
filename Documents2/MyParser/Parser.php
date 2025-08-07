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

$parser = new WorkoutParser('raw ru  [3,3] + 3*([1,2.2])');
$parser->parse();
$parser->echo();

// implementation of something like an enum using a class
// and static members

function stringToByteArray(string $string) {
    $result = [];
    foreach (str_split($string) as $chr) {
        $result[] = ord($chr);
    }
    return $result;
}

abstract class eTokenTypes {

    const WHITESPACE = 0;
    const NATURAL_NUMBER = 1;
    const DECIMAL_NUMBER = 2;
    const ALPHA_NUMERIC = 4;
    const SINGLE_CHAR_SYMBOL = 5;
    const UNKNOWN = 7;
    const TERMINATOR = 8;

}

abstract class eIntensityTypes {

    const FREE = 0;
    const HEART_RATE = 1;
    const PACE = 2;
    const POWER = 3;

}

abstract class eDurationTypes {

    const OPEN = 0;
    const TIME = 1;
    const DISTANCE = 2;

}

class WorkoutStep {

    protected $mDuration;
    protected $mIntensity;

    function __construct($duration, $intensity) {
        $this->mDuration = $duration;
        $this->mIntensity = $intensity;
    }

    function duration() {
        return $this->mDuration;
    }

    function intensity() {
        return $this->mIntensity;
    }

}

class CharSet {

    protected $mChars;

    public function __construct($chars) {
        $this->mChars = $chars;
    }

    public function contains($char) {
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
        
        
        echo $this->mTokenType . ' ' . $this->mToken . ' <br>';
    }

}

class WorkoutParser extends Tokeniser {

    protected $mDefaultDurationType;
    protected $mDefaultIntensityType;
    protected $mIsRaw;
    protected $mSport;
    protected $mSteps;

    function __construct($text) {
        parent::__construct($text);
        $this->mDefaultDurationType = eDurationTypes::OPEN;
        $this->mDefaultIntensityType = eIntensityTypes::FREE;
        $this->mIsRaw = false;
        $this->mSport = 'ru';
        $this->mSteps = [];
    }

 

    function tokenIn($tokens) {
        foreach ($tokens as $token) {
            if ($token === $this->mToken) {
                return true;
            }
        }
        return false;
    }

    function tokenTypeIn($tokenTypes) {
        foreach ($tokenTypes as $tokenType) {
            if ($tokenType === $this->mTokenType) {
                return true;
            }
        }
        return false;
    }


    function acceptToken($tokens, $obligatory) {
        if ($this->tokenIn($tokens)) {
            $this->get();
            return true;
        } else {
            if ($obligatory) {
                $this->raiseError('error: '.implode(' or ', $tokens) . ' expected');
            }
        }
        return false;
    }

    function acceptTokenType($tokenTypes, $obligatory) {
        if ($this->tokenTypeIn($tokenTypes)) {
            $this->get();
            return true;
        } else {
            if ($obligatory) {
                $this->raiseError('incorrect token type encountered');
            }
        }
        return false;
    }

    /*
     * Potentially different behaviours
     * 
     * We can raise an error and not advance it the token is not of the right type
     * 
     * We can not raise an error and merely not advance, returning false if the token is not of the right type
     * 
     * This is good for optional and mandatory syntax elements
     * 
     */

    function duration() {
        if ($this->tokenTypeIn([eTokenTypes::NATURAL_NUMBER, eTokenTypes::DECIMAL_NUMBER], OBLIGATORY_TOKEN)) {
            $value = floatval($this->mToken);
            $this->get();
            return $value;
        } else {
            return 0.0;
        }
    }

    function intensity() {
        if ($this->tokenTypeIn([eTokenTypes::NATURAL_NUMBER, eTokenTypes::DECIMAL_NUMBER], OBLIGATORY_TOKEN)) {
            $value = floatval($this->mToken);
            $this->get();
            return $value;
        } else {
            return 0.0;
        }
    }

    function step() {  // step is the primitive.  if we don't have a step at the top of the recursive tree we are f...
        if ($this->acceptToken(['[', '(', '{'], OPTIONAL_TOKEN)) {
            $duration = $this->duration();
            $this->acceptToken([','], OBLIGATORY_TOKEN); // mandatory as there are no options here
            $intensity = $this->intensity();
            $this->acceptToken([']', ')', '}'], OBLIGATORY_TOKEN);
            return [new WorkoutStep($intensity, $duration)];
       } else {
           return [];
       }
    }

    function parentheses() {
        $steps = [];
        if ($this->acceptToken(['[', '(', '{'], OBLIGATORY_TOKEN)) {
            $steps = $this->plus();
            $this->acceptToken([']', ')', '}'], OBLIGATORY_TOKEN);
        }
        return $steps;
    }

    function grow($multiplicand, $steps) {
        $result = $steps;
        for ($i = 0; $i < $multiplicand - 1; $i++) {
            $result = array_merge($result, $steps);
        }
        return $result;
    }

    function times() {
        if ($this->tokenTypeIn([eTokenTypes::NATURAL_NUMBER])) {  // optional
            $multiplicand = intval($this->mToken);
            $this->get();
            $this->acceptToken(['*', 'x'], OPTIONAL_TOKEN);
            return $this->grow($multiplicand, $this->parentheses());
        } else {
            return $this->step();
        }
    }

    function plus() {
        $steps = $this->times();
        while ($this->tokenIn(['+'])) {
            $this->get();
            $steps = array_merge($steps, $this->times());
        }
        return $steps;
    }

    function header() {

        if ($this->mToken === 'raw') {
            $this->mIsRaw = true;
            $this->get();
            return true;
        }

        if ($this->tokenIn(['ru', 'sw', 'bi', 'wa', 'cr'])) {
            $this->mSport = $this->mToken;
            $this->get();
            return true;
        }

        return false;
    }

    function parse() {
        
        echo $this->mText . '<br>';

        // prime the pump
        $this->getChar();
        $this->get();
        // parse the header
        while ($this->header());
        // parese the steps [] + n([] + )
        $this->mSteps = $this->plus();
    }

    function echo() {
        foreach ($this->mSteps as $step) {
            echo 'step ' . $step->intensity() . ' ' . $step->duration() . '<br>';
        }
        echo $this->mError;
       
    }

}
