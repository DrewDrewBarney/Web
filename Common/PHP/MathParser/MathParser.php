<?php

const debug = true;

if (debug) {
    include_once '../../../Common/PHP/Context.php';

    Context::loadClasses([Context::relativeRootURL() . 'Common']);
    Context::setCSSpaths(['Common/CSS/']);
}

class MathParser {

    protected ?Tokeniser $tokeniser = null;

    //static protected ?MathParser $mathParser = null;

    function __construct(string $text) {
        $this->tokeniser = new Tokeniser($text, new MathTokenMap());
    }

    function tokenise(): void {
        $this->tokeniser->reset();
        $this->tokeniser->get();
        while ($this->tokeniser->token()->type !== MathToken::NULL) {
            $this->tokeniser->token()->echo();
            $this->tokeniser->get();
        }
    }

    function primitive(): Tag {
        $result = null;

        // isAcceptableToken is more specific than isAcceptableType
        // so it should get first bite of the cherry
        if ($this->tokeniser->token()->rightType([MathToken::NUMBER, MathToken::DECIMAL, MathToken::ALPHA_NUMERIC])) {
            $result = Tag::make('span', $this->tokeniser->token()->prettyToken, ['class' => 'primitive']);
            $lastToken = $this->tokeniser->token();
            $this->tokeniser->get();
            
    
            
        } else if ($this->tokeniser->token()->rightType([MathToken::STRING])) {
            $result = Tag::make('span', $this->tokeniser->token()->prettyToken, ['class' => 'primitive']);
            $this->tokeniser->get();
            $this->tokeniser->get();
        } else {
            // WTF
            $result = Tag::make('span', 'WTF');
        }

        return $result;
    }

    function brace(): tag {
        if ($this->tokeniser->token()->rightToken(['(', '{', '['])) {
            $class = $this->tokeniser->token()->rightToken(['(', '[']) ? 'brace' : 'invisibleBrace';
            $result = Tag::make('span', '', ['class' => $class]);
            $this->tokeniser->get();
            $result->addChild($this->expression());
            $this->tokeniser->get(); // get the trailing bracket
        } else {
            $result = Tag::make('span', '', ['class' => 'mathBland']);
            $result->addChild($this->primitive());
        }
        return $result;
    }

