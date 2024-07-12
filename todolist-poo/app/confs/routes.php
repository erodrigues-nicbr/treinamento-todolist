<?php 

$route = new Routes();
$route->add('/', 'pages/list.php');
$route->add('/tasks/done', 'pages/done-list.php');
$route->add('/tasks/pend', 'pages/pend-list.php');

$route->add('/api/tasks', 'api/tasks.php', [ 'layout' => false ]);
$route->add('/api/tasks/{id}', 'api/tasks.php', [ 'layout' => false ]);
$route->add('/api/tasks/{id}/done', 'api/tasks.php', [ 'layout' => false ]);

$route->dispatch();
