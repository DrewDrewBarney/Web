<?php

include_once '../../../Common/PHP/Context.php';

Context::loadClasses([Context::relativeRootURL() . 'Common']);
Context::setCSSpaths(['Common/CSS/']);



class MathParser {

    protected ?Tokeniser $tokeniser = null;

    function __construct(string $text) {
        $this->tokeniser = new Tokeniser($text, new MathTokenMap());
    }

    function tokenise(): void {
        $this->tokeniser->reset();
        $this->tokeniser->get();
        while ($this->tokeniser->token()->type !== TokenTypes::NULL) {
            $this->tokeniser->token()->echo();
            $this->tokeniser->get();
        }
    }

    function primitive(): Tag {
        $tag = null;
        if ($this->tokeniser->token()->isAcceptableType([TokenTypes::NUMBER, TokenTypes::DECIMAL, TokenTypes::ALPHANUMERIC])) {
            $tag = Tag::make('div', $this->tokeniser->token()->prettyToken, ['class' => 'primitive']);
            $this->tokeniser->get();
        } else if ($this->tokeniser->token()->isAcceptableToken(['(', '{', '['])) {
            $class = $this->tokeniser->token()->isAcceptableToken(['(', '[']) ? 'brace' : 'invisibleBrace';
            $tag = Tag::make('div', '', ['class' => $class]);

            $this->tokeniser->get();
            $tag->addChild($this->expression());

            $this->tokeniser->get();
        }
        return $tag;
    }

    function subSuper(): Tag {
        $base = $this->primitive();

        if ($this->tokeniser->token()->isAcceptableToken(['_', '^'])) {
            $this->tokeniser->get();
            $super = $this->subSuper();

            $grid = Tag::make('div', '', ['class' =>'subSuperGrid']);

            $grid->makeChild('div', '', ['class' => 'empty_1_1']);
            $grid->makeChild('div', '', ['class' => 'empty_2_2']);

            $grid->addChild($super, ['class' => 'superScript'], true);
            $grid->addChild($base, ['class' => 'base'], true);
            return $grid;
        } else {
            return $base;
        }
    }

    function divideOver(): Tag {

        $numerator = $this->subSuper();

        if ($this->tokeniser->token()->isAcceptableToken(['/'])) {
            $this->tokeniser->get();
            $fraction = Tag::make('div', '', ['class' => 'divideOver']);
            $denominator = $this->divideOver();
            $fraction->addChild($numerator);
            $fraction->makeChild('fraction', '', ['class' => 'fractionDivider']);
            $fraction->addChild($denominator);
            return $fraction;
        } else {
            return $numerator;
        }
    }

    function timesDivide(): Tag {
        $tag = Tag::make('div', '', ['class' => 'binaryOperator']);
        $tag->addChild($this->divideOver());
        while ($this->tokeniser->token()->isAcceptableToken(['÷', '*'])) {
            $tag->makeChild('div', $this->tokeniser->token()->prettyToken, ['class'=>'binaryOperator']);

            $this->tokeniser->get();
            $tag->addChild($this->divideOver());
        }
        return $tag;
    }

    function addSubtract(): Tag {
        $tag = Tag::make('div', '', ['class'=>'binaryOperator']);
        $tag->addChild($this->timesDivide());
        while ($this->tokeniser->token()->isAcceptableToken(['+', '-'])) {
            $tag->makeChild('div', $this->tokeniser->token()->prettyToken, ['class'=>'binaryOperator']);

            $this->tokeniser->get();
            $tag->addChild($this->timesDivide());
        }
        return $tag;
    }

    function expression(): Tag {
        $tag = Tag::make('div', '', ['class' => 'expression']);

        $tag->addChild($this->addSubtract());
        while ($this->tokeniser->token()->isAcceptableToken(['=', '<=', '>=', '<>'])) {
            $tag->makeChild('div', $this->tokeniser->token()->prettyToken, ['class' => 'binaryOperator']);

            $this->tokeniser->get();
            $tag->addChild($this->addSubtract());
        }
        return $tag;
    }

    function evaluate(): Tag {
        
        $displayCard = Tag::make('div','',['class'=>'displayCard']);

        $this->tokeniser->reset();
        $this->tokeniser->get();
        
        $displayCard->addChild($this->expression());

        return $displayCard;
    }
    
    
    static function asTag(string $equation):Tag{
        return (new MathParser($equation))->evaluate();
    }
}


$page = new Page('fred', false);
$page->form->addChild(MathParser::asTag('({2+2^3}/{2+1}/3 + 1)'));
$page->render();

