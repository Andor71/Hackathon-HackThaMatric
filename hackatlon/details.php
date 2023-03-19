<?php
session_start();
$currentId = $_GET['id'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" media="screen" href="assets/css/theme.css">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Demo styles -->
    <style>
        .swiper {
            width: 100%;
            height: 100%;
        }

        .swiper-slide {
            margin-right: 0px !important;
        }

    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Features</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Pricing</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Dropdown link
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<?php
$card="";
$titleMovie="";
foreach($_SESSION['mymovvies'] as $cardsinfo) {
foreach ($cardsinfo->movies as $movies) {
    if ($movies->id == $currentId) {
        $genres = $movies->genres;
        $titleMovie=$movies->title;
        $genderstr = implode(", ", $genres); // Animation, Adventure, Comedy
//    echo $sub_string;
        $card = <<<DELIMITER
<section id="banner" class="clearfix" style="background: url('$movies->backdropURLs');">
    <div id="banner_content_wrapper">
        <div id="poster">
            <img src="$movies->poster" alt="Deadpool Movie Poster" class="featured_image">
            <img src="https://res.cloudinary.com/dw369yzsh/image/upload/v1470916845/play_button_ngnw1z.png" alt="Play Trailer" class="play_button" data-toggle="modal" data-target="#exampleModalCenter" onclick="redirect('$movies->youtubeLink')">
        </div>
        <div id="content">
            <h2 class="title">$movies->title</h2>
            <p class="description">$movies->overview</p>
            <p class="info">$movies->year<span>|</span>Minimum Year:$movies->minimumAge <span>|</span>$genderstr</p>
            <div class="spotify-embeds" id="music">
   
            </div>
        </div>
    </div>
    
</section>

DELIMITER;

    }
}

}
echo $card;
?>



<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/plugins/video/lg-video.min.js"></script>

<!-- Initialize Swiper -->
<script>
    window.onload = function() {
        // ide írhatja a függvényt, amelyet az oldal betöltődésekor futtatni akar
        var title = "<?php echo $titleMovie?>";
        requestMusic(title);
    }

    function requestMusic(title){
        var xhr = new XMLHttpRequest();
        var url = "http://localhost:8080/sound/get-soundtrack?movieTitle="+title;

        xhr.open("POST", url);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("Accept", "application/json");
        // xhr.setRequestHeader("Origin", "localhost");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);

                console.log(response);
                $.ajax({
                    url: 'show_music.php',
                    method: 'POST',
                    contentType: 'application/json;charset=utf-8',
                    data: JSON.stringify(response),
                    success: function (data) {
                        $("#music").html(data);
                    }


                });


            }
        };
        xhr.send();
    }

</script>
<script>
    function request(title){
        var xhr = new XMLHttpRequest();
        var url = "http://localhost:8080/stream/get-by-title?title="+title;

        xhr.open("GET", url);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("Accept", "application/json");
        // xhr.setRequestHeader("Origin", "localhost");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);

// console.log(response);
                $.ajax({
                    url: 'show_movie.php',
                    method: 'POST',
                    contentType: 'application/json;charset=utf-8',
                    data: JSON.stringify(response),
                    success: function (data) {
                        $("#cards").html(data);
                    }


                });


            }
        };
        xhr.send();
    }

</script>
<script>
    var swiper = new Swiper(".mySwiper", {
        slidesPerView: 3,
        spaceBetween: 30,
        freeMode: true,
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
    });
</script>
<script>
    function redirect(link) {
        window.location.href = link;
    }
</script>

</body>

</html>
