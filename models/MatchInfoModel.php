<?php
    namespace App\Models;   
    use App\Core\Model;
    use App\Core\Field;    
    use App\Validators\NumberValidator;
    use App\Validators\DateTimeValidator;
    use App\Validators\StringValidator;

    class MatchInfoModel extends Model {

        protected function getFields(): array {
            return [
                'match_info_id'        => new Field((new NumberValidator())->setIntegerLength(10), false),
                'fifa_id'              => new Field((new NumberValidator())->setIntegerLength(64)),
                'venue'                => new Field((new StringValidator())->setMaxLength(128)),
                'location'             => new Field((new StringValidator())->setMaxLength(255)),
                'completion_status'    => new Field((new StringValidator())->setMaxLength(64)),
                'time'                 => new Field((new StringValidator())->setMaxLength(64)),                
                'attendance'           => new Field((new NumberValidator())->setIntegerLength(255)),
                'stage_name'           => new Field((new StringValidator())->setMaxLength(128)),
                'home_team_country'    => new Field((new StringValidator())->setMaxLength(128)),
                'away_team_country'    => new Field((new StringValidator())->setMaxLength(128)),
                'home_team_goals'      => new Field((new NumberValidator())->setIntegerLength(10)),
                'away_team_goals'      => new Field((new NumberValidator())->setIntegerLength(10)),
                'home_team_penalties'  => new Field((new NumberValidator())->setIntegerLength(10)),
                'away_team_penalties'  => new Field((new NumberValidator())->setIntegerLength(10)),
                'datetime'             => new Field((new DateTimeValidator())->allowDate()->allowTime()),
                'winner'               => new Field((new StringValidator())->setMaxLength(128)),
                'winner_code'          => new Field((new StringValidator())->setMaxLength(128)),
                'last_event_update_at' => new Field((new DateTimeValidator())->allowDate()->allowTime())
            ];
        }   
        
        public function getAllMatchesOrderedByTemp(): array {
            $sql = 'SELECT match_info.*, weather.temp_celsius 
                    FROM match_info INNER JOIN weather ON match_info.fifa_id = weather.fifa_id 
                    ORDER BY weather.temp_celsius DESC;';
            $prep = $this->getConnection()->prepare($sql);            
            $res = $prep->execute([ ]);
            $matches = [];
            if ($res) {
                $matches = $prep->fetchAll(\PDO::FETCH_OBJ);                
            }
            return $matches; 
        }
    }