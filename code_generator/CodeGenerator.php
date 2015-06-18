<?php

class Tetrad {
    public $operation;
    public $arg1;
    public $arg2;
    public $result;

    function __construct($operation, $arg1, $arg2 = null, $result = null) {
        $this->operation = $operation;
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
        $this->result = $result;
    }

    public function __toString() {
        return '( '.$this->operation.', '.$this->arg1.', '.$this->arg2.', '.$this->result . ' )';
    }
}

class CodeGenerator {
    public $tree;
    public $variables;
    public $tetrads = [];

    public $lastTempVarID = 0;

    public $blocks = [];
    private $currentBlockID = null;
    private $lastBlockID = 0;

    private function getNewVarName() {
        $this->lastTempVarID += 1;
        return 'temp_variable_'.$this->lastTempVarID;
    }

    public function printTetrads() {
        foreach($this->tetrads as $tetrad) echo $tetrad.PHP_EOL;
    }

    public function __construct($tree = null, $variables = null) {
        if (is_null($tree)) throw new Exception('Tree is no tree to analyze!');
        if (is_null($variables)) throw new Exception('Tree is no variable declaration array!');
        $this->tree = $tree;
        $this->variables = $variables;
    }

    public function traverse() {
        if ($this->tree[0]->symbol !== 's')
            throw new Exception('Error making context analyze!');
        $this->blocks[0] = [];
        $this->currentBlockID = 0;
        $this->lastBlockID = 0;
        $this->traverseNode($this->tree[0]);
    }

    private function traverseNode($node){
        $this->log('Traversing node: '.$node->symbol . '   ---   '.$node->value);
        if ($node->symbol === 'namespace_block' || $node->symbol === 'block') {
            $this->lastBlockID += 1;
            $this->blocks[$this->lastBlockID] = array_merge([$this->currentBlockID], $this->blocks[$this->currentBlockID]);
            $this->currentBlockID = $this->lastBlockID;
            foreach ($node->children as $childNode) $this->traverseNode($childNode);
        } elseif ($node->symbol === 'BLOCK_CLOSE') {
            $this->currentBlockID = $this->blocks[$this->currentBlockID][0];
            foreach ($node->children as $childNode) {
                $this->traverseNode($childNode);
            }
        } elseif ($node->symbol === 'using_directive') {
        } elseif ($node->symbol === 'statement') {
            foreach ($node->children as $childNode) $this->traverseStatement($childNode);
        } else {
            foreach ($node->children as $childNode) $this->traverseNode($childNode);
        }
    }

    private $statementStack = [];
    private function wrapStatement($statementStack){
    }
    private function traverseStatement($node){
        $this->log('Traversing statement: '.$node->symbol . '   ---   '.$node->value);
        if ($node->symbol === 'block') {
            $this->lastBlockID += 1;
            $this->blocks[$this->lastBlockID] = array_merge([$this->currentBlockID], $this->blocks[$this->currentBlockID]);
            $this->currentBlockID = $this->lastBlockID;
            foreach ($node->children as $childNode) $this->traverseNode($childNode);
        } elseif ($node->symbol === 'BLOCK_CLOSE') {
            $this->currentBlockID = $this->blocks[$this->currentBlockID][0];
            foreach ($node->children as $childNode) $this->traverseNode($childNode);
        } elseif ($node->symbol === 'variable') {
        } elseif ($node->symbol === 'expression') {
            foreach ($node->children as $childNode) $this->traverseExpression($childNode);
            $this->statementStack[] = $this->wrapExpression($this->expressionStack);
            $this->expressionStack = [];
//        } elseif ($node->symbol === 'IDENTIFIER') {
//        } elseif ($node->symbol === 'method') {
//        } elseif ($node->symbol === 'method_application') {
        } else {
            foreach ($node->children as $childNode) $this->traverseStatement($childNode);
        }
    }

    private $expressionStack = [];
    private function wrapExpression($expressionStack) {
        $this->log('wrapping expression...');
        if (count($expressionStack) === 1) {
            if ($expressionStack[0]->symbol === 'INT_VARIABLE' || $expressionStack[0]->symbol === 'FLOAT_VARIABLE')
                return $expressionStack[0];
        } elseif (count($expressionStack) === 3) {
            $temp = $this->getNewVarName();
            $this->tetrads[] = new Tetrad($expressionStack[1]->value, $expressionStack[0]->value, $expressionStack[2]->value, $temp);
            return $temp;
        } else {
            $tempRecursiveResult = $this->wrapExpression(array_slice($expressionStack,2));
            $temp = $this->getNewVarName();
            $this->tetrads[] = new Tetrad($expressionStack[1]->value, $expressionStack[0]->value, $tempRecursiveResult, $temp);
            return $temp;
        }
    }
    private function traverseExpression($node) {
        $this->log('traversing expression...');
        if ($node->symbol === 'expression') {
            foreach ($node->children as $childNode) $this->traverseExpression($childNode);
        } elseif ($node->symbol === 'IDENTIFIER' || $node->symbol === 'INT_VARIABLE' || $node->symbol === 'FLOAT_VARIABLE') {
            $this->expressionStack[] = $node;
        } elseif ($node->symbol === 'arithmetic') {
            $this->expressionStack[] = $node->children[0];
        } elseif ($node->symbol === 'inc_operator') {
            if ($node->children[0]->symbol === 'ARITHMETIC_OPERATOR_INCREMENT'){
                $this->expressionStack[] = new Node('ARITHMETIC_OPERATOR_ADD', '+');
            } elseif ($node->children[0]->symbol === 'ARITHMETIC_OPERATOR_DECREMENT') {
                $this->expressionStack[] = new Node('ARITHMETIC_OPERATOR_SUBTRACT', '-');
            }
            $this->expressionStack[] = new Node('INT_VARIABLE', '1');
        }
    }

    private $logLine = 0;
    private function log($msg) {
        $this->logLine += 1;
//        echo $this->logLine.'. '.$msg.PHP_EOL;
    }
}