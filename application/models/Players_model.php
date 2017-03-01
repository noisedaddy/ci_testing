<?php

class Players_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * Saves players in players table
     * @param type $gameID
     * @param type $playerOne
     * @param type $playerTwo
     * @return type
     * @throws Exception
     */
    public function set_players($gameID = null, $playerOne = null, $playerTwo = null)
    {   
        $data1 = array(
            'player_nick' => $playerOne,           
            'fk_game_id' => $gameID
        );
        
        $data2 = array(
            'player_nick' => $playerTwo,           
            'fk_game_id' => $gameID
        ); 
        
        $res1 = $this->db->insert('players', $data1);
        $res2 = $this->db->insert('players', $data2);
                
        if ($res1 && $res2) return true;
        else throw new Exception("Error: DB Insert failed!"); //show_error("Error: Insert db failed!"); 
    }

}
