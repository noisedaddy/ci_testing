<?php

class Position_model extends CI_Model {
    
    public function __construct() {
        $this->load->database();
    }

    public function get_game_position($gameID = null){
        
        if ($gameID === null) {
            return;        
        }
        
        $columns = array();
        $winCells = array();
        
        $query = $this->db->get_where('position', array('fk_game_id' => $gameID));        
        $mytablescolumns = $query->result_array();
        
        if ($mytablescolumns){
            
            for($i = 0; $i < count($mytablescolumns); $i++) {            
               $columns[$mytablescolumns[$i]['pos_x']][$mytablescolumns[$i]['pos_y']] = $mytablescolumns[$i]['fk_player_id'];
               if ($mytablescolumns[$i]['win_field'] == 1) $winCells[$mytablescolumns[$i]['pos_x']][$mytablescolumns[$i]['pos_y']] = $mytablescolumns[$i]['win_field'];
            }   
            
            return array('position' => $columns, 'winner_cells' => $winCells);            
        }
         
        return false;
        
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
     * Get winningCells
     * @param type $id
     * @param type $player
     * @param type $field
     * @param type $fieldValue
     * @return type
     */
    public function get_position($id = null, $player = null, $field = null, $fieldValue = null){
        
        if ($id === NULL) {
            return;        
        }
        
        $params = ($player === null || $field === null || $fieldValue === null) ? array('fk_game_id' => $id) : array('fk_game_id' => $id, 'fk_player_id' => $player, 'pos_'.$field => $fieldValue);        
        $query = $this->db->get_where('position', $params);        
        $mytablescolumns = $query->result_array();   
                
        
        for($i = 0; $i < count($mytablescolumns); $i++) {            
            $columns[$mytablescolumns[$i]['pos_x']][$mytablescolumns[$i]['pos_y']] = $mytablescolumns[$i]['fk_player_id'];
         }
         
        return $columns;
    }
    
    /**
     * Check if current player won the game or it is a draw
     * @param type $gameID
     * @param type $player
     * @param type $x
     * @param type $y
     * @return type
     */
    public function check_win($gameID = null, $player = array(), $x = null, $y = null){
                
        $query_1 = $this->check_line_win($gameID, $player, $x, $y);
        $query_2 = $this->check_line_diagonaly($gameID, $player);
        $query_3 = $this->check_draw($gameID);
                
        if ($query_1['count_x'] >= Game::COUNT_WIN || $query_1['count_y'] >= Game::COUNT_WIN){            
            
            /**
             * Get winning cells array and set winner fields
             */
            $field = ($query_1['count_x'] == Game::COUNT_WIN) ? 'x' : 'y';
            $winningArray = $this->get_position($gameID, $player['player_symbol'], $field, $$field);              
            $this->set_winner($gameID, array('id' => $player['id'], 'player_symbol' => $player['player_symbol']), $winningArray);
            
            return array('result' => true, 'playerSymbol' => $player['player_symbol'], 'gameID' => $gameID, 'winningFields' => $winningArray);
            
        } else if ($query_2['total_left'] >= Game::COUNT_WIN){
            
            $winningArray = array(
                
                  1 => array( 3 => $player),
                  2 => array( 2 => $player),
                  3 => array( 1 => $player)

              );
            
            $this->set_winner($gameID, array('id' => $player['id'], 'player_symbol' => $player['player_symbol']), $winningArray);
            
            return array('result' => true, 'playerSymbol' => $player['player_symbol'], 'gameID' => $gameID, 'winningFields' => $winningArray);
            
        } else if($query_2['total_right'] >= Game::COUNT_WIN){
            
            $winningArray = array(
                
                  1 => array( 1 => $player),
                  2 => array( 2 => $player),
                  3 => array( 3 => $player)

              );
            
            $this->set_winner($gameID, array('id' => $player['id'], 'player_symbol' => $player['player_symbol']), $winningArray);            
            return array('result' => true, 'playerSymbol' => $player['player_symbol'], 'gameID' => $gameID, 'winningFields' => $winningArray);
            
        } else if ($query_3 == Game::NUMBER_FIELD){
            
            $this->_updateRowWhere('game', array('id' => $gameID), array('status' => Game::DRAW, 'fk_winner_id' => null));
            return array('result' => true, 'playerSymbol' => null, 'gameID' => $gameID, 'winningFields' => null);
            
        } else {
            return array('result' => false);
        }
        
    }
        
    /**
     * Checks vertical/horizontal win
     * @param type $gameID
     * @param type $player
     * @param type $x
     * @param type $y
     * @return type
     */
    public function check_line_win($gameID = null, $player = array(), $x = null, $y = null){
                        
        $sql = "
            SELECT
            SUM(IF(pos_x = '".$x."', 1, 0)) AS count_x,
            SUM(IF(pos_y = '".$y."', 1, 0)) AS count_y
            FROM position where fk_player_id = ".$player['player_symbol']." and fk_game_id = ".$gameID."
         ";
        
        return $this->db->query($sql)->row_array();
                        
    }
    
    /**
     * Check diagonally win
     * @param type $gameID
     * @param type $player
     * @return type
     */
    public function check_line_diagonaly($gameID = null, $player = array()){
        
         
        $sql = "SELECT COUNT(IF(((pos_x = 1 and pos_y = 1) 
					or (pos_x =2 and pos_y = 2) 
					or (pos_x = 3 and pos_y = 3)),1,NULL))  total_right,  
                                COUNT(IF((pos_x = 1 and pos_y = 3) 
                                                 or (pos_x =2 and pos_y = 2) 
                                                 or (pos_x = 3 and pos_y = 1),1,NULL))  total_left
                                FROM position WHERE fk_player_id = ".$player['player_symbol']." and fk_game_id = ".$gameID."";
        
        $query = $this->db->query($sql)->row_array();
        return array('total_left' => $query['total_left'], 'total_right' => $query['total_right']);               
    }
    
    /**
     * Check draw
     * @param type $gameID
     * @return type
     */
    public function check_draw($gameID = null){
        
        $query_draw = $this->db->get_where('position', array('fk_game_id' => $gameID));        
        return $query_draw->num_rows();
        
    }
    
    /**
     * Sets winner fields in game and position table
     * @param type $gameID
     * @param type $player = array(id=>playerid, player_symbol=>player_symbol)
     * @param type $winningArray
     * @return boolean
     */
    public function set_winner($gameID = null, $player = array(), $winningArray = array()){
        
    
        $this->_updateRowWhere('game', array('id' => $gameID), array('status' => Game::FINISHED, 'fk_winner_id' => $player['id']));
        
            for($i=1;$i<=3; $i++){

                for($j=1;$j<=3; $j++){
                    
                     if (isset($winningArray[$i][$j])){

                         $this->_updateRowWhere('position', array('fk_player_id' => $player['player_symbol'], 'fk_game_id' => $gameID, 'pos_x' => $i, 'pos_y' => $j), array('win_field' => '1'));
                     }
                     
                }

            }
    
        return true;
        
        
    }
    
    /**
     * Updater function
     * @param type $table
     * @param type $where
     * @param type $data
     */
    function _updateRowWhere($table, $where = array(), $data = array()) {
        
        $this->db->where($where);
        $this->db->update($table, $data);
        
    }


}
