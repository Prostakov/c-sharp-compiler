<?php

class Node {
    public $tokenID;
    public $symbol;
    public $value;
    public $children = [];

    public function __construct($symbol, $value, $tokenID = null) {
        $this->symbol = $symbol;
        $this->value = $value;
        $this->tokenID = $tokenID;
    }
}

class SyntaxTree {
    public $topTreeLevel = [];
    public $rules = [];
    public $iteration = 0;
    public $treeArray = [];

    public function __construct($rules) {
        $this->rules = $rules;
    }

    public function giveToken($tokenID, $token) {
        $this->topTreeLevel[] = new Node($token->type, $token->text, $tokenID);
        $this->wrapTree();
//        $this->logTopLevel();
    }

    private function wrapTree() {
        $wrapMore = false;
        for($i=count($this->topTreeLevel);$i>=1;$i--) {
//        for($i=1;$i<=count($this->topTreeLevel);$i++) {
            if ($this->wrapNodes(array_slice($this->topTreeLevel, -$i), $i)) {
                $wrapMore = true;
                break;
            }
        }
        if ($wrapMore) return $this->wrapTree();
        return true;
    }

    private function wrapNodes($nodes = [], $i) {
//        $this->logNodes($nodes);
        $nodesRule = [];
        foreach($nodes as $node) {
            $nodesRule[] = $node->symbol;
        }
        foreach($this->rules as $production) {
            if ($production['rule'] == $nodesRule) {
                $this->topTreeLevel = array_slice($this->topTreeLevel, 0, count($this->topTreeLevel)-$i);
                $wrapNode = new Node($production['non-terminal'], '');
                foreach($nodes as $node) $wrapNode->children[] = $node;
                $this->topTreeLevel[] = $wrapNode;
                return true;
            }
        }
        return false;
    }

    public function printNode($node, $stringOffset = 0){
        $stringOffset += 2;
        echo str_repeat(' ', $stringOffset) . $node->symbol . '   ' . $this->iteration . PHP_EOL;
        $this->iteration++;
        if (!empty($node->value)) echo str_repeat(' ', $stringOffset+2) . $node->value.PHP_EOL;
        foreach ($node->children as $childNode) $this->printNode($childNode, $stringOffset);
    }

    public function printTree(){
        $this->iteration = 0;
        if ($this->topTreeLevel[0]->symbol != 's'){
            echo "Error parsing tree!".PHP_EOL;
            foreach($this->topTreeLevel as $node) $this->printNode($node);
            return;
        }
        $this->printNode($this->topTreeLevel[0]);
    }

    private function populateTreeArray($node) {
        $item = [];
        $item['symbol'] = $node->symbol;
        $item['value'] = $node->value;
        $item['tokenID'] = $node->tokenID;
        $this->treeArray[] = $item;
        foreach($node->children as $childNode) $this->populateTreeArray($childNode);
    }

    public function getTreeAsArray() {
        if ($this->topTreeLevel[0]->symbol != 's'){
            echo "Error parsing tree!".PHP_EOL;
            return [];
        }
        $this->populateTreeArray($this->topTreeLevel[0]);
        return $this->treeArray;
    }

    private function logNodes($nodes = []) {
        echo '---------------------'.PHP_EOL;
        echo 'Wrap iteration #'.$this->iteration.':'.PHP_EOL;
        foreach ($nodes as $node) echo $node->symbol.PHP_EOL;
        $this->iteration++;
    }

    private function logTopLevel() {
        echo '---------------------'.PHP_EOL;
        echo 'Wrap iteration #'.$this->iteration.':'.PHP_EOL;
        foreach ($this->topTreeLevel as $item) echo $item->symbol.PHP_EOL;
        $this->iteration++;
    }
}