<?php  declare(strict_types=1);

class Link{
    
    public string $caption;
    public string $pageURL;
    
    function __construct(array $captionPageURL) {
        $this->caption = $captionPageURL['caption'];
        $this->pageURL = $captionPageURL['url'];
    }
    
    function toTag():Tag{
        return Tag::make('a', $this->caption, ['href'=> $this->pageURL]);
    }
    
    
}

