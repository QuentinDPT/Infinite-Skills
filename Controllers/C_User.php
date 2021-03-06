<?php
require_once($_SERVER['DOCUMENT_ROOT']."/Controllers/C_Mail.php") ;
require_once($_SERVER['DOCUMENT_ROOT']."/Models/User.php") ;
require_once($_SERVER['DOCUMENT_ROOT']."/Models/AccessDB.php") ;
require_once($_SERVER['DOCUMENT_ROOT']."/Controllers/C_Video.php") ;

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
    public static function GenerateUsers($users) {
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
                $u["Avatar"],
                $u["Description"]
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
    /* UserResetPassword: Send a reset-password mail to a user
     *      Input:
     *          - $user       : User object
     *          - $newPassword: New password string
     *      Output:
     *          Mail: Mail object
     */
    public static function UserResetPassword($user, $newPassword){
        $dest = $user->getMail() ; ;
        $sub = "Infinite skills - Password reset" ;
        $mailContent = "Hello " . $user->getName() . ",\n\nYou recenlty asked to change your password.\nHere is your new one: \n" . $newPassword . "\nOnce connected, we suggest you to create a new password." ;

        $headers  = "From: Infinite Skills <infinite.skills@quentin.depotter.fr>\r\n" ;
        $headers .= "MIME-Version: 1.0\r\n" ;
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n" ;

        $message = $mailContent;

        $newPassword = sha1(md5($newPassword)."WALLAH");

        // Change password into the db
        $bdd = C_User::GetBdd();
        $bdd->update("update User set Password = '" . $newPassword . "' where Id = " . $user->getId(), []);

        return new Mail($dest, $sub, $message, $headers) ;
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
    /* DeleteAccount: Delete an User from database
     *      Input:
     *          - $idUser: User id
     */
    public static function DeleteAccount($idUser){
        $bdd = C_User::GetBdd();
        $tables = array("Comment", "Follow", "See", "UserLike", "UserTheme");
        foreach($tables as $value){
            $bdd->delete("DELETE FROM $value WHERE UserId = :id", ["id" => $idUser]);
        }
        $bdd->delete("DELETE FROM Video WHERE OwnerId = :id", ["id" => $idUser]);
        $bdd->delete("DELETE FROM User WHERE Id = :id", ["id" => $idUser]);
    }
    /* GetUserById: Get user that match the id
     *      Input:
     *          - $id: User id
     *      Output:
     *          - User: User object
     */
    public static function GetUserById($id) {
        $bdd = C_User::GetBdd();
        $users = $bdd->select("SELECT * FROM User WHERE Id = :id", ["id" => $id]);
        return (!empty($users) ? C_User::GenerateUsers($users)[0] : null);
    }
    /* GetUserByLogin: Get user that match the given login
     *      Input:
     *          - $login: User's login
     *      Output:
     *          User: User object
     */
    public static function GetUserByLogin($login){
        $bdd = C_User::GetBdd();
        $users = $bdd->select("SELECT * FROM User WHERE Login = '$login'", []);
        $line = C_User::GenerateUsers($users) ;
        return (empty($line) ? null : $line[0] );
    }
    /* GetUserByMail: Get user that match the given mail
     *      Input:
     *          - $mail: User's mail
     *      Output:
     *          User: User object
     */
    public static function GetUserByMail($mail){
        $bdd = C_User::GetBdd();
        $users = $bdd->select("SELECT * FROM User WHERE Mail = :id", ["id" => $mail]);
        $line = C_User::GenerateUsers($users) ;
        return (empty($line) ? null : $line[0] );
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
    /* CreateUser: Add a user in database
     *      Input:
     *          - $login: User's login
     *          - $mail : User's mail
     *          - $pass : User's password
     */
    public static function CreateUser($login,$mail,$pass){
        $bdd = C_User::GetBdd();
        return $bdd->insert("INSERT INTO User (Name,Mail,Login,Password, SubscriptionId)
                              VALUES (:login,:mail,:login,:password,1)",
                              ["mail"=>$mail,"login"=>$login,"password"=>$pass]);


    }
    /* CreateNewCommentDom: Create a new html node to display a comment
     *      Input:
     *          - $userId : User Id
     *          - $videoId: Video Id
     *      Output:
     *          - string: html formatted string
     */
    public static function CreateNewCommentDom($userId, $videoId){
        $bdd = C_User::GetBdd();

        $comments = $bdd->select("SELECT * FROM Comment WHERE UserId = :user And VideoId = :video ORDER BY Id DESC LIMIT 1", ["user" => $userId, "video" => $videoId]);
        $c = C_Video::GenerateComments($comments)[0];

        $c_user = C_User::GetUserById($userId);
        // var_dumps($c_user);
        $avatar = $c_user->getAvatar();
        $id = $c_user->getId() ;
        $name = $c_user->getName();
        $commentDate =  $c->getDate();
        $content = str_replace("\\n", "</br>", $c->getContent()) ;
        $idComment = $c->getId();

        $dom = "<div class=\"comment-container\">
            <!-- User ========================================== -->
            <div class=\"col-lg-1 col-md-2 col-sm-2 col-3 pr-0 pl-0 comment-user\">
                <img class=\"comment-user-icon\" src='$avatar' alt=\"avatar\" id='$id' onclick=\"submitForm(this, 'userForm')\">
            </div>
            <!-- Text ========================================== -->
            <div class=\"col-lg-11 col-md-10 col-sm-10 col-9 pr-0 pl-0\">
                <div class=\"comment-text-container\">
                    <p class=\"comment-user-name basic\">$name • $commentDate</p>
                    <p class=\"comment-text basic\" id=\"$idComment\"> $content</p>
                </div>";
                /*if ($c->getNumberLines() > 3) {
                    $dom .= "<div class=\"comment-next\">
                        <span class=\"comment-button\" onclick=\"readMore(this, '" . $c->getId() . "')\">Read more</span>
                    </div>";
                }*/
            $dom .= "</div>
        </div>";
        return $dom;
    }
    /* EditDesc: Update the user channel's desctiption
     *      Input:
     *          - id:    User id
     *          - $text: New description
     */
    public static function EditDesc($id, $text) {
        $formatted = C_User::NormalizeString($text);
        $bdd = C_User::GetBdd();
        $update = $bdd->update("UPDATE User SET Description = '$formatted' WHERE Id = $id", []);
        if ($update === false) { echo "Error while executing request"; die(); }
    }
    /* UserOwnVideo: Check if a user already bought a video or not
     *      Input:
     *          - $idUser : User Id
     *          - $idVideo: Video Id
     *      Output:
     *          - Bool: true if they bought it, false otherwise
     */
    public static function UserOwnVideo($idUser, $idVideo) {
        $bdd = C_User::GetBdd();
        $res = $bdd->select("SELECT * FROM UserOwn WHERE VideoId = $idVideo AND UserId = $idUser", []);
        return (count($res) > 0);
    }
    /* UserOwnVideo: Check if a user already bought a video or not
     *      Input:
     *          - $idVideo: Video Id
     *          - $idUser : User Id
     *      Output:
     *          - Bool: true if operation is success, false otherwise
     */
    public static function AddPaidVideo($idVideo, $idUser) {
        $bdd = C_User::GetBdd();
        $insert = $bdd->insert("INSERT INTO UserOwn (VideoId, UserId, DatePurchase) VALUES ($idVideo, $idUser, CURRENT_DATE)", []);
        if ($insert === false) { echo "Error while executing request"; die(); }
    }
    /* GetPurchaseDate: Get purchase date of a video
     *      Input:
     *          - $idUser: User Id
     *          - $idVid : Video Id
     *      Output:
     *          - Date: Date at which video has been purchased
     */
    public static function GetPurchaseDate($idUser, $idVid) {
        $bdd = C_User::GetBdd();
        if (!C_User::UserOwnVideo($idUser, $idVid)) return "-1";

        $res = $bdd->select("SELECT DatePurchase FROM UserOwn WHERE VideoId = $idVid AND UserId = $idUser", []);
        return $res[0][0];
    }
    /* UpdateMail: Update the email of an user
     *      Input:
     *          - $id  : User Id
     *          - $mail: new mail
     *      Output:
     *          - Integer: 0 if success, otherwise 1;
     */
    public static function UpdateMail($id, $mail) {
        $bdd = C_User::GetBdd();
        $res = $bdd->update("UPDATE User SET Mail = :mail WHERE Id = $id", ["mail" => $mail]);
        return ($res ? 0 : 1);
    }
    /* CheckPass: Check if a user's password is correct
     *      Input:
     *          - $id  : User Id
     *          - $pass: Crypted user password
     *      Output:
     *          - Integer: 2 if password doesn't match, otherwise 0
     */
    public static function CheckPass($id, $pass) {
        $bdd = C_User::GetBdd();
        $res = $bdd->select("SELECT 1 FROM User WHERE Id = $id AND Password = :pass", ["pass" => $pass]);
        return (empty($res) ? 2 : 0);
    }
    /* EditAccount: Update information about user's account
     *      Input:
     *          - $id     : User Id
     *          - $name   : new name
     *          - $mail   : new mail
     *          - $pass   : crypted current password
     *          - $newpass: crypted new password
     *          - $url    : new thumbnail
     *      Output:
     *          - Integer: 1 if everything went well
     */
    public static function EditAccount($id, $name, $mail, $pass, $newPass, $url) {
        $bdd = C_User::GetBdd();
        if (C_User::CheckPass($id, $pass) == 2) return 2;
        $res = $bdd->update("UPDATE User SET Name = :name, Mail = :mail, Password = :pass, Avatar = :av WHERE Id = $id", ["name" => $name, "mail" => $mail, "pass" => ($newPass == null ? $pass : $newPass), "av" => $url]);
        return 1;
    }
    /* GetUserSubscription: Get subscription user purchased
     *      Input:
     *          - $id     : User Id
     *      Output:
     *          - Integer: Subscription id
     */
    public static function GetUserSubscription($id) {
        $bdd = C_User::GetBdd();
        return $bdd->select("SELECT SubscriptionId, ExpirationDate FROM User WHERE Id = $id", [])[0];
    }
}
?>
