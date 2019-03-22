<?php
    namespace App\Controllers;

    use App\Models\TeamModel;    
    use App\Models\PlayerModel;    
    use App\Core\Controller;

    class TeamController extends Controller {        
        
        public function teams() {            
            $playerModel = new PlayerModel($this->getDatabaseConnection());
            
            $teamModel = new TeamModel($this->getDatabaseConnection());
            $teams = $teamModel->getAll();

            if ($teams) {
                foreach ($teams as $team) {
                    $players = $playerModel->getPlayersByTeamId($team->team_id);
                    if ($players) {
                        $team->players = $players;
                    }
                }
                
                $this->set('teams', $teams);
                return;
            }
            
            $this->set('error', 'No teams found.');
        }

        public function teamResults() {

            $teamModel = new TeamModel($this->getDatabaseConnection());
            $teams = $teamModel->getAll();
            if ($teams) {
                print_r(\json_encode($teams));
            }
        }
    }