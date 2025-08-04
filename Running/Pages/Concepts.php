<?php

class Concepts extends Page {

    public function __construct() {
        parent::__construct('Error', true);
    }

    public function buildPage(): void {
        parent::buildPage();
        $this->form->makeChild('img', '', ['src' => Context::projectImagePath() . 'basis.png', 'class' => 'backgroundImage']);

        $article = $this->form->makeChild('article');
        $article->makeChild('div','',['class'=>'height33']);

        //$article->makeChild('h1', Tools::gp('page') . ' does not yet exist!',['class'=>'colorWhite']);
        //$article->makeChild('h3', 'It is under construction...',['class'=>'colorWhite']);
    }
}
