<?php
require_once ('../../config/conn.php');
class Users {
    protected $id;
    private $name;
    private $password;
    private $email;

    public function __construct()
    {
        $this->id = -1;
        $this->name = "";
        $this->email = "";
        $this->password= "";
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $newpassword = password_hash($password,PASSWORD_BCRYPT);
        $this->password = $newpassword;
    }

    public function saveToDB(PDO $conn):bool
    {
        if ($this->id == -1) {
            echo "<br>this is a new entry<br>";
            $txt = "INSERT INTO `Users` SET `name` = :name, `email` = :email, `password`= :password";
            $stmt = $conn->prepare($txt);
            $result = $stmt->execute([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password]);
            if ($result) {
                $this->setId($conn->lastInsertId());
                return true;
            }
        }else{                    //UPDATE Users SET `name` = "PESTEmaiName", `email` = "peste@mail.com", `password`= "cartof" WHERE `id`= 11
            $stmt = $conn->prepare("UPDATE Users SET `name` = :name, `email` = :email, `password`= :password WHERE `id`= :id");
            $result = $stmt->execute(
                [
                    'name' => $this->name,
                    'email'=>$this->email,
                    'password'=>$this->password,
                    'id'=>$this->id
                ]
            );
            if ($result==true){return true;}
        }
        return false;
    }

    public static function loadUserById(PDO $conn, int $id)
    {
        $txt = $conn->prepare("SELECT * FROM Users WHERE id=:id");
        $result=$txt->execute(['id'=>$id]);
        if ($result == true && $txt->rowCount()>0){
            $row = $txt->fetch(PDO::FETCH_ASSOC);  //fatches a row from a result set
            $loadedUser = new Users;
            $loadedUser->id = $row['id'];
            $loadedUser->name=$row['name'];
            $loadedUser->password=$row['password'];
            $loadedUser->email=$row['email'];
            return $loadedUser;
        }
        return null;
    }
    public static function loadAllUsers (PDO $conn):array{
        $loadAllUsers = [];
        $result= $conn->query("SELECT * FROM Users");
        if ($result !== false && $result->rowCount() > 0) {
            foreach ($result as $row){
                $loadedUser = new Users ();
                $loadedUser->id = $row ['id'];
                $loadedUser->name = $row ['name'];
                $loadedUser->email = $row['email'];
                $loadAllUsers[] = $loadedUser;
            }

        }
        return $loadAllUsers;
    }

    public function delete(PDO $conn){
        if ($this->id != -1){
            $txt=$conn->prepare("DELETE FROM Users WHERE `id` = :id");
            $result=$txt->execute(['id'=>$this->id]);
            if ($result == true){
                $this->id = -1;
                return true;
            }
        return false;
        }
        return true;
    }
}

$Users = new Users;


