<?php


class Game extends CI_Controller {

    private static $startTitle = 'Start new game';
    private static $titleMain = 'Listings:';
    private static $listingsClass = "hidden-xs hidden-sm";
    const WIDTH = 4;
    const HEIGHT = 4;
    const COUNT_WIN = 3;
    const NUMBER_FIELD = 9;
    const FINISHED = 'finished';
    const DRAW = 'draw';
    private $field = [];
    private $winnerCells = [];
    private $currentPlayer = 1;
    private $winner = null;

    public function __construct() {
        
        parent::__construct();
        $this->load->model('game_model');
        $this->load->model('players_model');
        $this->load->model('position_model');
        $this->load->helper('url_helper');
        
    }

    /**
     * Main game page with listings
     */
    public function index() {
        
            $data['games'] = $this->game_model->get_games();
            $data['title_main'] = self::$titleMain;
            $data['listings_class'] = "";
            
            $this->load->view('templates/header', $data);
            $this->load->view('game/index', $data);
            $this->load->view('templates/footer');
            
    }

    /**
     * Main game view
     * @param type $id
     */
    public function view($id = NULL)
    {    
        
        if ($id === null){
            show_404();
        }

            $this->load->helper('form');
            $data['details'] = $this->game_model->get_games($id);         
            $data['cells'] = $this->position_model->get_game_position($id);
            
            /**
             * Used for listings partial view
             */
            $data['games'] = $this->game_model->get_games();
            $data['title_main'] = Game::$titleMain;
            $data['listings_class'] = self::$listingsClass;
                    
            $this->field = $data['cells']['position'];
            
            $data['title'] = '';
            $data['currentPlayer'] = null;
            $data['playerNick'] = '';
            $data['playerID'] = ''; 
            
            
            //Finished, list details
            if ($data['details']['status'] == Game::FINISHED && $data['details']['fk_winner_id'] !== null){
                
                $data['player'] = $this->players_model->get_player($id, array('id'=>$data['details']['fk_winner_id']));
                $data['winnerCells'] = $data['cells']['winner_cells'];
                
                $data['title'] = '<ul class="list-group">
                                            <li class="list-group-item">Status: '.$data['details']['status'].'</li>
                                            <li class="list-group-item">Start: '.$data['details']['start_on'].'</li>
                                            <li class="list-group-item">Players: '.$data['details']['player_one_nick'].' Vs '.$data['details']['player_two_nick'].'</li>
                                            <li class="list-group-item">Winner: '.$data['player']['player_nick'].'</li>
                                 </ul>';
                             
            } else {
                
                //IF PENDING, CHECK CURRENTPLAYER - PUT IN SESSION
                
                $data['pn'] = $this->players_model->get_player($id, array('player_symbol'=>$this->getCurrentPlayer()));
                $data['players'] = $this->players_model->get_players($id);                
                $data['currentPlayer'] = $this->getCurrentPlayer();            
                $data['playerNick'] = $data['pn']['player_nick'];
                $data['playerID'] = $data['pn']['id'];     
                $data['title'] = '<div class="pl_setup"><label id="title_'.$data['players'][0]['id'].'">'.$data['players'][0]['player_nick'].'</label>'." Vs ".'<label id="title_'.$data['players'][1]['id'].'">'.$data['players'][1]['player_nick'].'</label></div>';            
            
            }
            
            $data['field'] = $this->getField();            
            $data['id'] = ($id);
            $data['width'] = self::WIDTH;
            $data['height'] = self::HEIGHT;            
                                                                      
            /**
             * ODL WORKING
             */
//            $this->load->helper('form');
//            $data['game_item'] = $this->game_model->get_games($id);
//            $data['players'] = $this->players_model->get_players($id);
//            $data['pn'] = $this->players_model->get_player_by_symbol($id, $this->currentPlayer);
//                       
//            if (empty($data['game_item']))
//            {
//                    show_404();
//            }
//
//            $data['title'] = '<div class="pl_setup"><label id="title_'.$data['players'][0]['id'].'">'.$data['players'][0]['player_nick'].'</label>'." Vs ".'<label id="title_'.$data['players'][1]['id'].'">'.$data['players'][1]['player_nick'].'</label></div>';            
//            $data['id'] = ($id);
//            $data['width'] = self::WIDTH;
//            $data['height'] = self::HEIGHT;
//            $data['field'] = $this->field;
//            $data['winnerCells'] = $this->winnerCells;
//            $data['currentPlayer'] = $this->currentPlayer;
//            //$data['winner'] = $this->winner;
//            $data['playerNick'] = $data['pn']['player_nick'];
//            $data['playerID'] = $data['pn']['id'];
            /**
             * END
             */
                                    
            $this->load->view('templates/header');
            $this->load->view('game/view', $data);
            $this->load->view('game/index', $data);
            $this->load->view('templates/footer');
    }
    
    /**
     * Create game page
     */
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
            
            /**
             * Save players in players table 
             */
            if (!is_null($res['id'])){
                
                    if ($this->players_model->set_players($res['id'])){
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
        $response = false;
        
        $x = $this->input->post('x');
        $y = $this->input->post('y');  
        $current = $this->input->post('currentPlayer');
        
        /**
         * Sets current players position
         */
        $position = $this->position_model->set_position($id, $current, $x, $y); 
                
        if ($position) {
            
            /**
             * Get current player details: id, player_symbol, player_nick
             */
            $player = $this->players_model->get_player($id, array('player_symbol' => $current));
            
            /**
             * Check if player won the game
             */
            $win = $this->position_model->check_win($id, array('id'=>$player['id'], 'player_symbol' => $current), $x, $y);
            
            
            /**
             * If player won the game
             */
            if ($win['result']){
                
              
                /**
                 * If game is draw, set winner as null
                 */
                $this->winner = (is_null($win['playerSymbol']) && is_null($win['winningFields'])) ? null : $player['player_nick'];
                
                $this->winnerCells = $win['winningFields'];
                $this->currentPlayer = $win['playerSymbol'];
                 
                $response = array(
                    'winner' => $this->winner, 
                    'winnerCells' => $this->getWinnerCells(), 
                    'playerSymbol' => $this->getCurrentPlayer(), 
                    'playerID' => $this->getCurrentPlayer(),
                    'playerName'=>$this->getWinner()
                );        
                                
            } else {
                
              /**
                * Change players
                */     
               $this->field[$x][$y] = $current;
               $this->currentPlayer = ($current == 1) ? 2 : 1;
               
                /**
                 * Find opponent
                 */
               $next = $this->players_model->get_player($id, array('player_symbol' => $this->getCurrentPlayer()));
                
                $response = array(
                    'winner' => $this->getWinner(), 
                    'winnerCells' => $this->getWinnerCells(), 
                    'playerSymbol' => $this->getCurrentPlayer(), 
                    'playerID' => $next['id'], 
                    'playerName'=>$next['player_nick']
                );
                        
            }
            
        } 
                                                                
        header('Content-Type: application/json');
        echo json_encode( $response );
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

   public function getField() {
        return $this->field;
    }

}
