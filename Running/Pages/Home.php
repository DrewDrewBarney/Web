<?php

class Home extends Page {

    public function __construct() {
        parent::__construct('Index');
    }

    protected function buildPage(): void {
        parent::buildPage();

        $this->form->makeChild('img', '', ['src' => Context::projectImagePath() . 'home.png', 'class' => 'backgroundImage']);
    }
}
