<footer class="border-top footer" style="position: static; width: 100%;bottom: 0;background-color: var(--white);">
  <div class="container" style="display: flex">
    <p style="margin: auto">Développé par chance<p>
    <form action="/RGPD" method="get" id="cgu" style="margin: auto">
        <input type="hidden" name="f" value="cgu">
        <p style="color: blue; text-decoration: underline; margin: auto" onclick="document.getElementById('cgu').submit()">read CGU</p>
    </form>
    <form action="/RGPD" method="get" id="privacy" style="margin: auto">
        <input type="hidden" name="f" value="privacy">
        <p style="color: blue; text-decoration: underline; margin: auto" onclick="document.getElementById('privacy').submit()">read Privacy Policy</p>
    </form>
  </div>
</footer>
