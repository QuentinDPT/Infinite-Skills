<?php
require_once("./Controllers/C_Video.php");
$video = C_Video::GetVideoById($_GET['v']);
$_SESSION["IdVideo"] = $video->getId();
?>
<form class="" action="paid/" method="post">
    <script id="btnPay"
        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
        data-key="pk_test_joErBT5GSf5MZ2jgPK7p0KaS00du3bmANx"
        data-amount="<?php echo $video->getPrice() * 100; ?>"
        data-name="Infinite Subscription"
        data-description="Pay to access Video"
        data-image="/src/img/infinite-logo.jpg"
        data-locale="auto"
        data-success-url="/yolo/">
    </script>
</form>
<script type="text/javascript">
    document.body.onload = () => {
        document.getElementsByClassName("stripe-button-el")[0].style.display = "none";
        document.getElementsByClassName("stripe-button-el")[0].click();
    };
</script>
