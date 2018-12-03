<?php

function presentPrice($price){
    return '$'.number_format($price / 100, 2);
}
function setActive($category_name,$currentName,$className='active'){
    return ($category_name == $currentName)?$className: "";
}
function imagePath($path){
    return ($path && file_exists('storage/'.$path))?asset('storage/'.$path):'https://via.placeholder.com/300x300';
}