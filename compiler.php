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
* keywords: "class", "const", "int", "namespace", "null", "private", "protected",
    "public", "return", "static", "throw", "using", "void"
*/

require_once('lexical_analyzer/ListLexer.php');
require_once('syntax_analyzer/SyntaxAnalyzer.php');
require_once('Token.php');

$fileName = 'code_samples/file5.cs';
//$fileName = $argv[1];
$input = file_get_contents('./'.$fileName, true);
echo '---------------------------------'.PHP_EOL;

// Get lexemes from input file into $lexTokens variable
$lexer = new ListLexer($input);
$lexTokens = [];
do {
    $token = $lexer->nextToken();
    $lexTokens[] = $token;
} while ($token->type != Lexer::EOF_TYPE);
echo '---------------------------------'.PHP_EOL;

// Transform tokens and collect them into $tokens variable
Token::$count = 0;
$tokens = [];
foreach($lexTokens as $lexToken) {
    $token = Token::reformToken($lexToken);
    if ($token->type != 'COMMENT' && $token->type != '<EOF>') $tokens[] = $token;
}

$rules = [
    's' => ['program'],
    'program' => ['method'],
    'method' => ['KEYWORD_PUBLIC','KEYWORD_VOID','IDENTIFIER','BRACKET_SIMPLE_LEFT','BRACKET_SIMPLE_RIGHT', 'block'],
    'block' => ['BLOCK_OPEN','statements','BLOCK_CLOSE'],
    'statements' => [
        ['statements', 'statements'],
        ['statement'],
    ],
    'statement' => [
        ['KEYWORD_INT','IDENTIFIER','DELIMITER_DOTCOMA'],
        ['IDENTIFIER','ASSIGNMENT_OPERATOR','expression','DELIMITER_DOTCOMA'],
    ],
    'expression' => [
        ['expression', 'ARITHMETIC_OPERATOR_ADD', 'expression'],
        ['INT_VARIABLE'],
    ],
];

$rules = [
    's' => ['program'],
    'program' => [
        ['using_directives','namespaces'],
        ['namespaces'],
    ],
    'using_directives' => [
        ['using_directives', 'using_directives'],
        ['using_directive'],
    ],
    'using_directive' => [
        ['KEYWORD_USING', 'method_application', 'DELIMITER_DOTCOMA'],
        ['KEYWORD_USING', 'IDENTIFIER', 'DELIMITER_DOTCOMA'],
    ],
    'method_application' => [
        ['method_application', 'DELIMITER_DOT', 'IDENTIFIER'],
        ['IDENTIFIER', 'DELIMITER_DOT', 'IDENTIFIER'],
    ],
    'namespaces' => [
        ['namespaces', 'namespace'],
        ['namespace'],
    ],
    'namespace' => ['KEYWORD_NAMESPACE', 'IDENTIFIER', 'block'],
    'block' => [
        ['BLOCK_OPEN','classes','BLOCK_CLOSE'],
        ['BLOCK_OPEN','statements','BLOCK_CLOSE'],
    ],
    'classes' => [
        ['classes', 'class'],
        ['class'],
    ],
    'class' => ['KEYWORD_CLASS', 'IDENTIFIER', 'block'],
    'statements' => [
        ['statements', 'statements'],
        ['statement'],
    ],
    'statement' => [
        ['IDENTIFIER','ASSIGNMENT_OPERATOR','expression','DELIMITER_DOTCOMA'],
    ],
    'expression' => [
        ['expression', 'ARITHMETIC_OPERATOR_ADD', 'expression'],
        ['INT_VARIABLE'],
    ],
];
$syntaxAnalyzer = new SyntaxAnalyzer($rules, $tokens);
$syntaxAnalyzer->process();




















