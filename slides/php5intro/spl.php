<?php
class dba_reader 
	implements spl_iterator {
    public $db = NULL;

    function __construct($file, $handler) 
	{
        $this->db = dba_open(
			$file, 
			'r', 
			$handler
		);
    }
    
    function new_iterator() {
        return new dba_iter($this);
    }
}

class dba_iter 
	implements spl_sequence_assoc 
{
    private $obj;
    private $key = NULL;
    private $val = NULL;

    function __construct($obj) {
        $this->obj = $obj;
    }

    function reset() {
        if (!$this->obj->db) {
			return;
		}
        $this->key = 
			dba_firstkey($this->obj->db);
    }

    function elem() {
        return $this->val;
    }

    function next() {
        $this->key = 
			dba_nextkey($this->obj->db);
    }

    function more() {
        if ($this->obj->db && 
			$this->key !== false) {
            $this->val = dba_fetch(
				$this->key, 
				$this->obj->db
			);
            return true;
        } else {
            return false;
        }
    }

    function key() {
        return $this->key;
    }
}

$db = new dba_reader($argv[1], $argv[2]);
foreach($db as $key => $val) {
    echo "'$key' => '$val'\n";
}

?>
