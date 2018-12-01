<?php

function presentPrice($price){
    return '$'.number_format($price / 100, 2);
}
function setActive($category_name,$currentName,$className='active'){
    return ($category_name == $currentName)?$className: "";
}