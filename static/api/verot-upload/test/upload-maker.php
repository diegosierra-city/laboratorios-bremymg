<?php
//namespace Verot\Upload;

error_reporting(E_ALL);
ini_set('display_errors', '1');
// we first include the upload class, as we will need it here to deal with the uploaded file
include('../src/class.upload.php');

$log = '';

    //error_reporting(E_ALL ^ (E_NOTICE | E_USER_NOTICE | E_WARNING | E_USER_WARNING));
    ini_set("max_execution_time",0);
    // we don't upload, we just send a local filename (image)
    $handle = new \Verot\Upload\Upload($_FILES['uploadFile']);

    // then we check if the file has been "uploaded" properly
    // in our case, it means if the file is present on the local file system
    if ($handle->uploaded) {

        // -----------
        //////TestProcess($handle, 'original file', '');
/*
        // -----------
        $handle->image_resize          = true;
        $handle->image_ratio_y         = true;
        $handle->image_x               = 150;
        */

        // -----------
        $handle->image_resize          = true;
        $handle->image_ratio_x         = true;
        $handle->image_y               = 150;
        $handle->file_new_name_body = 'prueba';
       // $handle->file_new_name_ext = 'png';
        $handle->file_overwrite = true;
        $handle->process('pages/');
        echo '+++++++++++++++++++++++++';

        /*
        // -----------
        $handle->image_resize          = true;
        $handle->image_y               = 150;
        $handle->image_x               = 150;
        
        
        // -----------
        $handle->image_resize          = true;
        $handle->image_ratio_crop      = true;
        $handle->image_y               = 50;
        $handle->image_x               = 50;
        

        
        // -----------
        $handle->image_crop            = '20%';
        

        // -----------
        $handle->image_rotate          = '90';
       

                // -----------
        $handle->image_convert         = 'png';
        $handle->image_flip            = 'H';
        $handle->image_rotate          = '90';
        

       

        // -----------
        $handle->image_unsharp         = true;
       


        // -----------
        $handle->image_text            = 'verot.net';
        $handle->image_text_direction  = 'v';
        $handle->image_text_background = '#000000';
        $handle->image_text_font       = 2;
        $handle->image_text_position   = 'BL';
        $handle->image_text_padding_x  = 2;
        $handle->image_text_padding_y  = 8;
                

        // -----------
        $handle->image_text            = 'verot.net';
        $handle->image_text_background = '#0000FF';
        $handle->image_text_background_opacity = 25;
        $handle->image_text_x          = 5;
        $handle->image_text_y          = 5;
        $handle->image_text_padding    = 20;
        

        // -----------
        $handle->image_unsharp         = true;
        $handle->image_border          = '0 0 16 0';
        $handle->image_border_color    = '#000000';
        $handle->image_text            = 'verot.net';
        $handle->image_text_font       = 2;
        $handle->image_text_position   = 'B';
        $handle->image_text_padding_y  = 2;
        TestProcess($handle, 'text label in a black line, plus unsharp mask', "\$foo->image_unsharp         = true;\n\$foo->image_border          = '0 0 16 0';\n\$foo->image_border_color    = '#000000';\n\$foo->image_text            = \"verot.net\";\n\$foo->image_text_font       = 2;\n\$foo->image_text_position   = 'B';\n\$foo->image_text_padding_y  = 2;");

       
        // -----------
        $handle->image_reflection_height = '50%';
        $handle->image_text    = "verot.net\nclass\nupload";
        $handle->image_text_background = '#000000';
        $handle->image_text_padding    = 10;
        $handle->image_text_line_spacing = 10;
        TestProcess($handle, 'text label and 50% reflection', "\$foo->image_text            = \"verot.net\\nclass\\nupload\";\n\$foo->image_text_background = '#000000';\n\$foo->image_text_padding    = 10;\n\$foo->image_text_line_spacing = 10;\n\$foo->image_reflection_height = '50%';");

        
        // -----------
        $handle->image_reflection_height = 60;
        $handle->image_reflection_space = -40;
        TestProcess($handle, '60px reflection and -40 pixels space', "\$foo->image_reflection_height = 60;\n\$foo->image_reflection_space = -40;");

        

        // -----------
        $handle->image_watermark       = "watermark.png";
        TestProcess($handle, 'overlayed watermark (alpha transparent PNG)', "\$foo->image_watermark       = 'watermark.png';");

        

        // -----------
        $handle->image_watermark       = "watermark_large.png";
        TestProcess($handle, 'large watermark automatically reduced (default)', "\$foo->image_watermark       = 'watermark_large.png';");

        // -----------
        $handle->image_watermark       = "watermark_large.png";
        $handle->image_watermark_no_zoom_out = true;
        $handle->image_watermark_position = 'TL';
        TestProcess($handle, 'large watermark, down-resizing deactivated, position top-left', "\$foo->image_watermark       = 'watermark_large.png';\n\$foo->image_watermark_no_zoom_out = true;\n\$foo->image_watermark_position = 'TL'");

        // -----------
        $handle->image_watermark       = "watermark_large.png";
        $handle->image_watermark_x     = 20;
        $handle->image_watermark_y     = -20;
        TestProcess($handle, 'large watermark automatically reduced, position 20 -20', "\$foo->image_watermark       = 'watermark_large.png';\n\$foo->image_watermark_x     = 20;\n\$foo->image_watermark_y     = -20;");

        

        // -----------
        $handle->image_convert         = 'jpg';
        $handle->jpeg_quality          = 80;
        TestProcess($handle, 'JPG quality set to 80%', "\$foo->image_convert         = 'jpg';\n\$foo->jpeg_quality          = 80;");

        
        // -----------
        $handle->image_convert         = 'png';
        $handle->png_compression       = 9;
        TestProcess($handle, 'PNG compression set to 9 (slow, smaller files)', "\$foo->image_convert         = 'png';\n\$foo->png_compression       = 9;");

       
        // -----------
        $handle->image_convert         = 'webp';
        $handle->webp_quality          = 80;
        TestProcess($handle, 'WEBP quality set to 80%', "\$foo->image_convert         = 'webp';\n\$foo->webp_quality          = 80;");
*/

    } else {
        // if we are here, the local file failed for some reasons
        echo '<b>local file error</b><br />';
        echo 'Error: ' . $handle->error . '';
    }

    $log .= $handle->log . '<br />';


echo '<p class="result"><a href="index.html">do another test</a></p>';

if ($log) echo '<pre>' . $log . '</pre>';

?>
