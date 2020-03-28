<?php
require_once("./Controllers/C_Mail.php") ;
require_once("./Models/User.php") ;
require_once("./Models/AccessDB.php") ;

class C_User {
    // Private ----------------------------------------------------------------
    /* GetBdd: Return a connection to a database
     *      Output:
     *          - AccessDB object: connected database
     */
    private static function GetBdd() {
        $bdd = new AccessDB();
        $bdd->connect();
        return $bdd;
    }
    /* GenerateUsers: Create a list of User objects from a list
     *      Input:
     *          - $users: list of users to transform into User objects
     *      Output:
     *          - array: list of User objects
     */
    private static function GenerateUsers($users) {
        $list = [];
        for ($i=0; $i < count($users); $i++) {
            $u = $users[$i];
            $list[] = new User(
                $u["Id"],
                $u["Name"],
                $u["Mail"],
                $u["Login"],
                $u["InscriptionDate"],
                $u["ExpirationDate"],
                $u["SubscriptionId"],
                $u["Avatar"]
            );
        }
        return $list;
    }


    // Public -----------------------------------------------------------------
    public static function UserResetPassword($user){
        $dest = $user->getMail() ; ;
        $sub = "Réinitialisation de votre mot de passe" ;
        $content = "Bonjour " . $user->getName() . ",\n\nVous avez demander récemment à changer votre mot de passe.\nVoici le code qu'il va faloir rentrer pour acceder à votre compte" ;

        return new Mail($dest, $sub, $content) ;
    }
    /* GetUsers: Get all users from database
     *      Output:
     *          - array: list of User objects
     */
    public static function GetUsers() {
        $bdd = C_User::GetBdd();
        $users = $bdd->select("SELECT * FROM User", []);
        return C_User::GenerateUsers($users);
    }
    /* GetUserById: Get user that match the id
     *      Input:
     *          - $id: User id
     *      Output:
     *          - User: User objects
     */
    public static function GetUserById($id) {
        $bdd = C_User::GetBdd();
        $users = $bdd->select("SELECT * FROM User WHERE Id = :id", ["id" => $id]);
        return C_User::GenerateUsers($users)[0];
    }

    public static function GetFollow($id) {
        $bdd = C_User::GetBdd();
        $users = $bdd->select("SELECT * FROM User WHERE Id IN (SELECT CreatorId FROM Follow WHERE UserId = :id)", ["id" => $id]);
        return C_User::GenerateUsers($users);
    }
    public static function GetCountFollowers($id) {
        $bdd = C_User::GetBdd();
        $count = $bdd->select("SELECT COUNT(CreatorId) FROM Follow WHERE UserId = :id", ["id" => $id]);
        return $count[0][0];
    }
    public static function Addfollow($userId, $creatorId){
      $bdd = C_User::GetBdd();
      $res = $bdd->insert("INSERT INTO Follow (UserId, CreatorId) VALUES (:user, :creator)", ["user" => $userId, "creator" => $creatorId]);
    }
    public static function RemoveFollow($userId, $creatorId){
      $bdd = C_User::GetBdd();
      $res = $bdd->delete("DELETE FROM Follow WHERE UserId = :user AND CreatorId = :creator", ["user" => $userId, "creator" => $creatorId]);
    }
    public static function Follow($userId, $creatorId){
      $bdd = C_User::GetBdd();
      $res = $bdd->select("SELECT 1 FROM Follow WHERE UserId = :user AND CreatorId = :creator", ["user" => $userId, "creator" => $creatorId]);
      return ($res) ? $res[0] : false;
    }
}
?>
