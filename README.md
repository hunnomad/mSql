mSQL Class usage  / examples
----------------------------

// Start instantiation

$sql = new SQL("servername","username",'password','databasename');

# Insert and update data array
$iData = array('nev'=>'Böszörményi Imre Zsolt','szulEv'=>'1969.04.08','szulHely'=>'Gyula','anyjaNeve'=>'Seres Ilona');

echo $sql->insert('probaTabla',$iData);

echo $sql->update("probaTabla",$iData,"id='1'");

# Delete record
echo $sql->delete("probaTabla","id='1'");

# Search fields array

$fieldArray = array('nev','szulEv','szulHely','anyjaNeve');

$return = $sql->select("probaTabla",$fieldArray,"nev like 'Zsolt%'",'nev ASC',0);

$return = $sql->selectOne("probaTabla",$fieldArray,"nev like 'Zsolt%'",'nev ASC');

print_r($return);
