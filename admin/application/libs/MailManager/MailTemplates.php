<?php
namespace MailManager;

class MailTemplates
{
	const DB_TABLE = 'email_templates';

	public $template_name;

	private $db;
	private $data;

	function __construct( $arg = array() )
	{
		$this->db = $arg['db'];

		return $this;
	}

	public function save( $template, $data)
	{	
		$this->db->update(
			self::DB_TABLE,
			$data,
			"elnevezes = '".$template."'"
		);
	}

	public function getList()
	{
		return $this->db->squery("SELECT * FROM ".self::DB_TABLE)->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function load( $what, $by = 'elnevezes' )
	{
		if ( $by == 'elnevezes' && !$this->exists($what) ) {
			return false;
		}

		$this->data = $this->db->squery("SELECT * FROM ".self::DB_TABLE." WHERE ".$by." = :nev;",array('nev' => $what))->fetch(\PDO::FETCH_ASSOC);

		return $this;
	}

	public function getData()
	{
		return $this->data;
	}

	public function get( $template, $params = array() )
	{
		if ( !$this->exists($template) ) {
			return false;
		}

		$this->template_name = $template;

		$content = $this->db->squery("SELECT content FROM ".self::DB_TABLE." WHERE elnevezes = :nev;",array('nev' => $template));

		$content = $content->fetchColumn();

		$this->replaceParams( $content, $params );

		return $content;
	}

	private function replaceParams( &$content, $params = array() )
	{
		$matches 	= false;
		$params 	= array_merge( $params, $params['settings']);

		$pattern = '/\{(.*?)\}/';
		preg_match_all($pattern, $content, $matches, PREG_OFFSET_CAPTURE);

		/*
		echo '<pre>';
		print_r($matches);
		echo '</pre>';
		*/

		if ($matches) {
			foreach ( $matches[0] as $key => $var ) 
			{
				$param = $matches[1][$key][0];

				if (!isset($params[$param])) {
					continue;
				}

				$content = preg_replace( "/".$var[0]."/", $params[$param], $content );
			}
			
		}
	}

	public function exists( $name )
	{
		$q = $this->db->squery("SELECT ID FROM ".self::DB_TABLE." WHERE elnevezes = :nev;",array('nev' => $name));

		if ($q->rowCount() == 0) {
			return false;
		}

		return true;
	}

	function __destruct()
	{
		$this->db = null;
	}

}