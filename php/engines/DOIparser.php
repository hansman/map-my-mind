<?php 


	class DOIparser 
	{
		
		private $doi;
		
		public function __construct($a)
		{
			 $this->doi=$a;
		}
		

		public function run()
		{
			
			$url = "http://dx.doi.org/".$this->doi;
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
			$authorslastnames=$xml->xpath('//surname');
			$authorsfirstnames=$xml->xpath('//given_name');
			$month=$xml->xpath('//month');
			$volume=$xml->xpath('//volume');
			$issue=$xml->xpath('//issue');
			$startpage=$xml->xpath('//first_page');
			$lastpage=$xml->xpath('//last_page');
			$author="";
			
			for( $i=0; $i < sizeof($authorsfirstnames)-1 ; $i++  )
				$author = $author . substr($authorsfirstnames[$i],0,1) . ". " . $authorslastnames[$i] . ", ";
			
			$author = $author . " " . substr($authorsfirstnames[sizeof($authorsfirstnames)-1],0,1) . ". " . $authorslastnames[sizeof($authorsfirstnames)-1];
			
			return "new Array(\"$author\",\"$title[0]\",\"$year[0]\",\"$publisher[0]\",\"$month[0]\",\"$volume[0]\",\"$issue[0]\",\"$startpage[0]\",\"$lastpage[0]\")";
				
			
		}
	}




?>