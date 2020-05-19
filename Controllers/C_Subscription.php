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
                $v["Offer"],
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
}
?>
