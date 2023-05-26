<?php 
require_once 'controllers/DBControllers.php';
require_once 'models/admin.php';

class admincontrol{
    protected $db;
    
    public function isUsernameExists($username) {
        $this->db = new DBController;

        if ($this->db->openConnection()) {
            $query = "SELECT * FROM atm_admin WHERE username = ?";
            $stmt = $this->db->connection->prepare($query);

            if ($stmt) {
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $this->db->closeConnection();
                    return true;
                } else {
                    $this->db->closeConnection();
                    return false;
                }
            } else {
                $_SESSION["errMsg"] = "Error in prepared statement";
                $this->db->closeConnection();
                return false;
            }
        } else {
            $_SESSION["errMsg"] = "Error in database connection";
            return false;
        }
    }

    public function isEmailExists($email) {
        $this->db = new DBController;
    
        if ($this->db->openConnection()) {
            $query = "SELECT email FROM atm_admin WHERE email = '$email'";
            $result = $this->db->connection->query($query);
    
            if ($result) {
                if ($result->num_rows > 0) {
                    $this->db->closeConnection();
                    return array(true, "Email already exists in the database");
                } else {
                    $this->db->closeConnection();
                    return array(false, "");
                }
            } else {
                $_SESSION["errMsg"] = "Error in query";
                $this->db->closeConnection();
                return array(false, "Error in query");
            }
        } else {
            $_SESSION["errMsg"] = "Error in database connection";
            return array(false, "Error in database connection");
        }
    }

    public function register(Admin $admin) {
        $this->db = new DBController;

        if ($this->db->openConnection()) {
            $query = "INSERT INTO atm_admin VALUES (NULL, ?, ?, ?, ?)";
            $stmt = $this->db->connection->prepare($query);

            if ($stmt) {
                $stmt->bind_param("ssss", $admin->name, $admin->username, $admin->email, $admin->password);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    session_start();
                    $_SESSION["id"] = $stmt->insert_id;
                    $_SESSION["name"] = $admin->name;
                    $_SESSION["username"] = $admin->username;
                    $_SESSION["email"] = $admin->email;
                    $_SESSION["password"] = $admin->password;

                    $this->db->closeConnection();
                    return true;
                } else {
                    $_SESSION["errMsg"] = "Something went wrong... try again later";
                    $this->db->closeConnection();
                    return false;
                }
            } else {
                $_SESSION["errMsg"] = "Error in prepared statement";
                $this->db->closeConnection();
                return false;
            }
        } else {
            $_SESSION["errMsg"] = "Error in database connection";
            return false;
        }
    }
}
?>