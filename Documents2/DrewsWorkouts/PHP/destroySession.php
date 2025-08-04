<?php

       session_start();
       session_destroy();
       echo '<h1>session destroyed</h1>';