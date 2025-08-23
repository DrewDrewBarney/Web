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

        if ($this->tokeniser->token()->isOfTypes([MathToken::NUMBER, MathToken::ALPHA_NUMERIC])) {
            $result = Tag::make('span', $this->tokeniser->token()->prettyToken, ['class' => 'primitive']);
            $this->tokeniser->get();
        } else if ($this->tokeniser->token()->isOfTypes([MathToken::STRING])) {
            $result = Tag::make('span', $this->tokeniser->token()->prettyToken, ['class' => 'string']);
            $this->tokeniser->get();
            $this->tokeniser->get();
        } else {
            // WTF
            $result = Tag::make('span', 'no primitive');
        }

        return $result;
    }

    function brace(): tag {
        if ($this->tokeniser->token()->isOf(['(', '{', '['])) {
            $class = $this->tokeniser->token()->isOf(['(', '[']) ? 'brace' : 'invisibleBrace';
            $container = Tag::make('span', '', ['class' => $class]);
            $this->tokeniser->get();
            $container->addChild($this->expression());
            $this->tokeniser->get(); // get the trailing bracket
            return $container;
        }
        return $this->primitive();
    }

    function postfixOperator(): tag {
        $result = $this->brace();

        if ($this->tokeniser->token()->isOf(['!'])) {
            $container = Tag::make('span', '', ['class' => 'postfixContainer']);
            $container->addChild($result);
            while ($this->tokeniser->token()->isOf(['!'])) {
                $container->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'postfix']);
                $this->tokeniser->get();
            }
            return $container;
        }
        return $result;
    }

    function prefixOperator(): tag {

        if ($this->tokeniser->token()->isOf(['sqrt', '-', '+'])) {
            $this->tokeniser->get();

            if ($this->tokeniser->previousToken()->isOf(['sqrt'])) {
                $container = Tag::make('span', '', ['class' => 'SVGsymbolPlusVinculumContainer']);
                $container->makeChild('span', MathSymbols::root(50), ['class' => 'SVGsymbol']);
                $container->makeChild('span', '', ['class' => 'vinculum'])->addChild($this->postfixOperator());
            } else {
                $container = Tag::make('span', '', ['class' => 'prefixContainer']);
                $container->makeChild('span', $this->tokeniser->previousToken()->prettyToken, ['class' => 'prefix']);
                $container->makeChild('span', '', ['class' => 'prefix'])->addChild($this->postfixOperator());
            }
            return $container;
        } else {
            return $this->postfixOperator();
        }
    }

    function subscript(): Tag {

        $result = $this->prefixOperator();

        if ($this->tokeniser->token()->isOf(['_'])) {
            $container = Tag::make('span','',['class'=>'subscriptContainer']);
            $container->addChild($result);
            while ($this->tokeniser->token()->isOf(['_'])) {
                $this->tokeniser->get();
                $sub =$container->makeChild('sub','',['class'=>'subscript']);
                $sub->addChild($this->subscript());
            }
            return $container;
        }

        return $result;
    }

    function superscript(): Tag {
   
        $result = $this->subscript();

        if ($this->tokeniser->token()->isOf(['^'])) {
            $container = Tag::make('span','',['class'=>'superscriptContainer']);
            $container->addChild($result);
            
            while ($this->tokeniser->token()->isOf(['^'])) {
                $class = $this->tokeniser->previousToken()->isOf([')',']','}']) ? 'superscriptAfterBrace' : 'superScript';
                $this->tokeniser->get();
                $sup = $container->makeChild('sup','',['class'=>$class]);
                $sup->addChild($this->superscript());
            }
            return $container;
        }

        return $result;
    }

    function divide(): Tag {

        $result = $this->superscript();

        if ($this->tokeniser->token()->isOf(['/'])) {
            $divide = Tag::make('span', '', ['class' => 'divide']);
            $divide->addChild($result);
            while ($this->tokeniser->token()->isOf(['/'])) {
                $divide->makeChild('span', '', ['class' => 'dividingLine']);
                $this->tokeniser->get();
                $divide->addChild($this->superscript());
            }
            return $divide;
        }

        return $result;
    }

    function timesDivide(): Tag {
        $result = $this->divide();

        if ($this->tokeniser->token()->isOf(['*', '÷'])) {
            $timesDivide = Tag::make('span', '', ['class' => 'timesDivide']);
            $timesDivide->addChild($result);
            while ($this->tokeniser->token()->isOf(['*', '÷'])) {
                $timesDivide->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'binary']);
                $this->tokeniser->get();
                $timesDivide->addChild($this->divide());
            }
            return $timesDivide;
        }
        return $result;
    }

    function impliedTimes(): Tag {
        $result = $this->timesDivide();

        $previousMatch = $this->tokeniser->previousToken()->isOfTypes([MathToken::NUMBER, MathToken::ALPHA_NUMERIC]) && !$this->tokeniser->previousToken()->isOfTypes([MathToken::NULL]);
        $currentMatch = $this->tokeniser->token()->isOfTypes([MathToken::NUMBER, MathToken::ALPHA_NUMERIC]) && !$this->tokeniser->token()->isOfTypes([MathToken::NULL]);
        $catch = $previousMatch && $currentMatch;
        if ($catch) {
            $impliedTimes = Tag::make('span', '', ['class' => 'impliedTimes']);
            $impliedTimes->addChild($result);
            while ($catch) {
                $impliedTimes->makeChild('span', MathToken::TIMES, ['class' => 'operator']);
                $impliedTimes->addChild($this->impliedTimes());
                $previousMatch = $this->tokeniser->previousToken()->isOfTypes([MathToken::NUMBER, MathToken::ALPHA_NUMERIC]) && !$this->tokeniser->previousToken()->isOfTypes([MathToken::NULL]);
                $currentMatch = $this->tokeniser->token()->isOfTypes([MathToken::NUMBER, MathToken::ALPHA_NUMERIC]) && !$this->tokeniser->token()->isOfTypes([MathToken::NULL]);
                $catch = $previousMatch && $currentMatch;
            }
            return $impliedTimes;
        }
        return $result;
    }

    function addSubtract(): Tag {

        $result = $this->impliedTimes();

        if ($this->tokeniser->token()->isOf(['+', '-', '+-', '-+', '--', '++'])) {
            $addSubtract = Tag::make('span', '', ['class' => 'addSubtract']);
            $addSubtract->addChild($result);
            while ($this->tokeniser->token()->isOf(['+', '-', '+-', '-+', '--', '++'])) {
                $addSubtract->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'operator']);
                $this->tokeniser->get();
                $addSubtract->addChild($this->impliedTimes());
            }
            return $addSubtract;
        }

        return $result;
    }

    function expression(): Tag {

        $result = $this->addSubtract();

        if ($this->tokeniser->token()->isOf(['=', '<=', '>=', '<>', '<', '>', '<<', '>>'])) {
            $expression = Tag::make('span', '', ['class' => 'expression']);
            $expression->addChild($result);
            while ($this->tokeniser->token()->isOf(['=', '<=', '>=', '<>', '<', '>', '<<', '>>'])) {
                $expression->makeChild('span', $this->tokeniser->token()->prettyToken, ['class' => 'binary']);
                $this->tokeniser->get();
                $expression->addChild($this->addSubtract());
            }
            return $expression;
        }

        return $result;
    }

    function expressions(): Tag {

        $result = $this->expression();

        if ($this->tokeniser->token()->isOf([','])) {
            $expressions = Tag::make('span', '', ['class' => 'expressions']);
            $expressions->addChild($result);

            while ($this->tokeniser->token()->isOf([','])) {
                $this->tokeniser->get();
                $expressions->makeChild('span', ';');
                $expressions->addChild($this->expression());
            }
            return $expressions;
        }
        return $result;
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
        $flex = Tag::make('div', '', ['class' => 'center-x']);
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
        "sqrt (2/{1+2})",
        "c+5+7, d=e=f/2/3",
        "x^2",
        "e^-x^2/{4 a}",
        "-b +- 3 *sqrt{b^2 - 4 a 2 c}/{2 a}",
        "sqrt(2 b^2 - 4.1 a)",
        "sqrt((a+b)^2)",
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
        "x^2, k^{n+1}, x^(a_b)",
        "(1/2)^n, ((a+b))^c, sqrt(x)^m",
        "1/a^(1/2), 1/(a^(b+c)), (a/b)^(c/d)"
            // I. Mixed prose + inline math
            //"Let f(x)=x^2 for x>=0; then sqrt(x)^3 grows."
    ];

   //$tests = ['e^x, e^-x, e^(-x)'];

    $page = new Page('MathParser', false);
    foreach ($tests as $expression) {
        //$p = new MathParser($expression);
        //$p->tokenise();
        $page->form->addChild(MathParser::card($expression, [$expression]));
    }


    $page->render(false);
}

