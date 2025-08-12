<?php

const debug = false;

if (debug) {
    include_once '../../../Common/PHP/Context.php';

    Context::loadClasses([Context::relativeRootURL() . 'Common']);
    Context::setCSSpaths(['Common/CSS/']);
}

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
        $primitive = Tag::make('span', '', ['class' => 'primitiveContainer']);

        if (TokenTypes::isAcceptableType($this->tokeniser->token(), [TokenTypes::NUMBER, TokenTypes::DECIMAL, TokenTypes::ALPHANUMERIC])) {
            $primitive->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'primitive']);
            $this->tokeniser->get();
        } else if (TokenTypes::isAcceptableType($this->tokeniser->token(), [TokenTypes::STRING])){
            $primitive->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'primitive']);
            $this->tokeniser->get();
              
        } else if (TokenTypes::isAcceptableToken($this->tokeniser->token(), ['(', '{', '['])) {
            $class = TokenTypes::isAcceptableToken($this->tokeniser->token(), ['(', '[']) ? 'brace' : 'invisibleBrace';
            $brace = $primitive->makeChild('span', '', ['class' => $class]);
            $this->tokeniser->get();
            $brace->addChild($this->expression());
            $this->tokeniser->get(); // get the trailing bracket
        }

        return $primitive;
    }

    function postfix(): tag {
        $postfix = Tag::make('span', '', ['class' => 'postfix']);
        $postfix->addChild($this->primitive());

        while (TokenTypes::isAcceptableToken($this->tokeniser->token(), ['!'])) {
            $postfix->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'postfix']);
            $this->tokeniser->get();
        }


        return $postfix;
    }

    function subscript(): Tag {

        $subSuper = Tag::make('span', '', ['class' => 'subscriptContainer']);
        $subSuper->addChild($this->postfix());

        while (TokenTypes::isAcceptableToken($this->tokeniser->token(), ['_'])) {
            $token = $this->tokeniser->token()->token;
            $this->tokeniser->get();
            $subSuper->makeChild('sub')->addChild($this->subscript(), ['class' => 'subScript']);
        }

        return $subSuper;
    }

    function superscript(): Tag {

        $subSuper = Tag::make('span', '', ['class' => 'superscriptContainer']);
        $subSuper->addChild($this->subscript());

        while (TokenTypes::isAcceptableToken($this->tokeniser->token(), ['^'])) {
            $token = $this->tokeniser->token()->token;
            $this->tokeniser->get();
            $subSuper->makeChild('sup')->addChild($this->superscript(), ['class' => 'superScript']);
        }

        return $subSuper;
    }

    function divide(): Tag {
        $divide = Tag::make('span', '', ['class' => 'divide']);
        $divide->addChild($this->superscript());

        while (TokenTypes::isAcceptableToken($this->tokeniser->token(), ['/'])) {
            $divide->makeChild('span', '', ['class' => 'dividingLine']);
            $this->tokeniser->get();
            $divide->addChild($this->superscript());
        }

        return $divide;
    }

    function timesDivide(): Tag {
        $timesDivide = Tag::make('span', '', ['class' => 'timesDivide']);
        $timesDivide->addChild($this->divide());

        while (TokenTypes::isAcceptableToken($this->tokeniser->token(), ['*', '÷'])) {
            $timesDivide->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'binary']);

            $this->tokeniser->get();
            $timesDivide->addChild($this->divide());
        }
        return $timesDivide;
    }

    function addSubtract(): Tag {
        $addSubtract = Tag::make('span', '', ['class' => 'addSubtract']);
        $addSubtract->addChild($this->timesDivide());

        while (TokenTypes::isAcceptableToken($this->tokeniser->token(), ['+', '-'])) {
            $addSubtract->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'operator']);
            $this->tokeniser->get();
            $addSubtract->addChild($this->timesDivide());
        }
        return $addSubtract;
    }

    function expression(): Tag {
        $expression = Tag::make('span', '', ['class' => 'expression']);
        $expression->addChild($this->addSubtract());

        while (TokenTypes::isAcceptableToken($this->tokeniser->token(), ['=', '<=', '>=', '<>'])) {
            $expression->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'binary']);
            $this->tokeniser->get();
            $expression->addChild($this->addSubtract());
        }

        return $expression;
    }

    function expressions(): Tag {
        $expressions = Tag::make('span', '', ['class' => 'expression']);
        $expressions->addChild($this->expression());

        while (TokenTypes::isAcceptableToken($this->tokeniser->token(), [','])) {
            $this->tokeniser->get();
            $expressions->addChild($this->expression());
        }
        return $expressions;
    }

    function evaluate(): Tag {

        $this->tokeniser->reset();
        $this->tokeniser->get();

        $expressions = $this->expressions();
        if ($this->tokeniser->token()->type !== TokenTypes::NULL) {
            $expressions->makeChild('span', 'error (expression finished before last token)', ['class' => 'error']);
        }

        return $expressions;
    }

    static function asTag(string $equation): Tag {
        return (new MathParser($equation))->evaluate();
    }

    static function card(string $equation, string $pre = '', string $post = ''): Tag {
        $flex = Tag::make('div', '', ['class' => 'center-x expression']);
        $card = $flex->makeChild('div', '', ['class' => 'center-x margin-bottom-2ch expression'])->makeChild('div', '', ['class' => 'card']);
        $card->makeChild('span', $pre, ['class' => 'expression']);
        $card->addChild(MathParser::asTag($equation));
        $card->makeChild('span', $post, ['class' => 'expression']);
        return $flex;
    }
}

if (debug) {
    $page = new Page('fred', false);
    $page->form->addChild(MathParser::card('(2_i_j^n^q+100/3)'));
    $page->render();
}

