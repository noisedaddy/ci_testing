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

//            $query = $this->db->get_where('game');
//            return $query->result_array();
        
        $sql = "
              SELECT G.id as game_id,
                       G.start_on as game_start,
                       G.status as game_status,
                       G.fk_winner_id as winner_id,
                       G.player_one_nick as player_one_nick,
                       G.player_two_nick as player_two_nick,
                       P.player_nick as player_winner,
                       P.player_symbol as player_symbol
                FROM game AS G
                        INNER JOIN players AS P
                    ON G.fk_winner_id = P.id 
                        ORDER BY G.start_on";         
            return $this->db->query($sql)->result_array();
        
        }
        
        $query = $this->db->get_where('game', array('id' => $id));
          return $query->row_array();
        
//                $sql = "
//                SELECT G.id as game_id,
//                       G.start_on as game_start,
//                       G.status as game_status,
//                       P.player_nick as player_nick,
//                       P.player_symbol as player_symbol
//                FROM game AS G
//                        INNER JOIN players AS P
//                    ON G.id = P.fk_game_id
//                        WHERE G.id=".$id."";
//         
//            return $this->db->query($sql)->row_array();
                    
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
        
        if ($res) return array('id'=>$this->db->insert_id());
        else throw new Exception("Error: DB Insert failed!"); //show_error("Error: Insert db failed!");       
                
    }
    
//    public function set_position(){
//        
//        $data = array(
//            'player_one_nick' => $this->input->post('player_one'),
//            'player_two_nick' => $this->input->post('player_two'),            
//            'status' => self::$statusPending
//        );
//        
//        $res = $this->db->insert('game', $data);
//        if ($res) return $this->db->insert_id();
//        else throw new Exception("Error: DB Insert failed!"); //show_error("Error: Insert db failed!"); 
//        
//    }

}
