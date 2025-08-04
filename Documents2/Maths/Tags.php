<?php






class tig{
    var $id;
    var $intra;
    
    function __construct($id, $intra = []) {
        $this->id = $id;
        $this->$intra = $intra;
    }
    
    
    function expandStyles($styles){
        $result = '';
        foreach ($array as $key => $value) {
            $result .= ' ' . $key . '"' . $value . '"';
        }
    }
    
   function expandIntraTagStuff($att){
       $result = '';
       foreach ($array as $key => $value) {
           $result .= ' ' . $key . ' = "';
           if (is_array($value)){
               $result .= $this->expandAttributes($att)
           }
       }
   }
    
    function toString(){
        $result = "<$this->id";
        foreach ($array as $key => $value) {
            
        }
        $result .= ">";
    }
}


class tag extends tig{
    var $out = [];
}