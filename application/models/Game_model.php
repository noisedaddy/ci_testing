<?php

class Game_model extends CI_Model {

    private static $statusFinish = 'finish';
    private static $statusPending = 'pending';
    
    public function __construct() {
        $this->load->database();
    }

    /**
     * Select played games
     * @param type $slug
     * @return type
     */
    public function get_games($id = NULL) {

        if ($id === NULL) {

            $query = $this->db->get_where('game', array('status' => self::$statusFinish));
            return $query->result_array();
        
        }
        
        $query = $this->db->get_where('game', array('id' => $id));
        return $query->row_array();

    }
    
    /**
     * Creates players and set up new game
     * @return type
     */
    public function set_game()
    {

        $data = array(
            'player_one_nick' => $this->input->post('player_one'),
            'player_two_nick' => $this->input->post('player_two'),            
            'status' => self::$statusPending
        );
        
        $res = $this->db->insert('game', $data);
        
        if ($res) return $this->db->insert_id();
        else throw new Exception("Error: DB Insert failed!"); //show_error("Error: Insert db failed!");       
                
    }
    
    public function set_position(){
        
        $data = array(
            'player_one_nick' => $this->input->post('player_one'),
            'player_two_nick' => $this->input->post('player_two'),            
            'status' => self::$statusPending
        );
        
        $res = $this->db->insert('game', $data);
        if ($res) return $this->db->insert_id();
        else throw new Exception("Error: DB Insert failed!"); //show_error("Error: Insert db failed!"); 
        
    }

}
