<x-layout pageName=intervalPaces pageTitle='Interval Paces'>

<?php
include_once 'PHP/all.php';


//list($html, $head, $body) = makePage('Track Intervals Pace Calculator', ['home' => 'index.php']);

$div = Tag::make("div");

$article = $div->makeChild("article");
$article->makeChild("img", "", ["src" => "Images/Stopwatch.png", "alt" => "Track", "width" => "40%", "style" => "float:right; margin:30px;"]);

$article->makeChild("p", "
    These paces are designed for multiple repetitions of these distances and so 
    are much slower than the corresponding race paces.
    It is so easy to overdo it on the track. This is counterproductive
    as excessive stress breaks down rather than builds up the necessary adaptations to 
    run longer and faster.
");

$article->makeChild("p", "
    We are competititive or we wouldn't be here. It is important not to get drawn 
    into competing on the track as this defeats the object, which is to train.
    Track training is all about consistency and control.
",
    ['id' => 'land']);


$article->makeChild('p', "
    Training intensity typically varies over two or three week cycles to create
    variety, to stress and recover over a longer timescale than the usual weekly
    routine. I have therefore added paces for Easy, Normal and Hard weeks.
    "
);


$form = $article->makeChild("form", '', ['action' => '#land']);
$form->makeChild("label", "Most recent 10k time (mm:ss)", ["for" => "last10k", "style" => "float:none;"]);
$last10k = $form->makeChild("input", "", ["type" => "text", "id" => "last10k", "name" => "last10k"]);
$form->makeChild("button", "calculate");

foreach (['Normal Week' => [7, 'lightgreen'], 'Hard Week' => [6, 'pink'], 'Easy Week' => [8, 'lightblue']] as $key => $valueColor) {

    $value = $valueColor[0];
    $color = $valueColor[1];

    $table = $article->makeChild("table", "", ["class" => "intervalPaces"]);
    
    $table->makeChild('tr')->makeChild('th', $key, ['colspan' => 3, 'style' => "color:$color;"]);

    $titleRow = $table->makeChild("tr");
    $titleRow->makeChild("th", "meters");
    $titleRow->makeChild("th", "pace mins/km");
    $titleRow->makeChild("th", "splits");
    
    //popup($_GET['last10k']);

    if ($last10k->value()) {
        $riegel = new Riegel(decimalMinutesFromString($last10k->value()), 10);
        $riegel->setRelaxation($value);
        foreach ([100, 200, 300, 400, 500, 600, 800, 1000, 1200, 1500] as $meters) {
            $row = $table->makeChild("tr");
            $row->makeChild("td", $meters);
            $pace = $riegel->intervalPaceForDistance($meters / 1000);
            $split = $pace * $meters / 1000;
            $row->makeChild("td", decimalMinutesToMinsSecsString($pace));
            $row->makeChild("td", decimalMinutesToMinsSecsString($split));
        }
    }
}


$div->echo();

?>

</x-layout>

