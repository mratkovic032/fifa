<?php
    namespace App\Models;   
    use App\Core\Model;
    use App\Core\Field;    
    use App\Validators\NumberValidator;
    use App\Validators\StringValidator;

    class StatisticModel extends Model {

        protected function getFields(): array {
            return [
                'statistic_id'          => new Field((new NumberValidator())->setIntegerLength(10), false),
                'attempts_on_goal'      => new Field((new NumberValidator())->setIntegerLength(10)),
                'on_target'             => new Field((new NumberValidator())->setIntegerLength(10)),
                'off_target'            => new Field((new NumberValidator())->setIntegerLength(10)),
                'blocked'               => new Field((new NumberValidator())->setIntegerLength(10)),
                'woodwork'              => new Field((new NumberValidator())->setIntegerLength(10)),
                'corners'               => new Field((new NumberValidator())->setIntegerLength(10)),
                'offsides'              => new Field((new NumberValidator())->setIntegerLength(10)),
                'ball_possession'       => new Field((new NumberValidator())->setIntegerLength(10)),
                'pass_accuracy'         => new Field((new NumberValidator())->setIntegerLength(10)),
                'num_passes'            => new Field((new NumberValidator())->setIntegerLength(10)),
                'passes_completed'      => new Field((new NumberValidator())->setIntegerLength(10)),
                'distance_covered'      => new Field((new NumberValidator())->setIntegerLength(10)),
                'balls_recovered'       => new Field((new NumberValidator())->setIntegerLength(10)),
                'tackles'               => new Field((new NumberValidator())->setIntegerLength(10)),
                'clearances'            => new Field((new NumberValidator())->setIntegerLength(10)),
                'yellow_cards'          => new Field((new NumberValidator())->setIntegerLength(10)),
                'red_cards'             => new Field((new NumberValidator())->setIntegerLength(10)),
                'fouls_committed'       => new Field((new NumberValidator())->setIntegerLength(10)),
                'tactics'               => new Field((new StringValidator())->setMaxLength(128)),
                'fifa_id'               => new Field((new NumberValidator())->setIntegerLength(10)),                
                'team_status'           => new Field((new StringValidator())->setMaxLength(64))
            ];
        }    
        
        public function getStatisticByFifaId(int $fifaId, string $teamStatus): array {
            $sql = 'SELECT statistic.attempts_on_goal, statistic.on_target, statistic.off_target, 
                    statistic.blocked, statistic.woodwork, statistic.corners, statistic.offsides, 
                    statistic.ball_possession, statistic.pass_accuracy, statistic.num_passes, 
                    statistic.passes_completed, statistic.distance_covered, statistic.balls_recovered, 
                    statistic.tackles, statistic.clearances, statistic.yellow_cards, 
                    statistic.red_cards, statistic.fouls_committed, statistic.tactics 
                    FROM statistic WHERE fifa_id = ? AND team_status = ?;';
            $prep = $this->getConnection()->prepare($sql);            
            $res = $prep->execute([ $fifaId, $teamStatus ]);
            $statistic = [];
            if ($res) {
                $statistic = $prep->fetchAll(\PDO::FETCH_OBJ);                
            }
            return $statistic; 
        }
    }