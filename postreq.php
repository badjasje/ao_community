<?php
    $target_url = 'https://www.dumpstorehengelo.nl/RPC/productlist/update';
        //This needs to be the full path to the file you want to send.
    $file_name_with_full_path = realpath('./test.csv');
    
        /* curl will accept an array here too.
         * Many examples I found showed a url-encoded string instead.
         * Take note that the 'key' in the array will be the key that shows up in the
         * $_FILES array of the accept script. and the at sign '@' is required before the
         * file name.
         */
    $post = array('file' => new CurlFile($file_name_with_full_path), 'text/plain' /* MIME-Type */, 'test.csv');
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $target_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result=curl_exec($ch);
    curl_close($ch);
    echo $result;
