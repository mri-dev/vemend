<?
namespace PortalManager;

use PortalManager\Formater;

/**
* class News
* @package PortalManager
* @version v1.0
*/
class News
{

  const DBVIEW = 'cikk_views';
  const DBVIEWHISTORY = 'cikk_view_history';

	private $db = null;
	public $tree = false;
	private $max_page = 1;
	private $current_page = 1;
	private $current_item = false;
	private $current_get_item = false;
	private $tree_steped_item = false;
	public $tree_items = 0;
	private $walk_step = 0;
	private $selected_news_id = false;
	private $item_limit_per_page = 50;
	private $sitem_numbers = 0;
  public $tematic_cikk_slugs = array('boltok', 'intezmenyek', 'vendeglatas', 'turizmus');

	function __construct( $news_id = false, $arg = array() )
	{
		$this->db = $arg[db];
		if ( $news_id ) {
			$this->selected_news_id = $news_id;
		}
	}

	public function get( $news_id_or_slug )
	{
		$data = array();
		$qry = "SELECT
			h.*,
		(SELECT count(uid) FROM ".self::DBVIEW." WHERE prog_id = h.ID) as uvisit
		FROM hirek as h ";

		if (is_numeric($news_id_or_slug)) {
			$qry .= " WHERE h.ID = ".$news_id_or_slug;
		}else {
			$qry .= " WHERE h.eleres = '".$news_id_or_slug."'";
		}

		$qry = $this->db->query($qry);

		$this->current_get_item = $qry->fetch(\PDO::FETCH_ASSOC);

		return $this;
	}

	public function add( $data )
	{
		$cim 	= ($data['cim']) ?: false;
		$eleres = ($data['eleres']) ?: false;
		$szoveg = ($data['szoveg']) ?: NULL;
		$bevezeto = ($data['bevezeto']) ?: NULL;
		$lathato= ($data['lathato'] == 'on') ? 1 : 0;
		$archiv = ($data['archiv']) ? 1 : 0;
    $archivalva = NULL;
    $optional = $data['optional'];
    $optional_data = array();

		if (!$cim) { throw new \Exception("Kérjük, hogy adja meg az <strong>Cikk címét</strong>!"); }


		if (!$eleres) {
			$eleres = $this->checkEleres( $cim );
		}

    // Optional
    if ($optional && !empty($optional)) {
      foreach ((array)$optional as $key => $value) {
        if ($value != '') {
          $optional_data[$key] = (is_string($value)) ? addslashes($value) : $value;
        }
      }
    }

    $upd = array(
      'cim' => $cim,
      'eleres' => $eleres,
      'szoveg' => $szoveg,
      'bevezeto' => $bevezeto,
      'idopont' => NOW,
      'letrehozva' => NOW,
      'lathato' => $lathato,
      'optional_contacts' => ($optional_data['contacts']) ? json_encode($optional_data['contacts'], \JSON_UNESCAPED_UNICODE) : NULL,
      'optional_nyitvatartas' => ($optional_data['nyitvatartas']) ? json_encode($optional_data['nyitvatartas'], \JSON_UNESCAPED_UNICODE) : NULL,
      'optional_maps' => ($optional_data['maps'] != '') ? $optional_data['maps'] : NULL,
      'optional_logo' => ($optional_data['logo'] != '') ? $optional_data['logo'] : NULL,
      'optional_firstimage' => ($optional_data['firstimage'] != '') ? $optional_data['firstimage'] : NULL
    );

		$this->db->insert(
			"hirek",
      $upd
		);

		$id = $this->db->lastInsertId();

    // Check archivalás
    if ($archiv == 1) {
      $prearch = (int)$this->db->squery("SELECT archiv FROM hirek WHERE ID = :id", array('id' => $id))->fetchColumn();
      if ($prearch == 0) {
        $archivalva = NOW;
        $upd['archivalva'] = $archivalva;
        $this->db->update(
    			"hirek",
    			array(
            'archivalva' => $archivalva,
            'archiv' => 1
          ),
    			sprintf("ID = %d", $id)
    		);
      }
    }

		$this->resaveCategories( $id, $data['cats'] );

		return $id;
	}

