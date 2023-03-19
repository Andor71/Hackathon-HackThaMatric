</main>


<!-- Footer -->
<footer class="footer dark-mode bg-dark pt-5 pb-4 pb-lg-5" style="margin-top: -150px;">
    <div style="height: 158px;"></div>
    <div class="container pt-lg-4">
        <div class="row pb-5">
            <div class="col-lg-4 col-md-6">
                <div class="navbar-brand text-dark p-0 me-0 mb-3 mb-lg-4">
                    <img src="assets/img/web/logo.png" width="200" alt="SmartCard">
                </div>
                <p class="fs-sm text-light opacity-70 pb-lg-3 mb-4"><?php echo $footerText ?></p>
                <form class="needs-validation" id="newsletterSubscribe">
                    <label for="subscr-email" class="form-label"><?php echo $Newsletter ?></label>
                    <div class="input-group">
                        <input type="email" id="subscr-email" class="form-control rounded-start ps-5" in
                               placeholder="Your email" required="">
                        <i class="bx bx-envelope fs-lg text-muted position-absolute top-50 start-0 translate-middle-y ms-3 zindex-5"></i>
                        <div class="invalid-tooltip position-absolute top-100 start-0">Please provide a valid email
                            address.
                        </div>
                        <button type="submit"
                                class="btn btn-warning text-primary"><?php echo $newsletterButton ?></button>
                    </div>
                    <div class="pt-3">
                        <input type="checkbox" id="terms" class="form-check-input" required="">
                        <label for="terms" class="form-check-label"><?php echo $freeProfileTerms1 ?><a
                                    href="termeni-si-conditii"
                                    style="color: #fff;font-weight: bold;"> <?php echo $freeProfileTerms2 ?></a></label>
                    </div>
                </form>
            </div>
            <div class="col-xl-6 col-lg-7 col-md-5 offset-xl-2 offset-md-1 pt-4 pt-md-1 pt-lg-0">
                <div id="footer-links" class="row">
                    <div class="col-lg-4">
                        <h6 class="mb-2">
                            <a href="#useful-links" class="d-block text-dark dropdown-toggle d-lg-none py-2"
                               data-bs-toggle="collapse"><?php echo $footerLink1 ?></a>
                        </h6>
                        <div id="useful-links" class="collapse d-lg-block" data-bs-parent="#footer-links">
                            <ul class="nav flex-column pb-lg-1 mb-lg-3">
                                <li class="nav-item"><a href="termeni-si-conditii"
                                                        class="nav-link d-inline-block px-0 pt-1 pb-2">
                                        Termeni si conditii</a></li>
                                <li class="nav-item"><a href="politica-de-returnare"
                                                        class="nav-link d-inline-block px-0 pt-1 pb-2">Politica de
                                        anulare</a></li>

                                <li class="nav-item"><a href="politica-de-confidentialitate"
                                                        class="nav-link d-inline-block px-0 pt-1 pb-2">Politica de
                                        confidentialitate</a></li>

                            </ul>
                            <ul class="nav flex-column mb-2 mb-lg-0">
                                <li class="nav-item"><a href="contact" class="nav-link d-inline-block px-0 pt-1 pb-2">Contact</a>
                                </li>
                                <li class="nav-item"><a href="https://anpc.ro/"
                                                        class="nav-link d-inline-block px-0 pt-1 pb-2">ANPC</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-3">
                        <h6 class="mb-2">
                            <a href="#social-links" class="d-block text-dark dropdown-toggle d-lg-none py-2"
                               data-bs-toggle="collapse"><?php echo $footerLink2 ?></a>
                        </h6>
                        <div id="social-links" class="collapse d-lg-block" data-bs-parent="#footer-links">
                            <ul class="nav flex-column mb-2 mb-lg-0">
                                <li class="nav-item"><a href="https://www.facebook.com/prismasmartcard"
                                                        class="nav-link d-inline-block px-0 pt-1 pb-2">Facebook</a></li>
                                <li class="nav-item"><a href="https://www.linkedin.com/showcase/prismasmartcard/"
                                                        class="nav-link d-inline-block px-0 pt-1 pb-2">LinkedIn</a></li>
                                <!--                  <li class="nav-item"><a href="#" class="nav-link d-inline-block px-0 pt-1 pb-2">Twitter</a></li>-->
                                <li class="nav-item"><a href="https://www.instagram.com/prismasmartcard/"
                                                        class="nav-link d-inline-block px-0 pt-1 pb-2">Instagram</a>
                                </li>
                                <li class="nav-item"><a href="https://twitter.com/prismasmartcard/"
                                                        class="nav-link d-inline-block px-0 pt-1 pb-2">Twitter</a>
                                </li>
                                <li class="nav-item"><a href="https://www.youtube.com/channel/UCin3elSFJXPHXh_sZfbRG2Q"
                                                        class="nav-link d-inline-block px-0 pt-1 pb-2">Youtube</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5 pt-2 pt-lg-0">
                        <h6 class="mb-2"><?php echo $footerLink3 ?></h6>
                        <a href="mailto:hello@smart-card.io" class="fw-medium text-white">hello@smart-card.io</a>
                        <iframe frameborder="0" scrolling="no" height="150" width="150" src="https://digitalromania.ro/partnerbadge.php"></iframe>
                    </div>
                    
                </div>
            </div>
        </div>
        <p class="nav d-block fs-xs text-center text-md-start pb-2 pb-lg-0 mb-0">
            <span class="text-light opacity-50">© All rights reserved. Made by </span>
            <a class="nav-link d-inline-block p-0" href="https://prismasolutions.ro/" target="_blank" rel="noopener">Prisma
                Solutions</a>
        </p>
    </div>
