<?php

$s = opcache_get_status();
echo 'OPcache: '.($s ? '✅ AKTIF' : '❌ NONAKTIF').'<br>';
echo 'Cached scripts: '.($s['opcache_statistics']['num_cached_scripts'] ?? 0).'<br>';
echo 'Memory used: '.round($s['memory_usage']['used_memory'] / 1024 / 1024, 2).' MB';
