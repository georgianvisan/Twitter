<?php
require_once "../../config/conn.php";
class Tweets
{
    private $id = -1;
    protected $userID ;
    private $text;
    private $creationDate;
    public function __construct()
    {
        $this->userID = "";
        $this->text = "";
        $this->creationDate= "";
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /*
     * @param int $id
     **/
    public function setId(int $id): void
    {
        $this->id = $id;
    }



    /**
     * @return string
     */
    public function getUserID(): string
    {
        return $this->userID;
    }

    /**
     * @param string $userID
     */
    public function setUserID(string $userID): void
    {
        $this->userID = $userID;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    /**
     * @param string $creationDate
     */
    public function setCreationDate(string $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    public function loadTweetByID (PDO $conn, int $id)
        {
            $txt = $conn->prepare("SELECT * FROM Tweets WHERE id=:id");
            $result=$txt->execute(['id'=>$id]);
        var_dump($result);
        if ($result == true && $txt->rowCount() > 0 ){
            $row = $txt->fetch(PDO::FETCH_ASSOC);  //fatches the next row from a result set
            $loadedResult = new Tweets();
            $loadedResult->id = $row['id'];
            $loadedResult->userID = $row['userID'];
            $loadedResult->text = $row['text'];
            $loadedResult->creationDate = $row['creationDate'];
            return $loadedResult;
            }
        return null;
        }



    public function loadAllTweetsByUserID (PDO $conn,$userID):array
    {
        $loadAllTweets = [];
        $result=$conn->query ('SELECT * FROM Tweets');
        //$txt = $conn->prepare("SELECT * FROM Tweets WHERE userID= :userID ");
        //$results = $txt->execute(['userID'=> $userID]);
        // $results=$txt->execute(['userID'=>$userID]);
        if ($result !== false && $result->rowCount()> 0){
            foreach($result as $row) {
                if ($row['userID'] == $userID) {
                    $loadedResult = new Tweets ();
                    $loadedResult->id = $row['id'];
                    $loadedResult->userID = $row['userID'];
                    $loadedResult->text = $row['text'];
                    $loadedResult->creationDate = $row['creationDate'];
                    $loadAllTweets [] = $loadedResult;
                }

            }
        }
        return $loadAllTweets;
    }


    public function loadAllTweets (PDO $conn):array
    {
        $loadAllTweets = [];
        $result=$conn->query ('SELECT * FROM Tweets');
        //$txt = $conn->prepare("SELECT * FROM Tweets WHERE userID= :userID ");
        //$results = $txt->execute(['userID'=> $userID]);
        // $results=$txt->execute(['userID'=>$userID]);
        if ($result !== false && $result->rowCount()> 0){
            foreach($result as $row) {

                    $loadedResult = new Tweets ();
                    $loadedResult->id = $row['id'];
                    $loadedResult->userID = $row['userID'];
                    $loadedResult->text = $row['text'];
                    $loadedResult->creationDate = $row['creationDate'];
                    $loadAllTweets [] = $loadedResult;


            }
        }
        return $loadAllTweets;
    }


    public function saveTweetDB(PDO $conn):bool
    {

        if ($this->userID == -1) {

            $txt = "INSERT INTO Tweets SET id=:id,userID=:userID, text:=text, creationDate=:creationDate";
            echo "ION";
            $stmt = $conn->prepare($txt);
            $result = $stmt->execute(
                [
                    'id'=>$this->id,
                    'userID' => $this->userID,
                    'text' => $this->text,
                    'creationDate' => $this->creationDate
                ]
            );
            if ($result == true) {
                $this->setId($conn->lastInsertId());
                return true;
            } else {
                $stmt = $conn->prepare("INSERT INTO Tweets SET id=:id,userID=:userID, text:=text, creationDate=:creationDate");
                $result = $stmt->execute([
                    'id' => $this->id,          // ELSE ?? should be no else.
                    'userID' => $this->userID,
                    'text' => $this->text,
                    'creationDate' => $this->creationDate,
                ]);
                if ($result == true) {
                    return true;
                }
            }
        }
        return false;
    }


   /* public function saveToDB(PDO $conn):bool
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
    }*/



}
$conn=connect();
$test = new Tweets($conn);
$test->setUserID(2);
$test->setText("ACESTA ESTE TEST TWEET TESTE");
$test->setCreationDate("2020-08-20");
var_dump($test);
echo "<br><br><strong>";
$test->saveTweetDB($conn);
echo "<br><br><strong>";
print_r($conn->lastInsertId());
