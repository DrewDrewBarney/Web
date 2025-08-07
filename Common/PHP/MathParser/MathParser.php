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
        $tag = Tag::make('div', '',['class'=>'subExpression']);
        if ($this->tokeniser->token()->isAcceptableType([TokenTypes::NUMBER, TokenTypes::DECIMAL])) {
            $tag->makeChild('div', $this->tokeniser->token()->token, ['class'=>'subExpression']);
            $this->tokeniser->get();
        } else if ($this->tokeniser->token()->isAcceptableToken(['(', '{', '['])) {
            $tag->makeChild('div', $this->tokeniser->token()->token,['class'=>'subExpression']);

            $this->tokeniser->get();
            $tag->addChild($this->addSubtract());
            $tag->makeChild('div', $this->tokeniser->token()->token, ['class'=>'subExpression']);

            $this->tokeniser->get();
        }
        return $tag;
    }

    function subSuper(): Tag {
        $tag = Tag::make('div', '',['class'=>'subExpression']);
        $tag->addChild($this->primitive());
        while ($this->tokeniser->token()->isAcceptableToken(['_', '^'])) {
            $tag->makeChild('div', $this->tokeniser->token()->token);
            $this->tokeniser->get();
            $tag->addChild($this->primitive());
        }
        return $tag;
    }

    function divideOver(): Tag {
        $tag = Tag::make('div', '',['class'=>'subExpression']);
        $tag->addChild($this->subSuper());
        while ($this->tokeniser->token()->isAcceptableToken(['/'])) {
            $tag->makeChild('div', $this->tokeniser->token()->token);
            //$this->tokeniser->token()->echo();
            $this->tokeniser->get();
            $tag->addChild($this->subSuper());
        }
        return $tag;
    }

    function timesDivide(): Tag {
        $tag = Tag::make('div', '',['class'=>'subExpression']);
        $tag->addChild($this->divideOver());
        while ($this->tokeniser->token()->isAcceptableToken(['÷', '*'])) {
            $tag->makeChild('div', $this->tokeniser->token()->token);
            //$this->tokeniser->token()->echo();
            $this->tokeniser->get();
            $tag->addChild($this->divideOver());
        }
        return $tag;
    }

    function addSubtract(): Tag {
        $tag = Tag::make('div', '',['class'=>'subExpression']);
        $tag->addChild($this->timesDivide());
        while ($this->tokeniser->token()->isAcceptableToken(['+', '-'])) {
            $tag->makeChild('div', $this->tokeniser->token()->token, ['class'=>'subExpression']);
            //$this->tokeniser->token()->echo();
            $this->tokeniser->get();
            $tag->addChild($this->timesDivide());
        }
        return $tag;
    }

    function expression(): Tag {
        $tag = Tag::make('div', '',['class'=>'subExpression']);

        $tag->addChild($this->addSubtract());
        while ($this->tokeniser->token()->isAcceptableToken(['=', '<=', '>=', '<>'])) {
            $tag->makeChild('div', $this->tokeniser->token()->token, ['class'=>'subExpression']);
            //$this->tokeniser->token()->echo();
            $this->tokeniser->get();
            $tag->addChild($this->addSubtract());
        }
        return $tag;
    }

    function evaluate(): Tag {
        $tag = Tag::make('div', '',['class'=>'subExpression']);
        $this->tokeniser->reset();
        $this->tokeniser->get();

        $tag->addChild($this->expression());

        return $tag;
    }
}


$parser = new MathParser('2+2');
$page = new Page('fred', false);
$page->form->addChild($parser->evaluate());
$page->render();
