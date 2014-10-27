<?php

/**
 * @author Zsolt Boszormenyi <hunnomad@gmail.com>
 * @package mSql class by Zsolt Boszormenyi
 * @version 1.0
 * @copyright 26.10.2014
 */

class SQL{

    /** MySQL Connection params */
    private $server;
    private $user;
    private $passwd;
    private $dbname;
    private $connect;
    private $setLocale;
    
    /** 
     * @name Connect to MySQL database
     * @params server ip or name ,user name,password,database name,setLocale (default: hu_HU)
     * @return mysql connection ($this->connection)
     */
     
    function __construct($server,$user,$passwd,$dbname,$setLocale="hu_HU")
        {
        $this->server = $server;
        $this->user = $user;
        $this->passwd = $passwd;
        $this->dbname = $dbname;
        $this->setLocale = $setLocale;
     
        /** MySQL Connection */
        $this->connect = @mysqli_connect($this->server,$this->user,$this->passwd,$this->dbname) or die ("Can't connect to server...");
        @mysqli_set_charset($this->connect,"utf8");
        @mysqli_query($this->connect,"SET lc_time_names = '".$this->setLocale."';");
        }    
    
    /**
     * @name Insert data
     * @param table name, data with array (key = value) See the example
     * @return insert ID, integer
     */
     
    public function insert($tableName,$dataArray){
        if(isset($tableName)){
        $output = implode(', ', array_map(function ($v, $k) { return ''.$this->cleanData($k).''.'="'.$this->cleanData($v).'"'; }, $dataArray, array_keys($dataArray)));
        $query = "INSERT INTO ".$tableName." SET ".$output;
        #echo $query;
        $result = @mysqli_query($this->connect,$query);
        $id = @mysqli_insert_id($this->connect);
        if($id>0){$insertId=$id;}else{$insertId=0;}
        return $insertId;
        }else{die("Missing table name");}
    }

    /**
     * @name Update data
     * @param table name, data with array (key = value), conditions with stings, See the example
     * @return 1 = success, 0 = failed
     */
     
    public function update($tableName,$dataArray,$conditions){
        if(isset($tableName)){
            if($conditions!='' and $conditions!=null){
                $output = implode(', ', array_map(function ($v, $k) { return ''.$this->cleanData($k).''.'="'.$this->cleanData($v).'"'; }, $dataArray, array_keys($dataArray)));
                $query = "UPDATE ".$tableName." SET ".$output." WHERE ".$conditions;
                #echo $query;
                $result = @mysqli_query($this->connect,$query) or die("Wrong SQL code");
                if($result==1){
                    return 1;
                    }
                else{
                    return 0;
                    }
            }else{die("Missing conditions");} 
        }else{die("Missing table name");}
    }

    /**
     * @name Deleta data
     * @param table name, conditions with stings, See the example
     * @return 1 = success, 0 = failed
     */
     
    public function delete($tableName,$conditions=null){
        if(isset($tableName)){
            if($conditions==null){$conditions=null;}else{$conditions=" WHERE ".$conditions;}
            $query = "DELETE FROM ".$tableName.$conditions;
            $result = @mysqli_query($this->connect,$query) or die("Wrong SQL code");
            if($result==1){
                return 1;
                }
            else{
                return 0;
                }
        }else{die("Missing table name");}        
    }

    /**
     * @name Select data with where, orderby and limit
     * @param table name, conditions with stings (if the limit value is 0, show all data), See the example
     * @return array
     */
     
    public function select($tableName,$fieldArray,$conditions=null,$orderBy=null,$groupBy=null,$limit=10){
        if(isset($tableName)){
            $fields = implode(',',$fieldArray);
            $query = "SELECT ".$fields." FROM ".$tableName." ";
            if($conditions!=null and $conditions!=''){$query .=" WHERE ".$conditions;}
            if($orderBy!=null and $orderBy!=''){$query .= " ORDER BY ".$orderBy;}
            if($groupBy!=null and $groupBy!=''){$query .= " GROUP BY ".$groupBy;}
            if($limit!=null and $limit!='' and $limit!=0){$query .= " LIMIT ".$this->cleanData($limit,"n");}
            #echo $query;
            $result = mysqli_query($this->connect,$query);
            $values = array();
            while($values[] = mysqli_fetch_object($result)) continue;
            return $values;    
        }else{die("Missing table name");}
        mysqli_free_result($result);
        mysqli_close($this->connect);
    }

    /**
     * @name selectOne - Select data with where, orderby and 1 value
     * @param table name, conditions with stings, See the example
     * @return array
     */

    public function selectOne($tableName,$fieldArray,$conditions=null,$orderBy=null,$groupBy=null){
        if(isset($tableName)){
            $fields = implode(',',$fieldArray);
            $query = "SELECT ".$fields." FROM ".$tableName." ";
            if($conditions!=null and $conditions!=''){$query .=" WHERE ".$conditions;}
            if($orderBy!=null and $orderBy!=''){$query .= " ORDER BY ".$orderBy;}
            if($groupBy!=null and $groupBy!=''){$query .= " GROUP BY ".$groupBy;}
            $query .= " LIMIT 1";
            #echo $query;
            $result = mysqli_query($this->connect,$query);
            $values = array();
            while($values[] = mysqli_fetch_object($result)) continue;
            return $values;  
        }else{die("Missing table name");}
    }
    
    /**
     * @name cleanData - Clean and protect data
     * @param dataType : s = string, n=integer, f=float. Default value : String, See the example
     * @return protected strings
     */
    
    public function cleanData($string,$dataType="s"){
        switch($dataType){
            case "s" :
            $dstring = $string;
            $protectedString = mysqli_real_escape_string($this->connect,$dstring);            
            break;

            case "n" :
            $dstring = $string;
            $protectedString = intval($dstring);       
            break;
            
            case "f" :
            $dstring = $string;
            $protectedString = floatval($dstring);       
            break;
                        
            default :
            $dstring = $string;
            $protectedString = mysqli_real_escape_string($this->connect,$dstring);  
            break;
        }
    return $protectedString;
    }
}

/* Examples */
#$sql = new SQL("localhost","root",'','test');

// Insert and update data array
/*
$iData = array
    (
    'nev'=>'Böszörményi Imre Zsolt',
    'szulEv'=>'1969.04.08',
    'szulHely'=>'Gyula',
    'anyjaNeve'=>'Seres Ilona'
    );
*/

#echo $sql->insert('probaTabla',$iData);
#echo $sql->update("probaTabla",$iData,"id='1'");

//
// Delete record
#echo $sql->delete("probaTabla","id='1'");

// Search fields array
/*
$fieldArray = array
    (
    'nev',
    'szulEv',
    'szulHely',
    'anyjaNeve'
    );

#$return = $sql->select("probaTabla",$fieldArray,"nev like 'Zsolt%'",'nev ASC',0);
#$return = $sql->selectOne("probaTabla",$fieldArray,"nev like 'Zsolt%'",'nev ASC');

echo "<pre>";
print_r($return);
echo "</pre>";
*/
?>