	public function save( $data )
	{
		$cim 	= ($data['cim']) ?: false;
		$eleres = ($data['eleres']) ?: false;
		$szoveg = ($data['szoveg']) ?: NULL;
		$bevezeto = ($data['bevezeto']) ?: NULL;
		$kep 	= ($data['belyegkep']) ?: NULL;
		$lathato= ($data['lathato']) ? 1 : 0;
		$archiv = ($data['archiv']) ? 1 : 0;
    $archivalva = NULL;
    $optional = $data['optional'];
    $optional_data = array();

		if (!$cim) { throw new \Exception("Kérjük, hogy adja meg a <strong>Cikk címét</strong>!"); }

		if (!$eleres) {
			$eleres = $this->checkEleres( $cim );
		}

    // Optional
    if ($optional && !empty($optional)) {
      foreach ((array)$optional as $key => $value) {
        if ($value != '') {
          $optional_data[$key] = (is_string($value)) ? addslashes($value) : $value;
        }
      }
    }

    $upd = array(
      'cim' => $cim,
      'eleres' => $eleres,
      'belyeg_kep' => $kep,
      'szoveg' => $szoveg,
      'bevezeto' => $bevezeto,
      'idopont' => NOW,
      'lathato' => $lathato,
      'optional_contacts' => ($optional_data['contacts']) ? json_encode($optional_data['contacts'], \JSON_UNESCAPED_UNICODE) : NULL,
      'optional_nyitvatartas' => ($optional_data['nyitvatartas']) ? json_encode($optional_data['nyitvatartas'], \JSON_UNESCAPED_UNICODE) : NULL,
      'optional_maps' => ($optional_data['maps'] != '') ? $optional_data['maps'] : NULL,
      'optional_logo' => ($optional_data['logo'] != '') ? $optional_data['logo'] : NULL,
      'optional_firstimage' => ($optional_data['firstimage'] != '') ? $optional_data['firstimage'] : NULL,
      'archiv' => $archiv
    );

    // Check archivalás
    if ($archiv == 1) {
      $prearch = (int)$this->db->squery("SELECT archiv FROM hirek WHERE ID = :id", array('id' => $this->selected_news_id))->fetchColumn();
      if ($prearch == 0) {
        $archivalva = NOW;
        $upd['archivalva'] = $archivalva;
      }
    }

		$this->db->update(
			"hirek",
			$upd,
			sprintf("ID = %d", $this->selected_news_id)
		);

		$this->resaveCategories( $this->selected_news_id, $data['cats'] );
	}

	public function resaveCategories( $id, $cats = array() )
	{
		// delete previous
		$this->db->squery("DELETE FROM cikk_xref_cat WHERE cikk_id = :cikkid", array(
			'cikkid' => $id
		));

		// reinsert
		if( !empty($cats) )
		foreach ((array)$cats as $cid ) {
			$this->db->insert(
				'cikk_xref_cat',
				array(
					'cikk_id' => $id,
					'cat_id' => $cid
				)
			);
		}
	}

