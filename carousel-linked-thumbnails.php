/*==============================================================================
  Carousel from ACF Repeater Field.
==============================================================================*/
/*  This uses repeater field data, where the sub-field is the "image" type.
*   Set ACF to return image ID for the sub-field.
*   Repeater field name  = 'carousel_images'
*   Sub field 1          = 'image'
*/

function carawebs_carousel_images_slider() {

 $number = 0;
 $carousel_images = get_field('carousel_images');

  if(!empty($carousel_images) ){ // run if the repeater field has content
    ?>
    <div class="row">
        <!-- data-ride="carousel" causes auto play class="slide" causes slide-->
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
          <!-- Carousel items -->
          <div class="carousel-inner">
            <?php
            $slidecount = 1; //Allows unique ids on items

            while( has_sub_field('carousel_images') ){

              $attachment_id = get_sub_field('image');
  		        $size = "full"; // (thumbnail, medium, large, full or custom size)
  		        $image = wp_get_attachment_image_src( $attachment_id, $size );
  		        // url = $image[0];
  		        // width = $image[1];
  		        // height = $image[2];

              ?>
                  <div id="slide-<?php echo $slidecount; ?>" class="item">
                      <img src="<?php echo $image[0]; ?>" />
                  </div><?php

              $slidecount++;
            } // End the while loop

          ?>
          </div>
          <!-- Carousel nav -->
          <a class="left carousel-control" href="#myCarousel" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left"></span></a>
          <a class="right carousel-control" href="#myCarousel" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right"></span></a>

        </div>
<!--============================================================================
  The Thumbnail Navigation
=============================================================================-->
    <div class="container">
      <div class="carousel slide" id="thumb-carousel">
          <div class="carousel-inner">
            <?php

            $index = 0;
            $i = 1;
            //added before the while loop to ensure the div gets opened
            echo '<div id="item-' . $i . '" class="item"><ul class="carousel-thumbnails">';

            $rowcount = count(get_field('carousel_images')); // the total number of images - counts rows in the ACF repeater array

                while( has_sub_field('carousel_images') ){
                     $count = $index++;
                     /*===========================================================*/
                     $slidenumber = 4; // SET NUMBER OF SLIDES HERE
                     /*===========================================================*/
                     $thumb_id = get_sub_field('image');
                     $thumbsize = "thumbnail"; // (thumbnail, medium, large, full or custom size)
                     $thumbimage = wp_get_attachment_image_src( $thumb_id, $thumbsize );
                     // url = $thumbimage[0];
                     // width = $thumbimage[1];
                     // height = $thumbimage[2];

                     ?>
                      <li data-target="#myCarousel" id="carousel-selector-<?php echo $count; ?>"data-slide-to="<?php echo $count; ?>">
                         <img src="<?php echo $thumbimage[0]; ?>" />
                      </li><?php

                 // if multiple of 3 close div and open a new div
                 $itemNumber = ($i / $slidenumber) + 1;
                 if($i % $slidenumber == 0 && $i < $rowcount) {echo '</ul></div><div id="item-' . $itemNumber . '" class="item"><ul class="carousel-thumbnails">';}

                $i++; };
            //make sure open div is closed
            echo '</ul></div>';

            ?>
          </div><!-- /.carousel-inner -->
          <!-- Carousel nav -->
          <a class="left carousel-control" href="#thumb-carousel" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left"></span></a>
          <a class="right carousel-control" href="#thumb-carousel" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right"></span></a>
          </div>
          <div id="carousel-index"></div><!-- data passed here by jQuery for debugging purposes -->
      </div><!-- /.container for the #thumb-carousel -->
  </div><!-- /.row this contains both carousels -->
    <?php
  } // The initial if statement

  else {
    return;
    }

} // End the function
