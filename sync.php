<?php
if ( 'cli' !== php_sapi_name() ) {
    die('Command line only!');
}

$fileState = is_file('status.php') ? require 'status.php' : array();

function filterInvalidateFileByName( $name ) {
    return !('.'===$name[0]);
}

function initDirectory( $path ) {
    global $fileState;
    
    if ( is_dir($path) ) {
        printf("init directory %s\n", $path);
        if ( !isset($fileState[$path]) ) {
            $fileState[$path]['type']='dir';
            $fileState[$path]['remote']=null;
        }
        
        $files = array_filter(scandir($path), 'filterInvalidateFileByName');
        foreach ( $files as $index => $fileName ) {
            $subPath = $path.DIRECTORY_SEPARATOR.$fileName;
            initDirectory($subPath);
        }
    } else {
        if ( !isset($fileState[$path]) ) {
            $fileState[$path]['type']='file';
            $fileState[$path]['md5']=null;
            $fileState[$path]['remote']=null;
        }
    }
}

printf("init directory...\n");
initDirectory('.');
printf("%d files in line...\n", count($fileState));

function shutdown() {
    global $fileState, $processedCount;
    file_put_contents('status.php', "<?php \n return ".var_export($fileState, true).';');
    printf("%d files has been synced.\n", $processedCount);
}

register_shutdown_function('shutdown');

$timeStarted = time();
$totalFileCount = count($fileState);
$processedCount = 0;
function syncPath( $connect, $path, $remotePath='/' ) {
    global $fileState, $processedCount, $totalFileCount, $timeStarted;
    $processedCount ++;
    $percentage = ((int)(($processedCount/$totalFileCount)*10000))/100;
    $timeDiff = time()-$timeStarted;
    $timeSpend = array(0,0,0);
    if ( 3600 <= $timeDiff ) {
        $timeSpend[0] = ((int)($timeDiff/3600));
        $timeDiff %= 3600; 
    }
    if ( 60 <= $timeDiff ) {
        $timeSpend[1] = ((int)($timeDiff/60));
        $timeDiff %= 60;
    }
    $timeSpend[2] = $timeDiff;
    $timeSpend = implode(':', $timeSpend);
    
    $displayPath = $remotePath;
    $displayMaxLength = 48;
    if ( $displayMaxLength < strlen($displayPath) ) {
        $displayPath = explode('/', $displayPath);
        $displayFileName = array_pop($displayPath);
        $displayPathLength = $displayMaxLength - strlen($displayFileName) - 4;
        $displayPath = implode('/', $displayPath);
        $displayPath = substr($displayPath, 0, $displayPathLength);
        $displayPath = $displayPath.'.../'.$displayFileName;
    }
    
    if ( is_dir($path) ) {
        printf("cd  %-48s %4d/%d %05.2f%% %8s\n", $displayPath, $processedCount, $totalFileCount, $percentage, $timeSpend);
        if ( null === $fileState[$path]['remote'] ) {
            $fileState[$path]['remote']=$remotePath;
            
            $remoteDir = ftp_nlist($connect, dirname($remotePath));
            if ( false === $remoteDir ) {
                die(sprintf("Unable to get file list in '%s'.\n", dirname($remotePath)));
            }
            $remoteCurrentDirName = array_pop(explode('/', $remotePath));
            if ( !empty($remoteCurrentDirName) && !in_array($remoteCurrentDirName, $remoteDir) ) {
                ftp_mkdir($connect, $remotePath);
            }
        }
        
        if ( '/' === $remotePath ) {
            $remotePath = '';
        }
        
        $files = array_filter(scandir($path), 'filterInvalidateFileByName');
        foreach ( $files as $index => $fileName ) {
            $subPath = $path.DIRECTORY_SEPARATOR.$fileName;
            syncPath($connect, $subPath, $remotePath.'/'.$fileName);
        }
    } else {
        $md5 = md5_file($path);
        if ( (null===$fileState[$path]['remote']) || ($fileState[$path]['md5']!==$md5) ) {
            $isUploaded = ftp_put($connect, $remotePath, $path, FTP_BINARY);
            if ( false === $isUploaded ) {
                die(printf("Fail to upload '%s'\n", $path));
            }
            
            printf("put %-48s %4d/%d %05.2f%% %8s\n", $displayPath, $processedCount, $totalFileCount, $percentage, $timeSpend);
            $fileState[$path]['md5']=$md5;
            $fileState[$path]['remote']=$remotePath;
        }
    }
}

$connect = ftp_connect('www.lunome.com');
ftp_login($connect, 'michael@lunome.com', 'ginhappy1215');
syncPath($connect, '.');
file_put_contents('status.php', "<?php \n return ".var_export($fileState, true).';');

function syncDeletedFiles( $path ) {
    global $fileState, $connect;
    
    $info = $fileState[$path];
    if ( 'dir' === $info['type'] ) {
        $files = ftp_nlist($connect, $info['remote']);
        if ( false === $files ) {
            die(sprintf("Unable to get file list in '%s'.\n", $info['remote']));
        }
        $files = array_filter($files, 'filterInvalidateFileByName');
        ---------------
    } else {
        ftp_delete($connect, $info['remove']);
    }
}

foreach ( $fileState as $filePath => $fileInfo ) {
    
}