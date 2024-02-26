<?php
function redirectToURL($url):void {

  //Apparently, this function sends actual request headers to the browser based
  //on my understanding of it. It returns an HTTP 302 when the `Location:` header
  //is sent.
  header("Location: $url");


  //Kill the script after sending the redirect headers. This prevents any output
  //from being sent after the redirect since browsers may still potentially load
  //the rest of the page before running the redirect.
  exit();
}
?>
