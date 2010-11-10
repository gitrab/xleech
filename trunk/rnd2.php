<?php
 function uniqueRand($n, $min = 0, $max = null)
 {
  if($max === null)
   $max = getrandmax();
  $array = range($min, $max);
  $return = array();
  $keys = array_rand($array, $n);
  foreach($keys as $key)
   $return[] = $array[$key];
  return $return;
 }

uniqueRand(5, 2);

?>
