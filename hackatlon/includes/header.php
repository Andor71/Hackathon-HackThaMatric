
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <title><?php echo $page_title ?></title>
    <!-- Viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon and Touch Icons -->
    <link rel="icon" type="image/png" href="assets/img/web/favicon.png">
    <link rel="mask-icon" href="assets/img/web/favicon.png" color="#00838d">
    <link rel="shortcut icon" href="assets/img/web/favicon.png">
    <meta name="msapplication-TileColor" content="#00838d">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- Vendor Styles -->
    <link rel="stylesheet" media="screen" href="assets/vendor/boxicons/css/boxicons.min.css"/>
    <link rel="stylesheet" media="screen" href="assets/vendor/swiper/swiper-bundle.min.css"/>
    <link rel="stylesheet" media="screen" href="assets/vendor/lightgallery.js/dist/css/lightgallery.min.css">
    <!-- password validation -->
    <link rel="stylesheet" href="as sets/vendor/validate-password-requirements/css/jquery.passwordRequirements.css"/>
    <!-- country code -->
    <link rel="stylesheet" href="assets/vendor/intl-tel-input-master/build/css/intlTelInput.css">
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"
            integrity="sha384-nvAa0+6Qg9clwYCGGPpDQLVpLNn0fRaROjHqs13t4Ggj3Ez50XnGQqc/r8MhnRDZ"
            crossorigin="anonymous">
    </script>
    <!-- Main Theme Styles + Bootstrap -->
    <link rel="stylesheet" media="screen" href="assets/css/theme.css">
    <!-- <link rel="stylesheet" href="css/jquery.passwordRequirements.css" /> -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <!-- <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet"> -->

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,100;0,200;0,300;0,600;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,100;0,200;0,300;0,600;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet"> -->
    <!-- Meta Pixel Code -->
    <noscript><img height="1" width="1" style="display:none"
                   src="https://www.facebook.com/tr?id=458174016529550&ev=PageView&noscript=1"
        /></noscript>
    <!-- End Meta Pixel Code -->

    <meta name="facebook-domain-verification" content="mkmvp1bqoexaftjt2gw88o2c4bahxz" />

    <!-- Google tag (gtag.js) -->
    <!--<script async src="https://www.googletagmanager.com/gtag/js?id=G-V9R2V36YEG"></script>-->
    <!--<script>-->
    <!--    window.dataLayer = window.dataLayer || [];-->
    <!--    function gtag(){dataLayer.push(arguments);}-->
    <!--    gtag('js', new Date());-->

    <!--    gtag('config', 'G-V9R2V36YEG');-->
    <!--</script>-->
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-TD2GC90ZFK"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-TD2GC90ZFK');
    </script>
    <!-- Theme mode -->
    <script>
        let mode = window.localStorage.getItem('mode'),
            root = document.getElementsByTagName('html')[0];
        if (mode !== undefined && mode === 'dark') {
            root.classList.add('dark-mode');
        } else {
            root.classList.remove('dark-mode');
        }
    </script>
    <meta name="google-site-verification" content="MY98Tt_MAyg6XHu32R4s4G7YY14J6dvWdJ-B6YId1yQ"/>
</head>


<!-- Body -->
<body>


<!-- Page loading spinner -->
<div class="page-loading active">
    <div class="page-loading-inner">
        <div class="page-spinner"></div>
        <span>Loading...</span>
    </div>
</div>


