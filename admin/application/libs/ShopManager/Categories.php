<?
namespace ShopManager;

use ShopManager\Category;

/**
* class Categories
* @package ShopManager
* @version 1.0
*/
class Categories
{
	private $db = null;
	public $tree = false;
	private $current_category = false;
	private $tree_steped_item = false;
	private $tree_items = 0;
	private $walk_step = 0;
	private $parent_data = false;
	public $table = 'shop_termek_kategoriak';
	public $authorid = 0;
	public $onlyauthor = false;
	public $ws = false;

	function __construct( $arg = array() )
	{
		$this->db = $arg[db];
		if (isset($arg['authorid'])) {
			$this->authorid = $arg['authorid'];
		}
		if (isset($arg['onlyauthor'])) {
			$this->onlyauthor = $arg['onlyauthor'];
		}
		if (isset($arg['ws'])) {
			$this->ws = $arg['ws'];
		}

		return $this;
  }

	public function setTable( $table )
	{
		$this->table = $table;

		return $this;
	}

	/**
	 * Kategória létrehzás
	 * @param array $data új kategória létrehozásához szükséges adatok
	 * @return void
	 */
	public function add( $data = array() )
	{
		$deep = 0;
		$name = ($data['name']) ?: false;
		$sort = ($data['sortnumber']) ?: 0;
		$parent = ($data['parent_category']) ?: NULL;
		$hashkey = ($new_data['hashkey']) ?: NULL;
		$oldal_hashkeys = (count($new_data['oldal_hashkeys']) > 0) ? implode(",",$new_data['oldal_hashkeys']) : NULL;
		$eleres = ($data['slug']) ?: NULL;

		if (!$eleres) {
			$eleres = $this->checkEleres( $name );
		} else {
			$eleres = \PortalManager\Formater::makeSafeUrl($eleres,'');
		}

		if ($parent) {
			$xparent = explode('_',$parent);
			$parent = (int)$xparent[0];
			$deep = (int)$xparent[1] + 1;
		}

		if ( !$name ) {
			throw new \Exception( "Kérjük, hogy adja meg a kategória elnevezését!" );
		}

		$author = (!$this->authorid || $this->authorid == 0) ? NULL : $this->authorid;

		$this->db->insert(
			$this->table,
			array(
				'neve' 		=> $name,
				'slug' => $eleres,
				'szulo_id' 	=> $parent,
				'sorrend' 	=> $sort,
				'deep' 		=> $deep,
				'hashkey' 	=> $hashkey,
				'oldal_hashkeys' => $oldal_hashkeys,
				'author' => $author
			)
		);
	}

	/**
	 * Kategória szerkesztése
	 * @param  Category $category ShopManager\Category class
	 * @param  array    $new_data
	 * @return void
	 */
	public function edit( Category $category, $new_data = array() )
	{
		$deep = 0;
		$name = ($new_data['name']) ?: false;
		$sort = ($new_data['sortnumber']) ?: 0;
		$parent = ($new_data['parent_category']) ?: NULL;
		$hashkey = ($new_data['hashkey']) ?: NULL;
		$oldal_hashkeys = (count($new_data['oldal_hashkeys']) > 0) ? implode(",",$new_data['oldal_hashkeys']) : NULL;
		$image = ( isset($new_data['image']) ) ? $new_data['image'] : NULL;
		$eleres = ($new_data['slug']) ?: NULL;

		if (!$eleres) {
			$eleres = $this->checkEleres( $name );
		} else {
			$eleres = \PortalManager\Formater::makeSafeUrl($eleres,'');
		}

		if ($parent) {
			$xparent = explode('_',$parent);
			$parent = (int)$xparent[0];
			$deep = (int)$xparent[1] + 1;
		}

		if ( !$name ) {
			throw new \Exception( "Kérjük, hogy adja meg a kategória elnevezését!" );
		}

		$row = array(
			'neve' => $name,
			'slug' => $eleres,
			'szulo_id' => $parent,
			'sorrend' => $sort,
			'deep' => $deep,
			'hashkey' => $hashkey,
			'oldal_hashkeys' => $oldal_hashkeys,
			'kep' => $image
		);

		if (isset($new_data['bgcolor'])) {
			$row['bgcolor'] = '#'.str_replace("#","",$new_data['bgcolor']);
		}

		$category->edit($row);
	}

	public function delete( Category $category )
	{
		$category->delete();
	}