</footer>


<!-- Back to top button -->
<a href="#top" class="btn-scroll-top" data-scroll>
    <span class="btn-scroll-top-tooltip text-muted fs-sm me-2">Top</span>
    <i class="btn-scroll-top-icon bx bx-chevron-up"></i>
</a>


<!-- Vendor Scripts -->
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>
<script src="assets/vendor/vanilla-tilt/dist/vanilla-tilt.min.js"></script>
<script src="assets/vendor/parallax-js/dist/parallax.min.js"></script>
<script src="assets/vendor/jarallax/dist/jarallax.min.js"></script>
<script src="assets/vendor/jarallax/dist/jarallax-element.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/img-comparison-slider/dist/index.js"></script>
<script src="assets/vendor/lightgallery.js/dist/js/lightgallery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lax.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="assets/js/theme.min.js"></script>


<script type="text/javascript">
    $("#newsletterSubscribe").submit(function (e) {
        e.preventDefault();
        var newsletterEmail = $("#subscr-email").val();
        var data = {
            'email': newsletterEmail,
        };
        $.ajax({
            url: 'includes/handlers/insert_newsletter.php',
            method: 'POST',
            contentType: 'application/json;charset=utf-8',
            data: JSON.stringify(data),
            success: function (data) {
                console.log(data);
                swal("Multumim", " Te vom contacta cu noutatile.", "success").then(
                    () => {
                        document.getElementById('top').scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                );
            },
            error: function (err) {
                swal("System error! Please contact us.", "error");
            }
        });
    });

    window.onload = function () {
        const preloader = document.querySelector('.page-loading');
        preloader.classList.remove('active');
        setTimeout(function () {
            preloader.remove();
        }, 1000);

        lax.init()

        lax.addDriver(
            "scrollY",
            function () {
                return document.documentElement.scrollTop;
            },
            { frameStep: 1 }
        );
        // Add animation bindings to elements
        lax.addElements(".smartcard-inner", {
            scrollY: {
                rotateY: [["elInY", "elCenterY"], [0, -180]],
                opacity: [["elInY", "elCenterY"], [0.8, 1]]
            }
        });
    }

</script>

</body>
</html>