<!-- Page wrapper for sticky footer -->
<!-- Wraps everything except footer to push footer to the bottom of the page if there is little content -->
<main class="page-wrapper">


    <!-- Navbar -->
    <!-- Remove "navbar-sticky" class to make navigation bar scrollable with the page -->
    <header class="header navbar navbar-expand-lg <?php echo $navbarColor; ?> position-absolute navbar-sticky" id="top">
        <div class="container px-3">
            <a href="./" class="navbar-brand pe-3">
                <img src="assets/img/web/logo.png" class="logo-white" width="130" alt="Prisma NFC cards">
                <img src="assets/img/web/logo-dark.png" class="logo-dark" width="130" alt="Prisma NFC cards">
            </a>
            <div class="d-flex">
                <a href="cart" class="nav-link d-block d-lg-none" style="position: relative!important;">
                    <i class='bx bx-cart' style="font-size:24px;"></i>
                    <div class="navbar-icon-link-badge" id="cart-counter-phone">
                        <?php calculate_the_cart(); ?>
                        <?php get_cart_item_nr() ?>
                    </div>
                </a>
                <div id="navbarNav" class="offcanvas offcanvas-end">
                    <div class="offcanvas-header border-bottom">
                        <h5 class="offcanvas-title">Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav mb-2 mb-lg-0">

                            <li class="nav-item dropdown">
                                <?php
                                $selected_languege_en_show = "d-none";
                                $selected_languege_ro_show = "d-none";
                                $selected_languege_hu_show = "d-none";
                                if ($_SESSION['lan'] == "ro") {
                                    $selected_languege_ro_show = "d-block";
                                } else if ($_SESSION['lan'] == "hu") {
                                    $selected_languege_hu_show = "d-block";
                                } else {
                                    $selected_languege_en_show = "d-block";
                                }
                                ?>
                                <a href="#" class="nav-link dropdown-toggle <?php echo $selected_languege_en_show ?>"
                                   data-bs-toggle="dropdown"><img alt="Image placeholder"
                                                                  src="assets/img/icons/flags/us.svg"
                                                                  style="width: 20px;">
                                    <span class="d-lg-inline-block">&nbsp;EN</span></a>
                                <a href="#" class="nav-link dropdown-toggle <?php echo $selected_languege_ro_show ?>"
                                   data-bs-toggle="dropdown"><img alt="Image placeholder"
                                                                  src="assets/img/icons/flags/ro.svg"
                                                                  style="width: 20px;">
                                    <span class="d-lg-inline-block">&nbsp;RO</span></a>
                                <a href="#" class="nav-link dropdown-toggle <?php echo $selected_languege_hu_show ?>"
                                   data-bs-toggle="dropdown"><img alt="Image placeholder"
                                                                  src="assets/img/icons/flags/hu.svg"
                                                                  style="width: 20px;">
                                    <span class="d-lg-inline-block">&nbsp;HU</span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo $current_url ?>/en" class="dropdown-item"><img
                                                    alt="Image placeholder" src="assets/img/icons/flags/us.svg"
                                                    style="width: 20px;"><span>&nbsp;EN</span></a></li>
                                    <li><a href="<?php echo $current_url ?>/ro" class="dropdown-item"><img
                                                    alt="Image placeholder" src="assets/img/icons/flags/ro.svg"
                                                    style="width: 20px;"><span>&nbsp;RO</span></a></li>
                                    <li><a href="<?php echo $current_url ?>/hu" class="dropdown-item"><img
                                                    alt="Image placeholder" src="assets/img/icons/flags/hu.svg"
                                                    style="width: 20px;"><span>&nbsp;HU</span></a></li>

                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="shop" class="nav-link"><?php echo $menu2 ?></a>
                            </li>

                            <li class="nav-item">
                                <a href="contact" class="nav-link"><?php echo $menu3 ?></a>
                            </li>
                            <li class="nav-item d-none d-lg-block" style="">
                                <a href="cart" class="nav-link" style="position: relative!important;">
                                    <i class='bx bx-cart' style="font-size:24px;"></i>
                                    <div class="navbar-icon-link-badge" id="cart-counter-desktop">
                                        <?php calculate_the_cart(); ?>
                                        <?php get_cart_item_nr() ?>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item d-none d-lg-block">
                                <a href="login" class="btn btn-outline-warning">
                                    <?php echo $menu4 ?>
                                </a>
                            </li>
                            <li class="nav-item d-none d-lg-block ms-3">
                                <a href="company/login2.php" class="btn btn-warning shadow-primary">
                                    Company
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="offcanvas-footer border-top">
                        <a href="login" class="btn btn-primary w-100">
                            <i class="bi bi-box-arrow-in-right" style="font-size:20px; padding-right:7px;"></i>
                            <?php echo $menu4 ?>
                        </a>
                        <a href="company/login2.php" class="btn btn-warning mt-3 w-100">
                            Company
                        </a>
                    </div>

                </div>
                <button type="button" class="navbar-toggler" data-bs-toggle="offcanvas" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </header>
