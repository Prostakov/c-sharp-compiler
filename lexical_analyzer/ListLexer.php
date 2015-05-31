<?php

require_once('lexer.php');

class ListLexer extends Lexer {
    const BRACKET               = 2;
    const BLOCK                 = 3;
    const ARITHMETIC_OPERATOR   = 4;
    const ASSIGNMENT_OPERATOR   = 5;
    const COMPARE_OPERATOR      = 6;
    const LOGICAL_OPERATOR      = 7;
    const IDENTIFIER            = 8;

    // Variable types:
    const STRING_VARIABLE       = 9;
    const BOOLEAN_VARIABLE      = 10;
    const FLOAT_VARIABLE        = 11;
    const INT_VARIABLE          = 12;

    const CONSTRUCTION          = 13;
    const DELIMITER             = 14;
    const KEYWORD               = 15;
    const COMMENT               = 16;

    static $keywords = array("class", "const", "int", "namespace", "array",
        "null", "private", "protected", "public", "return", "static", "throw", "using", "void", "case");
    static $constructions = array("if", "else", "for", "switch");
    static $booleanVariables = array("true", "false");

    static $tokenNames = array("n/a", "<EOF>",
                                "BRACKET", "BLOCK",
                                "ARITHMETIC_OPERATOR", "ASSIGNMENT_OPERATOR",
                                "COMPARE_OPERATOR", "LOGICAL_OPERATOR",
                                "IDENTIFIER", "STRING_VARIABLE",
                                "BOOLEAN_VARIABLE", "FLOAT_VARIABLE", "INT_VARIABLE",
                                "CONSTRUCTION", "DELIMITER", "KEYWORD", "COMMENT");

    public function ListLexer($input) {
        parent::__construct($input);
    }

    public function nextToken() {
        while ( $this->c != self::EOF ) {
            switch ( $this->c ) {
                // End of line
                case PHP_EOL :  $this->consume();
                                $this->row++;
                                $this->col = 1;
                                continue;
                // Whitespaces
                case ' ' :  case '\t': case '\n': case '\r': $this->WS();
                            continue;
                // Arithmetic operators:
                case '+' :  $this->consume();
                            if ($this->c == '+') {
                                $this->consume();
                                return new Token(self::$tokenNames[self::ARITHMETIC_OPERATOR], "++", $this->row, $this->col-2, 2);
                            }
                            else return new Token(self::$tokenNames[self::ARITHMETIC_OPERATOR], "+", $this->row, $this->col-1, 1);
                case '-' :  $this->consume();
                            if ($this->c == '-') {
                                $this->consume();
                                return new Token(self::$tokenNames[self::ARITHMETIC_OPERATOR], "--", $this->row, $this->col-2, 2);
                            }
                            else return new Token(self::$tokenNames[self::ARITHMETIC_OPERATOR], "-", $this->row, $this->col-1, 1);
                case '*' :  $this->consume();
                            return new Token(self::$tokenNames[self::ARITHMETIC_OPERATOR], "*", $this->row, $this->col-1, 1);
                case '/' :  $this->consume();
                            if ($this->c == '*') return $this->COMMENT();
                            else return new Token(self::$tokenNames[self::ARITHMETIC_OPERATOR], "/", $this->row, $this->col-1, 1);
                case '|' :  $this->consume();
                            if ($this->c == '|') {
                                $this->consume();
                                return new Token(self::$tokenNames[self::LOGICAL_OPERATOR], "||", $this->row, $this->col-2, 2);
                            }
                            else return new Token(self::$tokenNames[self::ARITHMETIC_OPERATOR], "|", $this->row, $this->col-1, 1);
                case '&' :  $this->consume();
                            if ($this->c == '&') {
                                $this->consume();
                                return new Token(self::$tokenNames[self::LOGICAL_OPERATOR], "&&", $this->row, $this->col-2, 2);
                            }
                            else return new Token(self::$tokenNames[self::ARITHMETIC_OPERATOR], "&", $this->row, $this->col-1, 1);
                // Assignment operators:
                case '=' :  $this->consume();
                            if ($this->c == '=') {
                                $this->consume();
                                return new Token(self::$tokenNames[self::COMPARE_OPERATOR], "==", $this->row, $this->col-2, 2);
                            }
                            else return new Token(self::$tokenNames[self::ASSIGNMENT_OPERATOR], "=", $this->row, $this->col-1, 1);
                // Compare operators:
                case '!' :  $this->consume();
                            if ($this->c == '=') {
                                $this->consume();
                                return new Token(self::$tokenNames[self::COMPARE_OPERATOR], "!=", $this->row, $this->col-2, 2);
                            }
                            else return new Token(self::$tokenNames[self::LOGICAL_OPERATOR], "!", $this->row, $this->col-1, 1);
                case '<' :  $this->consume();
                            if ($this->c == '=') {
                                $this->consume();
                                return new Token(self::$tokenNames[self::COMPARE_OPERATOR], "<=", $this->row, $this->col-2, 2);
                            }
                            else return new Token(self::$tokenNames[self::COMPARE_OPERATOR], "<", $this->row, $this->col-1, 1);
                case '>' :  $this->consume();
                            if ($this->c == '=') {
                                $this->consume();
                                return new Token(self::$tokenNames[self::COMPARE_OPERATOR], ">=", $this->row, $this->col-2, 2);
                            }
                            else return new Token(self::$tokenNames[self::COMPARE_OPERATOR], ">", $this->row, $this->col-1, 1);
                // Delimiters:
                case ',' :  $this->consume();
                            return new Token(self::$tokenNames[self::DELIMITER], ",", $this->row, $this->col-1, 1);
                case ';' :  $this->consume();
                            return new Token(self::$tokenNames[self::DELIMITER], ";", $this->row, $this->col-1, 1);
                case '.' :  $this->consume();
                            return new Token(self::$tokenNames[self::DELIMITER], ".", $this->row, $this->col-1, 1);
                case ':' :  $this->consume();
                            return new Token(self::$tokenNames[self::DELIMITER], ":", $this->row, $this->col-1, 1);
                // Brackets:
                case '[' :  $this->consume();
                            return new Token(self::$tokenNames[self::BRACKET], "[", $this->row, $this->col-1, 1);
                case ']' :  $this->consume();
                            return new Token(self::$tokenNames[self::BRACKET], "]", $this->row, $this->col-1, 1);
                case '(' :  $this->consume();
                            return new Token(self::$tokenNames[self::BRACKET], "(", $this->row, $this->col-1, 1);
                case ')' :  $this->consume();
                            return new Token(self::$tokenNames[self::BRACKET], ")", $this->row, $this->col-1, 1);
                // Blocks:
                case '{' :  $this->consume();
                            return new Token(self::$tokenNames[self::BLOCK], "{", $this->row, $this->col-1, 1);
                case '}' :  $this->consume();
                            return new Token(self::$tokenNames[self::BLOCK], "}", $this->row, $this->col-1, 1);
                case '"' :  $this->consume();
                            return $this->STRING();
                default:
                    if ($this->isDIGIT() ) return $this->NUMBER();
                    if ($this->isLETTER() ) return $this->NAME();
                    throw new Exception("invalid character: " . $this->c);
            }
        }
        return new Token(self::EOF_TYPE,"<EOF>");
    }


