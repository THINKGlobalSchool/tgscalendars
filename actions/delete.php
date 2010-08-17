<?php

$guid = get_input('guid');
delete_entity($guid);
forward($vars['url'].'pg/calendar_admin');

?>