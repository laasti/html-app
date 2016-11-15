<?php

define('BASEPATH', __DIR__ );
define('APPVERSION', '');

require BASEPATH.(APPVERSION ? '/'.APPVERSION : '').$bootfile;
