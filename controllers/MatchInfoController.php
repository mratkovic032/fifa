<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Models\MatchInfoModel;    
    use App\Models\OfficialModel;    
    use App\Models\EventInfoModel;        
    use App\Models\WeatherModel;    
    use App\Models\StatisticModel;    
    use App\Models\PlayerModel;    
    use App\Models\TeamModel;    

    class MatchInfoController extends Controller {        
        
        public function matches() {

            $matchModel = new MatchInfoModel($this->getDatabaseConnection());
            $matches = $matchModel->getAll();            
            if ($matches) {
                $this->set('matches', $matches);
                return;
            }
            $this->set('error', 'No matches found.');
        }

        public function orderbytemp() {
            $matchModel = new MatchInfoModel($this->getDatabaseConnection());
            $matches = $matchModel->getAllMatchesOrderedByTemp();  

            if ($matches) {

                $officialModel = new OfficialModel($this->getDatabaseConnection());
                $eventInfoModel = new EventInfoModel($this->getDatabaseConnection());
                $weatherModel = new WeatherModel($this->getDatabaseConnection());
                $statisticModel = new StatisticModel($this->getDatabaseConnection());
                $playerModel = new PlayerModel($this->getDatabaseConnection());
                $teamModel = new TeamModel($this->getDatabaseConnection());

                foreach ($matches as $match) {

                    $weather = $weatherModel->getWeatherByFifaId($match->fifa_id);
                    if($weather) {
                        $match->weather = $weather;
                    }

                    $officials = $officialModel->getAllOfficialsByFifaId($match->fifa_id);
                    if ($officials) {
                        $match->officials = $officials;
                    }

                    $homeEvents = $eventInfoModel->getAllEventsByFifaId($match->fifa_id, 'home');
                    if ($homeEvents) {
                        $match->home_team_events = $homeEvents;
                    }

                    $awayEvents = $eventInfoModel->getAllEventsByFifaId($match->fifa_id, 'away');
                    if ($awayEvents) {
                        $match->away_team_events = $awayEvents;
                    }

                    $homeStatistics = $statisticModel->getStatisticByFifaId($match->fifa_id, 'home');
                    if ($homeStatistics) {
                        $match->home_team_statistics = $homeStatistics;
                    }

                    $awayStatistics = $statisticModel->getStatisticByFifaId($match->fifa_id, 'away');
                    if ($awayStatistics) {
                        $match->away_team_statistics = $awayStatistics;
                    }

                    $homeTeamId = $teamModel->getTeamIdByCountry($match->home_team_country);            

                    $homeStartingElevenPlayers = $playerModel->getPlayersByTeamIdAndStartingEleven($homeTeamId->team_id, 1);
                    if ($homeStartingElevenPlayers) {                        
                        $match->home_team_statistics[0]->starting_eleven = $homeStartingElevenPlayers;
                    }

                    $homeSubstitutePlayers = $playerModel->getPlayersByTeamIdAndStartingEleven($homeTeamId->team_id, 0);
                    if ($homeSubstitutePlayers) {
                        $match->home_team_statistics[0]->substitutes = $homeSubstitutePlayers;
                    }

                    $awayTeamId = $teamModel->getTeamIdByCountry($match->away_team_country);

                    $awayStartingElevenPlayers = $playerModel->getPlayersByTeamIdAndStartingEleven($awayTeamId->team_id, 1);
                    if ($awayStartingElevenPlayers) {
                        $match->away_team_statistics[0]->starting_eleven = $awayStartingElevenPlayers;
                    }

                    $awaySubstitutePlayers = $playerModel->getPlayersByTeamIdAndStartingEleven($awayTeamId->team_id, 0);
                    if ($awaySubstitutePlayers) {
                        $match->away_team_statistics[0]->substitutes = $awaySubstitutePlayers;
                    }
                }

            }
            print_r(\json_encode($matches));

        }
    }