<?php

/**
 * @author Zsolt Boszormenyi <hunnomad@gmail.com>
 * @copyright 2014
 */

$sql = new SQL("localhost","root",'','test','en_US');

// Insert record
$iData = array('nev'=>'Böszörményi Imre Zsolt','szulEv'=>'1969.04.08','szulHely'=>'Gyula','anyjaNeve'=>'Seres Ilona');
echo $sql->insert('probaTabla',$iData);

// Update data
$iDataUpdate = array('nev'=>'Imre Zsolt Boszormenyi','szulEv'=>'1969.04.08','szulHely'=>'Gyula','anyjaNeve'=>'Seres Ilona');
echo $sql->update("probaTabla",$iDataUpdate,"id='1'");

//Delete record
echo $sql->delete("probaTabla","id='1'");

// Select data
$fieldArray = array('nev','szulEv','szulHely','anyjaNeve');
$return = $sql->select("probaTabla",$fieldArray,"nev like 'Zsolt%'",'nev ASC',0);
print_r($return);

// Select one record
$fieldArray = array('nev','szulEv','szulHely','anyjaNeve');
$return = $sql->selectOne("probaTabla",$fieldArray,"nev like 'Zsolt%'",'nev ASC');
print_r($return);

?>