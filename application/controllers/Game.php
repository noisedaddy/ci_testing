<?php

//include_once (dirname(__FILE__) . "/News.php");

class Game extends CI_Controller {

    private static $startTitle = 'Start new game';
    const WIDTH = 4;
    const HEIGHT = 4;
    const COUNT_WIN = 3;
    private $field = [];
    private $winnerCells = [];
    private $currentPlayer = 1;
    private $winner = null;

    public function __construct() {
        
        parent::__construct();
        $this->load->model('game_model');
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

            if (empty($data['game_item']))
            {
                    show_404();
            }

            $data['title'] = $data['game_item']['player_one_nick']." Vs ".$data['game_item']['player_two_nick'];
            $data['status'] = $data['game_item']['status'];
            $data['id'] = ($id);
            $data['width'] = self::WIDTH;
            $data['height'] = self::HEIGHT;
            $data['field'] = $this->field;
            $data['winnerCells'] = $this->winnerCells;
            $data['currentPlayer'] = $this->currentPlayer;
            $data['winner'] = $this->winner;
            
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
            $insert_id = $this->game_model->set_game();         
            
            //redirect('/News/test');
            
            //NEEDS TO LOAD GAME MODEL AND PLAYERS MODEL 
            if (!is_null($insert_id)) redirect(base_url().'game/view/'.$insert_id);
            else $this->load->view('news/success', $data);
            
        }
    }
    
    /**
     * Ajax call from field click
     * @param type $id
     */
    public function makeMove($id = NULL){
        
        //$arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);    

         header('Content-Type: application/json');
         echo json_encode( $id );
    }
    
    
    

}
