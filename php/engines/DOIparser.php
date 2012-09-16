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
				$month=$xml->xpath('//month');
				$volume=$xml->xpath('//volume');
				$issue=$xml->xpath('//issue');
				$startpage=$xml->xpath('//first_page');
				$lastpage=$xml->xpath('//last_page');
					
				$data[]=$xml->xpath('//surname');
				$data[]=$xml->xpath('//given_name');
				$data[]=$title[0];
				$data[]=$year[0];
				$data[]=$publisher[0];
				$data[]=$month[0];
				$data[]=$volume[0];
				$data[]=$issue[0];
				$data[]=$startpage[0];
				$data[]=$lastpage[0];				
				
			}
			else
				$this->meta['status']='failed';			
			
			return $this->buildjson($data,$this->meta);
				
		}
	}




?>