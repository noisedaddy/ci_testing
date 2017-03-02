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
    
    public function checkWinModel($gameID = null, $player = null, $x = null, $y = null){
        //query goes here
        $sql = "
            SELECT
            SUM(IF(pos_x = '".$x."', 1, 0)) AS count_x,
            SUM(IF(pos_y = '".$y."', 1, 0)) AS count_y
            FROM position where fk_player_id = ".$player." and fk_game_id = ".$gameID."
         ";
        $query = $this->db->query($sql)->row_array();
        
        if ($query['count_x'] == 3 || $query['count_y'] == 3){
            return true;
        }
        
//        if ( ! $this->db->simple_query('SELECT `example_field` FROM `example_table`'))
//        {
//                $error = $this->db->error(); // Has keys 'code' and 'message'
//        }
    }

}
