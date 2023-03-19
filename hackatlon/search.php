<?php
session_start();
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
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="search.php">Search</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">About</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
<body>
<div id="preloader">
    <div id="loader"></div>
</div>

<!--header-->
<section class="" style="height: 40vh; background-image: url('images/image.png');background-repeat: no-repeat; background-position: center; background-size: cover;">
    <div class="customcontainer h-100" style="z-index: 2;position: relative;">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 textsearch">
                <h1 class="textwhite">Unlimited movies, TV shows, and more.</h1>

                <form id="my-form" class="d-none d-sm-flex mb-5 w-100">
                    <div class="d-flex justify-content-center align-items-center w-100">
                        <div class="input-group d-block d-sm-flex input-group-lg me-3 w-50">
                            <input type="text" class="form-control w-50" name="title" placeholder="Search..." style="border-top-left-radius: 16px;border-bottom-left-radius: 16px;">
                            <select class="form-control w-25" required name="switch">
                                <option value="" selected disabled>Type</option>
                                <option value="1">Movie</option>
                                <option value="2">Music</option>
                            </select>
                            <select class="form-control w-25" style="border-top-right-radius: 16px;border-bottom-right-radius: 16px;" required name="region">
                                <option value="0" selected>Country</option>
                                <option value="hu">Hu</option>
                                <option value="ro">Ro</option>
                                <option value="us">Us</option>
                                <option value="du">Du</option>
                                <option value="uk">Uk</option>


                            </select>
                        </div>
                        <button type="submit" class="btn btn-icon btn-primary btn-lg" style="margin-left: 5px;border-radius: 16px; background-color: #E64F4C;border: 2px solid #E64F4C;">
                            <i class="bx bx-search"></i>
                        </button>
                    </div>
                </form>

            </div>

        </div>
    </div>

