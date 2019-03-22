<?php
    namespace App\Models;   
    use App\Core\Model;
    use App\Core\Field;    
    use App\Validators\NumberValidator;
    use App\Validators\StringValidator;

    class WeatherModel extends Model {

        protected function getFields(): array {
            return [
                'weather_id'           => new Field((new NumberValidator())->setIntegerLength(10), false),
                'fifa_id'              => new Field((new NumberValidator())->setIntegerLength(64)),                
                'humidity'             => new Field((new NumberValidator())->setIntegerLength(10)),
                'temp_celsius'         => new Field((new NumberValidator())->setIntegerLength(10)),
                'temp_farenheit'       => new Field((new NumberValidator())->setIntegerLength(10)),
                'wind_speed'           => new Field((new NumberValidator())->setIntegerLength(10)),                
                'description'          => new Field((new StringValidator())->setMaxLength(128))                
            ];
        } 
        
        public function getWeatherByFifaId(int $fifaId): array {
            $sql = 'SELECT weather.humidity, weather.temp_celsius, weather.temp_farenheit, 
                    weather.wind_speed, weather.description 
                    FROM weather WHERE fifa_id = ?;';
            $prep = $this->getConnection()->prepare($sql);            
            $res = $prep->execute([ $fifaId ]);
            $weather = [];
            if ($res) {
                $weather = $prep->fetchAll(\PDO::FETCH_OBJ);                
            }
            return $weather; 
        }
    }