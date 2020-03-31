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
    /* NormalizeString: Format a string to match database requirements
     *      Input:
     *          - $string: string to format
     *      Ouptut:
     *          - string: formatted string
     */
    private static function NormalizeString($string) {
        $string = htmlentities($string);
        $string = preg_replace("/\n/i", "</br>", $string);
        $string = preg_replace("/\'/i", "''", $string);
        return $string;
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
    /* GetFollow: Get followed creators
     *      Input:
     *          - $id: User id
     *      Output:
     *          - array: array of User objects
     */
    public static function GetFollow($id) {
        $bdd = C_User::GetBdd();
        $users = $bdd->select("SELECT * FROM User WHERE Id IN (SELECT CreatorId FROM Follow WHERE UserId = :id)", ["id" => $id]);
        return C_User::GenerateUsers($users);
    }
    /* GetCountFollowers: Get the number of followers for a user
     *      Input:
     *          - $id: User id
     *      Output:
     *          - int: number of followers
     */
    public static function GetCountFollowers($id) {
        $bdd = C_User::GetBdd();
        $count = $bdd->select("SELECT * FROM Follow WHERE CreatorId = :id", ["id" => $id]);
        return count($count);
    }
    /* AddComment: Add a comment to a video
     *      Input:
     *          - $idUser : User id
     *          - $idVideo: Video id
     *          - $content: text of the comment
     */
    public static function AddComment($idUser, $idVideo, $content) {
        $bdd = C_User::GetBdd();
        $content = C_User::NormalizeString($content);
        $req = "INSERT INTO Comment (VideoId, UserId, Content)
                VALUES ($idVideo, $idUser, '$content')";
        $res = $bdd->insert($req, []);
        if ($res === false) { echo "Error while executing request"; die(); }
    }
    /* AddFollower: Add a follower to a User
     *      Input:
     *          - $idOwner: Creator id
     *          - $idUser : User id
     */
    public static function AddFollower($idOwner, $idUser) {
        $bdd = C_User::GetBdd();

        // Check if the person is already following
        $reqS = "SELECT * FROM Follow WHERE UserId = $idUser AND CreatorId = $idOwner";
        $resS = $bdd->select($reqS, []);

        $reqI = "";
        $resI = null;
        // If the user already follow the creator: delete entry to unfollow
        if (count($resS) > 0) {
            $reqI = "DELETE FROM Follow WHERE UserId = $idUser AND CreatorId = $idOwner";
            $resI = $bdd->delete($reqI, []);
        }
        // Else insert to follow
        else {
            $reqI = "INSERT INTO Follow (UserId, CreatorId) VALUES ($idUser, $idOwner)";
            $resI = $bdd->insert($reqI, []);
        }
        if ($resI === false) { echo "Error while executing request"; die(); }
    }
    /* AddLike: Add a like to a Video
     *      Input:
     *          - $idVideo: Video id
     *          - $idUser : User id
     */
    public static function AddLike($idVideo, $idUser) {
        $bdd = C_User::GetBdd();
        // Check if the person already liked the video
        $reqS = "SELECT * FROM UserLike WHERE UserId = $idUser AND VideoId = $idVideo";
        $resS = $bdd->select($reqS, []);

        $reqI = "";
        $resI = null;
        // If the user already liked: delete entry to unlike
        if (count($resS) > 0) {
            $reqI = "DELETE FROM UserLike WHERE UserId = $idUser AND VideoId = $idVideo";
            $resI = $bdd->delete($reqI, []);
        }
        // Else insert to like
        else {
            $reqI = "INSERT INTO UserLike (VideoId, UserId) VALUES ($idVideo, $idUser)";
            $resI = $bdd->insert($reqI, []);
        }
        if ($resI === false) { echo "Error while executing request"; die(); }
    }
    /* AddSee: Add a View to a Video
     *      Input:
     *          - $idVideo: Video id
     *          - $idUser : User id
     */
    public static function AddSee($idVideo, $idUser) {
        $bdd = C_User::GetBdd();

        // Check if the person already saw the video
        $reqS = "SELECT * FROM See WHERE UserId = $idUser AND VideoId = $idVideo";
        $resS = $bdd->select($reqS, []);

        // If the user has not already seen it
        if (count($resS) < 1) {
            $bdd->insert("INSERT INTO See (UserId, VideoId) VALUES ($idUser, $idVideo)", []);
        }

    }
    /* GetFollowByOwnerAndUser: check if a user follows or not a creator
     *      Input:
     *          - $idOwner: Creator id
     *          - $idUser : User id
     *      Ouptut:
     *          - bool: boolean
     */
    public static function GetFollowByOwnerAndUser($idOwner, $idUser) {
        $bdd = C_User::GetBdd();
        $res = $bdd->select("SELECT * FROM Follow WHERE UserId = $idUser AND CreatorId = $idOwner", []);
        return (count($res) > 0 ? true : false);
    }
    /* GetLikeByVideoAndUser: check if a user liked or not a video
     *      Input:
     *          - $idVideo: Video id
     *          - $idUser : User id
     *      Ouptut:
     *          - bool: boolean
     */
    public static function GetLikeByVideoAndUser($idVideo, $idUser) {
        $bdd = C_User::GetBdd();
        $res = $bdd->select("SELECT * FROM UserLike WHERE VideoId = $idVideo AND UserId = $idUser", []);
        return (count($res) > 0 ? true : false);
    }
}
?>
