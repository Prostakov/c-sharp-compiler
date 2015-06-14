<?php

class ContextAnalyzer {
    private $tree;

    // Tree can be traversed in three modes: standard, variable, statement
    private $treeMode = 'standard';

    public $blocks = [];
    private $currentBlockID = null;
    private $lastBlockID = 0;

    public $variableDeclarationArray = [];
    private $lastDeclaredVarID = 0;
    public $variableUsageArray = [];

    public $logLine = 0;

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
        $this->log('traversing node: '.$node->symbol);
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

    private function traverseStatement($node){
        $this->log('traversing statement: '.$node->symbol);
        if ($node->symbol === 'block') {
            $this->lastBlockID += 1;
            $this->blocks[$this->lastBlockID] = array_merge([$this->currentBlockID], $this->blocks[$this->currentBlockID]);
            $this->currentBlockID = $this->lastBlockID;
            foreach ($node->children as $childNode) $this->traverseNode($childNode);
        } elseif ($node->symbol === 'BLOCK_CLOSE') {
            $this->currentBlockID = $this->blocks[$this->currentBlockID][0];
            foreach ($node->children as $childNode) $this->traverseNode($childNode);
        } elseif ($node->symbol === 'variable') {
            // traversing variable subtree from right to left
            for ($i = count($node->children) - 1; $i > -1; $i--) {
                $this->traverseVariableDeclaration($node->children[$i]);
            }
        } elseif ($node->symbol === 'expression') {
            foreach ($node->children as $childNode) $this->traverseStatement($childNode);
        } elseif ($node->symbol === 'IDENTIFIER') {
            // check declaration
            $variableID = $this->getVariable($node, $this->blocks[$this->currentBlockID]);
            if (isset($this->variableUsageArray[$this->currentBlockID][$variableID])) {
                $this->variableUsageArray[$this->currentBlockID][$variableID] += 1;
            } else {
                $this->variableUsageArray[$this->currentBlockID][$variableID] = 1;
            }
        } elseif ($node->symbol === 'method') {
            $this->traverseNode(end($node->children));
        } elseif ($node->symbol === 'method_application') {
        } else {
            foreach ($node->children as $childNode) $this->traverseStatement($childNode);
        }
    }

    private function traverseVariableDeclaration($node) {
        $this->log('traversing var declaration: '.$node->symbol);
        if ($node->symbol === 'IDENTIFIER') {
            if (isset($this->variableDeclarationArray[$this->currentBlockID]))
                foreach($this->variableDeclarationArray[$this->currentBlockID] as $varID => $value) {
                    if ($value === $node->value)
                        throw new Exception('Duplicate declaration at block #'.$this->currentBlockID);
                }
            $this->lastDeclaredVarID += 1;
            $this->variableDeclarationArray[$this->currentBlockID][$this->lastDeclaredVarID] = $node->value;
        } elseif ($node->symbol === 'expression') {
            $this->traverseStatement($node);
        } else {
            foreach ($node->children as $childNode) $this->traverseVariableDeclaration($childNode);
        }
    }

    private function getVariable($node, $blockIDs) {
        $blockIDs = array_merge([$this->currentBlockID],$blockIDs);
        foreach($blockIDs as $blockID) {
            if (isset($this->variableDeclarationArray[$blockID]))
                foreach($this->variableDeclarationArray[$blockID] as $varID => $value) {
                    if ($node->value === $value) return $varID;
                }
        }
        throw new Exception('Variable '.$node->value.' has not been declared! Line #'.$node->row);
    }

    private function log($msg) {
        $this->logLine += 1;
//        echo $this->logLine.'. '.$msg.PHP_EOL;
    }

    public function printInfoAboutVariables() {
        foreach($this->variableDeclarationArray as $blockID => $vars) {
            foreach ($vars as $varID => $varName) {
                echo 'VARIABLE_'.$varID.': '.$varName.', declared in BLOCK_'.$blockID.PHP_EOL;
                echo '  Used in:'.PHP_EOL;
                foreach($this->variableUsageArray as $usageBlockID => $usageVars) {
                    if (isset($usageVars[$varID]))
                        echo '    BLOCK_'.$usageBlockID.': '.$usageVars[$varID].' time(s).'.PHP_EOL;
                }
            }
        }
    }
}


























