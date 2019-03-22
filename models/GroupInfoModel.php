<?php
    namespace App\Models;   
    use App\Core\Model;
    use App\Core\Field;    
    use App\Validators\NumberValidator;
    use App\Validators\StringValidator;

    class GroupInfoModel extends Model {

        protected function getFields(): array {
            return [
                'group_id'      => new Field((new NumberValidator())->setIntegerLength(10), false),
                'group_letter'  => new Field((new StringValidator())->setMaxLength(1))
            ];
        }                

        
    }