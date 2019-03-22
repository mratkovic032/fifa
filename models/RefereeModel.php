<?php
    namespace App\Models;   
    use App\Core\Model;
    use App\Core\Field;    
    use App\Validators\NumberValidator;
    use App\Validators\StringValidator;

    class RefereeModel extends Model {

        protected function getFields(): array {
            return [
                'referee_id'    => new Field((new NumberValidator())->setIntegerLength(10), false),
                'name'          => new Field((new StringValidator())->setMaxLength(128))
            ];
        }        
    }