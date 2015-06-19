<?php

$rules = [
    's' => ['program'],
    'program' => [
        ['using_directives','namespaces'],
        ['namespaces'],
    ],
    'using_directives' => [
        ['using_directives', 'using_directive'],
        ['using_directive', 'using_directive'],
    ],
    'using_directive' => [
        ['KEYWORD_USING', 'statement'],
    ],
    'method_application' => [
        ['method_application', 'DELIMITER_DOT', 'IDENTIFIER', 'operand'],
        ['method_application', 'DELIMITER_DOT', 'IDENTIFIER'],
        ['IDENTIFIER', 'DELIMITER_DOT', 'IDENTIFIER'],
    ],
    'namespaces' => [
        ['namespaces', 'namespace'],
        ['namespace'],
    ],
    'namespace' => ['KEYWORD_NAMESPACE', 'IDENTIFIER', 'namespace_block'],
    'namespace_block' => [
        ['BLOCK_OPEN','classes','BLOCK_CLOSE'],
    ],
    'classes' => [
        ['classes', 'class'],
        ['class'],
    ],
    'class' => ['KEYWORD_CLASS', 'IDENTIFIER', 'operand', 'block'],
    'method' => [
        ['KEYWORD_PUBLIC', 'KEYWORD_VOID', 'IDENTIFIER', 'operand', 'block'],
        ['KEYWORD_PUBLIC', 'KEYWORD_INT', 'IDENTIFIER', 'operand', 'block'],
        ['KEYWORD_PUBLIC', 'KEYWORD_FLOAT', 'IDENTIFIER', 'operand', 'block'],
        ['KEYWORD_PUBLIC', 'KEYWORD_STRING', 'IDENTIFIER', 'operand', 'block'],
        ['KEYWORD_PRIVATE', 'KEYWORD_VOID', 'IDENTIFIER', 'operand', 'block'],
        ['KEYWORD_PRIVATE', 'KEYWORD_INT', 'IDENTIFIER', 'operand', 'block'],
        ['KEYWORD_PRIVATE', 'KEYWORD_FLOAT', 'IDENTIFIER', 'operand', 'block'],
        ['KEYWORD_PRIVATE', 'KEYWORD_STRING', 'IDENTIFIER', 'operand', 'block'],
        ['KEYWORD_PROTECTED', 'KEYWORD_VOID', 'IDENTIFIER', 'operand', 'block'],
        ['KEYWORD_PROTECTED', 'KEYWORD_INT', 'IDENTIFIER', 'operand', 'block'],
        ['KEYWORD_PROTECTED', 'KEYWORD_FLOAT', 'IDENTIFIER', 'operand', 'block'],
        ['KEYWORD_PROTECTED', 'KEYWORD_STRING', 'IDENTIFIER', 'operand', 'block'],
        ['KEYWORD_STATIC', 'KEYWORD_VOID', 'IDENTIFIER', 'operand', 'block'],
        ['KEYWORD_STATIC', 'KEYWORD_INT', 'IDENTIFIER', 'operand', 'block'],
        ['KEYWORD_STATIC', 'KEYWORD_FLOAT', 'IDENTIFIER', 'operand', 'block'],
        ['KEYWORD_STATIC', 'KEYWORD_STRING', 'IDENTIFIER', 'operand', 'block'],
    ],
    'operand' => [
        ['BRACKET_SIMPLE_LEFT', 'BRACKET_SIMPLE_RIGHT'],
        ['BRACKET_SIMPLE_LEFT', 'IDENTIFIER', 'BRACKET_SIMPLE_RIGHT'],
        ['BRACKET_SIMPLE_LEFT', 'expression', 'BRACKET_SIMPLE_RIGHT'],
        ['BRACKET_SIMPLE_LEFT', 'STRING_VARIABLE', 'BRACKET_SIMPLE_RIGHT'],
        ['BRACKET_SIMPLE_LEFT', 'method_application', 'BRACKET_SIMPLE_RIGHT'],
    ],
    'block' => [
        ['BLOCK_OPEN','statements','BLOCK_CLOSE'],
        ['BLOCK_OPEN','statement','BLOCK_CLOSE'],
    ],
    'statements' => [
        ['statements', 'statement'],
        ['statement', 'statement'],
    ],
    'statement' => [
        ['variable'],
        ['method'],
        ['IDENTIFIER','ASSIGNMENT_OPERATOR','expression','DELIMITER_DOTCOMA'],
//        ['method_application', 'operand', 'DELIMITER_DOTCOMA'],
//        ['IDENTIFIER','ASSIGNMENT_OPERATOR', 'method_application', 'BRACKET_SIMPLE_LEFT', 'method_application', 'operand', 'BRACKET_SIMPLE_RIGHT', 'DELIMITER_DOTCOMA'],
        ['CONSTRUCTION_FOR', 'BRACKET_SIMPLE_LEFT', 'for_operands', 'BRACKET_SIMPLE_RIGHT', 'block'],
    ],
    'for_operands' => [
        ['statement', 'comparison', 'expression'],
    ],
    'comparison' => [
        ['IDENTIFIER', 'compare_operator', 'IDENTIFIER', 'DELIMITER_DOTCOMA'],
        ['IDENTIFIER', 'compare_operator', 'expression', 'DELIMITER_DOTCOMA'],
        ['expression', 'compare_operator', 'IDENTIFIER', 'DELIMITER_DOTCOMA'],
    ],
    'compare_operator' => [
        ['COMPARE_OPERATOR_EQUAL'],
        ['COMPARE_OPERATOR_GREATER_EQUAL'],
        ['COMPARE_OPERATOR_LOWER_EQUAL'],
    ],
    'expression' => [
        ['expression', 'arithmetic', 'expression'],
        ['IDENTIFIER', 'arithmetic', 'IDENTIFIER'],
        ['IDENTIFIER', 'arithmetic', 'expression'],
        ['expression', 'arithmetic', 'IDENTIFIER'],
        ['IDENTIFIER', 'inc_operator'],
        ['INT_VARIABLE'],
        ['FLOAT_VARIABLE'],
    ],
    'arithmetic' => [
        ['ARITHMETIC_OPERATOR_ADD'],
        ['ARITHMETIC_OPERATOR_SUBTRACT'],
        ['ARITHMETIC_OPERATOR_MULTIPLY'],
        ['ARITHMETIC_OPERATOR_DIVIDE'],
    ],
    'inc_operator' => [
        ['ARITHMETIC_OPERATOR_INCREMENT'],
        ['ARITHMETIC_OPERATOR_DECREMENT'],
    ],
    'variable' => [
        ['KEYWORD_INT', 'IDENTIFIER', 'DELIMITER_DOTCOMA'],
        ['KEYWORD_INT', 'IDENTIFIER', 'ASSIGNMENT_OPERATOR', 'expression', 'DELIMITER_DOTCOMA'],
        ['KEYWORD_FLOAT', 'IDENTIFIER', 'ASSIGNMENT_OPERATOR', 'expression', 'DELIMITER_DOTCOMA'],
        ['KEYWORD_STRING', 'IDENTIFIER', 'ASSIGNMENT_OPERATOR', 'STRING_VARIABLE', 'DELIMITER_DOTCOMA'],
        ['KEYWORD_INT', 'identifiers', 'DELIMITER_DOTCOMA'],
    ],
    'identifiers' => [
        ['identifiers', 'DELIMITER_COMA', 'IDENTIFIER'],
        ['IDENTIFIER', 'DELIMITER_COMA', 'IDENTIFIER'],
    ],
];

?>