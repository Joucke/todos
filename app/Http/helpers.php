<?php

use Illuminate\Support\Facades\Route;

function current_url(String $url) {
    return $url === url()->current();
}
