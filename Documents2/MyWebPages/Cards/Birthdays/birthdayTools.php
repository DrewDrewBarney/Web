<?php

function makeHead(string $title, string $favIconPath = '../../Common/Images/favIcon.png') {
    $head = Tag::make("head", '', ['style' => 'z-index: 1000;']);
    $head->makeChild('title', $title);
    //$head->makeChild('meta', '', ['name' => 'viewport', 'content' => 'width = device-width']);

    $rnd = random_int(0, 10000000);
    $head->makeChild("link", "", ["rel" => "stylesheet", "type" => "text/css", "href" => "birthdays.css?v=$rnd"]);
    $head->makeChild('link', '', ['rel' => 'icon', 'type' => 'image/x-icon', 'href' => $favIconPath]);
    $head->makeChild("meta", "", ["charset" => "UTF-8"]);
    srand();
    return $head;
}





