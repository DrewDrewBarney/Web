<?php

//declare(strict_types=1);

class Tag {

    const INPUT_FILTERS = [
        '' => FILTER_SANITIZE_SPECIAL_CHARS,
        'email' => FILTER_VALIDATE_EMAIL,
        'number' => FILTER_SANITIZE_NUMBER_FLOAT,
        'text' => FILTER_SANITIZE_SPECIAL_CHARS,
        "text/javascript" => FILTER_SANITIZE_SPECIAL_CHARS,
        'date' => FILTER_SANITIZE_SPECIAL_CHARS
    ];

    private string $typeName = '';
    private string $inner = '';
    private array $children = [];
    private array $attributes = [];
    private array $selfClosingTags = ['area', 'base', 'br', 'colembed', 'hr', 'img', 'link', 'meta', 'param', 'source', 'track', 'wbr', /* ZWIFT */ 'tag', 'textevent'];
    private bool $visible = true;
    // literal output related stuff (</> escaped etc...)
    private bool $literal = false;
    private bool $zeroTheTab = false;


    public function __construct(string $typeName, string $inner = "", ?array $attributes = null) {
        //$this->domID = uniqid();
        $this->typeName = $typeName;
        //$this->selfClosing = in_array($this->typeName, $this->selfClosingTags);

        $this->attributes = $attributes ? $attributes : [];

        if ($this->value()) {
            $this->attributes["value"] = $this->value();
        }

        if ($this->selfClosing()) {
            if ($inner !== '') {
                throw new Exception("a self-closing tag of type $this->typeName should have no inner!");
            }
        } else {
            $this->inner = $inner;
        }
        $this->children = [];
    }

    static public function make(string $typeName, string $inner = "", array $attributes = []): Tag {
        return new Tag($typeName, $inner, $attributes);
    }

    public function selfClosing(): bool {
        return in_array($this->typeName, $this->selfClosingTags);
    }

    public function setVisibility(bool $visible): void {
        $this->visible = $visible;
    }

    protected function _setLiterality(bool $literal): void {
        $this->literal = $literal;
        foreach ($this->children as $child) {
            $child->_setLiterality($literal);
        }
    }

    public function setLiterality(bool $literal): void {
        $this->zeroTheTab = true;
        $this->_setLiterality($literal);
    }

    public function addChild(Tag $child, ?array $attributes = null, $append = false): Tag {
        if ($this->selfClosing()) {
            throw new Psy\Readline\Hoa\Exception('Unable to use the method ->addChild of a self-closing tag!');
        } else {
            if ($attributes) {
                $child->setAttributes($attributes, $append);
            }
            $this->children[] = $child;
            return $child;
        }
    }

    public function makeChild(string $typeName, string $inner = "", ?array $attributes = null): Tag {
        if ($this->selfClosing()) {
            throw new Psy\Readline\Hoa\Exception('Unable to use the method ->makeChild of a self-closing tag!');
        } else {
            return $this->children[] = new Tag($typeName, $inner, $attributes);
        }
    }

    public function makeChildren(string $typeName, array $inners, array $attributes = []): Tag {
        $result = null;
        foreach ($inners as $inner) {
            $result = $this->makeChild($typeName, $inner, $attributes);
        }
        return $result;
    }

    public function addChildren(array $children): void {
        foreach ($children as $child) {
            $this->addChild($child);
        }
    }

    public function clearChildren(): void {
        $this->children = [];
    }

    public function children(): array {
        return $this->children;
    }

    public function hasChildren(): bool {
        return count($this->children) > 0;
    }

    public function setInner($inner): void {
        $this->inner = $inner;
    }
    
    public function hasInner():bool{
        return $this->inner !== '';
    }

    public function setAttributes(array $keysValues, bool $append = false): void {
        foreach ($keysValues as $key => $value) {
            $this->setAttribute($key, $value, $append);
        }
    }

    public function setAttribute(string $key, string $value, bool $append = false) {
        if ($append && isset($this->attributes[$key])) {
            $this->attributes[$key] = $this->attributes[$key] . ' ' . $value;
        } else {
            $this->attributes[$key] = $value;
        }
    }

    private function attributesToString(): string {
        $result = "";
        foreach ($this->attributes as $key => $value) {

            if (strpos($value, '"') !== false) {
                $result .= ' ' . $key . "='" . $value . "'";
            } else {
                $result .= ' ' . $key . '="' . $value . '"';
            }
        }
        return $result;
    }

