<?php

return [
    'immages_space_path' => env('FILESYSTEM_DRIVER') == 'spaces' ? 'https://laravelstorage1.sgp1.digitaloceanspaces.com/' : "http://" . env("MEMCACHED_HOST") . "/storage/",
];
