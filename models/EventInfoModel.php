<?php  
    namespace App\Models;

    use App\Core\Model;
    use App\Core\Field;    
    use App\Validators\NumberValidator;
    use App\Validators\StringValidator;

    class EventInfoModel extends Model {

        protected function getFields(): array {
            return [
                'event_info_id' => new Field((new NumberValidator())->setIntegerLength(10), false),
                'json_id'       => new Field((new NumberValidator())->setIntegerLength(10)),
                'type_of_event' => new Field((new StringValidator())->setMaxLength(128)),
                'player'        => new Field((new StringValidator())->setMaxLength(128)),
                'time'          => new Field((new StringValidator())->setMaxLength(64)),
                'fifa_id'       => new Field((new NumberValidator())->setIntegerLength(64)),
                'team_status'   => new Field((new StringValidator())->setMaxLength(64))
            ];
        }
        
        public function getAllEventsByFifaId(int $fifaId, string $teamStatus): array {
            $sql = 'SELECT event_info.event_info_id, event_info.type_of_event, event_info.player, event_info.time FROM event_info 
            WHERE event_info.fifa_id = ? AND team_status = ?;';
            $prep = $this->getConnection()->prepare($sql);            
            $res = $prep->execute([ $fifaId, $teamStatus ]);
            $matches = [];
            if ($res) {
                $matches = $prep->fetchAll(\PDO::FETCH_OBJ);                
            }
            return $matches; 
        }
    }