	/**
	 * Kategória fa kilistázása
	 * @param int $top_category_id Felső kategória ID meghatározása, nem kötelező. Ha nincs megadva, akkor
	 * a teljes kategória fa listázódik.
	 * @return array Kategóriák
	 */
	public function getTree( $top_category_id = false, $arg = array() )
	{
		$tree 		= array();
		$wsfilter = '';

		if ( $top_category_id ) {
			$this->parent_data = $this->db->query( sprintf("SELECT * FROM ".$this->table." WHERE ID = %d", $top_category_id) )->fetch(\PDO::FETCH_ASSOC);
		}

		if ($this->ws) {
			$wsfilter = "(SELECT count(k.ID) FROM shop_termek_in_kategoria as k LEFT OUTER JOIN shop_termekek as sp ON sp.ID = k.termekID LEFT OUTER JOIN shop_settings as ws ON ws.author_id = sp.author WHERE sp.lathato = 1 and k.kategoria_id = cat.ID and ws.author_id IS NOT NULL )";
		}

		// Legfelső színtű kategóriák
		$qry = "SELECT cat.*";
		if ($this->ws) {
			$qry .= ", ".$wsfilter." as prod";
		}
		$qry .= " FROM	".$this->table." as cat ";

		$qry .= " WHERE	1=1 ";

		if ( $this->authorid != 0 && $this->onlyauthor === true ) {
		 $qry .= " and cat.author = ". (int)$this->authorid;
		}

		if ( !$top_category_id ) {
			$qry .= " and cat.szulo_id IS NULL ";
		} else {
			$qry .= " and cat.szulo_id = ".$top_category_id;
		}

		// ID SET
		if( isset($arg['id_set']) && count($arg['id_set']) )
		{
			$qry .= " and cat.ID IN (".implode(",",$arg['id_set']).") ";
		}

		if ($this->ws) {
			$qry .= " and (SELECT count(k.ID) FROM shop_termek_in_kategoria as k LEFT OUTER JOIN shop_termekek as sp ON sp.ID = k.termekID LEFT OUTER JOIN shop_settings as ws ON ws.author_id = sp.author WHERE sp.lathato = 1 and k.kategoria_id = cat.ID and ws.author_id IS NOT NULL ) != 0 ";
		}

		$qry .= " ORDER BY cat.sorrend ASC, cat.ID ASC;";
		$top_cat_qry 	= $this->db->query($qry);
		$top_cat_data 	= $top_cat_qry->fetchAll(\PDO::FETCH_ASSOC);

		if( $top_cat_qry->rowCount() == 0 ) return $this;

		foreach ( $top_cat_data as $top_cat ) {
			$this->tree_items++;

			$top_cat['link'] = DOMAIN.'termekek/'.\PortalManager\Formater::makeSafeUrl($top_cat['neve'],'_-'.$top_cat['ID']);

			$this->tree_steped_item[] = $top_cat;

			// Alkategóriák betöltése
			$top_cat['child'] = $this->getChildCategories($top_cat['ID'], $arg);
			$tree[] = $top_cat;
		}

		$this->tree = $tree;

		return $this;
	}

	/**
	 * Végigjárja az összes kategóriát, amit betöltöttünk a getFree() függvény segítségével. while php függvénnyel
	 * járjuk végig. A while függvényen belül használjuk a the_cat() objektum függvényt, ami az aktuális kategória
	 * adataiat tartalmazza tömbbe sorolva.
	 * @return boolean
	 */
	public function walk()
	{
		if( !$this->tree_steped_item ) return false;

		$this->current_category = $this->tree_steped_item[$this->walk_step];

		$this->walk_step++;

		if ( $this->walk_step > $this->tree_items ) {
			// Reset Walk
			$this->walk_step = 0;
			$this->current_category = false;

			return false;
		}

		return true;
	}

	/**
	 * A walk() fgv-en belül visszakaphatjuk az aktuális kategória elem adatait tömbbe tárolva.
	 * @return array
	 */
	public function the_cat()
	{
		return $this->current_category;
	}

	public function getParentData( $field = false )
	{
		if ( $field ) {
			return $this->parent_data[$field];
		} else
		return $this->parent_data;
	}

