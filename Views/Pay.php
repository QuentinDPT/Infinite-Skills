<?php
require_once("./Controllers/C_Video.php");
$video = C_Video::GetVideoById($_GET['v']);
?>
<form class="" action="/paid/" method="post">
    <script id="btnPay"
        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
        data-key="pk_test_joErBT5GSf5MZ2jgPK7p0KaS00du3bmANx"
        data-amount="<?php echo $video->getPrice() * 100; ?>"
        data-name="Infinite Subscription"
        data-description="Pay to access Video"
        data-image="/src/img/infinite-logo.jpg"
        data-locale="auto">
    </script>
    <input type="hidden" name="idVideo" value="<?php echo $video->getId(); ?>">
</form>
<script type="text/javascript">
    document.body.onload = () => {
        document.getElementsByClassName("stripe-button-el")[0].style.display = "none";
        document.getElementsByClassName("stripe-button-el")[0].click();

    };
</script>
