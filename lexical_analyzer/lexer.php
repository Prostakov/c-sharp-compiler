<?php

abstract class Lexer {

    const EOF       = -1; // represent end of file char
    const EOF_TYPE  = "<EOF>";  // represent EOF token type
    protected $input;     // input string
    protected $p = 0;     // index into input of current character
    protected $c;         // current character

    // position of cursor
    protected $row = 1;
    protected $col = 1;

    protected $length = 0;

    public function Lexer($input) {
        $this->input = $input;
        // prime lookahead
        $this->c = substr($input, $this->p, 1);
    }

    /** Move one character; detect "end of file" */
    public function consume() {
        $this->p++;
        $this->col++;
        if ($this->p >= strlen($this->input)) {
            $this->c = Lexer::EOF;
        }
        else {
            $this->c = substr($this->input, $this->p, 1);
        }
    }

    public abstract function nextToken();
}

?>
