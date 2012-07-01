<?php
      
      //$url = "http://dx.doi.org/10.1023/B:SUPE.0000011386.69245.f5";
      $url = $_GET["doi"];
      if (!function_exists('curl_init'))
      {
        die('You need to install php curl');
      }
      
      $ch = curl_init();
      curl_setopt ($ch, CURLOPT_URL, $url );
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true );
      curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, true );

      curl_setopt ($ch, CURLOPT_HTTPHEADER,array ("Accept: application/unixref+xml"));

      $response = curl_exec ($ch);
      $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $contenttype = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
      
      if( curl_errno( $ch ) == 404 ){
          curl_close($ch);
          die('curl error');
      }
      
      curl_close ($ch);
     
      $xml = new SimpleXMLElement( $response );
      
  
      //print $xml->asXML();
      
      $title=$xml->xpath('//title');
      $publisher=$xml->xpath('//full_title');
      $year=$xml->xpath('//year');
      $author=$xml->xpath('//surname');
      
      echo "new Array( \" $author[0] \" , \" $title[0] \"  , \" $year[0] \" , \" $publisher[0] \" ) ";

?>