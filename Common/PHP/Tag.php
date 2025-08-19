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
    private array $children = [];
    private array $attributes = [];
    private array $selfClosingTags = ['area', 'base', 'br', 'colembed', 'hr', 'img', 'link', 'meta', 'param', 'source', 'track', 'wbr', /* ZWIFT */ 'tag', 'textevent'];
    private bool $visible = true;
    // literal output related stuff (</> escaped etc...)
    private bool $literal = false;
    private bool $zeroTheTab = false;

    public function __construct(string $typeName, ?array $attributes = null) {
        $this->children = [];

        $this->typeName = $typeName;

        $this->attributes = $attributes ? $attributes : [];

        // update from the GET/POST
        if ($this->value()) {
            $this->attributes["value"] = $this->value();
        }
    }

    // L E G A C Y
    //////////////
    static public function make(string $typeName, string $text = '', array $attributes = []): Tag {
        $tag = new Tag($typeName, $attributes);
        $tag->addText($text);
        return $tag;
    }

    public function addChild(Tag $child, ?array $attributes = null, $append = false): Tag {
        if ($this->selfClosing()) {
            throw new Exception('You cannot add a child to a self-closing tag silly!');
        } else {
            if ($attributes) {
                $child->setAttributes($attributes, $append);
            }
            $this->children[] = $child;
            return $child;
        }
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

    public function addText(string $text, bool $prepend = false): void {
        if ($text !== '') {
            if ($this->selfClosing()) {
                throw new Exception('You cannot add text to a self-closing tag silly (-;');
            } else {
                if ($prepend) {
                    array_unshift($this->children, $text);
                } else {
                    $this->children[] = trim($text);
                }
            }
        }
    }

    // L E G A C Y
    public function makeChild(string $typeName, string $inner = '', ?array $attributes = null): Tag {
        $result = $this->addChild(new Tag($typeName, $attributes));
        if ($inner !== '') {
            $result->addText($inner);
        }
        return $result;
    }

    // L E G A C Y
    public function makeChildren(string $typeName, array $inners, array $attributes = []): Tag {
        $result = null;
        foreach ($inners as $inner) {
            $result = $this->makeChild($typeName, $inner, $attributes);
        }
        return $result;
    }

    // L E G A C Y
    public function setInner($inner): void {
        $this->children = array_unshift($this->children, $inner);
    }

    // L E G A C Y
    public function hasInner(): bool {
        foreach ($this->children as $child) {
            if (is_string($child)) {
                return true;
            }
        }
        return false;
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

    public function hasAttributes(): bool {
        return count($this->attributes) > 0;
    }

    private function _toString(string $tab): string {
        $newLine = "\n";
        $slash = $this->literal ? '&#47' : '/';
        $openTag = $this->literal ? '&#60' : '<';
        $closeTag = $this->literal ? '&#62' : '>';
        $openCloseTag = $openTag . $slash;
        $closeCloseTag = $slash . $closeTag;

        if (!$this->visible) {
            return '';
        }

        $tab = $this->zeroTheTab ? '' : $tab;

        if ($this->selfClosing()) {
            // THE SELF CLOSING TAG
            return $tab . $openTag . $this->typeName . $this->attributesToString() . $closeCloseTag . $newLine;
        } else {
            // THE OPENING TAG
            $result = $tab . $openTag . $this->typeName . $this->attributesToString() . $closeTag . $newLine;
            //
            foreach ($this->children as $child) {
                if (is_string($child)) {
                    $result .= $tab . $child . $newLine;
                } else {
                    $result .= $child->_toString($tab . '    ');
                }
            }
            // THE CLOSING TAG
            $result .= $tab . $openCloseTag . $this->typeName . $this->attributesToString() . $closeTag . $newLine;
            //
            return $result;
        }
    }

    public function toString(): string {
        return $this->_toString('');
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

    public function createIndices(string $path = '0') {
        $this->setAttributes(['id' => $path, 'name' => $path]);
        $index = 0;
        foreach ($this->children as $child) {
            $child->createIndices($path . '.' . $index);
            $index++;
        }
    }

    public static function ugly(string $pretty): string {
        return preg_replace('/>(?:\h|\R)+</u', '><', $pretty);
    }

    public function render(bool $pretty = false): void {
        print($pretty ? $this->toString() : self::ugly($this->toString()));
    }

    /*     * ************************************************************************
     * 
     *                          OUTPUT TO PHP
     * 
     * ************************************************************************ */

    const DEBUG = true;

    private function attributesToPHP(): string {
        $result = '';
        if ($this->hasAttributes()) {
            $result = '[';
            foreach ($this->attributes as $key => $value) {
                $result .= "'$key' => '$value',";
            }
            $result .= ']';
        }
        return $result;
    }

    private function _toPHP(array &$php, string $id, string $tab): void {

        $typeName = $this->typeName;
        $attributes = $this->attributesToPHP();
        $childTab = $tab . '  ';

        if (self::DEBUG) {
            $count = sizeof($php);
            $php[] = "$tab\$$typeName = \${$id}->makeChild('div', $count );\n";
        }

        if ($id !== '') {
            $php[] = $attributes !== '' ? "$tab\$$typeName = \${$id}->addChild(new Tag('$typeName', $attributes));\n" : "$tab\$$typeName = \${$id}->addChild(new Tag('$typeName'));\n";
        } else {
            $php[] = "\$html = Tag::make('html');\n";
        }



        foreach ($this->children as $child) {
            if (is_string($child)) {
                if ($child !== '') {
                    $string = var_export($child, true);
                    $lineNumber = sizeof($php);
                    $php[] = "$tab\${$typeName}->addText(" . $string . ");\n";
                }
            } else {
                $child->_toPHP($php, $typeName, $childTab);
            }
        }
    }

    public function toPHP(): array {
        $php = [];
        $this->_toPHP($php, '', '');
        $php[] = "\$html->render();\n";
        return $php;
    }
}