</section>
<section style="">
    <div class="partner">
        <div class="scroll">
            <div class="part">
                <div class="box" style="background-image: url('images/logos/1.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                <div class="box" style="background-image: url('images/logos/2.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                <div class="box" style="background-image: url('images/logos/3.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                <div class="box" style="background-image: url('images/logos/4.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                <div class="box" style="background-image: url('images/logos/5.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
            </div>
            <div class="part">
                <div class="box" style="background-image: url('images/logos/1.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                <div class="box" style="background-image: url('images/logos/2.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                <div class="box" style="background-image: url('images/logos/3.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                <div class="box" style="background-image: url('images/logos/4.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                <div class="box" style="background-image: url('images/logos/5.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
            </div>
            <div class="part">
                <div class="box" style="background-image: url('images/logos/1.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                <div class="box" style="background-image: url('images/logos/2.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                <div class="box" style="background-image: url('images/logos/3.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                <div class="box" style="background-image: url('images/logos/4.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                <div class="box" style="background-image: url('images/logos/5.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
            </div>
            <div class="part">
                <div class="box" style="background-image: url('images/logos/1.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                <div class="box" style="background-image: url('images/logos/2.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                <div class="box" style="background-image: url('images/logos/3.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                <div class="box" style="background-image: url('images/logos/4.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
                <div class="box" style="background-image: url('images/logos/5.png');background-repeat: no-repeat; background-position: center; background-size: cover;"></div>
            </div>


        </div>
    </div>
</section>
<!--movies-->
<section class="customcontainer">
    <div>
        <div class="pt-3" id="cards">
            <?php
            $card="";
            if(isset($_SESSION['mymovvies'])){
                foreach($_SESSION['mymovvies'] as $cardsinfo) {
                    if($cardsinfo->streaming == null)
                    {
                        $cardsinfo->streaming = "No streaming";
                    }

                    $card .= <<<DELIMITER
<h1>$cardsinfo->streaming <i class='bx bx-chevron-right' style=""></i></h1>
                <div class="row swiper mySwiper">
                    <div class="swiper-wrapper" >
DELIMITER;
                    foreach ($cardsinfo->movies as $movies) {
                        $subArray = $movies->streamingInfoDto;
                        $sub_string="";
                        foreach ($subArray->streamingDto as $subtitle)
                        {
                            if($subtitle->name == $cardsinfo->streaming)
                            {
                                $sub_string = implode(', ', $subtitle->sub);
                            }
                        }
                        $subStr = "";
                        if(strlen($sub_string)>0)
                        {
                        $subStr=<<<DELIMITER
        <span>Subtitles:</span>
            <br>
            <span style="color:#7c7c7c;font-size: 13px;">$sub_string</span>
DELIMITER;
                        }

                        $card .= <<<DELIMITER
<div class="col-3 swiper-slide">
    <div class="moviecard">
        <div class="cardimage" style="background-image: url($movies->poster);">

        </div>
        <div class="cardtext p-3">
            <span style="font-size: 30px;">$movies->title</span>
            <br>
            $subStr
            <a class="btn btn-custom-outline" href="details.php?id=$movies->id">More Details</a>
        </div>
        <!--                        <div class="imagebutton"><i class='bx bx-play'></i></div>-->
    </div>
</div>
DELIMITER;

                        $subtitlesStr = "";
//foreach($streamingName->sub as $subtitle){
//    $subtitlesStr=",";
//}


//    echo $sub_string;


                    }
                    $card .= <<<DELIMITER
</div>
</div>
<hr>
DELIMITER;
                    echo $card;
                }

            }

                ?>

            </div>
        <hr>
    </div>


</section>
<!-- Swiper -->



<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    window.addEventListener("load", function(){
        var preloader = document.getElementById("preloader");
        preloader.style.display = 'none';
    })
</script>
<script>
    function start(){
        var preloader = document.getElementById("preloader");
        preloader.style.display = 'block';
    }
</script>
<script>
    function end(){
        var preloader = document.getElementById("preloader");
        preloader.style.display = 'none';
    }
</script>
<script>

    const form = document.querySelector('#my-form');
    start()
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const formData = new FormData(form);
        const title = formData.get('title');
        const category = formData.get('switch');
        const region = formData.get('region');

        if(category==1)
        {
            if(region!=0)
            {
                requestwithregion(title,region)
            }else{
                request(title)
            }

        }else{
            requestMusic(title);
        }
    });
</script>

<!-- Initialize Swiper -->
<script>
    function requestMusic(title){
        start()
        var xhr = new XMLHttpRequest();
        // http://localhost:8080/music/get-by-music?musicTitle=Highway to hell
        var url = "http://localhost:8080/music/get-by-music?musicTitle="+title;

        xhr.open("POST", url);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("Accept", "application/json");
        // xhr.setRequestHeader("Origin", "localhost");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                console.log(response);
                response.forEach(function(item, index) {
                    if (index < response.length ) {
                        end()
                        // csak akkor hajtjuk végre a műveleteket, ha az elem nem az utolsó
                        console.log(item);
                        end();
                        requestwithregionappend(item,"hu");
                        // itt lehet végezni a kívánt műveleteket az elemmel
                    }
                });
// console.log(response);

//                 $.ajax({
//                     url: 'show_movie_from_music.php',
//                     method: 'POST',
//                     contentType: 'application/json;charset=utf-8',
//                     data: JSON.stringify(response),
//                     success: function (data) {
//                         $("#cards").html(data);
//                     }
//
//
//                 });


            }
        };
        xhr.send();
    }

</script>
<script>
    function requestwithregionappend(title,region){
        start()
        var xhr = new XMLHttpRequest();
        var url = "http://localhost:8080/stream/get-by-title?title="+title+"&country="+region;

        xhr.open("GET", url);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("Accept", "application/json");
        // xhr.setRequestHeader("Origin", "localhost");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);

                console.log(response);
                $.ajax({
                    url: 'show_movie.php',
                    method: 'POST',
                    contentType: 'application/json;charset=utf-8',
                    data: JSON.stringify(response),
                    success: function (data) {
                        end();
                        $("#cards").append(data);
                    }
                });
            }
        };
        xhr.send();
    }

</script>
<script>
    function requestwithregion(title,region){
        start()
        var xhr = new XMLHttpRequest();
        var url = "http://localhost:8080/stream/get-by-title?title="+title+"&country="+region;

        xhr.open("GET", url);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.setRequestHeader("Accept", "application/json");
        // xhr.setRequestHeader("Origin", "localhost");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);

                console.log(response);
                $.ajax({
                    url: 'show_movie.php',
                    method: 'POST',
                    contentType: 'application/json;charset=utf-8',
                    data: JSON.stringify(response),
                    success: function (data) {
                        end();
                        $("#cards").html(data);
                    }
                });
            }
        };
        xhr.send();
    }

</script>

<script>
function request(title){
    start()
    var xhr = new XMLHttpRequest();
    var url = "http://localhost:8080/stream/get-by-title?title="+title;

    xhr.open("GET", url);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.setRequestHeader("Accept", "application/json");
    // xhr.setRequestHeader("Origin", "localhost");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);

console.log(response);
            $.ajax({
                url: 'show_movie.php',
                method: 'POST',
                contentType: 'application/json;charset=utf-8',
                data: JSON.stringify(response),
                success: function (data) {
                    end();
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
</body>

</html>
