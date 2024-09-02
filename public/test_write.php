<?php
$file = 'uploads/perfiles/test.txt';
$content = 'Test file for writing permissions.';

if (file_put_contents($file, $content)) {
    echo 'File written successfully!';
} else {
    echo 'Failed to write file.';
}
?>
