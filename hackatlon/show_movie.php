
<?php
// if(isset($_POST['cardArray'])){
//
// }
$data = file_get_contents('php://input');
$cards = json_decode($data);
//foreach ($cards as $card) {
//    if($card->type == "Premium"){
//        if(strlen($card->name) == 0 || strlen($card->job) == 0){
//            echo "Invalid Fields";
//            exit;
//        }
//    }
//}
$card="";

session_start();
$_SESSION['mymovvies'] = $cards;
foreach($cards as $cardsinfo) {
    $card .= <<<DELIMITER
<h1>$cardsinfo->streaming <i class='bx bx-chevron-right' style=""></i></h1>
                <div class="row swiper mySwiper">
                    <div class="swiper-wrapper" >
DELIMITER;
    foreach ($cardsinfo->movies as $movies) {
        $subArray = $movies->streamingInfoDto;
        foreach ($subArray->streamingDto as $subtitle)
        {
            if($subtitle->name == $cardsinfo->streaming)
            {
                $sub_string = implode(', ', $subtitle->sub);
            }
        }

        $card .= <<<DELIMITER
<div class="col-3 swiper-slide">
    <div class="moviecard">
        <div class="cardimage" style="background-image: url($movies->poster);">

        </div>
        <div class="cardtext p-3">
            <span style="font-size: 30px;">$movies->title</span>
            <br>
            <span>Subtitles:</span>
            <br>
            <span style="color:#7c7c7c;font-size: 13px;">$sub_string</span>
            <a class="btn btn-custom" href="details.php?id=$movies->id">More Details</a>
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

}
echo $card;
?>

