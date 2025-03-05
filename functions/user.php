<?php 



if( ! function_exists('get_user_demands') ){
    function get_user_demands(string $user_id){
        $db = load_db();

        $sql = "SELECT * FROM `demands` WHERE `employee_id` = ?";

        $stmt = $db->prepare($sql);


        try{
            $stmt->execute([$user_id]);
        }catch(PDOException $e){
            die($e->getMessage());
        }

        if( $stmt->rowCount() < 1 ){
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if( ! function_exists('get_users_demands') ){
    function get_users_demands(){
        $db = load_db();

        $sql = "SELECT * FROM `demands` WHERE `status`=?";

        $stmt = $db->prepare($sql);

        try{
            $stmt->execute(['waiting']);
        }catch(PDOException $e){
            die($e->getMessage());
        }

        if( $stmt->rowCount() < 1 ){
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

if( ! function_exists('change_status') ) {
    function change_status($demand_id, $status){
        $db = load_db();

        $sql = "UPDATE `demands` SET `status`=? WHERE `id` = ?";

        $stmt = $db->prepare($sql);

        try{

            $stmt->execute([$status, $demand_id]);

        }catch(PDOException $err){

            die($err->getMessage());
            
        }
    }
}

if(! function_exists(('get_notifications'))){
    function get_notifications($user_id){
        $db = load_db();

        $sql = "SELECT * FROM `notifications` WHERE `employee_id` = ?";

        $stmt = $db->prepare($sql);

        try{
            $stmt->execute([$user_id]);
        }catch(PDOException $e){
            die($e->getMessage());
        }

        if( $stmt->rowCount() < 1 ){
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


if( !function_exists('fetch_creation_demands') ){
    function fetch_creation_demands($compte_valid='waiting'){
        $db = load_db();

        $sql = "SELECT * FROM `employees` WHERE `compte_valid` = ?";

        $stmt = $db->prepare($sql);

        try{
            $stmt->execute([$compte_valid]);
        }catch(PDOException $e){
            die($e->getMessage());
        }

        if( $stmt->rowCount() < 1 ){
            return [];
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


if(!function_exists('handleAccount')){
    function handleAccount($type, $id){
        $t = $type == 'activate' ? 1 : 0;

        $db = load_db();

        $sql = "UPDATE `employees` SET `compte_valid` = ? WHERE `id` = ?";

        $stmt = $db->prepare($sql);

        $stmt->execute([$t, $id]);
    }
}

if(  !function_exists('push_user_creation_notification') ){
    function push_user_creation_notification($user_id){
        $db = load_db();

        $title = 'creation demand';
        $description = 'some one created an account, please review it';

        $sql = "INSERT INTO `notifications` (`employee_id`, `title`, `description`,`url` ) VALUES (?, ?, ?, ?)";

        $stmt = $db->prepare($sql);

        try{
            $stmt->execute([$user_id, $title, $description, url('admin/employees_requests.php')]);
        }catch(PDOException $e){
            throw new Exception($e->getMessage());
        }
    }
}

if( !function_exists('get_grh_id') ){
    function get_grh_id(){
        $db = load_db();

        $sql = "SELECT `employees`.`id` FROM `employees` JOIN `roles` ON `employees`.`role_id` = `roles`.`id` WHERE `roles`.`nom` = ?";

        $stmt = $db->prepare($sql);

        try{
            $stmt->execute(['GRH']);
        }catch(PDOException $e){
            die($e->getMessage());
        }

        if( $stmt->rowCount() < 1 ){
            return null;
        }

        return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
    }
}