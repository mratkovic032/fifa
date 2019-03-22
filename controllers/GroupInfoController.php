<?php
    namespace App\Controllers;

    use App\Models\GroupInfoModel;    
    use App\Models\TeamModel;    
    use App\Core\Controller;

    class GroupInfoController extends Controller {        
        
        public function groups() {            
            $teamModel = new TeamModel($this->getDatabaseConnection());

            $groupModel = new GroupInfoModel($this->getDatabaseConnection());
            $groups = $groupModel->getAll();
            
            if ($groups) {
                foreach ($groups as $group) {
                    $teams = $teamModel->getTeamsByGroupId($group->group_id);
                    if ($teams) {
                        $group->teams = $teams;
                    }
                }
                $this->set('groups', $groups);
                return;
            }

            $this->set('error', 'No groups found.');
        }
    }