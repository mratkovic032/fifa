<?php
    namespace App\Controllers;

    use App\Models\PlayerModel;    
    use App\Core\Controller;

    class PlayerController extends Controller {        
        
        public function players() {

            $playerModel = new PlayerModel($this->getDatabaseConnection());
            $players = $playerModel->getAllPlayers();            
            if ($players) {
                $this->set('players', $players);
                return;
            }
            $this->set('error', 'No players found.');
        }
    }