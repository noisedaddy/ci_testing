<?php

class Players_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * Get players for selected game id
     * @param type $id
     * @return type
     */
    public function get_players($id = NULL) {

        if ($id === NULL) {

            return;
        
        }
        
        $query = $this->db->get_where('players', array('fk_game_id' => $id));
        return $query->result_array();       
    }
    
    /**
     * Gets player by symbol
     * @param type $id
     * @param type $playerSymbol
     * @return type
     */
    public function get_player_by_symbol($id = NULL, $playerSymbol = NULL){
        
        if ($id === NULL || $playerSymbol === NULL){
            return;            
        }
        
        $query = $this->db->get_where('players', array('fk_game_id' => $id, 'player_symbol' => $playerSymbol));
        return $query->row_array();      
        
    }
    /**
     * Find players opponent
     * @param type $id
     * @param type $playerID
     * @return type
     */
    public function get_opponent($id = NULL, $playerID = NULL) {

        if ($id === NULL || $playerID === NULL) {

            return;
        
        }
        
        $query = $this->db->get_where('players', array('fk_game_id' => $id, 'id !='=>$playerID));
        return $query->row_array();
        
    }
    
    /**
     * Saves players in players table
     * @param type $gameID
     * @param type $playerOne
     * @param type $playerTwo
     * @return type
     * @throws Exception
     */
    public function set_players($gameID = null)
    {   
        $data1 = array(
            'player_nick' => $this->input->post('player_one'),           
            'fk_game_id' => $gameID,
            'player_symbol' => '1'
        );
        
        $data2 = array(
            'player_nick' => $this->input->post('player_two'),           
            'fk_game_id' => $gameID,
            'player_symbol' => '2'
        ); 
        
        $res1 = $this->db->insert('players', $data1);
        $res2 = $this->db->insert('players', $data2);
                
        if ($res1 && $res2) return true;
        else throw new Exception("Error: DB Insert failed!"); //show_error("Error: Insert db failed!"); 
    }

}
