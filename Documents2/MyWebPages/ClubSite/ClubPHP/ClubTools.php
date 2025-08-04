<?php

include_once '../../Common/PHP/all.php';
include_once 'menu.php';


function makeClubBanner(?string $rightImage = null): Tag {
// set up the containers
    $flexDiv = Tag::make('div', '', ['style' => 'display:flex; justify-content: space-between; height:20vh; margin: 20px; border: solid 0px black;']);
    $leftDiv = $flexDiv->makeChild('div', '', ['class' => 'wavyBackground', 'style' => 'display:block; width:33%; height: 100%; padding:0%; border: solid 0px black;']);
    $rightDiv = $flexDiv->makeChild('div', '', ['style' => 'display:block; width:33%; height: 100%; margin: 0px 0px 0px auto; padding:0%; border: solid 0px black;']);

// add the right image
    if ($rightImage) {
        $rightDiv->makeChild("img", "", ["src" => "$rightImage", "alt" => "Track", "style" => "width:100%; height:100%;"]);
    }

// add the left image
    $leftDiv->makeChild('img', '', ['src' => '../ClubImages/angerien.png', 'style' => 'height: 80%; width: 80%; padding:5%;', 'class' => 'wobble']);

    return $flexDiv;
}

function makeImageGrid(array $images): Tag {
    $result = Tag::make('div', '', ['id' => 'parentGrid', 'class' => 'ParentImageGrid rotateIn']);
    $i = 0;
    $localPathToClubImages = "../AthleteImages/";
    $locatingClasses = ['ParentImageGridUpperLeft', 'ParentImageGridUpperRight', 'ParentImageGridLowerLeft', 'ParentImageGridLowerRight'];
    foreach (range(0, 3, 1) as $i) {
        $image = $images[$i];
        $locatingClass = $locatingClasses[$i];
        $src = $localPathToClubImages . $image;
        $result->makeChild('img', '', ['id' => 'image_' . $i, 'src' => $src, 'alt' => $image, 'class' => 'rotateIn slowGrow ' . $locatingClass]);
        $i++;
    }
    return $result;
}

function getCleansedPathsArray(string $searchPath): array {
    $result = [];
    $paths = scandir($searchPath);
    $forbiddens = ['.', '..', '.DS_Store'];

    foreach ($paths as $path) {
        if (!in_array($path, $forbiddens)) {
            $result[] = $path;
        }
    }

    return $result;
}



function makeClubImages(): Tag {
    $images = getCleansedPathsArray('../AthleteImages');
    //srand(time());
    //shuffle($images);
    //$images = array_merge(['../AthleteImages/track.png'], $images);
    return makeImageGrid($images);
}

const dbl = '"';
const sng = "'";

function storeImagesScript(): string {
    $imagesPath = '../AthleteImages';
    $result = '[';
    $images = getCleansedPathsArray($imagesPath);
    srand(time());
    shuffle($images);
    $dbl = '"';
    $sng = "'";
    $delim = '';
    foreach ($images as $image) {
        $result .= $delim . dbl . $imagesPath . '/' . $image . dbl;
        $delim = ',';
    }
    $result .= ']';
    $result = 'window.localStorage.setItem("images",' .
            sng . $result . sng .
            " );\n";
    $result .= 'window.localStorage.setItem("baseImageIndex","0");';
    $result .= 'window.localStorage.setItem("imageIndex","0");';    
    $result .= 'window.localStorage.setItem("zIndex","0");';    

    return $result;
}



const javaFunctionToggleMusic = 
    "
        function toggleMusic(){
            const player = document.getElementById('audio');
            if (player.paused){
                player.src = '../ClubMusic/club.mp3';
                player.play();    
            } else {
                player.pause();
            }
        }
    ";

const javaFunctionLoadImages = 
    "
        function loadImages(){
            const jsonString = window.localStorage.getItem('images');
            const images = JSON.parse(jsonString);  
            return images;
        }
    ";



const javaFunctionTriggerAnimation = "
    function triggerAnimation(element, animationClass){
        element.classList.remove(animationClass);
        void element.offsetWidth;
        element.classList.add(animationClass);
    }
";



const javaScriptSwitchImages = 
    javaFunctionLoadImages .
    javaFunctionTriggerAnimation .
    "
        parentGrid = document.getElementById('parentGrid');
        triggerAnimation(parentGrid, 'rotateIn');
        let images = loadImages();

        function switchImages(){ 
            //shuffleArray(images);   

            document.getElementById('image_0').src = '../ClubImages/track.png';

            let imageIndex = parseInt(window.localStorage.getItem('imageIndex'));
            let baseImageIndex = parseInt(window.localStorage.getItem('baseImageIndex'));
            let zIndex = parseInt(window.localStorage.getItem('zIndex'));
                     
            img = document.getElementById('image_' + (imageIndex + 1));
            img.style.zIndex = zIndex;
            img.src = images[baseImageIndex];
            triggerAnimation(img, 'rotateIn');
            triggerAnimation(img, 'slowGrow');
           

            baseImageIndex = (baseImageIndex + 1) % images.length;
            imageIndex = (imageIndex + 1) % 3;
            zIndex++;
            window.localStorage.setItem('baseImageIndex', baseImageIndex.toString());
            window.localStorage.setItem('imageIndex', imageIndex.toString());
            window.localStorage.setItem('zIndex', zIndex.toString());
        }
        window.setInterval(switchImages, 2000);
    ";

 
