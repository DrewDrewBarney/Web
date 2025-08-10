<?php

class Home extends Page {

    public function __construct() {
        parent::__construct('Index');
    }

    protected function buildPage(): void {
        parent::buildPage();
        
        //$this->form->makeChild('img', '', ['src' => Context::projectImagePath() . 'home.png', 'class' => 'backgroundImage']);

        $article = $this->form->makeChild('article');
        
        $div = $article->makeChild('div');
        $div->makeChild('span', 'one + ');
        $sup = $div->makeChild('sup');
        $sup->makeChild('span', 'supOne');
        $supSup = $sup->makeChild('sup');
        $supSup->makeChild('span', 'supSup1');
        
       $supSup->makeChild('span', 'supSup2');
        $sup->makeChild('span', 'supThree');
        $div->makeChild('span', 'three');

        
        
    }
}
