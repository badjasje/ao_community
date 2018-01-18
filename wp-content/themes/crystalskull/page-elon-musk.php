<?php
/*
 * Template Name: Elon Musk
 */
include "table_usermeta.php"
?>
<html>
    <body>
        <code style="display: block; white-space: pre-wrap">
            <?php
            $user = new Usermeta(1);
            
            $user->tryLock();
            $user->setLand("100000");
            $user->unlock();
            

            ?>
        </code>
    </body>
</html>

