<?php

class Table {

    private Tag $table;

    function __construct(int $rows, int $cols, string $inner = '', array $attributes = []) {

        // make the DOM
        $this->table = Tag::make('table', $inner, $attributes);
        for ($r = 0; $r < $rows; $r++) {
            $row = $this->table->makeChild('tr');

            for ($c = 0; $c < $cols; $c++) {
                $row->makeChild('td', 'X');
            }
        }
    }
    
    
    function getTR(int $row){
        return $this->table->children()[$row];
    }

    function getTD(int $row, int $col) {
        return $this->table->children()[$row]->children()[$col];
    }

    function getTag(): Tag {
        return $this->table;
    }
}
