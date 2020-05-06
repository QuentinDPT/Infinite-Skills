<link rel="stylesheet" href="./src/styles/footer.css">
<footer class="border-top footer">
  <div class="footer-container">
    <p class="footer-text basic">Developed by chance<p>
    <form action="/RGPD" method="get" id="cgu" style="margin: auto">
        <input type="hidden" name="f" value="cgu">
        <p class="footer-link link" onclick="document.getElementById('cgu').submit()">read CGU</p>
    </form>
    <form action="/RGPD" method="get" id="privacy" style="margin: auto">
        <input type="hidden" name="f" value="privacy">
        <p class="footer-link link" onclick="document.getElementById('privacy').submit()">read Privacy Policy</p>
    </form>
  </div>
</footer>
