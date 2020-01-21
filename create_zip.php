<?php
$folderZip="groupfile";
function copyfile($data_file,$folderZip){
    if(file_exists("./myzipfile.zip")){
        unlink("./myzipfile.zip");
    }
    if(is_dir("./".$folderZip)){
        delete_directory("./".$folderZip);
    }
    if(!is_dir("./".$folderZip)){
        mkdir("./".$folderZip, 0777);
    }
    foreach ($data_file as $item){
        copy($item['pathfile'],"./".$folderZip."/".$item['file_name']);
    }

}
function delete_directory($dirname) {
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname."/".$file))
                unlink($dirname."/".$file);
            else
                delete_directory($dirname.'/'.$file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}
if(isset($_POST['create']) && !empty($_POST['create'])){

    copyfile($_POST['data-file'],$folderZip);

    $zip = new ZipArchive();
    $filename = "./myzipfile.zip";

    if ($zip->open($filename, ZipArchive::CREATE)!==TRUE) {
        exit("cannot open <$filename>\n");
    }

    $dir = $folderZip.'/';

    // Create zip
    createZip($zip,$dir);

    $zip->close();
}

// Create zip
function createZip($zip,$dir){
    if (is_dir($dir)){

        if ($dh = opendir($dir)){
            while (($file = readdir($dh)) !== false){

                // If file
                if (is_file($dir.$file)) {
                    if($file != '' && $file != '.' && $file != '..'){

                        $zip->addFile($dir.$file);
                    }
                }else{
                    // If directory
                    if(is_dir($dir.$file) ){

                        if($file != '' && $file != '.' && $file != '..'){

                            // Add empty directory
                            $zip->addEmptyDir($dir.$file);

                            $folder = $dir.$file.'/';

                            // Read data of the folder
                            createZip($zip,$folder);
                        }
                    }

                }

            }
            closedir($dh);
        }
    }
}

?>
