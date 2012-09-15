<?php 

 	include_once 'EngineContainer.php';
 	
	class DOIparser extends EngineContainer
	{
		
		private $doi;
		private $meta;
		
		public function __construct($a)
		{
			 $this->doi=$a;
			 $this->meta['engine']='getdoi';
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
			if($response)
			{
				$this->meta['status']='passed';
				$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				$contenttype = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
				$xml = new SimpleXMLElement( $response );
				curl_close ($ch);			 
			
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
					
				$data[]=$author;
				$data[]=$title[0].implode();
				$data[]=$year[0].implode();
				$data[]=$publisher[0].implode();
				$data[]=$month[0].implode();
				$data[]=$volume[0].implode();
				$data[]=$issue[0].implode();
				$data[]=$startpage[0].implode();
				$data[]=$lastpage[0].implode();
				
			}
			else
				$this->meta['status']='failed';			
			
			return $this->buildjson($data,$this->meta);
				
		}
	}




?>