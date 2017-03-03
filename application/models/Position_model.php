<?php

class Position_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    /**
     * Saves players position in the position table
     * @param type $gameID
     * @param type $playerOne
     * @param type $playerTwo
     * @return type
     * @throws Exception
     */
    public function set_position($gameID = null, $player = null, $x = null, $y = null)
    {   
        $data = array(
            'pos_x' => $x,           
            'pos_y' => $y,
            'fk_player_id' => $player,
            'fk_game_id' => $gameID
        );
        
        $res = $this->db->insert('position', $data);
                
        if ($res) return true;
        else throw new Exception("Error: DB Insert failed!"); //show_error("Error: Insert db failed!"); 
    }
    
    /**
     * Check if current player won the game
     * @param type $gameID
     * @param type $player
     * @param type $x
     * @param type $y
     * @return type
     */
    public function checkWinModel($gameID = null, $player = null, $x = null, $y = null){
        
        $sql = "
            SELECT
            SUM(IF(pos_x = '".$x."', 1, 0)) AS count_x,
            SUM(IF(pos_y = '".$y."', 1, 0)) AS count_y
            FROM position where fk_player_id = ".$player." and fk_game_id = ".$gameID."
         ";
        $query = $this->db->query($sql)->row_array();
        
        if ($query['count_x'] == 3 || $query['count_y'] == 3){
            return array('result' => true, 'playerSymbol' => $player, 'gameID' => $gameID);
        } else {
            return array('result' => false);
        }
        
    }

}
