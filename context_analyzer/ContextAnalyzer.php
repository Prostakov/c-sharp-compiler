<?php

class ContextAnalyzer {
    private $tree;

    public $currentBlockNode = null;
    public $lastBlockID = 0;

    public function __construct($tree) {
        $this->tree = $tree;
    }
}