<?php

require_once('SyntaxTree.php');

class SyntaxAnalyzer {
    public $inputRules = [];
    public $tokens = [];
    public $rules = [];

    public function __construct($inputRules = [], $inputTokens=[]) {
        $this->inputRules = $inputRules;
        $this->tokens = $inputTokens;
        $this->flipRules();
    }

    public function process() {
        $tree = new SyntaxTree($this->rules);
        foreach($this->tokens as $token) {
            $tree->giveToken($token);
        }
        foreach($tree->topTreeLevel as $item) {
//            echo $item->symbol.PHP_EOL;
        }
    }

    private function flipRules() {
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