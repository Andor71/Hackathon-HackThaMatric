<!-- Back to top button -->
<a href="#top" class="btn-scroll-top" data-scroll>
    <span class="btn-scroll-top-tooltip text-muted fs-sm me-2">Top</span>
    <i class="btn-scroll-top-icon bx bx-chevron-up"></i>
</a>


<!-- Vendor Scripts -->
<script src="assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>
<script src="assets/vendor/vanilla-tilt/dist/vanilla-tilt.min.js"></script>
<script src="assets/vendor/jarallax/dist/jarallax.min.js"></script>
<script src="assets/vendor/jarallax/dist/jarallax-element.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/img-comparison-slider/dist/index.js"></script>
<script src="assets/vendor/lightgallery.js/dist/js/lightgallery.min.js"></script>
<!-- password validaiton -->
<!-- <script src="assets/vendor/validate-password-requirements/path/to/cdn/jquery.min.js"></script> -->

<script src="assets/js/theme.min.js"></script>


<script>
    const part = document.querySelector(".part");
    const parner = document.querySelector(".partner");
    const scrollContainer = document.querySelector(".scroll");

    const partWidth = part.clientWidth;

    scrollContainer.style.width = `${partWidth}px`;
    scrollContainer.style.animationName = "scroll";

    const partClons = [];

    const updatePartCount = _.throttle(function () {
        const parnerWidth = parner.clientWidth;
        const cloneCount = Math.floor(parnerWidth / partWidth);

        if (cloneCount > partClons.length) {
            const addCount = cloneCount - partClons.length;

            [...Array(addCount)].forEach(() => {
                const newNode = part.cloneNode(true);
                partClons.push(newNode);
                scrollContainer.appendChild(newNode);
            });
        }

        if (cloneCount < partClons.length) {
            const removeCount = partClons.length - cloneCount;

            [...Array(removeCount)].forEach(() => {
                const oldNode = partClons.pop();
                scrollContainer.removeChild(oldNode);
            });
        }
    }, 200);

    updatePartCount();

    window.addEventListener("resize", updatePartCount);

</script>

</body>
</html>