    public function isLETTER() {
        return $this->c >= 'a' &&
        $this->c <= 'z' ||
        $this->c >= 'A' &&
        $this->c <= 'Z';
    }

    /** NAME : ('a'..'z'|'A'..'Z')+; // NAME is sequence of >=1 letter */
    public function NAME() {
        $buf = '';
        do {
            $buf .= $this->c;
            $this->consume();
        } while ($this->isLETTER() || $this->c == "_");
        if (in_array($buf, ListLexer::$keywords))
            return new Token(self::$tokenNames[self::KEYWORD], $buf, $this->row, $this->col - strlen($buf), strlen($buf));
        if (in_array($buf, ListLexer::$constructions))
            return new Token(self::$tokenNames[self::CONSTRUCTION], $buf, $this->row, $this->col - strlen($buf), strlen($buf));
        if (in_array($buf, ListLexer::$booleanVariables))
            return new Token(self::$tokenNames[self::BOOLEAN_VARIABLE], $buf, $this->row, $this->col - strlen($buf), strlen($buf));
        return new Token(self::$tokenNames[self::IDENTIFIER], $buf, $this->row, $this->col - strlen($buf), strlen($buf));
    }

    public function isDIGIT() {
        return $this->c >= '0' && $this->c <= '9';
    }

    public function NUMBER() {
        $buf = '';
        $dotCount = 0;
        $beforeDotCount = 0;
        $afterDotCount = 0;
        do {
            if ($this->c == '.')
                $dotCount++;
            else
                if ($dotCount == 0) $beforeDotCount++; else $afterDotCount++;
            if ($dotCount > 1) throw new Exception("Wrong number format! Too many dots!");
            $buf .= $this->c;
            $this->consume();

        } while ($this->isDIGIT() || $this->c == '.');
        // For int 8 digits is limit, for double - 16.16
        if (($dotCount == 0) && ($beforeDotCount > 8) || ($dotCount == 1) && (($beforeDotCount>16) || ($afterDotCount>16)))
            throw new Exception("Wrong number format! Too many digits! Count is ".$dotCount);
        if ($dotCount == 1)
            return new Token(self::$tokenNames[self::FLOAT_VARIABLE], $buf, $this->row, $this->col - strlen($buf), strlen($buf));
        elseif ($dotCount == 0)
            return new Token(self::$tokenNames[self::INT_VARIABLE], $buf, $this->row, $this->col - strlen($buf), strlen($buf));
        else
            throw new Exception("Something went wrong in parsing a number");
    }

    public function STRING() {
        $buf = '';
        do {
            $buf .= $this->c;
            $this->consume();
            if ($this->c == Lexer::EOF) throw new Exception("Double quotes are not closed!");
        } while ($this->c != '"');
        $this->consume();
        return new Token(self::$tokenNames[self::STRING_VARIABLE], $buf, $this->row, $this->col - strlen($buf) - 1, strlen($buf));
    }

    public function COMMENT() {
        $buf = '';
        $this->consume();
        do {
            if ($this->c == Lexer::EOF) throw new Exception("Comment is not closed!");
            $endFlag = false;
            $buf .= $this->c;
            if ($this->c == '*') $endFlag = true;
            $this->consume();
        } while ($this->c != '/' || !$endFlag);
        $this->consume();
        $buf = substr_replace($buf, "", -1);
        return new Token(self::$tokenNames[self::COMMENT], $buf, $this->row, $this->col - strlen($buf) - 2, strlen($buf));
    }

    /** WS : (' '|'\t'|'\n'|'\r')* ; // ignore any whitespace */
    public function WS() {
        while(ctype_space($this->c)) {
            $this->consume();
        }
    }
}

?>
