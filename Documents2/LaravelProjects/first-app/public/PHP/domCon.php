<?php

include_once 'dom.php';

function makeHead(string $title) {
    $head = Tag::make("head", '', ['style' => 'z-index: 1000;']);
    $head->makeChild('title', $title);
    $head->makeChild('meta', '', ['name' => 'viewport', 'content' => 'width = device-width']);
    $head->makeChild("meta", "", ["charset" => "UTF-8"]);
    $head->makeChild("link", "", ["rel" => "stylesheet", "href" => "CSS/DrewsStyle.css?id=" . strval(rand())]);
    return $head;
}

function makeBanner(string $title, $breadcrumbDict = []) {
    $div = Tag::make('header', $title, ['class' => 'fixedTopBar']);
    $div->makeChild('img', '', ['src' => 'Images/flash.png', 'style' => 'float:right;', 'class' => 'dazzle']);
    $div->addChild(makeBreadcrumbTrail($breadcrumbDict));
    $div->makeChild('div', '', ['class' => 'clearBoth']);
    return $div;
}

function makeFooter() {
    $div = new Tag('div', 'X', ['style' => 'clear:both; background-color:#d7bf96; color: #d7bf96;']);
    $footer = $div->makeChild('footer', '', ['style' => 'margin-top:15px;']);
    $footer->makeChild('img', '', ['src' => 'Images/flash.png', 'style' => 'float:right', 'class' => 'dazzle']);
    $footer->makeChild('p', 'Dr Drew Shardlow', ['style' => 'color:#d7bf96 ;']);
    $footer->makeChild('p', 'Matha');
    $footer->makeChild('p', 'Nouvelle-Aquitaine');
    $footer->makeChild('p', 'France');
    $footer->makeChild('p')->makeChild('a', 'shardlow.a@gmail.com', ['href' => 'mailto:shardlow.a@gmail.com', 'style' => 'color:lightblue; text-decoration: none;']);

    $keys = ['running', 'cycling', 'swimming', 'performance', 'race', 'racing'];

    for ($i = 0; $i < 3; $i++) {
        foreach ($keys as $key) {
            $footer->makeChild('p', $key, ['style' => 'visibility:collapse;']);
        }
    }

    return $div;
}

function makePage(string $title, array $breadcrumbDict = []): array {
    $html = Tag::make('html');
    $head = makeHead($title);
    $html->addChild($head);
    $body = $html->makeChild('body');
    $banner = makeBanner($title, $breadcrumbDict);
    $body->addChild($banner);
    return [$html, $head, $body];
}

function makeBreadcrumbTrail(array $captionLinks) {
    $ul = Tag::make("ul", "", ["class" => "breadcrumb"]);
    foreach ($captionLinks as $caption => $link) {
        $li = $ul->addChild(new Tag("li"));
        $li->addChild(new Tag("a", $caption, ["href" => $link]));
    };
    return $ul;
}

/* * ****************** */
/* equation elements */
/* * ****************** */

function makeTableDatum($item): Tag {
    if (is_string($item)) {
        return Tag::make('td', $item, ['class' => 'equationDatum']);
    } else {
        $td = Tag::make('td', '', ['class' => 'equationDatum']);
        $td->addChild($item);
        return $td;
    }
}

function makeFrac($numerator, $denominator): Tag {
    $table = Tag::make('table', '', ['class' => 'equationTable']);
    $table->makeChild('tr', '', ['class' => 'equationRow'])->addChild(makeTableDatum($numerator));
    $table->makeChild('tr', '', ['class' => 'equationRow'])->makeChild('td', '', ['class' => 'equationDividingLine']);
    $table->makeChild('tr', '', ['class' => 'equationRow'])->addChild(makeTableDatum($denominator));
    return $table;
}

function makeEquation(array $parts): Tag {
    $table = Tag::make('table', '', ['class' => 'equationTable']);
    $tr = $table->makeChild('tr', '', ['class' => 'equationRow']);
    foreach ($parts as $part) {
        $tr->addChild(makeTableDatum($part));
    }
    return $table;
}

function makeBrace($char) {
    if ($char == '[') {
        return Tag::make('td', '', ['class' => 'leftBrace']);
    } else {
        return Tag::make('td', '', ['class' => 'rightBrace']);
    }
}

function buildLegend($explanations, $class = 'formulaAside'): Tag {
    $div = Tag::make('div', '', ['class' => $class]);
    $explans = explode(';', $explanations);
    foreach ($explans as $explan) {
        $div->makeChild('div', $explan, ['class' => 'formulaAsideLine']);
    }
    return $div;
}

function mountFormulaOnCard(Tag $formula, string $legend = ''): Tag {
    $div = Tag::make('div', '', ['class' => 'formulaDiv']);
    $div->addChild(buildLegend($legend, 'formulaAsideDummy')); // dummy div for spacing
    $div->makeChild('div', '', ['class' => 'formulaBox'])->addChild($formula);
    $div->addChild(buildLegend($legend));
    return $div;
}

/* make an unordered list */

function makeVerticalNavMenu(array $items): Tag {

    $nav = Tag::make('nav', '', ['class' => 'index']);
    $ul = $nav->makeChild('ul');
    foreach ($items as $detail) {
        list($title, $link) = $detail;
        $li = $ul->makeChild('li',);
        $div = $li->makeChild('div', '', ['class' => 'unorderedListItem']);
        $div->makeChild('a', $title, ['href' => $link, 'class' => '']);
    }
    return $nav;
}

/* make an image tile menu */

function makeImageTileMenu(array $items): Tag {

    $nav = Tag::make('nav', '', ['class' => 'index']);
    foreach ($items as $detail) {
        list($image, $description, $link) = $detail;
        $div = $nav->makeChild('div', '', ['class' => 'imageTileMenuRow']);
        $a = $div->makeChild('a', '', ['href' => $link]);
        $a->makeChild('img', '', ['src' => $image, 'class' => 'imageTileMenu']);
        $div->makeChild('p', $description, ['class' => 'tileMenuRowDescription']);
    }
    return $nav;
}

function makeTempFilePathGetter($postKey, $actionHome, $prompt = 'Select file to upload: ') {

    $tempPath = '';
    $fileInputID = 'fileInput';
    $form = Tag::make('form', '', ['method' => 'post', 'action' => $actionHome, 'enctype' => 'multipart/form-data']);
    $form->makeChild('label', $prompt, ['for'=>$fileInputID]);
    $form->makeChild('input', '', ['type' => 'file', 'name' => $postKey, 'id'=>$fileInputID]);
    $form->makeChild('input', '', ['type' => 'submit']);

    $_SESSION[$postKey] = null;

    if (key_exists($postKey, $_FILES)) {

        $fileMeta = $_FILES[$postKey];

        // unable to check for presence in array - funny stuff going on here
        
        //$fileName = $fileMeta['name'];
        
        //$form->makeChild('p', $fileName);
        
        
        //$fullpath = $fileMeta['full_path'];

        if ($fileMeta['error']) {
            $form->makeChild('p', 'Unable to load file', ['class'=>'error']);
            
        } else {

            $tempPath = $fileMeta['tmp_name'];

            if (is_uploaded_file($tempPath)) {
                $_SESSION[$postKey] = $tempPath;
            }
        }
    }
    return $form;
}

function popup($msg){
    Tag::make('script', "alert('$msg');")->echo();
}

function makeSelect(array $options){
    $select = Tag::make('select');
    foreach($options as $option){
        $select->makeChild('option', $option);
    }
    return $select;
}
