<?php
$host= 'ftp_host';
$user = 'ftp_user';
$src__dir = '/home/idehwebc/public_html';
$des__dir = '/public_html';
$password = 'ftp_password';
$ftpConn = ftp_connect($host);
$login = ftp_login($ftpConn,$user,$password);
ftp_pasv($ftpConn, true) or die("Unable switch to passive mode");
// check connection
if ((!$ftpConn) || (!$login)) {
    echo 'FTP connection has failed! Attempted to connect to '. $host. ' for user '.$user.'.';
}
function ftp_putAll($conn_id, $src_dir, $dst_dir)
{
    $d = dir($src_dir);
    while ($file = $d->read()) { // do this for each file in the directory
        if ($file != "." && $file != "..") { // to prevent an infinite loop
            if (is_dir($src_dir . "/" . $file)) { // do the following if it is a directory
                if (!@ftp_nlist($conn_id, $dst_dir . "/" . $file)) {
                    echo "create directory".$dst_dir . "/" . $file."<br>";
                    ftp_mkdir($conn_id, $dst_dir . "/" . $file); // create directories that do not yet exist
                }
                ftp_putAll($conn_id, $src_dir . "/" . $file, $dst_dir . "/" . $file); // recursive part
            } else {
                $upload = ftp_put($conn_id, $dst_dir . "/" . $file, $src_dir . "/" . $file, FTP_BINARY); // put the files
                if($upload){
                    echo "create file".$src_dir . "/" . $file."<br>";
                }else{
                    echo "not creating file".$src_dir . "/" . $file."<br>";

                }
            }
        }
    }
    $d->close();
}
ftp_putAll($ftpConn,$src__dir,$des__dir);
ftp_close($ftpConn);
