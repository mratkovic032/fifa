<?php
    namespace App\Models;   
    use App\Core\Model;
    use App\Core\Field;    
    use App\Validators\NumberValidator;
    use App\Validators\StringValidator;
    use App\Validators\BitValidator;

    class PlayerModel extends Model {

        protected function getFields(): array {
            return [
                'player_id'       => new Field((new NumberValidator())->setIntegerLength(10), false),
                'name'            => new Field((new StringValidator())->setMaxLength(128)),
                'captain'         => new Field(new BitValidator()),
                'shirt_number'    => new Field((new NumberValidator())->setIntegerLength(10)),
                'position'        => new Field((new StringValidator())->setMaxLength(128)),
                'team_id'         => new Field((new NumberValidator())->setIntegerLength(10)),
                'starting_eleven' => new Field(new BitValidator())
            ];
        } 
        
        public function getAllPlayers(): array {
            $sql = 'SELECT player.*, team.country FROM 
            team INNER JOIN player ON team.team_id = player.team_id ORDER BY player.name ASC;';
            $prep = $this->getConnection()->prepare($sql);            
            $res = $prep->execute();            
            $players = [];
            if ($res) {
                $players = $prep->fetchAll(\PDO::FETCH_OBJ);                
            }
            return $players; 
        }

        public function getPlayersByTeamId(int $teamId): array {
            $sql = 'SELECT player.* FROM player INNER JOIN team ON player.team_id = team.team_id WHERE player.team_id = ?;';
            $prep = $this->getConnection()->prepare($sql);            
            $res = $prep->execute([ $teamId ]);
            $players = [];
            if ($res) {
                $players = $prep->fetchAll(\PDO::FETCH_OBJ);                
            }
            return $players; 
        }
        
        public function getPlayersByTeamIdAndStartingEleven(int $teamId, int $startingEleven): array {
            $sql = 'SELECT player.name, player.captain, player.shirt_number, player.position 
                    FROM player INNER JOIN team ON player.team_id = team.team_id 
                    WHERE player.team_id = ? AND player.starting_eleven = ?;';
            $prep = $this->getConnection()->prepare($sql);            
            $res = $prep->execute([ $teamId, $startingEleven ]);
            $players = [];
            if ($res) {
                $players = $prep->fetchAll(\PDO::FETCH_OBJ);                
            }
            return $players; 
        }
    }