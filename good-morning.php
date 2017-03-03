
add_action( 'init', 'sayGoodMorningSession' );
function sayGoodMorningSession($content) {
  global $currentUserHour;
  /** 
  Get the users time zone by IP using freegeoip.net.
  The "Time Zone" in this example is stored in a Cookie but could also be stored in a PHP Session.
  Note this method assumes the content is not cached! 
  **/
  if(isset($_COOKIE['TimeZone'])) {
      $userTimeZone = $_COOKIE['TimeZone'];
  } else {
      $userTimeZone = json_decode(file_get_contents('http://freegeoip.net/json/'.$_SERVER['REMOTE_ADDR']))->time_zone;
      setcookie( 'TimeZone', $userTimeZone, time() + 10800, '/');
  }
  $date = new DateTime('now', new DateTimeZone($userTimeZone));
  $currentUserHour = $date->format('H');

}

add_action( 'the_post', 'sayGoodMorning' );
function sayGoodMorning($content) {
  global $post,$currentUserHour;
  /** Check if it is before 11AM and if current post type is 'page' **/
  if ($currentUserHour < 11 && is_page($post->ID) ){
    function welcomeGoodMorningFilter($content) {
      $content = str_replace('Welcome', 'Good Morning' ,$content);
      return $content;
    }
    add_filter('the_content','welcomeGoodMorningFilter');
  }
}
