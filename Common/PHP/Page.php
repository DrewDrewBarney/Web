<?php

class Page {

    private string $title = '';
    public Tag $html;
    public Tag $head;
    public Tag $body;
    public Tag $form;

    function __construct(string $title = '', bool $withFooter = true) {
        $this->title = $title;
        $this->buildPage();
        if ($withFooter) {
            $this->makeFooter();
        }
    }

    protected function buildPage(): void {
        $this->html = Tag::make('html');
        $this->head = $this->html->makeChild('head');
        $this->body = $this->html->makeChild('body');
        $this->body->addChild(Menu::make(Context::sitePlan())); // THE MENU
        $this->form = $this->body->makeChild('form','',['class'=>'mainBody']);
        $this->makeHead($this->title);
    }

    private function makeHead(string $title = ''): void {
        //$head = Tag::make("head", '', ['style' => 'z-index: 1000;']);
        $this->head->makeChild('title', $title);
        $this->head->makeChild('meta', '', ['name' => 'viewport', 'content' => 'width = device-width']);

        foreach (Context::relativeCSSpaths() as $files) {
            $this->head->makeChild("link", "", ["rel" => "stylesheet", "type" => "text/css", "href" => $files]);
        }
        $this->head->makeChild('link', '', ['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Context::iconPath()]);
        $this->head->makeChild("meta", "", ["charset" => "UTF-8"]);
    }

    private function makeFooter() {
        $footer = $this->body->makeChild('footer', '', ['class' => 'footer']);
        $footer->makeChildren('div',
                [
                    'Dr Drew Shardlow',
                    'Gibourne',
                    'Nouvelle-Aquitaine',
                    'France',
                    'shardlow.a@gmail.com'
                ],
                ['class' => 'footerLines']
        );
    }

    public function render() {
        $this->html->render();
    }

// STATICS
}
