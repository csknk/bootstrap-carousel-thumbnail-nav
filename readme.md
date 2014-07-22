A Bootstrap carousel with thumbnail controls for WordPress.

Fork or clone your own version of this repo.

##Quick Start
* Paste contents of carousel-linked-thumbnails.php into the theme custom.php file
* Create ACF repeater field called **carousel_images**
* Create a sub field **image** - set this to return image ID
* If using the Roots framework, add **js/_carousel.js** to the theme assets/js/vendor directory
* If you use a different directory for the js files, remember to reference it properly in enqueue.php
* Include **jquery.touchSwipe.min.js** and **touchControl.js** in assets/js/vendor for mobile swipe support.
* Enqueue the javascript files by adding the contents of **enqueue.php** to your theme custom.php file, or /lib/scripts.php for the Roots framework
* Include the contents of carousel-linked-thumbnails.css in the theme CSS/LESS

---

##Introduction

This is really two linked carousels:

* A full size carousel, populated from an ACF repeater field
* A thumbnail sized carousel, populated from the same field

The thumbnail carousel targets the main carousel by means of the Bootstrap **data-target** attribute. The following code block shows how the carousel with id "myCarousel" is targeted. $count works as a counter, and because both sliders are populated from the same repeater field cross-referencing is easy.

{% highlight html  startinline%}
?>
<li data-target="#myCarousel" id="carousel-selector-<?php echo $count; ?>"data-slide-to="<?php echo $count; ?>">
    <img src="<?php echo $thumbimage[0]; ?>" />
</li>
<?php
{% endhighlight html %}

The data attribute **data-slide-to** is used to pass a raw slide index to myCarousel - it does what it says on the tin. In this case, clicking the thumbnail triggers a slide to the $count slide on the main carousel.

##Building the Carousels
The carousel is dynamic - it takes advantage of the Advanced Custom Fields WordPress plugin that allows the user to upload/select images.

* An ACF field group is built, and associated with a Custom Post Type (in this case, the CPT "project").
* A repeater field is added - the repeater field is a premium add-on for ACF (it is extremely useful and ridiculously under-priced).
* An "image" sub-field is added to the repeater field.
* If necessary, extra fields can be added to allow fine user control over what is displayed on each slide.
* The image data for each slide is held as a row in the ACF repeater field.

For these files, the repeater field is named **carousel_images**.

The image sub-field is named **image**.

##PHP function
The PHP function for building the carousels is in the file **carousel-linked-thumbnails.php**. The code in this file could be added to the theme **custom.php** file. View the code here:

{% highlight php  startinline%}

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
{% endhighlight php %}

When using the Roots framework, add the code to **lib/custom.php**.

The function can then be used in page templates. In this example, the function is added to the **content-single-project.php** template part:

{% highlight html  startinline%}

<div class="row">
  <div class="col-md-12">
    <?php carawebs_carousel_images_slider(); ?>
  </div>
</div>
{% endhighlight html %}

##Controls

Controls are built into **/js/_carousel.js**:

{% highlight js startinline%}

/*==============================================================================
    Set up - give first items emphasis classes.
    Necessary because the slider is built dynamically
==============================================================================*/
   $(".carousel-inner .item:first").addClass("active");

   // Give active class to the first thumbnail image
   $("#thumb-carousel li:first").addClass("selected");

   // Give active class to the first thumbnail item (image-block)
   $("#thumb-carousel .item:first").addClass("active");

   // Prevent auto play, both carousels
   $('#myCarousel').carousel({ interval: false });
   $('#thumb-carousel').carousel({ interval: false });


