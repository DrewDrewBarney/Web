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
        if ($this->tokeniser->token()->isAcceptableType([TokenTypes::NUMBER, TokenTypes::DECIMAL])) {
            $tag = Tag::make('div', $this->tokeniser->token()->token, ['class' => 'primitive']);
            $this->tokeniser->get();
        } else if ($this->tokeniser->token()->isAcceptableToken(['(', '{', '['])) {
            $tag = Tag::make('div', 'brace', ['class' => 'brace']);

            $this->tokeniser->get();
            $tag->addChild($this->addSubtract());

            $this->tokeniser->get();
        }
        return $tag;
    }

    function subSuper(): Tag {
        $base = $this->primitive();

        if ($this->tokeniser->token()->isAcceptableToken(['_', '^'])) {
            $this->tokeniser->get();
            $super = $this->subSuper();

            $grid = Tag::make('div', 'subSuperGrid', ['class' => 'subSuperGrid']);

            $grid->makeChild('div');
            $grid->addChild($super);
            $grid->addChild($base);
            $grid->makeChild('div');
            return $grid;
        } else {
            return $base;
        }
    }

    function divideOver(): Tag {
        $tag = Tag::make('div', 'divideOver', ['class' => 'divideOver']);
        $tag->addChild($this->subSuper());
        while ($this->tokeniser->token()->isAcceptableToken(['/'])) {
            $tag->makeChild('div', '', ['class' => 'fractionDivider']);
            //$this->tokeniser->token()->echo();
            $this->tokeniser->get();
            $tag->addChild($this->subSuper());
        }
        return $tag;
    }

    function timesDivide(): Tag {
        $tag = Tag::make('div', 'timesDivide', ['class' => 'binaryOperator']);
        $tag->addChild($this->divideOver());
        while ($this->tokeniser->token()->isAcceptableToken(['÷', '*'])) {
            $tag->makeChild('div', $this->tokeniser->token()->token, ['class' => 'operator']);
            //$this->tokeniser->token()->echo();
            $this->tokeniser->get();
            $tag->addChild($this->divideOver());
        }
        return $tag;
    }

    function addSubtract(): Tag {
        $tag = Tag::make('div', 'addSubtract', ['class' => 'binaryOperator']);
        $tag->addChild($this->timesDivide());
        while ($this->tokeniser->token()->isAcceptableToken(['+', '-'])) {
            $tag->makeChild('div', $this->tokeniser->token()->token, ['class' => 'operator']);
            //$this->tokeniser->token()->echo();
            $this->tokeniser->get();
            $tag->addChild($this->timesDivide());
        }
        return $tag;
    }

    function expression(): Tag {
        $tag = Tag::make('div', 'expression', ['class' => 'expression']);

        $tag->addChild($this->addSubtract());
        while ($this->tokeniser->token()->isAcceptableToken(['=', '<=', '>=', '<>'])) {
            $tag->makeChild('div', $this->tokeniser->token()->token, ['class' => 'expression']);
            //$this->tokeniser->token()->echo();
            $this->tokeniser->get();
            $tag->addChild($this->addSubtract());
        }
        return $tag;
    }

    function evaluate(): Tag {
        $tag = Tag::make('div', '', ['class' => 'evaluate']);
        $this->tokeniser->reset();
        $this->tokeniser->get();

        return $this->expression();
    }
}

$parser = new MathParser('2^2');
$page = new Page('fred', false);
$page->form->addChild($parser->evaluate());
$page->render();