	private function checkEleres( $text )
	{
		$text = Formater::makeSafeUrl($text,'');

		$qry = $this->db->query(sprintf("
			SELECT 		eleres
			FROM 		hirek
			WHERE 		eleres = '%s' or
						eleres like '%s-_' or
						eleres like '%s-__'
			ORDER BY 	eleres DESC
			LIMIT 		0,1", trim($text), trim($text), trim($text) ));
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

	public function delete( $id = false )
	{
		$del_id = ($id) ?: $this->selected_news_id;

		if ( !$del_id ) return false;

		$this->db->query(sprintf("DELETE FROM hirek WHERE ID = %d", $del_id));
	}

	/**
	 * Hír fa kilistázása
	 * @param int $top_page_id Felső Hír ID meghatározása, nem kötelező. Ha nincs megadva, akkor
	 * az összes Hír fa listázódik.
	 * @return array Hírak
	 */
	public function getTree( $arg = array() )
	{
		$tree 		= array();

		if ( $arg['limit'] ) {
			if( $arg['limit'] > 0 ) {
				$this->item_limit_per_page = ( is_numeric($this->item_limit_per_page) && $this->item_limit_per_page > 0) ? (int)$arg['limit'] : $this->item_limit_per_page;
			} else if( $arg['limit'] == -1 ){
				$this->item_limit_per_page = 999999999999;
			}
		}

		// Legfelső színtű
		$qry = "
			SELECT SQL_CALC_FOUND_ROWS
				h.*,
				(SELECT count(uid) FROM ".self::DBVIEW." WHERE prog_id = h.ID) as uvisit
			FROM hirek as h
			WHERE h.ID IS NOT NULL ";

		if( $arg['except_id'] ) {
			$qry .= " and h.ID != ".$arg['except_id'];
		}

    if( isset($arg['hide_offline']) && !empty($arg['hide_offline']) ) {
      $qry .= " and h.lathato = 1";
    }

    if( isset($arg['hide_archiv']) && !empty($arg['hide_archiv']) ) {
      $qry .= " and h.archiv = 0";
    }

    if( isset($arg['only_archiv']) && !empty($arg['only_archiv']) ) {
      $qry .= " and h.archiv = 1";
    }

    if( isset($arg['on_date']) && !empty($arg['on_date']) ) {
      $qry .= " and h.letrehozva LIKE '".addslashes($arg['on_date'])."%'";
    }

    // Kategória slug exlude
    if( isset($arg['exc_cat_slug']) && !empty($arg['exc_cat_slug']) ) {
      $qry .= " and (";
        foreach ((array)$arg['exc_cat_slug'] as $exc_cat ) {
          $qry .= "'".$exc_cat."' NOT IN (SELECT ck.slug  FROM `cikk_xref_cat` as c LEFT OUTER JOIN cikk_kategoriak as ck ON ck.ID = c.cat_id WHERE c.`cikk_id` = h.ID) and ";
        }
        $qry = trim($qry," and ");
      $qry .= ")";
    }

		if( isset($arg['in_id']) && !empty($arg['in_id']) ) {
      $qry .= " and h.ID IN(".implode(",", (array)$arg['in_id']).")";
    }

		if (isset($arg['in_cat']) && !empty($arg['in_cat']) && $arg['in_cat'] != 0) {
			$qry .= " and ".$arg['in_cat']." IN (SELECT cat_id FROM cikk_xref_cat WHERE cikk_id = h.ID)";
		}

    if (isset($arg['search']) && !empty($arg['search'])) {
			$qry .= " and h.cim LIKE '%".addslashes($arg['search'])."%'";
		}


		if( $arg['order'] ) {
      if ($arg['order'] == 'in_id') {
        $qry .= " ORDER BY FIELD(h.ID, ".implode(",", $arg['in_id']).")";
      } else {
        $qry .= " ORDER BY ".$arg['order']['by']." ".$arg['order']['how'];
      }
		} else {
			$qry .= " ORDER BY h.idopont DESC ";
		}

		// LIMIT
		$current_page = ($arg['page'] ?: 1);
		$start_item = $current_page * $this->item_limit_per_page - $this->item_limit_per_page;
		$qry .= " LIMIT ".$start_item.",".$this->item_limit_per_page.";";

		$top_news_qry 	= $this->db->query($qry);
		$top_page_data 	= $top_news_qry->fetchAll(\PDO::FETCH_ASSOC);

		$this->sitem_numbers = $this->db->query("SELECT FOUND_ROWS();")->fetch(\PDO::FETCH_COLUMN);

		$this->max_page = ceil($this->sitem_numbers / $this->item_limit_per_page);
		$this->current_page = $current_page;

		if( $top_news_qry->rowCount() == 0 ) return $this;

		foreach ( $top_page_data as $top_page ) {
			$this->tree_items++;
			$this->tree_steped_item[] = $top_page;

			$tree[] = $top_page;
		}

		$this->tree = $tree;

		return $this;
	}

	public function has_news()
	{
		return ($this->tree_items === 0) ? false : true;
	}

	/**
	 * Végigjárja az összes Hírt, amit betöltöttünk a getTree() függvény segítségével. while php függvénnyel
	 * járjuk végig. A while függvényen belül használjuk a the_news() objektum függvényt, ami az aktuális Hír
	 * adataiat tartalmazza tömbbe sorolva.
	 * @return boolean
	 */
	public function walk()
	{
		if( !$this->tree_steped_item ) return false;

		$this->current_item = $this->tree_steped_item[$this->walk_step];
		$this->current_get_item = $this->current_item;

		$this->walk_step++;

		if ( $this->walk_step > $this->tree_items ) {
			// Reset Walk
			$this->walk_step = 0;
			$this->current_item = false;

			return false;
		}

		return true;
	}

	public function getWalkInfo()
	{
		return array(
			'walk_step' => $this->walk_step,
			'tree_steped_item' => $this->tree_steped_item,
			'tree_items' => $this->tree_items,
			'current_item' => $this->current_item,
		);
	}

	/**
	 * A walk() fgv-en belül visszakaphatjuk az aktuális Hír elem adatait tömbbe tárolva.
	 * @return array
	 */
	public function the_news()
	{
		return $this->current_item;
	}


	public static function textRewrites( $text )
	{
		// Kép
		$text = str_replace( '../../../src/uploads/', UPLOADS, $text );

		return $text;
	}

  public function getArchiveDates()
  {
    $list = array();

    $qry = "SELECT
      substr(letrehozva,1,7) as dateg,
      count(ID) as counts
    FROM `hirek`
    WHERE
      lathato = 1 and
      archiv = 1
    GROUP BY dateg
    ORDER BY dateg DESC";

    $qry = $this->db->query($qry);

    if ($qry->rowCount() == 0 ) {
      return $list;
    }

    foreach ((array)$qry->fetchAll(\PDO::FETCH_ASSOC) as $d) {
      $list[] = array(
        'date' => $d['dateg'],
        'datef' => utf8_encode(strftime ('%Y. %B', strtotime($d['dateg']))),
        'posts' => (int)$d['counts'],
      );
    }

    return $list;
  }

	public function historyList( $limit = 5 )
  {
    $ids = array();
    $uid = \Helper::getMachineID();

    if ( empty($uid) ) {
      return false;
    }

    $getids = $this->db->squery("SELECT prod_id FROM ".self::DBVIEWHISTORY." WHERE uid = :uid ORDER BY watchtime DESC LIMIT 0,".$limit, array(
      'uid' => $uid
    ));

    if ( $getids->rowCount() != 0 )
    {
      $gidsdata = $getids->fetchAll(\PDO::FETCH_ASSOC);
      foreach ($gidsdata as $id) {
        $ids[] = (int)$id['prod_id'];
      }
    }

    if ( !empty($ids) )
    {
      $this->getTree(array(
        'in_id' => $ids,
        'order' => 'in_id'
      ));
      return $this;
    }
  }

  public function log_view( $id = 0 )
  {
    if ( $id === 0 || !$id ) {
      return false;
    }

    $date = date('Y-m-d');
    $uid = \Helper::getMachineID();

    if ( empty($uid) ) {
      return false;
    }

    $check = $this->db->squery("SELECT click FROM ".self::DBVIEW." WHERE uid = :uid and prog_id = :progid and ondate = :ondate", array(
      'uid' => $uid,
      'progid' => $id,
      'ondate' => $date
    ));

    if ( $check->rowCount() == 0 ) {
      $this->db->insert(
        self::DBVIEW,
        array(
          'uid' => $uid,
          'prog_id' => $id,
          'ondate' => $date
        )
      );
    } else {
      $click = $check->fetchColumn();
      $this->db->update(
        self::DBVIEW,
        array(
          'click' => $click + 1
        ),
        sprintf("uid = '%s' and prog_id = %d and ondate = '%s'", $uid, $id, $date)
      );
    }

    // History log
    $hhkey = md5($uid.'_'.$id);
    $this->db->multi_insert(
      self::DBVIEWHISTORY,
      array('hashkey', 'uid', 'prod_id', 'watchtime'),
      array(
        array(
          $hhkey,
          $uid,
          $id,
          date('Y-m-d H:i:s')
        )
      ),
      array(
        'duplicate_keys' => array('hashkey', 'watchtime')
      )
    );
    $this->db->query("DELETE FROM ".self::DBVIEWHISTORY." WHERE datediff(now(), watchtime) > 30");
  }


	/*===============================
	=            GETTERS            =
	===============================*/

	public function getFullData()
	{
		return $this->current_get_item;
	}

	public function getImage( $url = false )
	{
		if ( $url ) {
			return UPLOADS . str_replace('/src/uploads/','',$this->current_get_item['belyeg_kep']);
		} else {
			return $this->current_get_item['belyeg_kep'];
		}
	}
	public function getId()
	{
		return $this->current_get_item['ID'];
	}
	public function getTitle()
	{
		return $this->current_get_item['cim'];
	}
	public function getUrl( $cat_prefix = false, $include_domain = true )
	{
    $tematic_cikk_slug = false;
    $cats = $this->getCategories();
    foreach ((array)$cats['list'] as $l) {
      if (in_array($l['slug'], $this->tematic_cikk_slugs)) {
        $tematic_cikk_slug = $l['slug'];
        break;
      }
    }

    if ($tematic_cikk_slug) {
    	return ( ($include_domain) ? DOMAIN : '/' ) .$tematic_cikk_slug.'/'.$this->current_get_item['eleres'];
    } else {
    	return ( ($include_domain) ? DOMAIN : '/' ) .'cikkek/'.( ($cat_prefix) ? $cat_prefix : 'olvas' ).'/'.$this->current_get_item['eleres'];
    }
	}
	public function getAccessKey()
	{
		return $this->current_get_item['eleres'];
	}
	public function getHtmlContent()
	{
		return $this->current_get_item['szoveg'];
	}
	public function getDescription()
	{
		return $this->current_get_item['bevezeto'];
	}
	public function getCatID()
	{
		return $this->current_get_item['cat_id'];
	}
	public function getCommentCount()
	{
		return 0;
	}
	public function getVisitCount()
	{
		return (int)$this->current_get_item['uvisit'];
	}
	public function getIdopont( $format = false )
	{
		return ( !$format ) ? $this->current_get_item['idopont'] : date($format, strtotime($this->current_get_item['idopont']));
	}
	public function getVisibility()
	{
		return ($this->current_get_item['lathato'] == 1 ? true : false);
	}
	public function isFontos()
	{
		return ($this->current_get_item['fontos'] == 1 ? true : false);
	}
  public function isArchiv()
	{
		return ($this->current_get_item['archiv'] == 1 ? true : false);
	}
	public function isKozerdeku()
	{
		return ($this->current_get_item['kozerdeku'] == 1 ? true : false);
	}
	public function getMaxPage()
	{
		return $this->max_page;
	}
	public function getCurrentPage()
	{
		return $this->current_page;
	}
  public function getOptional( $what, $json_decode = false )
  {
    if ($json_decode) {
      return json_decode($this->current_get_item['optional_'.$what], \JSON_UNESCAPED_UNICODE);
    } else {
      return $this->current_get_item['optional_'.$what];
    }
  }
	public function categoryList( $arg = array() )
	{
    if (isset($arg['archiv'])) {
      $archivfilter = true;
    }
		$q = "SELECT
      c.*,
      (SELECT count(cx.cikk_id) FROM cikk_xref_cat as cx LEFT OUTER JOIN hirek as h ON h.ID = cx.cikk_id WHERE cx.cat_id = c.ID and h.lathato = 1 ".( ($archivfilter)?'and h.archiv = 1':'' )." ) as postc
    FROM cikk_kategoriak as c
    WHERE
      1=1 and
      (SELECT count(cx.cikk_id) FROM cikk_xref_cat as cx LEFT OUTER JOIN hirek as h ON h.ID = cx.cikk_id WHERE cx.cat_id = c.ID and h.lathato = 1 ".( ($archivfilter)?'and h.archiv = 1':'' )." ) != 0
    ORDER BY c.sorrend ASC";

		$qry = $this->db->squery( $q, array());

		if ($qry->rowCount() == 0) {
			return array();
		} else {
			$data = $qry->fetchAll(\PDO::FETCH_ASSOC);
			$bdata = array();
			foreach ($data as $d) {
				$d['label'] = '<span class="cat-label" style="background-color:'.$d['bgcolor'].';">'.$d['neve'].'</span>';
				$bdata[$d['slug']] = $d;
			}
			unset($data);
			unset($qry);

			return $bdata;
		}
	}
	public function getCategories( $by_cikk = true )
	{
		$q = "SELECT
			c.cat_id,
			ct.neve,
      ct.slug,
			ct.bgcolor
		FROM
		cikk_xref_cat as c
		LEFT OUTER JOIN cikk_kategoriak as ct ON ct.ID = c.cat_id
		WHERE 1=1 and ";
		if ($by_cikk) {
				$q .= " c.cikk_id = :cikk";
		}

		$q .= "	ORDER BY ct.sorrend ASC";

		$param = array();

		if ($by_cikk) {
			$param['cikk'] = $this->getId();
		}

		$qry = $this->db->squery( $q, $param);

		if ($qry->rowCount() == 0) {
			return array();
		} else {
			$data = $qry->fetchAll(\PDO::FETCH_ASSOC);
			$inids = array();

			$bdata = array();
			foreach ($data as $d) {
				$d['label'] = '<span class="cat-label" style="background-color:'.$d['bgcolor'].';">'.$d['neve'].'</span>';
				$inids[] = $d['cat_id'];
				$bdata[] = $d;
			}
			unset($data);
			unset($qry);

			return array(
				'ids' => $inids,
				'list' => $bdata
			);
		}
	}
	/*-----  End of GETTERS  ------*/
	public function __destruct()
	{
		$this->db = null;
		$this->tree = false;
		$this->max_page = 1;
		$this->current_page = 1;
		$this->current_item = false;
		$this->current_get_item = false;
		$this->tree_steped_item = false;
		$this->tree_items = 0;
		$this->walk_step = 0;
		$this->selected_news_id = false;
		$this->item_limit_per_page = 50;
		$this->sitem_numbers = 0;
	}
}
?>
