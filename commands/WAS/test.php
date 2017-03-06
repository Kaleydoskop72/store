<?php

$str = "h1 == МТ-003/1 Год козы 2015";
//preg_match('/([а-яёА-Я]+-\d+)\s+(.+)/', $str, $matches);
preg_match('/([а-яёА-Я]+-\d+\/\d+)\s+(.+)/', $str, $matches);
print_r($matches);


