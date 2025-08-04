<?php

class Tag {

    //public string $domID;
    private $typeName;
    private $inner;
    private $children = [];
    private $attributes = [];
    private $selfClosing = false;
    private $selfClosingTags = ['area', 'base', 'br', 'colembed', 'hr', 'img', 'link', 'meta', 'param', 'source', 'track', 'wbr'];

    //public $log = "";

    public function __construct(string $typeName, string $inner = "", array $attributes = []) {
        //$this->domID = uniqid();
        $this->typeName = $typeName;
        $this->attributes = $attributes;

        if ($this->value()) {
            $this->attributes["value"] = $this->value();
        }

        $this->inner = $inner;
        $this->children = [];
        $this->selfClosing = in_array($this->typeName, $this->selfClosingTags);
    }

    static public function make(string $typeName, string $inner = "", array $attributes = []) {
        return new Tag($typeName, $inner, $attributes);
    }

    public function addChild(Tag $child) {
        $this->children[] = $child;
        return $child;
    }

    public function makeChild(string $typeName, string $inner = "", array $attributes = [], bool $selfClosing = false) {
        return $this->children[] = new Tag($typeName, $inner, $attributes, $selfClosing);
    }

    public function addChildren(array $children) {
        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    public function children(): array {
        return $this->children;
    }

    public function setInner($inner) {
        $this->inner = $inner;
    }

    public function setAttributes($keysValues) {
        foreach ($keysValues as $key => $value) {
            $this->attributes[$key] = $value;
        }
    }
    


    private function attributesToString(): string {
        $result = "";
        foreach ($this->attributes as $key => $value) {
            $result .= ' ' . $key . '="' . $value . '"';
        }
        return $result;
    }

    public function readAttribute(string $key) {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        } else {
            return "";
        }
    }

    private function _toString($tab): string {

        $result = $tab . "<" . $this->typeName . $this->attributesToString() . ">";

        $hasChildren = count($this->children) > 0;

        if ($this->selfClosing) {
            if ($this->inner) {
                throw new Exception("a self-closing tag of type $this->typeName should have no inner!");
            }
            if ($hasChildren) {
                throw new Exception("a self-closing tag of type $this->typeName should have no children!");
            }
            $result .= "\n";
        } else {
            if ($hasChildren) {
                $result .= "\n";
                if ($this->inner) {
                    $result .= $tab . $this->inner . "\n";
                }
                foreach ($this->children as $child) {
                    $result .= $child->_toString($tab . "  ");
                }
                $result .= $tab . "</" . $this->typeName . ">\n";
            } else {
                $result .= $this->inner;
                $result .= "</" . $this->typeName . ">\n";
            }
        }

        return $result;
    }

    public function value(): string {

        //$this->log .= "\n\nCALL TO VALUE\n";
        $result = '';
        if (isset($this->attributes['name'])) {

            //$this->log .= "the attribute name exists in the input tag\n";
            if (isset($this->attributes['name'])) {
                $name = $this->attributes['name'];
                //$this->log .= "the posting name of the input is " . $name . "\n";

                if (filter_var($_SERVER['REQUEST_METHOD'], FILTER_SANITIZE_SPECIAL_CHARS) == 'POST') {
                    //$this->log .= "POST\n";
                    $result = filter_input(INPUT_POST, $name);
                    //$this->log .= 'the value is ' . $result . "\n";
                } else if (filter_var($_SERVER['REQUEST_METHOD'], FILTER_SANITIZE_SPECIAL_CHARS) == 'GET') {
                    //$this->log .= "GET\n";
                    $result = filter_input(INPUT_GET, $name);
                    //$this->log .= 'the value is ' . $result . "\n";
                } else {
                    //$this->log .= "neither a GET nor a POST!!\n";
                    //$this->log .= "it is instead a " . filter_input(INPUT_SERVER, 'REQUEST_METHOD') . "\n";
                }
            }
        }

        //Tag::make('h2', $this->log)->echo();

        return $result ? $result : '';
    }

    public function echo() {
        print($this->_toString(""));
    }

    /*
      public function findTagWithDomID($id){
      if ($this->domID == $id){
      return $this;
      } else{
      foreach($this->children as $child){
      if ($child->findTagWithDomID($id)){
      return $child;
      }
      }
      }
      return null;
      }
     * */
}
