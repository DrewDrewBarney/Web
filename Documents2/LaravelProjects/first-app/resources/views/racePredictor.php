<?php
include_once 'PHP/all.php';

list($html, $head, $body)=makePage('Track Intervals Pace Calculator', ['home'=>'index.php']);

$article = $body->makeChild("article");
$article->makeChild("img", "", ["src" => "Images/Stopwatch.png", "alt" => "Track", "width" => "40%", "style" => "float:right; margin:30px;"]);

$para = $article->makeChild("p", 
        
        "These race predictions are based on Riegel's formula<sup>*</sup>. ".
        
     "I believe these in turn are based on flat racing records. ".
        "Common sense has to be used in their interpretation. ".
        "Actual race times will depend on many factors such as the weather, the terrain and of course how you feel on the day. "
        , 
        ['id' => 'land']);

   $para->makeChild('a','Peter Riegel', ['href'=>"https://en.wikipedia.org/wiki/Peter_Riegel", 'class'=>'buttonRight']);
     
        

        
   

$form = $article->makeChild("form", '', ['action' => '#land']);
$form->makeChild("label", "Most recent 10k time (mm:ss)", ["for" => "last10k", "style" => "float:none;"]);
$last10k = $form->makeChild("input", "", ["type" => "text", "id" => "last10k", "name" => "last10k"]);
$form->makeChild("button", "calculate");


$riegel = new Riegel(decimalMinutesFromString($last10k->value()), 10);

$table = $article->makeChild('table', '', ["class" => "intervalPaces"]);
$tr = $table->makeChild('tr');
$tr->makeChild('th', 'Race Distance / km');
$tr->makeChild('th', 'Predicted Time h:m:ss');

foreach ([5, 10, 21.1, 42.2] as $distance) {
    $mins = $riegel->raceTimeForDistance($distance);
    if ($mins) {
        $prediction = decimalMinutesToMinsSecsString($mins);
    } else {
        $prediction = "";
    }
    $tr = $table->makeChild('tr');
    $tr->makeChild('td', $distance);
    $tr->makeChild('td', $prediction);
}

$article->makeChild('p', "* this is predicted from Riegel's formula which says:");

$article->addChild(
        mountFormulaOnCard(
                makeEquation(
                        [
                            'T<sub>2</sub>', '=',
                            'T<sub>1</sub>', 'x',
                            makeBrace('['),
                            makeFrac('D<sub>2</sub>', 'D<sub>1</sub>'),
                            makeBrace(']'),
                            '<sup>1.06</sup'
                        ]
                ),
                'T<sub>1</sub> is a recent race time; D<sub>1</sub> is a recent race distance; D<sub>2</sub> the target race distance; T<sub>2</sub> the predicted race time'
        )
);


$body->addChild(makeFooter());

$html->echo();

