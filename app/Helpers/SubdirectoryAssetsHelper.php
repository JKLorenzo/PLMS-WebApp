<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

if (!function_exists('subdirMix')) {
  function subdirMix($path)
  {
    return mix((App::environment('production') ? Config::get('app.dir') : '') . "/" . $path);
  }
}
