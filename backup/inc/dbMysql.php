<?
//implements DB
/*
private $kad="infopazari-com";
private $pass="dkui538";
private $database="infopazaricom";
private $IP="mysql5.turkticaret.net";
 *
 private $kad="infopsql";
private $pass="bktR381";
private $database="infopazari";
private $IP="localhost";
*/
class dbMysql {
    var $ready=true;
    var $dbh;

    private $kad="alanadiara";
private $pass="t6yH5gh6d4gsa";
private $database="alanadiara";
private $IP="localhost";

    function __construct() {
        register_shutdown_function(array(&$this, "__destruct"));
        if(!$this->dbh = mysql_connect($this->IP, $this->kad, $this->pass, true)) {
            echo "HATA : ".mysql_error();
        }
        if (!@mysql_select_db($this->database, $this->dbh)) {
            $this->ready=false;
            return;
        }
        @mysql_query( "SET NAMES 'UTF8'" );
        @mysql_query( "SET CHARACTER SET 'utf8_turkish_ci'" );
        @mysql_query( "COLLATE 'utf8_turkish_ci'" );
    }
    function __destruct() {
        @mysql_close($this->dbh);
        return true;
    }
    public function insert($table, $data,$idg=true) {
        if ( ! $this->ready )return false;
        $data = add_magic_quotes($data);
        $fields = array_keys($data);
        $result = @mysql_query("INSERT INTO $table (`".implode('`,`',$fields)."`) VALUES ('".implode("','",$data)."')", $this->dbh);
        $insert_id=0;
        if($result) {
            if($idg)$insert_id = mysql_insert_id($this->dbh);
            @mysql_free_result($result);
        }
        return $idg ? $insert_id : $result;
    }
    public function delete($table, $where) {
        if ( ! $this->ready )return false;
        if ( is_array( $where ) ) {
            foreach ( $where as $c => $v ) $wheres[] = "$c = '" . escape( $v ) . "'";
        }else return false;
        $result = @mysql_query("DELETE FROM $table WHERE " . implode( ' AND ', $wheres ), $this->dbh);
        if($result)@mysql_free_result($result);
        return $result;
    }
    public function update($table, $data, $where) {
        if ( ! $this->ready )return false;
        $data = add_magic_quotes($data);
        $bits = $wheres = array();
        foreach ( array_keys($data) as $k )$bits[] = "`$k` = '$data[$k]'";
        if ( is_array( $where ) ) {
            foreach ( $where as $c => $v ) $wheres[] = "$c = '" . escape( $v ) . "'";
        }else return false;
        //echo "UPDATE $table SET " . implode( ', ', $bits ) . ' WHERE ' . implode( ' AND ', $wheres );
        $result = @mysql_query("UPDATE $table SET " . implode( ', ', $bits ) . ' WHERE ' . implode( ' AND ', $wheres ), $this->dbh);
        if($result)@mysql_free_result($result);
        return $result;
    }
    public function updateSql($sql) {
        $result = @mysql_query($sql, $this->dbh);
        if($result)@mysql_free_result($result);
        return $result;
    }
    private function get_fields($result) {
        for ($i = 0; $i < mysql_num_fields($result); $i++) {
            $col_info[$i] = @mysql_field_name($result,$i);//@mysql_fetch_field($result);
        }
        return $col_info;
    }
    public function get_rows($query = null) {
        if ( ! $this->ready )return false;
        $result = @mysql_query($query, $this->dbh);
        if(!$result) return false;
        $i = -1;
        $columns = $this->get_fields($result);
        while ( $row = @mysql_fetch_object($result) ) {
            $r_result[++$i] = del_magic_quotes($row,$columns);
        }
        @mysql_free_result($result);
        return empty($r_result) ? false : $r_result;
    }
    public function get_row($query = null,$col = 0) {
        $a=$this->get_rows($query);
        if(!$a)return false;
        return $a[$col];
    }
    public function num_rows($query="") {
        $result = @mysql_query($query, $this->dbh);
        $num=mysql_num_rows($result);
        return $num;
    }
}
function de_escape($string) {
    return stripslashes($string);
}
function del_magic_quotes( $array,$fields) {
    foreach($fields as $field)$array->$field = de_escape($array->$field);
    return $array;
}
function escape($string) {
    return (!get_magic_quotes_gpc() ? addslashes( $string ) : $string );
}
function add_magic_quotes( $array ) {
    foreach ( $array as $k => $v ) $array[$k]=escape( $v );
    return $array;
}
?>
