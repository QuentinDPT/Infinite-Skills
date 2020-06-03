<?php
require_once("./Models/AccessDB.php");
require_once("./Models/Subscription.php");

class C_Subscription {
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

    /* GenerateVideos: Create a list of Video objects from a list
     *      Input:
     *          - $videos: list of videos to transform into Video objects
     *      Output:
     *          - array: list of Video objects
     */
    public static function GenerateSubscription($sub) {
        $list = [];
        for ($i=0; $i < count($sub); $i++) {
            $v = $sub[$i];
            $list[] = new Subscription(
                $v["Id"],
                $v["Name"],
                $v["Price"],
                $v["Offer"]
            );
        }
        return $list;
    }

    // Public -----------------------------------------------------------------
    /* GetVideos: Get all videos from database
     *      Output:
     *          - array: list of Video objects
     */
    public static function GetSubscriptionById($id) {
        $bdd = C_Subscription::GetBdd();
        $sub = $bdd->select("SELECT * FROM Subscription where Id = :id", ["id" => $id]);
        return C_Subscription::GenerateSubscription($sub)[0];
    }

    // Public -----------------------------------------------------------------
    /* GetVideos: Get all videos from database
     *      Output:
     *          - array: list of Video objects
     */
    public static function GetAllSubscription() {
        $bdd = C_Subscription::GetBdd();
        $sub = $bdd->select("SELECT * FROM Subscription", []);
        return C_Subscription::GenerateSubscription($sub);
    }
    public static function UpdateSubscription($idSub, $idUser) {
        $bdd = C_Subscription::GetBdd();
        $date = $bdd->select("SELECT DATE_ADD(CURRENT_DATE, INTERVAL (SELECT Duration FROM subscription WHERE Id = $idSub) DAY) AS d", [])[0]['d'];
        $res = $bdd->update("UPDATE User SET SubscriptionId = $idSub, ExpirationDate = '$date' WHERE Id = $idUser", []);

        if ($idSub == '4') {

        }
    }
    public static function HadTrial($id) {
        $bdd = C_Subscription::GetBdd();
        $res = $bdd->select("SELECT * FROM UserTrial WHERE UserId = $id", []);
        return count($res) > 0;
    }
    public static function AddUserTrial($idUser, $idSub) {
        $bdd = C_Subscription::GetBdd();
        $bdd->insert("INSERT INTO UserTrial (UserId, LimitDate) VALUES ($idUser, DATE_ADD(CURRENT_DATE, INTERVAL (SELECT Offer FROM Subscription WHERE Id = $idSub) DAY))", []);
    }
    public static function TrialHasEnded($idUser) {
        $bdd = C_Subscription::GetBdd();
        if (C_Subscription::HadTrial($idUser)) {
            $res = $bdd->select("SELECT CURRENT_DATE > LimitDate FROM UserTrial WHERE UserId = $idUser AND Reminded < 1", []);
            if (count($res) > 0 && $res[0][0] == "1") {
                $_SESSION["TrialEnded"] = true;
                return true;
            }
        }
        return false;
    }
    public static function TrialReminded($id) {
        $bdd = C_Subscription::GetBdd();
        $bdd->update("UPDATE UserTrial SET Reminded = 1 WHERE UserId = $id", []);
        $bdd->update("UPDATE User SET SubscriptionId = 1 WHERE Id = $id", []);
    }
}
