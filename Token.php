<?php

class Token {
    public $type;
    public $text;
    public $id;
    public $row;
    public $col;
    public $length;
    static $count = 0;


    public function Token($type, $text, $row=0, $col=0, $length=0 ) {
        $this->type = $type;
        $this->text = $text;
        Token::$count++;
        $this->id = Token::$count;
        $this->row = $row;
        $this->col = $col;
        $this->length = $length;
    }
    
    public function __toString() {
        return "<" . $this->type . ", '" . $this->text .
          "', ROW:" . $this->row . ", COL:" . $this->col . ", LENGTH:" . $this->length . ">";
    }

    static function reformToken($inputToken)
    {
        switch ($inputToken->type) {
            case "n/a":
                return $inputToken;
                break;
            case "<EOF>":
                return $inputToken;
                break;
            case "BRACKET":
                if ($inputToken->text == "[") {
                    return new Token("BRACKET_SQUARE_LEFT", '[', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "]") {
                    return new Token("BRACKET_SQUARE_RIGHT", ']', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "(") {
                    return new Token("BRACKET_SIMPLE_LEFT", '(', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == ")") {
                    return new Token("BRACKET_SIMPLE_RIGHT", ')', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                break;
            case "BLOCK":
                if ($inputToken->text == "{") {
                    return new Token("BLOCK_OPEN", '{', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "}") {
                    return new Token("BLOCK_CLOSE", '}', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                break;
            case "ARITHMETIC_OPERATOR":
                if ($inputToken->text == "++") {
                    return new Token("ARITHMETIC_OPERATOR_INCREMENT", '++', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "--") {
                    return new Token("ARITHMETIC_OPERATOR_DECREMENT", '--', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "+") {
                    return new Token("ARITHMETIC_OPERATOR_ADD", '+', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "-") {
                    return new Token("ARITHMETIC_OPERATOR_SUBTRACT", '-', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "*") {
                    return new Token("ARITHMETIC_OPERATOR_MULTIPLY", '*', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "/") {
                    return new Token("ARITHMETIC_OPERATOR_DIVIDE", '/', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "|") {
                    return new Token("ARITHMETIC_OPERATOR_BINARY_DISJUNCTION", '|', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "&") {
                    return new Token("ARITHMETIC_OPERATOR_BINARY_CONJUCTION", '&', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                break;
            case "ASSIGNMENT_OPERATOR":
                return $inputToken;
                break;
            case "COMPARE_OPERATOR":
                if ($inputToken->text == "==") {
                    return new Token("COMPARE_OPERATOR_EQUAL", '==', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == ">=") {
                    return new Token("COMPARE_OPERATOR_GREATER_EQUAL", '>=', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "<=") {
                    return new Token("COMPARE_OPERATOR_LOWER_EQUAL", '<=', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "!=") {
                    return new Token("COMPARE_OPERATOR_NOT_EQUAL", '!=', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == ">") {
                    return new Token("COMPARE_OPERATOR_GREATER", '>', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "<") {
                    return new Token("COMPARE_OPERATOR_LOWER", '<', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                break;
            case "LOGICAL_OPERATOR":
                if ($inputToken->text == "!") {
                    return new Token("LOGICAL_OPERATOR_NOT", '!', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "||") {
                    return new Token("LOGICAL_OPERATOR_OR", '||', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "&&") {
                    return new Token("LOGICAL_OPERATOR_AND", '&&', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                break;
            case "IDENTIFIER":
                return $inputToken;
                break;
            case "STRING_VARIABLE":
                return $inputToken;
                break;
            case "BOOLEAN_VARIABLE":
                if ($inputToken->text == "true") {
                    return new Token("BOOLEAN_VARIABLE_TRUE", 'true', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "false") {
                    return new Token("BOOLEAN_VARIABLE_FALSE", 'false', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                break;
            case "FLOAT_VARIABLE":
                return $inputToken;
                break;
            case "INT_VARIABLE":
                return $inputToken;
                break;
            case "CONSTRUCTION":
                if ($inputToken->text == "if") {
                    return new Token("CONSTRUCTION_IF", 'if', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "else") {
                    return new Token("CONSTRUCTION_ELSE", 'else', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "for") {
                    return new Token("CONSTRUCTION_FOR", 'for', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "switch") {
                    return new Token("CONSTRUCTION_SWITCH", 'switch', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                break;
            case "DELIMITER":
                if ($inputToken->text == ",") {
                    return new Token("DELIMITER_COMA", ',', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == ".") {
                    return new Token("DELIMITER_DOT", '.', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == ";") {
                    return new Token("DELIMITER_DOTCOMA", ';', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == ":") {
                    return new Token("DELIMITER_DOUBLEDOTS", ':', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                break;
            case "KEYWORD":
                if ($inputToken->text == "class") {
                    return new Token("KEYWORD_CLASS", 'class', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "const") {
                    return new Token("KEYWORD_CONST", 'const', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "int") {
                    return new Token("KEYWORD_INT", 'int', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "namespace") {
                    return new Token("KEYWORD_NAMESPACE", 'namespace', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "array") {
                    return new Token("KEYWORD_ARRAY", 'array', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "null") {
                    return new Token("KEYWORD_NULL", 'null', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "private") {
                    return new Token("KEYWORD_PRIVATE", 'private', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "protected") {
                    return new Token("KEYWORD_PROTECTED", 'protected', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "public") {
                    return new Token("KEYWORD_PUBLIC", 'public', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "return") {
                    return new Token("KEYWORD_RETURN", 'return', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "static") {
                    return new Token("KEYWORD_STATIC", 'static', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "throw") {
                    return new Token("KEYWORD_THROW", 'throw', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "using") {
                    return new Token("KEYWORD_USING", 'using', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "void") {
                    return new Token("KEYWORD_VOID", 'void', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                if ($inputToken->text == "case") {
                    return new Token("KEYWORD_CASE", 'case', $inputToken->row, $inputToken->col,
                        $inputToken->length);
                }
                break;
            case "COMMENT":
                return $inputToken;
                break;
            default:
                throw new Exception("invalid token");
        }
    }
}

?>