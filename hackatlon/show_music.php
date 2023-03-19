
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

//session_start();
//$_SESSION['mymovvies'] = $cards;
$otherSong=<<<DELIMITER
<p class="info">
DELIMITER;

foreach($cards as $cardsinfo) {
    if($cardsinfo->spotifyLink != null)
        {
            $embed_url = str_replace('open.spotify.com', 'embed.spotify.com',  $cardsinfo->spotifyLink);
            $card .= <<<DELIMITER
<div class="spotify-embed"><iframe src="$embed_url" width="340" height="80" frameborder="0" allowtransparency="true"></iframe></div>
DELIMITER;
        }
    else{
        $otherSong.=<<<DELIMITER
    $cardsinfo->title<br>
DELIMITER;
    }
}
$otherSong.=<<<DELIMITER
    </p>
DELIMITER;
$card.=$otherSong;
echo $card;
?>

