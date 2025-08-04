<?php

// THIS IS THE ONLY PAGE OF THIS SINGLE PAGE PROJECT


session_start();

include_once '../../Common/PHP/Context.php';
$relativeRoot = Context::relativeRootURL();
Context::loadClasses([
    $relativeRoot . 'Common',
    $relativeRoot . 'Running'
]);
Context::setIconPath('Common/Images/favIcon.png');
Context::setCSSpaths(['Common/CSS/']);
Context::setCommonImagePath('Common/Images/');
Context::setProjectImagePath('Running/Images/');
Context::setSiteTitle("Drew's Run Files");
Context::setSitePlan(RunningSitePlan::MAIN);



class Index {

    private ?Page $page = null;

    function __construct() {

        $class = Tools::gp('page');
        $class = $class ? $class : 'Home';

        if (class_exists($class)) {
            $this->page = new $class();
        } else {
            $this->page = new ErrorPage();
        }
    }

    function render() {
        $this->page->render();
    }
}

(new Index())->render();
