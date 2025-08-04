<?php


class Scene{
    private $owner;
    private $narrative;
    
    
    function show(){
        echo $this->narrative;
    }
}


class Play{
    private $cursor;
    private $scenes = [];
    
    function addScene($scene){
        $this->scenes[] = $scene;
    }
    
    function makeScene($narrative){
        $scene = new Scene();
    }
    
    function show(){
        foreach ($this->scenes as $scene) {
            $scene->show();
        }
    }
}