<?php

function get_filename_from_storage_url($url)
{
  return str_replace(config('app.url') . '/storage/', '', $url);
}