    function postfix(): tag {
        $postfix = Tag::make('span', '', ['class' => 'postfix']);
        $postfix->addChild($this->brace());

        while ($this->tokeniser->token()->rightToken(['!'])) {
            $postfix->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'postfix']);
            $this->tokeniser->get();
        }
        return $postfix;
    }

    function prefix(): tag {
        $prefix = Tag::make('span', '', ['class' => 'symbolContainer']);

        if ($this->tokeniser->token()->rightToken(['sqrt', '-', '+'])) {
            if ($this->tokeniser->token()->rightToken(['sqrt'])) {
                $this->tokeniser->get();
                $prefix->makeChild('span', MathSymbols::root(50), ['class' => 'symbol']);
                $prefix->makeChild('span', '', ['class' => 'vinculum'])->addChild($this->postfix());
            } else {
                $prefix->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'prefix']);
                $this->tokeniser->get();
                $prefix->addChild($this->postfix());
            }
        } else {
            $prefix->addChild($this->postfix());
        }
        return $prefix;
    }

    function subscript(): Tag {

        $subSuper = Tag::make('span', '', ['class' => 'subscriptContainer']);
        $subSuper->addChild($this->prefix());

        while ($this->tokeniser->token()->rightToken(['_'])) {
            //$token = $this->tokeniser->token()->token;
            $this->tokeniser->get();
            $subSuper->makeChild('sub', '', ['class' => 'subScript'])->addChild($this->subscript());
        }

        return $subSuper;
    }

    function superscript(): Tag {

        $subSuper = Tag::make('span', '', ['class' => 'superscriptContainer']);
        $subSuper->addChild($this->subscript());

        while ($this->tokeniser->token()->rightToken(['^'])) {
            //$token = $this->tokeniser->token()->token;
            $this->tokeniser->get();
            $subSuper->makeChild('sup', '', ['class' => 'superScript'])->addChild($this->superscript());
        }

        return $subSuper;
    }

    function divide(): Tag {
        $divide = Tag::make('span', '', ['class' => 'divide']);
        $divide->addChild($this->superscript());

        while ($this->tokeniser->token()->rightToken(['/'])) {
            $divide->makeChild('span', '', ['class' => 'dividingLine']);
            $this->tokeniser->get();
            $divide->addChild($this->superscript());
        }

        return $divide;
    }

    function timesDivide(): Tag {
        $timesDivide = Tag::make('span', '', ['class' => 'timesDivide']);
        $timesDivide->addChild($this->divide());

        while ($this->tokeniser->token()->rightToken(['*', '÷'])) {
            $timesDivide->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'binary']);
            $this->tokeniser->get();
            $timesDivide->addChild($this->divide());
        }
        return $timesDivide;
    }

   

    function addSubtract(): Tag {
        $addSubtract = Tag::make('span', '', ['class' => 'addSubtract']);
        $addSubtract->addChild($this->timesDivide());

        while ($this->tokeniser->token()->rightToken(['+', '-', '+-', '-+', '--', '++'])) {
            $addSubtract->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'operator']);
            $this->tokeniser->get();
            $addSubtract->addChild($this->timesDivide());
        }
        return $addSubtract;
    }

    function expression(): Tag {
        $expression = Tag::make('span', '', ['class' => 'expression']);
        $expression->addChild($this->addSubtract());

        while ($this->tokeniser->token()->rightToken(['=', '<=', '>=', '<>', '<', '>', '<<', '>>'])) {
            $expression->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'binary']);
            $this->tokeniser->get();
            $expression->addChild($this->addSubtract());
        }

        return $expression;
    }

    function expressions(): Tag {
        $expressions = Tag::make('span', '', ['class' => 'expression']);
        $expressions->addChild($this->expression());

        while ($this->tokeniser->token()->rightToken([','])) {
            $this->tokeniser->get();
            $expressions->addChild($this->expression());
        }
        return $expressions;
    }

    function evaluate(): Tag {

        $this->tokeniser->reset();
        $this->tokeniser->get();

        $expressions = $this->expressions();
        if ($this->tokeniser->token()->type !== MathToken::NULL) {
            $expressions->makeChild('span', 'error (expression finished before last token)', ['class' => 'error']);
        }

        return $expressions;
    }

    static function asTag(string $equation): Tag {
        //self::$mathParser = ?? new MathParser()
        return (new MathParser($equation))->evaluate();
    }

    static function card(string $equation, array $legend = []): Tag {
        $flex = Tag::make('span', '', ['class' => 'center-x']);
        $card = $flex->makeChild('div', '', ['class' => 'margin-bottom-2ch card expression']);

        //$flex->makeChild('span', $pre, ['class' => 'expression']);
        $card->addChild(MathParser::asTag($equation));
        $legendBox = $card->makeChild('div', '', ['class' => '']);
        foreach ($legend as $line) {
            $legendBox->makeChild('div', $line, ['class' => 'legend']);
        }
        //$flex->makeChild('span', $post, ['class' => 'expression']);
        return $flex;
    }
}

if (debug) {

    $tests = [
        // A. Radicals + superscripts
        "3.1 x",
        "sqrt(2 b^2 - 4.1 a)",
        "sqrt(3(a+b)^2)",
        "(1/x)^n",
        "sqrt(x^x)^x",
        "sqrt(1 + sqrt(1 + sqrt(x)))",
        // B. Script order & nesting
        "a^b_c",
        "a_b^c",
        "(a_b)^c",
        "a^(b_c)",
        "x^{y_z}",
        "x_{y^z}",
        "a^{b^{c}}",
        "a_{b_{c}}",
        // C. Fractions with tall parts
        "(a/b)^(c/d)",
        "((a^2 + b^2)/(c + d^3))^k",
        "sqrt((1 + 1/x) / (1 - 1/x))",
        // D. Unary vs binary minus
        "-a^2", // prefix minus
        "(-a)^2",
        "a-(-b)",
        "-(a+b)",
        "a * (-b)",
        // E. Multiplication with *
        "a * b",
        "a * (b+c)",
        // F. Multi-char operators
        "a <= b",
        "a >= b",
        "a << b",
        "a >> b",
        "a <> b",
        // G. Factorials and scripts
        "n!^2",
        "(n!)^2",
        "n!_k",
        // H. Tall delimiters
        "((a+b))^c",
        "[(a+b)/(c+d)]^e",
        "{a + (b/c)}^d",
        "x^2, k^(n+1), x^(a_b)",
        "(1/2)^n, ((a+b))^c, sqrt(x)^m",
        "1/a^(1/2), 1/(a^(b+c)), (a/b)^(c/d)"
            // I. Mixed prose + inline math
            //"Let f(x)=x^2 for x>=0; then sqrt(x)^3 grows."
    ];

    $page = new Page('MathParser', false);
    foreach ($tests as $expression) {
        $page->form->addChild(MathParser::card($expression));
    }


    $page->render();
}