/*==============================================================================
    Listen to the 'slid carousel' event to trigger code after each slide change
==============================================================================*/

  $('#myCarousel').on('slid.bs.carousel', function () {

    // Useful variables
    var carouselData = $(this).data('bs.carousel');
    var currentIndex = carouselData.getActiveIndex();
    var total = carouselData.$items.length;

    // To display a slide counter for debugging, enable this block.
    // Create a HTML element <div id="carousel-count"></div> to display the text.
    //var text = (currentIndex + 1) + " of " + total;
    //$('#carousel-count').text(text);

    // Highlight the thumbnail corresponding to the currently displayed slide in the main carousel
    $('[id^=carousel-selector-]').removeClass('selected');
    $('[id^=carousel-selector-'+(currentIndex)+']').addClass('selected');

/*==============================================================================
    Display the thumbnail slider item that contains the current myCarousel slide
    Enable this code block for a simple shift (no slide)
==============================================================================*/
    // Remove active class from #item-x
    //$('[id^=item-]').removeClass('active');
    // Which item contains the current thumbnail
    //$( "[id^=item-]" ).has("[id^=carousel-selector-"+(currentIndex)+"]").addClass( "active" );

/*==============================================================================
    SLIDE to the thumbnail slider item that contains the current myCarousel slide
    Enable this code block for a sliding shift
==============================================================================*/
    // Find the item in the thumb carousel that contains the currently displayed main image
    var targetItem = $( "[id^=item-]" ).has("[id^=carousel-selector-"+(currentIndex)+"]");

    // Find the index number of this item by getting the last character of the id string
    var id_selector = $(targetItem).attr("id");
    var id = id_selector.substr(id_selector.length -1);

    // Parse the string, return as an integer in base 10 format
    id = parseInt(id, 10);

    // Make a correction, because the first thumbnail carousel item has a count of zero
    id = (id -1);

    // Create a HTML element <div id="carousel-index"></div> to display the item index for debugging
    $('#carousel-index').text(id);

    // Force #thumb-carousel to slide to the correct item
    $('#thumb-carousel').carousel( id );

/*==============================================================================
    Hiding Prev & Next Links at extremes
==============================================================================*/
    checkitem = function() {
    var $this;
    $this = $("#myCarousel");
        if ($("#myCarousel .carousel-inner .item:first").hasClass("active")) {
          $this.children(".left").hide();
          $this.children(".right").show();
        } else if ($("#myCarousel .carousel-inner .item:last").hasClass("active")) {
          $this.children(".right").hide();
          $this.children(".left").show();
        } else {
          $this.children(".carousel-control").show();
        }
    };

  checkitem();

  $("#myCarousel").on("slid.bs.carousel", "", checkitem);


}); // Close the "slid" function
{% endhighlight js %}

##WordPress: Enqueue Scripts
When working within WordPress it's important to enqueue scripts properly - this prevents inefficient double loading of libraries and ensures that scripts load rationally.

Within the Roots framework, the js files could be added to the assets/js folder and named with a leading underscore - they would then be concatenated into **scripts.min.js**. This isn't a bad option - but it means that unecessary kilobytes will be added to pages that don't contain the carousel.

Consider enqueueing the scripts only on the pages where they are required - in this case, the scripts are enqueued only on project CPT pages.

For better efficiency, the raw js files could be targeted by Grunt and uglified.

The enqueue script within the Roots framework are placed in **lib/scripts.php** in order to keep all script calls rational. In other setups the enqueue function could be added to the **custom.php** file.

{% highlight php startinline%}

function carawebs_carousel_control(){

	if ( is_singular( 'projects') ) { // only do this for "project" CPTs

    // Register the control script
    wp_register_script( 'carawebs_carousel', get_template_directory_uri() . '/assets/js/vendor/_carousel.js', array( 'jquery' ), null, true);
    wp_register_script('touchswipe', get_template_directory_uri() . '/assets/js/vendor/jquery.touchSwipe.min.js', array( 'jquery' ), null, true);
    wp_register_script('touchControl', get_template_directory_uri() . '/assets/js/vendor/touchControl.js', array( 'jquery' ), null, true);

	// Enqueue the carousel controls - they will be built into the footer
    wp_enqueue_script('carawebs_carousel');
    wp_enqueue_script('touchswipe');
    wp_enqueue_script('touchControl');
	}
}
// Add hooks for front-end
add_action('wp_enqueue_scripts', 'carawebs_carousel_control', 101);
{% endhighlight php %}

##Mobile Swipe Support
I used the [touchSwipe jQuery plugin](https://github.com/mattbryson/TouchSwipe-Jquery-Plugin/blob/master/jquery.touchSwipe.js) to achieve swipe support for mobile devices. This plugin is copyright (c) 2010 Matt Bryson and is dual licensed under the MIT or GPL Version 2 licenses.

I plan to build in conditional logic so that touchSwipe is only loaded for mobile devices.

{% highlight js startinline%}
$(document).ready(function() {

          //Enable swiping...
          $(".carousel-inner").swipe( {
            //Generic swipe handler for all directions
            swipeLeft:function(event, direction, distance, duration, fingerCount) {
              $(this).parent().carousel('next');
            },
            swipeRight: function() {
              $(this).parent().carousel('prev');
            },
            //Default is 75px, set to 0 for demo so any distance triggers swipe
            threshold:0;

            // Fixes the non-clickable thumbnail nav
            allowPageScroll: 'vertical';
          });
        });
{% endhighlight php %}

##Useful Tutorials

* [http://www.sitepoint.com/creating-javascript-sliders-using-twitter-bootstrap-3/](http://www.sitepoint.com/creating-javascript-sliders-using-twitter-bootstrap-3/)
