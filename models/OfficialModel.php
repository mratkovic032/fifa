<?php
    namespace App\Models;   
    use App\Core\Model;
    use App\Core\Field;    
    use App\Validators\NumberValidator;

    class OfficialModel extends Model {

        protected function getFields(): array {
            return [
                'official_id' => new Field((new NumberValidator())->setIntegerLength(10), false),
                'fifa_id'     => new Field((new NumberValidator())->setIntegerLength(64)),
                'referee_id'  => new Field((new NumberValidator())->setIntegerLength(10))
            ];
        }        

        public function getAllOfficialsByFifaId(int $fifaId): array {
            $sql = 'SELECT referee.name FROM 
                    official INNER JOIN referee ON 
                    official.referee_id = referee.referee_id 
                    WHERE official.fifa_id = ?;';
            $prep = $this->getConnection()->prepare($sql);            
            $res = $prep->execute([ $fifaId ]);
            $matches = [];
            if ($res) {
                $matches = $prep->fetchAll(\PDO::FETCH_OBJ);                
            }
            return $matches; 
        }
    }