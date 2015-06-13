<?php

require_once('SyntaxTree.php');

class SyntaxAnalyzer {
    public $inputRules = [];
    public $tokens = [];
    public $rules = [];
    public $tree;

    public function __construct($inputRules = [], $inputTokens=[]) {
        $this->inputRules = $inputRules;
        $this->tokens = $inputTokens;
        $flippedRules = [];
        foreach ($this->inputRules as $nonTerminal=>$productions) {
            if (is_array($productions[0])) {
                foreach ($productions as $production) {
                    $flippedRules[] = [
                        'rule' => $production,
                        'non-terminal' => $nonTerminal,
                    ];
                }
            } else {
                $flippedRules[] = [
                    'rule' => $productions,
                    'non-terminal' => $nonTerminal,
                ];
            }
        }
        $this->rules = $flippedRules;
        $this->tree = new SyntaxTree($this->rules);
    }

    public function process() {
        foreach($this->tokens as $tokenID => $token) {
            $this->tree->giveToken($tokenID, $token);
        }
    }

    public function printResult() {
        $this->tree->printTree();
    }

    public function getTreeAsArray() {
        return $this->tree->getTreeAsArray();
    }

    public function printRules() {
        foreach($this->inputRules as $nonTerminal=>$productions) {
            echo $nonTerminal . ' => ';
            if (is_array($productions[0])) {
                echo PHP_EOL;
                foreach($productions as $production) {
                    echo '  ';
                    foreach($production as $value)
                        echo ' ' . $value;
                    echo ';'.PHP_EOL;
                }
            } else {
                foreach ($productions as $value)
                    echo ' ' . $value;
                echo ';'.PHP_EOL;
            }
        }
    }

    public function printFlippedRules() {
        print_r($this->rules);
    }
}