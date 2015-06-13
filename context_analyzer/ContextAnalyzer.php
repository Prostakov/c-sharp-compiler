<?php


class Block {
    public $ID;
    private $name;
    public $parentBlocksIDs = [];

    public function __construct($blockID, $parentBlocksIDs = []) {
        $this->ID = $blockID;
        $this->name = 'BLOCK_'.$blockID;
        $this->parentBlocksIDs = $parentBlocksIDs;
    }
}

class ContextAnalyzer {

    private $treeArray = [];
    private $variableDeclaration = [];
    private $lastDeclaredVarID = null;
    private $variableUsage = [];
    private $currentBlock = null;
    private $lastBlockID = null;
    public $blocks = [];
    private $resultLog = '';

    public function __construct($treeArray = []) {
        $this->treeArray = $treeArray;
    }

    public function process() {
        if ($this->treeArray[0]['symbol'] !== 's')
            throw new Exception('Tree is not properly parsed!');
        $block0 = new Block(0, []);
        $this->currentBlock = $block0;
        $this->lastBlockID = 0;
        $this->blocks[0] = $block0;
        $this->treeArray = array_slice($this->treeArray, 1);
        foreach($this->treeArray as $metaToken) {
            if ($metaToken['symbol'] === 'namespace_block' || $metaToken['symbol'] === 'block') {
                $this->lastBlockID += 1;
                $newBlock = new Block($this->lastBlockID, array_merge([$this->currentBlock->ID],$this->currentBlock->parentBlocksIDs));
                $this->currentBlock = $newBlock;
                $this->blocks[$this->lastBlockID] = $newBlock;
            } elseif ($metaToken['symbol'] === 'BLOCK_CLOSE') {
                $this->currentBlock = $this->blocks[$this->currentBlock->parentBlocksIDs[0]];
            }
        }
    }
}