    public function getAttribute(string $key): string {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        } else {
            return "";
        }
    }
    
    public function hasAttributes():bool{
        return count($this->attributes) > 0;
    }

    private function _toString(string $tab): string {
        $newLine = "\n";
        $slash = $this->literal ? '&#47' : '/';
        $openTag = $this->literal ? '&#60' : '<';
        $closeTag = $this->literal ? '&#62' : '>';
        $openCloseTag = $openTag . $slash;
        $closeCloseTag = $slash . $closeTag;

        if ($this->zeroTheTab === true) {
            $tab = '';
        }

        if ($this->visible) {

            $result = $tab . $openTag . $this->typeName . $this->attributesToString() . ($this->selfClosing() ? $closeCloseTag : $closeTag);

            if ($this->selfClosing()) {
                $result .= $newLine;
            } else {
                if ($this->hasChildren()) {
                    $result .= $newLine;
                    if ($this->inner) {
                        $result .= $tab . $this->inner . $newLine;
                    }
                    foreach ($this->children as $child) {
                        $result .= $child->_toString("    " . $tab);
                    }
                    $result .= $tab . $openCloseTag . $this->typeName . $closeTag . $newLine;
                } else {
                    $result .= $this->inner;
                    $result .= $openCloseTag . $this->typeName . $closeTag . $newLine;
                }
            }

            return $result;
        } else {
            return '';
        }
    }

    public function toString(): string {
        return $this->_toString('');
    }

    public function name(): string {
        return isset($this->attributes['name']) ? $this->attributes['name'] : '';
    }

    public static function readValue(string $name, $type = ''): string {
        $result = '';
        $method = filter_var($_SERVER['REQUEST_METHOD'], FILTER_SANITIZE_SPECIAL_CHARS);
        $filter = isset(self::INPUT_FILTERS[$type]) ? self::INPUT_FILTERS[$type] : FILTER_SANITIZE_SPECIAL_CHARS;

        if ($method === 'POST') {
            $result = isset($_POST[$name]) ? filter_var($_POST[$name], $filter) : $result;
        } else if ($method === 'GET') {
            $result = isset($_GET[$name]) ? filter_var($_GET[$name], $filter) : $result;
        }

        return $result === null ? '' : $result;
    }

    public function pressed(): bool {
        return self::readValue($this->name()) != '';
    }

    public function value(): string {
        $result = isset($this->attributes['value']) ? $this->attributes['value'] : '';

        $name = $this->name();
        $type = $this->getAttribute('type');
        $getpostValue = self::readValue($name, $type);

        $result = $getpostValue ? $getpostValue : $result;
        return $result;
    }

    public function intVal(): int {
        return intval($this->value());
    }

    public function floatVal(): float {
        return floatval($this->value());
    }

    public function setValue(string $value): void {
        if (isset($this->attributes['name'])) {
            $name = $this->attributes['name'];
            $this->attributes['value'] = $value;
            if (filter_var($_SERVER['REQUEST_METHOD'], FILTER_SANITIZE_SPECIAL_CHARS) == 'POST') {
                $_POST[$name] = $value;
            } else if (filter_var($_SERVER['REQUEST_METHOD'], FILTER_SANITIZE_SPECIAL_CHARS) == 'GET') {
                $_GET[$name] = $value;
            }
        }
    }

    public function render(): void {
        print($this->_toString(''));
    }

    public function createIndices(string $path = '0') {
        $this->setAttributes(['id' => $path, 'name' => $path]);
        $index = 0;
        foreach ($this->children as $child) {
            $child->createIndices($path . '.' . $index);
            $index++;
        }
    }
    
    /***************************************************************************
     * 
     *                          OUTPUT TO PHP
     * 
     **************************************************************************/
    
    
    private function quoteInner(string $inner):string{
        $singleQuote = "'";
        $doubleQuote = '"';
        $hasDoubleQuote = strpos($inner,'"') !== false;
        $hasSingleQuote = strpos($inner, "'") !== false;
        
        if ($hasSingleQuote && $hasDoubleQuote){
            return $singleQuote . 'inner has single and double quotes' . $singleQuote;
        } else if ($hasSingleQuote){
            return $doubleQuote . $inner . $doubleQuote;
        } else {
            return $singleQuote . $inner . $singleQuote;
        } 
    }
    
    
    private function attributesToPHP():string{
        $result = '[';
        foreach ($this->attributes as $key => $value) {
            $result .= "'" . $key . "' => '" . $value . "',";
        }
        return $result . ']';
    }

    private function _toPHP(array &$php, string $id): void {
        $newId = $this->typeName;
        
        $result = $this->hasChildren() ? '$' . $newId . ' =' : '';

        $result .= '$' . $id . '->makeChild(' . "'" . $this->typeName . "'";
        
        if ($this->hasInner() || $this->hasAttributes()){
            $result .= ', ' .$this->quoteInner($this->inner);
        }
        
        if ($this->hasAttributes()){
            $result .= ", " . $this->attributesToPHP();
        }
        
        $result .= ');<br>';
        
        $php[] = $result;

        if ($this->hasChildren()) {
           
            foreach ($this->children as $child) {
                $result .= $child->_toPHP($php, $newId);
            }
           
        } 
    }
    
    public function toPHP():array{
        $php = [];
        $this->_toPHP($php, 'root');
        return $php;
    }
}
