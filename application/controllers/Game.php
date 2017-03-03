<?php


class Game extends CI_Controller {

    private static $startTitle = 'Start new game';
    const WIDTH = 4;
    const HEIGHT = 4;
    const COUNT_WIN = 3;
    private $field = [];
    private $winnerCells = [];
    //private $currentPlayer;
    private $currentPlayer = 1;
    private $winner = null;
    private $step = 0;

    public function __construct() {
        
        parent::__construct();
        $this->load->model('game_model');
        $this->load->model('players_model');
        $this->load->model('position_model');
        $this->load->helper('url_helper');
        
    }

    public function index() {
        
            $data['games'] = $this->game_model->get_games();
            $data['title'] = 'Games played';

            $this->load->view('templates/header', $data);
            $this->load->view('game/index', $data);
            $this->load->view('templates/footer');
            
    }

    public function view($id = NULL)
    {
            $this->load->helper('form');
            $data['game_item'] = $this->game_model->get_games($id);
            $data['players'] = $this->players_model->get_players($id);
            $data['pn'] = $this->players_model->get_player_by_symbol($id, $this->currentPlayer);
            
            if (empty($data['game_item']))
            {
                    show_404();
            }

            $data['title'] = '<div class="pl_setup"><label id="title_'.$data['players'][0]['id'].'">'.$data['players'][0]['player_nick'].'</label>'." Vs ".'<label id="title_'.$data['players'][1]['id'].'">'.$data['players'][1]['player_nick'].'</label></div>';            
            $data['status'] = $data['game_item']['status'];
            $data['id'] = ($id);
            $data['width'] = self::WIDTH;
            $data['height'] = self::HEIGHT;
            $data['field'] = $this->field;
            $data['winnerCells'] = $this->winnerCells;
            $data['currentPlayer'] = $this->currentPlayer;
            //$data['winner'] = $this->winner;
            $data['playerNick'] = $data['pn']['player_nick'];
            $data['playerID'] = $data['pn']['id'];
            
            $this->load->view('templates/header');
            $this->load->view('game/view', $data);
            $this->load->view('templates/footer');
    }
    
    public function create()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = self::$startTitle;

        $this->form_validation->set_rules('player_one', 'Player One', 'trim|required|min_length[3]|max_length[12]');
        $this->form_validation->set_rules('player_two', 'Player Two', 'trim|required|min_length[3]|max_length[12]');

        if ($this->form_validation->run() === FALSE) {
            
            $this->load->view('templates/header', $data);
            $this->load->view('game/create');
            $this->load->view('templates/footer');

        } else {
            
            $this->load->helper('url');
            $res = $this->game_model->set_game();         
            
            //Save players in players table 
            if (!is_null($res['id'])){
                
                if ($this->players_model->set_players($res['id'], $res['player_one_nick'], $res['player_two_nick'])){
                    redirect(base_url().'game/view/'.$res['id']);
                }
                                
            } else $this->load->view('news/success', $data);
            
        }
    }
    
    /**
     * Ajax call from field click
     * @param type $id
     */
    public function makeMove($id = NULL){
        
        $this->load->helper('url');
        
        $x = $this->input->post('x');
        $y = $this->input->post('y');  
        $currentPlayer = $this->input->post('currentPlayer');
        
        /**
         * Change players
         */
        $current = $currentPlayer;
        $this->currentPlayer = ($current == 1) ? 2 : 1;
        
        
        /**
         * Find opponent
         */
        $opponent = $this->players_model->get_player_by_symbol($id, $this->currentPlayer);
        
        /**
         * Sets current players position
         */
        $res = $this->position_model->set_position($id, $this->currentPlayer, $x, $y);  
                
                                       
//        if ($res){
//            $win = $this->checkWin($id, $playerID, $x, $y);
//        } else {
//            
//        }
        
        $response = array('winner' => $this->getWinner(), 'winnerCells' => $this->getWinnerCells(), 'playerSymbol' => $this->getCurrentPlayer(), 'playerID' => $opponent['id'], 'playerName'=>$opponent['player_nick']);

        header('Content-Type: application/json');
        echo json_encode( $response );
    }
        
    public function checkWin($id = null, $playerID = null, $x = null, $y = null){
        return $this->position_model->checkWinModel($id, $playerID, $x, $y);
    }
    
    public function getCurrentPlayer() {
        return $this->currentPlayer;
    }
    
    public function getWinner() {
        return $this->winner;
    }
    
    public function getWinnerCells() {
        return $this->winnerCells;
    }

    
    /**
     * USED FOR TESTING!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
     */
    public function checkWinner() {
        
        for ($y = 0; $y < $this->fieldHeight; $y++) {
            for ($x = 0; $x < $this->fieldWidth; $x++) {
                $this->checkLine($x, $y, 1, 0);
                $this->checkLine($x, $y, 1, 1);
                $this->checkLine($x, $y, 0, 1);
                $this->checkLine($x, $y, -1, 1);
            }
        }

        if ($this->getWinner()) {
            $this->currentPlayer = null;
        }
        
    }

    public function checkLine($startX, $startY, $dx, $dy) {
        $x = $startX;
        $y = $startY;
        $field = $this->field;
        $value = isset($field[$x][$y]) ? $field[$x][$y] : null;
        $cells = [];
        $foundWinner = false;
        if ($value) {
            $cells[] = [$x, $y];
            $foundWinner = true;
            for ($i = 1; $i < $this->countToWin; $i++) {
                $x += $dx;
                $y += $dy;
                $value2 = isset($field[$x][$y]) ? $field[$x][$y] : null;
                if ($value2 == $value) {
                    $cells[] = [$x, $y];
                } else {
                    $foundWinner = false;
                    break;
                }
            }
        }

        if ($foundWinner) {
            foreach ($cells as $cell) {
                $this->winnerCells[$cell[0]][$cell[1]] = $value;
            }
            $this->winner = $value;
        }
    }

}
