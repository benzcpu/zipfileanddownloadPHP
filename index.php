<?php
// Create ZIP file


// Download Created Zip file
if(isset($_POST['download'])){

    $filename = "myzipfile.zip";

    if (file_exists($filename)) {
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="'.basename($filename).'"');
        header('Content-Length: ' . filesize($filename));

        flush();
        readfile($filename);
        // delete file
//        unlink($filename);


    }
}
$dir = "./img/";
$files=array();
// Open a directory, and read its contents
if (is_dir($dir)){
    if ($dh = opendir($dir)){
        while (($file = readdir($dh)) !== false){
            $files[]=$file;

        }
        closedir($dh);
    }
}

?>
<!doctype html>
<html>
    <head>
      <title>How to create and download a Zip file using PHP</title>
      <link href='style.css' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div class='container'>

        <form method='post' action='./'>

            <input type='submit'id="download-zip" class="ui-hidden" name='download' value='Download' />
        </form>
            <input type='submit' name='create' id="create-zip" class="ui-hidden" value='Download Files' />&nbsp;
            <br/>
            <?php
            foreach ($files as $item){
               ?>
                <img class="select-image" width="100px" data-file_name="<?php echo $item; ?>" data-pathfile="<?php echo $dir.$item; ?>" src="<?php echo $dir.$item; ?>" alt="">
            <?php
            }
            ?>
        </div>
    </body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script>
    $('.select-image').click(function(){
        $(this).toggleClass('active');
        checkFile();
    });
    function checkFile(){
       if( $('.select-image.active').length>=1){
           $('#create-zip').removeClass("ui-hidden");
       }else{
           $('#create-zip').addClass("ui-hidden");
       }
    }
    $('#create-zip').click(function(){

        var file=[{}];
        jQuery.each($('.select-image.active'),function (idx,item) {
            file[idx]={'pathfile':$(item).attr('data-pathfile'),'file_name':$(item).attr('data-file_name')};
        })
        console.log(file);

        $.ajax({
                url:"./create_zip.php",
                method:"POST",
                data:{"data-file":file,'create':'true'},
                    success:function(res){
                    console.log(res);

                          $('#download-zip').click();
                    }
            });
    });
</script>
