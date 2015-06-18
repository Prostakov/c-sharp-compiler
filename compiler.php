<?php

require_once('Token.php');
require_once('lexical_analyzer/ListLexer.php');
require_once('syntax_analyzer/SyntaxAnalyzer.php');
require_once('syntax_analyzer/rules.php');
require_once('context_analyzer/ContextAnalyzer.php');


$fileName = 'code_samples/file2.cs';
//$fileName = $argv[1];
$input = file_get_contents('./'.$fileName, true);

// Get lexemes from input file into $lexTokens variable
$lexer = new ListLexer($input);
$lexTokens = [];
do {
    $token = $lexer->nextToken();
    $lexTokens[] = $token;
//    echo $token.PHP_EOL;
} while ($token->type != Lexer::EOF_TYPE);

// Transform tokens and collect them into $tokens variable
Token::$count = 0;

$tokens = [];
$iterator = 1;
foreach($lexTokens as $lexToken) {
    $token = Token::reformToken($lexToken);
    if ($token->type != 'COMMENT' && $token->type != '<EOF>') {
        $tokens[$iterator] = $token;
        $iterator++;
//        echo $token.PHP_EOL;
    }
}

$syntaxAnalyzer = new SyntaxAnalyzer($rules, $tokens);
$syntaxAnalyzer->process();
//$syntaxAnalyzer->printResult();

//return;

$contextAnalyzer = new ContextAnalyzer($syntaxAnalyzer->getTree());
$contextAnalyzer->traverse();

//print_r($contextAnalyzer->blocks);
//print_r($contextAnalyzer->variableDeclarationArray);
//print_r($contextAnalyzer->variableUsageArray);
//$contextAnalyzer->printInfoAboutVariables();

$codeGenerator = new CodeGenerator($syntaxAnalyzer->getTree());








