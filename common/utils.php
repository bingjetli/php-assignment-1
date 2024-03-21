<?php
function redirectToURL($url): void
{

  //Apparently, this function sends actual request headers to the browser based
  //on my understanding of it. It returns an HTTP 302 when the `Location:` header
  //is sent.
  header("Location: $url");


  //Kill the script after sending the redirect headers. This prevents any output
  //from being sent after the redirect since browsers may still potentially load
  //the rest of the page before running the redirect.
  exit();
}


function generateUniqueIdentifier(): string
{

  //First get the current time in hexadecimal.
  $current_time = dechex(time());


  //Now generate a random set of bytes and convert it to hexadecimal.
  $random_bytes = bin2hex(random_bytes(32));


  //Now append the hex-timestamp with the hex-random bytes. The resulting string
  //ought to be reasonably unique, although this is not guaranteed.
  return "$current_time$random_bytes";
}
?>