<?php

class ErrorPage extends Page {
    
    private string $error = '';

    public function __construct(string $error = 'Error') {
        parent::__construct($error, false);
        $this->error = $error;
    }
    
    public function buildPage(): void {
        parent::buildPage();
        
        $this->form->makeChild('img','',['src'=>Context::commonImagePath() . 'UnderConstruction.png', 'class'=>'backgroundImage']);

        $article = $this->form->makeChild('article');

        $article->makeChild('h1', Tools::gp('page') . ' does not yet exist!',['class'=>'colorWhite']);
        $article->makeChild('h3', 'It is under construction...',['class'=>'colorWhite']);
    }
}
