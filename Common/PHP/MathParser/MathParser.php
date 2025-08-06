<?php

include_once '../../../Common/PHP/Context.php';

Context::loadClasses([Context::relativeRootURL() . 'Common']);


class MathParser{
    
    protected ?Tokeniser $tokeniser = null;
    
    
    function __construct(string $text) {
        $this->tokeniser = new Tokeniser($text, new MathTokenMap());
    }
    
    function tokenise():void{
        $this->tokeniser->prime();
        $token = $this->tokeniser->getToken();
        while ($token){
            $token->echo();
            $token = $this->tokeniser->getToken();
        }
    }
    
    
    
    function primitve(){
        
    }
     
}


$parser = new MathParser('2+2=4.4/8');
$parser->tokenise();
