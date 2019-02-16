<?php

/* Tic Tac Tie Game
* AI Algorithm:
* - Check for winning move
* - Check for player possible winning next turn
* - Take center pos if it is empty
* - Take empty corner (random empty corner)
* - Take empty edge (random empty edge)
*/

class Tictactoe {

    // declare Player and AI side.
    public $humanPlayer = 'X';
    public $AIPlayer = 'O';

    // Indicator if the game is starting new game
    public $startIndicator = false;

    // game board Array it should store
    // something like this: [ "", "X", "", "", "O", "", "", "X", "" ]
    // X character for Player, O character for AI,
    //  empty string for available space
    public $board = [];

    // Game current status
    public $status = [
        'gameOver' => false,
        'winner' => NULL,
        'tie' => false
    ];

    // Function for inserting New Moves to Board
    // $player: String of playing character (AI / Player)
    // $position: space position between 0 to 8.
    protected function newMoves($player, $position){
        $this->board[$position] = $player;
    }

    // Function for detecting empty space for AI to used.
    protected function possibleMoves(){
        $empty = array_filter($this->board, function($space){
            return $space == '';
        });

        return array_keys($empty);
    }

    // Shuffle move list and return shuffled one
    // $moves: array of available move option
    protected function shuffleMove($moves){
        $toShuffle = $moves;
        shuffle($toShuffle);
        return $toShuffle[0];
    }

    // check for winning condition
    // $box: array of board game
    // $player: String of playing character (AI / Player)
    protected function isWinner($box, $player){
        return 
            // bottom row
            ( $box[6] == $player && $box[7] == $player && $box[8] == $player ) || 
            // middle row
            ( $box[3] == $player && $box[4] == $player && $box[5] == $player ) ||
            // upper row
            ( $box[0] == $player && $box[1] == $player && $box[2] == $player ) ||
            // first column
            ( $box[0] == $player && $box[3] == $player && $box[6] == $player ) ||
            // second column
            ( $box[1] == $player && $box[4] == $player && $box[7] == $player ) ||
            // third column
            ( $box[2] == $player && $box[5] == $player && $box[8] == $player ) ||
            // diagonal 1
            ( $box[0] == $player && $box[4] == $player && $box[8] == $player ) ||
            // diagonal 2
            ( $box[2] == $player && $box[4] == $player && $box[6] == $player );
    }

    // calculate AI move using mentioned algorithm above.
    protected function AIMoves(){
        // get possible move.
        $possibleMoves = $this->possibleMoves();
        $move = 0;

        // Detect AI winning move first. if there is one, then take it.
        // then Detect Player possible winning next turn. if there is one, then block it.
        foreach (['O', 'X'] as $tester) {
            foreach ($possibleMoves as $moveIndex) {
                $tryBoard = $this->board;
                $tryBoard[$moveIndex] = $tester;
                if($this->isWinner($tryBoard, $tester)){
                    $move = $moveIndex;
                    return $move;
                }
            }
        }

        // Detect Center position, if empty take it.
        if(in_array('4', $possibleMoves)){
            $move = '4';
            return $move;
        }

        // Detect Open Corner space.
        $OpenCorner = array_values(
            array_filter($possibleMoves, function($moves){
                return in_array(intval($moves), [0, 2, 6, 8]);
            })
        );

        // if there is an Open Corner, take it (random Empty Corner)
        if(!empty($OpenCorner)){
            $move = $this->shuffleMove($OpenCorner);
            return $move;
        }

        // Detect Open Edge space.
        $OpenEdge = array_values(
            array_filter($possibleMoves, function($moves){
                return in_array(intval($moves), [1, 3, 5, 7]);
            })
        );

        // if there is an Open Edge, take it (random Empty Edge)
        if(!empty($OpenEdge)){
            $move = $this->shuffleMove($OpenCorner);
            return $move;
        }

        return false;
    }

    // Constructor, get board array (filled / empty)
    public function __construct($game = []){
        if(!empty($game)){
            $this->board = $game;
        }else{
            $this->startIndicator = true;
            $this->board = [
                '', '', '', 
                '', '', '', 
                '', '', ''
            ];
        }
    }

    // Scan given board array
    public function scanGame(){
        if($this->startIndicator){
            return $this;
        }

        if($this->isWinner($this->board, $this->humanPlayer)){
            $this->status['gameOver'] = true;
            $this->status['winner'] = 'Player';
        }else{
            if(empty($this->possibleMoves())){
                $this->status['gameOver'] = true;
                $this->status['tie'] = true;
            }else{
                $nextMove = $this->AIMoves();
                if($nextMove){
                    $this->newMoves($this->AIPlayer, $nextMove);
                    if($this->isWinner($this->board, $this->AIPlayer)){
                        $this->status['gameOver'] = true;
                        $this->status['winner'] = 'AI';
                    }
                }
            }
        }

        return $this;
    }

    // return a box based on given $position (string [0-8])
    public function getBox($position){
        if($this->board[$position] == 'X'){
            return '<div class="box is-x"><span>x</span><input type="hidden" name="box[]" value="X"></div>';
        }else if($this->board[$position] == 'O'){
            return '<div class="box is-o"><span>o</span><input type="hidden" name="box[]" value="O"></div>';
        }else{
            return '<div class="box is-open" data-target="box-'.$position.'"><input id="box-'.$position.'" type="hidden" name="box[]"></div>';
        }
    }
}
