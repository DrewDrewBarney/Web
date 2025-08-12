<?php

class TokenTypes {

    const ATYPICAL = 999;
    const NULL = 1000;
    const NUMBER = 0;
    const DECIMAL = 1;
    const ALPHA = 2;
    const ALPHANUMERIC = 3;
    const OPERATOR = 4;
    const BRACE = 5;
    const PUNCT = 6;
    const STRING = 7;
    const MAP = [
        self::ATYPICAL => 'atypical',
        self::NUMBER => 'number',
        self::DECIMAL => 'decimal',
        self::ALPHA => 'alpha',
        self::ALPHANUMERIC => 'alphanumeric',
        self::OPERATOR => 'operator',
        self::BRACE => 'brace',
        self::PUNCT => 'punctuation',
        self::STRING => 'string'
    ];

    static function typeString(int $type): string {
        return isset(self::MAP[$type]) ? self::MAP[$type] : self::MAP[self::ATYPICAL];
    }
    
    static function isAcceptableType(?Token $token, array $types): bool {
        return $token->type !== TokenTypes::NULL ? in_array($token->type, $types) : false;
    }

    static function isAcceptableToken(?Token $token, array $tokens): bool {
        return $token->token !== '' ? in_array($token->token, $tokens) : false;
    }

}

