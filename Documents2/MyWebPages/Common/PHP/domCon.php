<?php

include_once 'UserManagement.php';

function makeHead(string $title, string $favIconPath = '../../Common/Images/favIcon.png') {
    $head = Tag::make("head", '', ['style' => 'z-index: 1000;']);
    $head->makeChild('title', $title);
    //$head->makeChild('meta', '', ['name' => 'viewport', 'content' => 'width = device-width']);

    $rnd = random_int(0, 10000000);
    $head->makeChild("link", "", ["rel" => "stylesheet", "type" => "text/css", "href" => clientDocumentRoot() . "Common/CSS/DrewsStyle.css?v=$rnd"]);
    $head->makeChild('link', '', ['rel' => 'icon', 'type' => 'image/x-icon', 'href' => $favIconPath]);
    $head->makeChild("meta", "", ["charset" => "UTF-8"]);
    srand();
    return $head;
}

function makeTopBar() {
    $bar = Tag::make('div', '', ['class' => 'fixedTopBar']);
    if (UserManagement::loggedIn()) {
        $bar->makeChild('div', 'logged in', ['class' => 'LoggedIn']);
    }
    return $bar;
}

function makePageTitle($title) {
    return new Tag('div', $title, ['class' => 'pageTitle']);
}

function makeFooter() {
    $footer = Tag::make('footer', '', ['class' => 'footer']);
    $footer->makeChild('img', '', ['src' => '../../Common/Images/flash.png', 'style' => 'float:right', 'class' => 'dazzle']);
    $footer->makeChild('p', 'Dr Drew Shardlow');
    $footer->makeChild('p', 'Gibourne');
    $footer->makeChild('p', 'Nouvelle-Aquitaine');
    $footer->makeChild('p', 'France');
    $footer->makeChild('p')->makeChild('a', 'shardlow.a@gmail.com', ['href' => 'mailto:shardlow.a@gmail.com']);

    $keys = ['running', 'cycling', 'swimming', 'performance', 'race', 'racing'];

    for ($i = 0;
            $i < 3;
            $i++) {
        foreach ($keys as $key) {
            $footer->makeChild('p', $key, ['style' => 'visibility:collapse;']);
        }
    }

    return $footer;
}

function makePage(string $title): array {
    $html = Tag::make('html');
    $head = makeHead($title);
    $html->addChild($head);
    $body = $html->makeChild('body', '', ['class' => 'easeInPage']);
    //$form = $body->makeChild('form','',['id'=>'form']);
    return [$html, $head, $body];
}

function makeSpace(string $height): Tag {
    return Tag::make('div', '', ['style' => "height: $height;"]);
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

function chooseFile(Tag $form, Tag $elementContainingButtons, string $fileTypes = '', string $submitButtonCaption = 'Submit'): array {
    $form->setAttributes(['name' => 'chooseFileForm', 'enctype' => 'multipart/form-data', 'method' => 'post']);
    $elementContainingButtons->makeChild('input', '', ['type' => 'file', 'name' => 'chosen', 'accept' => $fileTypes]);
    $elementContainingButtons->makeChild('br');
    $elementContainingButtons->makeChild('input', '', ['type' => 'submit', 'value' => $submitButtonCaption]);
    return isset($_FILES['chosen']) ? $_FILES['chosen'] : [];
}

function popup($msg) {
    Tag::make('script', "alert('$msg');")->echo();
}

function makeSelect(array $options, ?string $selectedValue = null, string $name = 'default_select_name') {
    $select = Tag::make('select', '', ['name' => $name, 'value' => $selectedValue]);
    $selectedValue = $select->value() ? $select->value() : $selectedValue;
    foreach ($options as $option => $value) {
        $option = $select->makeChild('option', $option, ['value' => $value]);
        if ($selectedValue == $value){
            $option->setAttribute('selected', 'selected');
        }
    }
    return $select;
}

function makeRadio(array $options, string $default = '') {

    /*
     * THIS WAS FUCKING FIENDISH TO GET WORKING BECAUSE THE SELECTED OPTION GETS STUCK ON POSTING BACK TO THE SAME FORM
     * TO GET AROUND THIS YOU CREATE A NEW GROUP NAME FOR THE OPTION INPUTS BUT THEN
     * THE POSTED/GOTTEN VALUES HAVE CHANGED 
     * YOU HAVE TO STORE THE OLD NAME IN A HIDDEN INPUT AND RETRIEVE THE ASSOCIATED VALUE
     */

    $groupName = 'radioButtons_' . uniqid(); // needs to be new each form refresh otherwise selected value persists after posting back to same form even after new selection

    $div = Tag::make('div', '', ['name' => 'radioButtons']);
    $hiddenInput = $div->makeChild('input', '', ['type' => 'text', 'name' => 'hiddenInput', 'hidden' => 'true']);
    $hiddenInput->setAttribute('value', $groupName);

    $virginPage = sizeof($_POST) + sizeof($_GET) == 0;

    $i = 0;
    $radio = null;
    foreach ($options as $caption => $value) {
        $radio = $div->makeChild('input', '', ['type' => 'radio', 'id' => $value, 'name' => $groupName, 'value' => $value]);
        if ($virginPage) {
            if ($value == $default) {
                $radio->setAttribute('checked', 'true');
            }
        } else {
            if ($hiddenInput->value()) {
                $lastValue = Tag::readValue($hiddenInput->value());
                if ($value == $lastValue) {
                    $radio->setAttribute('checked', 'true');
                }
            }
        }

        $div->makeChild('label', $caption, ['for' => $value]);
        $i++;
    }

    $div->setValue(Tag::readValue($hiddenInput->value()));
    return $div;
}

function makeImage(string $source): Tag {
    $div = Tag::make('div', '', ['style' => 'height:60vh; overflow:clip;']);
    $div->makeChild('img', '', ['src' => $source, 'class' => 'backgroundImage',]);
    return $div;
}

function makePopUp(Tag $tagToPop, string $function) {
    $tagToPopString = $tagToPop->toString();
    $result = Tag::make('script',
                    "function $function{"
                    . "var popupWin = window.open('', '', 'width=1200px,height=1000px');"
                    . "popupWin.document.title = 'x';"
                    . "popupWin.document.write(`$tagToPopString`);"
                    . "popupWin.document.close();"
                    . "}"
    );

    return $result;
}

function makeClippingButton(string $clippingString, string $buttonCaption = 'Copy to Clipboard'): Tag {
    $inputId = 'hiddenInputForClipping_' . uniqid();
    $buttonId = 'clippingButton_' . uniqid();

    $scriptString = "
            function clip(){
                //window.alert('check');
                const button = document.getElementById('$buttonId');
                button.innerHTML = 'clipped';
                button.style.backgroundColor = 'green';

                const workoutText = document.getElementById('$inputId');
                workoutText.setSelectionRange(0,99999);
                workoutText.select();
                navigator.clipboard.writeText(workoutText.value);
            }
    ";
    $result = Tag::make('div');

    $result->makeChild('input', '', ['type' => 'text', 'style' => 'display:none; whitespace:pre-wrap;', 'id' => $inputId, 'name' => $inputId, 'value' => "$clippingString"]);
    $result->makeChild('button', $buttonCaption, ['id' => $buttonId, 'onclick' => $scriptString . 'clip();']);

    return $result;
}
