<?php
    namespace App\Controllers;
        
    use App\Core\ApiController;
    use App\Models\TeamModel;
    use App\Models\GroupInfoModel;
    use App\Models\MatchInfoModel;
    use App\Models\RefereeModel;
    use App\Models\OfficialModel;    
    use App\Models\PlayerModel;
    use App\Models\EventInfoModel;
    use App\Models\StatisticModel;
    use App\Models\WeatherModel;
    
    class ApiInitController extends ApiController {

        public function init() {   
            ini_set('max_execution_time', 300);         
            $jsonTeamsString = \file_get_contents("http://worldcup.sfg.io/teams/");
            $jsonTeams = json_decode($jsonTeamsString);
            
            $jsonMatchesString = \file_get_contents("http://worldcup.sfg.io/matches");
            $jsonMatches = json_decode($jsonMatchesString);

            $teamModel = new TeamModel($this->getDatabaseConnection());
            $groupInfoModel = new GroupInfoModel($this->getDatabaseConnection());
            $matchModel = new MatchInfoModel($this->getDatabaseConnection());
            $refereeModel = new RefereeModel($this->getDatabaseConnection());
            $officialModel = new OfficialModel($this->getDatabaseConnection());
            $playerModel = new PlayerModel($this->getDatabaseConnection());
            $eventInfoModel = new EventInfoModel($this->getDatabaseConnection());
            $statisticModel = new StatisticModel($this->getDatabaseConnection());
            $weatherModel = new WeatherModel($this->getDatabaseConnection());
            
            foreach ($jsonTeams as $team) {                 
                $groupObj = $groupInfoModel->getByFieldName('group_letter', $team->group_letter);                
                if (!$groupObj) {
                    $groupId = $groupInfoModel->add([
                        'group_letter'  => $team->group_letter
                    ]);

                    if (!$groupId) {
                        $this->set('error', 'Failed importing groups');
                        return;
                    }
                }
            }
            
            foreach ($jsonTeams as $team) {
                $groupObj = $groupInfoModel->getByFieldName('group_letter', $team->group_letter);
                $teamObj = $teamModel->getByFieldName('fifa_code', $team->fifa_code);                
                if (!$teamObj) {                    
                    $teamId = $teamModel->add([
                        'country'           => $team->country,
                        'fifa_code'         => $team->fifa_code,
                        'group_id'          => $groupObj->group_id,
                        'group_letter'      => $team->group_letter
                    ]);

                    if (!$teamId) {
                        $this->set('error', 'Failed importing teams');
                        return;
                    }
                }
            }
            
            foreach($jsonMatches as $match) {                   
                $matchObj = $matchModel->getByFieldName('fifa_id', $match->fifa_id);
                if (!$matchObj) {
                    $matchId = $matchModel->add([
                        'venue'                 => $match->venue,
                        'location'              => $match->location,
                        'completion_status'     => $match->status,
                        'time'                  => $match->time,
                        'fifa_id'               => $match->fifa_id,                        
                        'attendance'            => $match->attendance,
                        'stage_name'            => $match->stage_name,
                        'home_team_country'     => $match->home_team_country,
                        'away_team_country'     => $match->away_team_country,
                        'home_team_goals'       => $match->home_team->goals,
                        'away_team_goals'       => $match->away_team->goals,
                        'home_team_penalties'   => $match->home_team->penalties,
                        'away_team_penalties'   => $match->away_team->penalties,
                        'datetime'              => $match->datetime,
                        'winner'                => $match->winner,
                        'winner_code'           => $match->winner_code,
                        'last_event_update_at'  => $match->last_event_update_at
                    ]);

                    if (!$matchId) {
                        $this->set('error', 'Failed importing matches');
                        return;
                    }

                    $weatherId = $weatherModel->add([
                        'fifa_id'           => $match->fifa_id,
                        'humidity'          => $match->weather->humidity,
                        'temp_celsius'      => $match->weather->temp_celsius,
                        'temp_farenheit'    => $match->weather->temp_farenheit,
                        'wind_speed'        => $match->weather->wind_speed,
                        'description'       => $match->weather->description
                    ]);

                    if (!$weatherId) {
                        $this->set('error', 'Failed importing weather');
                        return;
                    }
                    
                    $homeFoulsCommitted = $match->home_team_statistics->fouls_committed;
                    if ($homeFoulsCommitted == NULL) {
                        $homeFoulsCommitted = 0;
                    }
                    $homeStatisticId = $statisticModel->add([
                        'attempts_on_goal'      => $match->home_team_statistics->attempts_on_goal,
                        'on_target'             => $match->home_team_statistics->on_target,
                        'off_target'            => $match->home_team_statistics->off_target,
                        'blocked'               => $match->home_team_statistics->blocked,
                        'woodwork'              => $match->home_team_statistics->woodwork,
                        'corners'               => $match->home_team_statistics->corners,
                        'offsides'              => $match->home_team_statistics->offsides,
                        'ball_possession'       => $match->home_team_statistics->ball_possession,
                        'pass_accuracy'         => $match->home_team_statistics->pass_accuracy,
                        'num_passes'            => $match->home_team_statistics->num_passes,
                        'passes_completed'      => $match->home_team_statistics->passes_completed,
                        'distance_covered'      => $match->home_team_statistics->distance_covered,
                        'balls_recovered'       => $match->home_team_statistics->balls_recovered,
                        'tackles'               => $match->home_team_statistics->tackles,
                        'clearances'            => $match->home_team_statistics->clearances,
                        'yellow_cards'          => $match->home_team_statistics->yellow_cards,
                        'red_cards'             => $match->home_team_statistics->red_cards,
                        'fouls_committed'       => $homeFoulsCommitted,
                        'tactics'               => $match->home_team_statistics->tactics,
                        'fifa_id'               => $match->fifa_id,
                        'team_status'           => 'home'
                    ]);
        
                    if (!$homeStatisticId) {
                        $this->set('error', 'Failed importing home statistics');
                        return;
                    }
                    
                    $awayFoulsCommitted = $match->away_team_statistics->fouls_committed;
                    if ($awayFoulsCommitted == NULL) {
                        $awayFoulsCommitted = 0;
                    }                    
                    $awayStatisticId = $statisticModel->add([
                        'attempts_on_goal'      => $match->away_team_statistics->attempts_on_goal,
                        'on_target'             => $match->away_team_statistics->on_target,
                        'off_target'            => $match->away_team_statistics->off_target,
                        'blocked'               => $match->away_team_statistics->blocked,
                        'woodwork'              => $match->away_team_statistics->woodwork,
                        'corners'               => $match->away_team_statistics->corners,
                        'offsides'              => $match->away_team_statistics->offsides,
                        'ball_possession'       => $match->away_team_statistics->ball_possession,
                        'pass_accuracy'         => $match->away_team_statistics->pass_accuracy,
                        'num_passes'            => $match->away_team_statistics->num_passes,
                        'passes_completed'      => $match->away_team_statistics->passes_completed,
                        'distance_covered'      => $match->away_team_statistics->distance_covered,
                        'balls_recovered'       => $match->away_team_statistics->balls_recovered,
                        'tackles'               => $match->away_team_statistics->tackles,
                        'clearances'            => $match->away_team_statistics->clearances,
                        'yellow_cards'          => $match->away_team_statistics->yellow_cards,
                        'red_cards'             => $match->away_team_statistics->red_cards,
                        'fouls_committed'       => $awayFoulsCommitted,
                        'tactics'               => $match->away_team_statistics->tactics,
                        'fifa_id'               => $match->fifa_id,
                        'team_status'           => 'away'
                    ]);
        
                    if (!$awayStatisticId) {
                        $this->set('error', 'Failed importing away statistics');
                        return;
                    }

                    $teamHome = $teamModel->getByFieldName('country', $match->home_team_country);
                    $teamAway = $teamModel->getByFieldName('country', $match->away_team_country);

                    $teamModel->updateTeamGoalsInfo($teamHome->team_id, $match->home_team->goals, $match->away_team->goals);
                    $teamModel->updateTeamGoalsInfo($teamAway->team_id, $match->away_team->goals, $match->home_team->goals);
                    

                    if ($match->winner == "Draw") {
                        $teamModel->updateTeamStatistics($teamHome->team_id, 0, 1, 0);
                        $teamModel->updateTeamStatistics($teamAway->team_id, 0, 1, 0);
                    } else {
                        if ($match->winner == $teamHome->country) {
                            $teamModel->updateTeamStatistics($teamHome->team_id, 1, 0, 0);
                            $teamModel->updateTeamStatistics($teamAway->team_id, 0, 0, 1);
                        } else {
                            $teamModel->updateTeamStatistics($teamAway->team_id, 1, 0, 0);
                            $teamModel->updateTeamStatistics($teamHome->team_id, 0, 0, 1);
                        }
                    }                    
    
                    foreach($match->officials as $referee) {
                        $refereeObj = $refereeModel->getByFieldName('name', $referee);
                        
                        if (!$refereeObj) {

                            $refereeId = $refereeModel->add([
                                'name'  =>  $referee
                            ]);

                            if (!$refereeId) {
                                $this->set('error', 'Failed importing referees');
                                return;
                            }

                            $officialId = $officialModel->add([
                                'fifa_id'       => $match->fifa_id,
                                'referee_id'    => $refereeId
                            ]);
    
                            if (!$officialId) {
                                $this->set('error', 'Failed importing officials');
                                return;
                            }

                        } else {
                            $officialId = $officialModel->add([
                                'fifa_id'       => $match->fifa_id,
                                'referee_id'    => $refereeObj->referee_id
                            ]);
    
                            if (!$officialId) {
                                $this->set('error', 'Failed importing officials');
                                return;
                            }
                        }                            
                    }
    
                    foreach ($match->home_team_events as $event) {                        

                        $eventId = $eventInfoModel->add([
                            'json_id'       => $event->id,
                            'type_of_event' => $event->type_of_event,
                            'player'        => $event->player,
                            'time'          => $event->time,
                            'fifa_id'       => $match->fifa_id,
                            'team_status'   => 'home'
                        ]);

                        if (!$eventId) {
                            $this->set('error', 'Failed importing home events');
                            return;
                        }
                        
                    }

                    foreach ($match->away_team_events as $event) {
                        
                        $eventId = $eventInfoModel->add([
                            'json_id'       => $event->id,
                            'type_of_event' => $event->type_of_event,
                            'player'        => $event->player,
                            'time'          => $event->time,
                            'fifa_id'       => $match->fifa_id,
                            'team_status'   => 'away'
                        ]);

                        if (!$eventId) {
                            $this->set('error', 'Failed importing away events');
                            return;
                        }
                        
                    }

                    
                    foreach ($match->home_team_statistics->starting_eleven as $player) {   
                        $playerObj = $playerModel->getByFieldName('name', $player->name);
    
                        if (!$playerObj) {                                             

                            $teamObj = $teamModel->getByFieldName('country', $match->home_team_statistics->country);
                            $teamId = $teamObj->team_id;

                            $captain = 0;
                            if ($player->captain == true) {
                                $captain = 1;
                            }
                            $playerId = $playerModel->add([
                                'name'              => $player->name,
                                'captain'           => $captain,
                                'shirt_number'      => $player->shirt_number,
                                'position'          => $player->position,
                                'team_id'           => $teamId,
                                'starting_eleven'   => 1
                            ]);
                            }
                    }
    
                    foreach ($match->home_team_statistics->substitutes as $player) {
                        $playerObj = $playerModel->getByFieldName('name', $player->name);
    
                        if (!$playerObj) {
                        
                            $teamObj = $teamModel->getByFieldName('country', $match->home_team_statistics->country);
                            $teamId = $teamObj->team_id;

                            $captain = 0;
                            if ($player->captain == true) {
                                $captain = 1;
                            }
                            $playerId = $playerModel->add([
                                'name'          => $player->name,
                                'captain'       => $captain,
                                'shirt_number'  => $player->shirt_number,
                                'position'      => $player->position,
                                'team_id'       => $teamId
                            ]);          
                        }
                    }
    
                    foreach ($match->away_team_statistics->starting_eleven as $player) {
                        $playerObj = $playerModel->getByFieldName('name', $player->name);
    
                        if (!$playerObj) {

                            $teamObj = $teamModel->getByFieldName('country', $match->away_team_statistics->country);
                            $teamId = $teamObj->team_id;

                            $captain = 0;
                            if ($player->captain == true) {
                                $captain = 1;
                            }
                            $playerId = $playerModel->add([
                                'name'              => $player->name,
                                'captain'           => $captain,
                                'shirt_number'      => $player->shirt_number,
                                'position'          => $player->position,
                                'team_id'           => $teamId,
                                'starting_eleven'   => 1
                            ]);
                        }
                    }
    
                    foreach ($match->away_team_statistics->substitutes as $player) {
                        $playerObj = $playerModel->getByFieldName('name', $player->name);
    
                        if (!$playerObj) {

                            $teamObj = $teamModel->getByFieldName('country', $match->away_team_statistics->country);
                            $teamId = $teamObj->team_id;

                            $captain = 0;
                            if ($player->captain == true) {
                                $captain = 1;
                            }
                            $playerId = $playerModel->add([
                                'name'          => $player->name,
                                'captain'       => $captain,
                                'shirt_number'  => $player->shirt_number,
                                'position'      => $player->position,
                                'team_id'       => $teamId
                            ]);
                        }
                    }
                }                
            }
            
            $teamModel->updateGoalDifferential();
            $teamModel->updatePointsAndGamesPlayed();

            $this->set('error', 0);
            return;
        }
    }