function GBsubstr($string, $start, $length) {
if(strlen($string)>$length){
$str=null;
$len=$start+$length;
for($i=$start;$i<$len;$i++){
if(ord(substr($string,$i,1))>0xa0){
$str.=substr($string,$i,2);
$i++;
}else{
$str.=substr($string,$i,1);
}
}
return $str.'...';
}else{
return $string;
}
}