	/**
	 * Kategória alkategóriáinak listázása
	 * @param  int $parent_id 	Szülő kategória ID
	 * @return array 			Szülő kategória alkategóriái
	 */
	public function getChildCategories( $parent_id, $arg = array() )
	{
		$tree = array();
		$qryparam = array();

		if ($this->ws) {
			$wsfilter = "(SELECT count(k.ID) FROM shop_termek_in_kategoria as k LEFT OUTER JOIN shop_termekek as sp ON sp.ID = k.termekID LEFT OUTER JOIN shop_settings as ws ON ws.author_id = sp.author WHERE sp.lathato = 1 and k.kategoria_id = cat.ID and ws.author_id IS NOT NULL  )";
		}

		$qry = "SELECT cat.*";

		if ($this->ws) {
			$qry .= ", ".$wsfilter." as prod";
		}

		$qry .= " FROM ".$this->table." as cat";

		$qry .= " WHERE cat.szulo_id = :parent";
		if ($this->ws) {
			$qry .= " and (SELECT count(k.ID) FROM shop_termek_in_kategoria as k LEFT OUTER JOIN shop_termekek as sp ON sp.ID = k.termekID LEFT OUTER JOIN shop_settings as ws ON ws.author_id = sp.author WHERE sp.lathato = 1 and k.kategoria_id = cat.ID and ws.author_id IS NOT NULL ) != 0  ";
		}
		$qry .= " ORDER BY cat.sorrend ASC, cat.ID ASC";
		$qryparam['parent'] = $parent_id;

		// Gyerek kategóriák
		$child_cat_qry 	= $this->db->squery( $qry, $qryparam );
		$child_cat_data	= $child_cat_qry->fetchAll(\PDO::FETCH_ASSOC);

		if( $child_cat_qry->rowCount() == 0 ) return false;
		foreach ( $child_cat_data as $child_cat ) {
			$this->tree_items++;
			$child_cat['link'] 	= DOMAIN.'termekek/'.\PortalManager\Formater::makeSafeUrl($child_cat['neve'],'_-'.$child_cat['ID']);
			$child_cat['kep'] 	= ($child_cat['kep'] == '') ? '/src/images/no-image.png' : $child_cat['kep'];
			$this->tree_steped_item[] = $child_cat;

			$child_cat['child'] = $this->getChildCategories($child_cat['ID'], $arg);
			$tree[] = $child_cat;
		}

		return $tree;

	}


	/**
	 * Kategória szülő listázása
	 * @param  int $child_id 	Szülő kategória ID
	 * @return array 			Szülő szülő kategóriái
	 */
	public function getCategoryParentRow( $id, $return_row = 'ID', $deep_allow_under = 0 )
	{
		$row = array();

		$has_parent = true;

		$limit = 10;

		$sid = $id;

		while( $has_parent && $limit > 0 ) {

			$q 		= "SELECT ".( ($return_row) ? $return_row.', szulo_id, deep' : '*' )." FROM ".$this->table." WHERE ID = ".$sid.";";
			$qry 	= $this->db->query($q);
			$data 	= $qry->fetch(\PDO::FETCH_ASSOC);

			$sid = $data['szulo_id'];

			if( is_null( $data['szulo_id'] ) ) {
				$has_parent = false;
			}

			if( (int)$data['deep'] >= $deep_allow_under ) {
				if (!$return_row) {
					$row[] = $data;
				} else {
					$row[] = $data[$return_row];
				}
			}

			$limit--;
		}

		return $row;
	}

	private function checkEleres( $text )
	{
		$text = \PortalManager\Formater::makeSafeUrl($text,'');
		$qrystr = array();
		$qry = "
			SELECT 		slug
			FROM 		".$this->table."
			WHERE 1=1 and (slug = :str or
						slug like :str1 or
						slug like :str2)";
		if ($this->authorid != 0) {
			$qry .= " and author = ".(int)$this->authorid;
		}
		$qry .= "	ORDER BY slug DESC LIMIT 0,1";
		$qrystr['str'] = trim($text);
		$qrystr['str1'] = '%'.trim($text).'-_';
		$qrystr['str2'] = '%'.trim($text).'-__';

		$qry = $this->db->squery($qry, $qrystr);
		$last_text = $qry->fetch(\PDO::FETCH_COLUMN);

		if( $qry->rowCount() > 0 ) {

			$last_int = (int)end(explode("-",$last_text));

			if( $last_int != 0 ){
				$last_text = str_replace('-'.$last_int, '-'.($last_int+1) , $last_text);
			} else {
				$last_text .= '-1';
			}
		} else {
			$last_text = $text;
		}

		return $last_text;
	}

	public function killDB()
	{
		$this->db = null;
	}

	public function __destruct()
	{
		//echo ' -DEST- ';
		$this->tree = false;
		$this->current_category = false;
		$this->tree_steped_item = false;
		$this->tree_items = 0;
		$this->walk_step = 0;
		$this->parent_data = false;
	}
}
?>
