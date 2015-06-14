<?php

class ContextAnalyzer {
    private $tree;

    // Tree can be traversed in three modes: standard, variable, statement
    private $treeMode = 'standard';

    public $blocks = [];
    private $currentBlockID = null;
    private $lastBlockID = 0;

    private $variableDeclaration = [];
    private $lastDeclaredVarID = null;
    private $variableUsage = [];

    public function __construct($tree = null) {
        if (is_null($tree)) throw new Exception('Tree is no tree to analyze!');
        $this->tree = $tree;
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
        if ($node->symbol === 'namespace_block' || $node->symbol === 'block') {
            $this->lastBlockID += 1;
            $this->blocks[$this->lastBlockID] = array_merge([$this->currentBlockID], $this->blocks[$this->currentBlockID]);
            $this->currentBlockID = $this->lastBlockID;
        } elseif ($node->symbol === 'BLOCK_CLOSE') {
            $this->currentBlockID = $this->blocks[$this->currentBlockID][0];
        }
        foreach ($node->children as $childNode) $this->traverseNode($childNode);
    }

    private function traverseStatement(){

    }

    private function traverseVariableDeclaration() {

    }
}


























