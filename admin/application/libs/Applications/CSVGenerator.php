<?
namespace Applications;

class CSVGenerator
{
	private static $separator 		= ";";
	private static $enclosure 		= '"';
	private static $download_file 	= true;
	private static $csv_head 		= array();
	private static $csv_items 		= array();
	private static $output_filename = 'untitled.csv';
	private static $encode 			= 'iso-8859-2'; 
	
	public static function prepare($head = array(), $items = array(), $fileName = 'untitled'){
		self::$csv_head 		= $head;
		self::$csv_items 		= $items; 
		self::$output_filename 	= $fileName.'.csv';
	}
	
	public static function changeSeparator($sep){
		if($sep != ''){
			self::$separator = trim($sep);
		}
	}

	public static function changeEncoding( $charset )
	{
		self::$encode = $charset;
	}

	private function conv( $str )
	{
		return iconv( 'UTF-8', self::$encode, $str);
	}
			
	public static function run(){
		$data 	= array();
		
		if(count(self::$csv_head) > 0){
			$data[] = self::$csv_head;
		}
		
		$item = self::$csv_items; 
		
		if(count($item) == 0){return false;}
		foreach($item as $i){
			$data[] = $i;
		}
		
		if(self::$download_file){
			$filename = self::$output_filename;
			
			header("Content-type: text/csv; charset=".self::$encode);
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header("Content-Transfer-Encoding: binary");
			header("Pragma: no-cache");
			header("Expires: 0");
		}
		
		$output = fopen("php://output", "w");

		foreach ($data as $row) {
			if( $row && !empty($row) ){
				$into = array();
				foreach($row as $r){
					if(!is_null($r)){
						$into[] = self::conv($r);	
					}
				}
				fputcsv($output, $into, self::$separator, '"');
			}
			
		}
		
		/*echo '<pre>';
		var_dump($output);
		echo '</pre>';*/
		
		fclose($output);
	}
}
?>