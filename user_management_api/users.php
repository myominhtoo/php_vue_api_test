<?php
    require("./database/DB.php");
    
    // header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Origin: http://localhost:8080");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); 
    // header("Content-Type: application/json");
    header("Access-Control-Allow-Headers: *");

   
    if(isset($_GET['action'])){
        $action = $_GET['action'];

       $id = isset($_GET['id']) ? $_GET['id'] : "";
       $name = isset($_GET['name']) ? $_GET['name'] : "";   
       $email = isset($_GET['email']) ? $_GET['email'] : "";

        switch($action){
            case "readUsers" : readUsers();break;
            case "readUser" : readUser($id);break;
            case "insert" : insertUser($name,$email);break;
            case "delete" : deleteUser($id);break;
            case "update" : updateUser($id , $name , $email );break;
            default : echo json_encode(["error"=>true,"msg"=>"Not Found Method!"]);
        }
    }

    function readUsers(){
        global $db;
        $query = "SELECT * From users ";
        $stm = $db->prepare($query);
        $stm->execute();
        echo json_encode($stm->fetchAll());
    }

    function readUser(int|string $id ){
        global $db;
        $query = "SELECT * FROM users WHERE id = $id";
        $stm = $db->prepare($query);
        $stm->execute();
        $user = $stm->fetchObject();
       if($user){
            echo json_encode($user);
       }else{
           echo json_encode(["error"=>true,"msg"=>"User Not Found!"]);
       }
    }

    function insertUser(string $name , string $email){
        global $db;
        $query = "SELECT * FROM users WHERE email = :email";
        $stm = $db->prepare($query);
        $stm->execute([
            ":email" => $email,
        ]);
        $user = $stm->fetch();
	
        if(empty($user)){
            $query = "INSERT INTO users (name , email) VALUES(:name,:email)";
                $stm = $db->prepare($query);
                $status = $stm->execute([
                        ":name" => $name,
                        ":email" => $email,
                    ]);
                if($status){
                        echo json_encode(["msg"=>"Successfully Added","error" => false]);
                }else{
                        echo json_encode(["msg"=>"Duplicate values!" , "error" => true]);
                }
        }else{
            echo json_encode(["msg"=>"Duplicate Email!","error" => true]);
        }
    }

    function deleteUser(int|string $id){
        global $db;
        $query = "DELETE FROM users WHERE id = $id";
        $stm = $db->prepare($query);
        $status = $stm->execute();
        if($status){
            echo json_encode(["msg"=>"Successfully Deleted","error" => false]);
        }else{
            echo json_encode(["msg" => "Failed deleting!" , "error" => true]);
        }
    }

    function updateUser(int $id,string $name , string $email){
        global $db; 

        $query = "SELECT * FROM users WHERE email = :email";
        $stm = $db->prepare($query);
        $stm->execute([
            ":email" => $email,
        ]);
        $findWithEmail = $stm->fetch();

        $findWithId = findById($id);

        if((empty($findWithEmail)) || (!empty($findWithEmail) && $email === $findWithId->email)){
            $query = "UPDATE users SET name = :name , email = :email WHERE id = :id";
            $stm = $db->prepare($query);
            $status = $stm->execute([
                ":name" => $name,
                ":email" => $email,
                ":id" => $id
            ]);

            if($status){
                echo json_encode(["msg" => "Successfully Updated!" , "error" => false]);
            }else{
                echo json_encode(["msg" => "Unknow Error!!" , "error" => true]);
            }
        }else{
            echo json_encode(["msg" => "Duplicate Email!!" , "error" => true]);
        }
    }

    //to find with id
    function findById($id){
        global $db;

        $stm = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stm->execute([
            ":id" => $id
        ]);
        return $stm->fetch();
    }
