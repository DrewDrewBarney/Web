<?php

class Home extends Page {

    public function __construct() {
        parent::__construct('Index');
    }

    protected function buildPage(): void {
        parent::buildPage();
        
        $this->form->makeChild('img', '', ['src' => Context::projectImagePath() . 'home.png', 'class' => 'backgroundImage']);

        $article = $this->form->makeChild('article');

        $article->makeChild('h1', 'Home');
    }
}
