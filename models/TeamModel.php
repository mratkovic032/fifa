<?php
    namespace App\Models;   
    use App\Core\Model;
    use App\Core\Field;    
    use App\Validators\NumberValidator;
    use App\Validators\StringValidator;

    class TeamModel extends Model {

        protected function getFields(): array {
            return [
                'team_id'           => new Field((new NumberValidator())->setIntegerLength(10), false),
                'country'           => new Field((new StringValidator())->setMaxLength(128)),
                'alternate_name'    => new Field((new StringValidator())->setMaxLength(128)),
                'fifa_code'         => new Field((new StringValidator())->setMaxLength(3)),
                'group_id'          => new Field((new NumberValidator())->setIntegerLength(10)),
                'group_letter'      => new Field((new StringValidator())->setMaxLength(1)),
                'wins'              => new Field((new NumberValidator())->setIntegerLength(10)),
                'draws'             => new Field((new NumberValidator())->setIntegerLength(10)),
                'losses'            => new Field((new NumberValidator())->setIntegerLength(10)),
                'games_played'      => new Field((new NumberValidator())->setIntegerLength(10)),
                'points'            => new Field((new NumberValidator())->setIntegerLength(10)),
                'goals_for'         => new Field((new NumberValidator())->setIntegerLength(10)),
                'goals_against'     => new Field((new NumberValidator())->setIntegerLength(10)),
                'goal_differential' => new Field((new NumberValidator())->setSigned()->setIntegerLength(10))
            ];
        }
        
        public function updateTeamGoalsInfo(int $teamId, int $goalsFor, int $goalsAgains) {
            $sql = 'UPDATE team SET 
                    goals_for = goals_for + ?, 
                    goals_against = goals_against + ? 
                    WHERE team.team_id = ?;';
            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([ $goalsFor, $goalsAgains, $teamId ]);
            if ($res) {
                return true;
            }
            return false;
        }

        public function updateGoalDifferential() {
            $sql = 'UPDATE team SET goal_differential = goals_for - goals_against;';
            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([ ]);
            if ($res) {
                return true;
            }
            return false;
        }

        public function updateTeamStatistics(int $teamId, int $wins, int $draws, int $losses) {
            $sql = 'UPDATE team SET 
                    wins = wins + ?, 
                    draws = draws + ?, 
                    losses = losses + ?
                    WHERE team.team_id = ?;';
            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([ $wins, $draws, $losses, $teamId ]);
            if ($res) {
                return true;
            }
            return false;
        }

        public function updatePointsAndGamesPlayed() {
            $sql = 'UPDATE team SET 
                    points = (3 * wins) + draws,
                    games_played = wins + draws + losses;';
            $prep = $this->getConnection()->prepare($sql);
            $res = $prep->execute([ ]);
            if ($res) {
                return true;
            }
            return false;
        }

        public function getTeamsByGroupId(int $groupId): array {
            $sql = 'SELECT team.* FROM team INNER JOIN group_info ON team.group_id = group_info.group_id WHERE team.group_id = ? ORDER BY points DESC;';
            $prep = $this->getConnection()->prepare($sql);            
            $res = $prep->execute([ $groupId ]);
            $teams = [];
            if ($res) {
                $teams = $prep->fetchAll(\PDO::FETCH_OBJ);                
            }
            return $teams; 
        }

        public function getTeamIdByCountry(string $country) {
            $sql = 'SELECT team.team_id FROM team WHERE team.country = ?;';
            $prep = $this->getConnection()->prepare($sql);  
            $res = $prep->execute([ $country ]);
            $teamId = NULL;
            if ($res) {
                $teamId = $prep->fetch(\PDO::FETCH_OBJ);
            }
            return $teamId;
        }
    }