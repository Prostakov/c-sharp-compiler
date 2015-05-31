<?php

/* brackets ( )
* program blocks { }
* arithmetic operators: "+" "-" "*" "/", "|", "&", "++", "--";
* assignment operators: "="
* compare operators: "==" "!=" "<" ">" "<=" ">="
* logical operators: "||" "!" "&&"
* identifier (variable)
* variable types: bool, float, string
* constructions: "if" "if-else" "for"
* delimiters: ";",",",":","."
* keywords: "abstract", "class", "const", "int", "interface", "namespace", "null", "private", "protected",
    "public", "return", "static", "throw", "using", "void"
*/

require_once('lexical_analyzer/ListLexer.php');
require_once('syntax_analyzer/SyntaxAnalyzer.php');
require_once('Token.php');

$fileName = 'code_samples/file3.cs';
//$fileName = $argv[1];
$input = file_get_contents('./'.$fileName, true);
echo '---------------------------------'.PHP_EOL;

// Get lexemes from input file into $lexTokens variable
$lexer = new ListLexer($input);
$lexTokens = [];
do {
    $token = $lexer->nextToken();
    $lexTokens[] = $token;
    echo $token . "\n";
} while ($token->type != Lexer::EOF_TYPE);
echo '---------------------------------'.PHP_EOL;

// Transform tokens and collect them into $tokens variable
Token::$count = 0;
$tokens = [];
foreach($lexTokens as $lexToken) {
    $token = Token::reformToken($lexToken);
    $tokens[] = $token;
    echo $token.PHP_EOL;
}






















