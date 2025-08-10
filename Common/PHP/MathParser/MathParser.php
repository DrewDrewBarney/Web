<?php

/*
  include_once '../../../Common/PHP/Context.php';

  Context::loadClasses([Context::relativeRootURL() . 'Common']);
  Context::setCSSpaths(['Common/CSS/']);

 */

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
            $tag = Tag::make('span', $this->tokeniser->token()->prettyToken, ['class' => 'primitive']);
            $this->tokeniser->get();
        } else if ($this->tokeniser->token()->isAcceptableToken(['(', '{', '['])) {
            $class = $this->tokeniser->token()->isAcceptableToken(['(', '[']) ? 'brace' : 'invisibleBrace';
            $tag = Tag::make('span', '', ['class' => $class]);

            $this->tokeniser->get();
            $tag->addChild($this->expression());

            $this->tokeniser->get();
        }
        return $tag;
    }

   

    function subSuper(): Tag {

        $base = $this->primitive();

        if ($this->tokeniser->token()->isAcceptableToken(['_', '^'])) {

            $token = $this->tokeniser->token()->token;
            $this->tokeniser->get();

            if ($token === '^') {
                $base->makeChild('sup')->addChild($this->subSuper());
            } else if ($token === '_') {
                $base->makeChild('sub')->addChild($this->subSuper());
            }
        }

        return $base;
    }

    function divideOver(): Tag {

        $numerator = $this->subSuper();

        if ($this->tokeniser->token()->isAcceptableToken(['/'])) {
            $this->tokeniser->get();
            $fraction = Tag::make('span', '', ['class' => 'divideOver']);
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
        $tag = $this->divideOver();
        while ($this->tokeniser->token()->isAcceptableToken(['÷', '*'])) {
            $tag->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'binary']);

            $this->tokeniser->get();
            $tag->addChild($this->divideOver());
        }
        return $tag;
    }

    function addSubtract(): Tag {
        $tag = $this->timesDivide();
        while ($this->tokeniser->token()->isAcceptableToken(['+', '-'])) {
            $tag->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'binary']);
            $this->tokeniser->get();
            $tag->addChild($this->timesDivide());
        }
        return $tag;
    }

    function expression(): Tag {
        $tag = $this->addSubtract();
        while ($this->tokeniser->token()->isAcceptableToken(['=', '<=', '>=', '<>'])) {
            $tag->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'binary']);
            $this->tokeniser->get();
            $tag->addChild($this->addSubtract());
        }
        return $tag;
    }

    function evaluate(): Tag {

        $this->tokeniser->reset();
        $this->tokeniser->get();

        return $this->expression();
    }

    static function asTag(string $equation): Tag {
        return (new MathParser($equation))->evaluate();
    }

    static function card(string $equation, string $pre = '', string $post = ''): Tag {
        $flex = Tag::make('div', '', ['class' => 'center-x']);
        $card = $flex->makeChild('div', '', ['class' => 'center-x margin-bottom-2ch'])->makeChild('div', '', ['class' => 'card']);
        $card->makeChild('div', $pre, ['class' => 'expression']);
        $card->addChild(MathParser::asTag($equation));
        $card->makeChild('div', $post, ['class' => 'expression']);
        return $flex;
    }
}

/*
$page = new Page('fred', false);
$page->form->addChild(MathParser::asTag('({2+2^3}/{2+1}/3 + 1)'));
$page->render();
 * 
 */

