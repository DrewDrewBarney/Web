<?php

class About extends Page {

    function __construct() {
        parent::__construct('about');
    }

    protected function buildPage(): void {
        parent::buildPage();


        $article = $this->form->makeChild('article','',['class'=>'']);
        
        $article->makeChild('img', '', ['src' => Context::projectImagePath() . 'Drew.jpeg', 'class' => 'rightImage width20']);

        $article->makeChild('h2', 'About the Author');

        $dob = new DateTime('1958/04/02');
        $now = new DateTime('now');
        $age = $dob->diff($now)->y;

        $article->makeChildren('p', [
            "I am a $age yr old retired general practitioner though have worked as a software engineer in the past.
                I am a also lifelong runner and running enthusiast.",
            "I am not an elite athlete and I am definitely slowing down with age!
                I enjoy most outdoor pursuits including cycling and swimming and have competed in shorter triathlons.",
            "I am not a sports scientist but have attended and enjoyed several courses organised by the then British Association of Sports Medicine (BASM, now BASEM) 
                at The National Sports Center in Lilleshall, Shrophsire, United Kingdom.
                The information presented here are my own ideas and opinions.",
            " If you believe there are important factual errors feel free to contact me and discuss them and if appropriate I can incorporate changes if needed.
                All opinions should be respected and this document is essentially my own views at the time of writing."
        ]);

        $article->makeChild('a', 'Run Britain ranking', ['href' => 'https://www.runbritainrankings.com/runners/profile.aspx?athleteid=246959', 'class' => 'button']);
    }
}
