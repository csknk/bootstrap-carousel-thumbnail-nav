
/*==============================================================================
    Set up
==============================================================================*/
   // Give first items emphasis classes
   $(".carousel-inner .item:first").addClass("active");
   $("#thumb-carousel li:first").addClass("selected");
   $("#thumb-carousel .item:first").addClass("active");

   // Prevent auto play
   $('#myCarousel').carousel({
     interval: false
   });
   $('#thumb-carousel').carousel({
     interval: false
   });


/*==============================================================================
    Listen to the 'slid carousel' event to trigger code after each slide change
==============================================================================*/

  $('#myCarousel').on('slid.bs.carousel', function () {

    // Useful variables
    var carouselData = $(this).data('bs.carousel');
    var currentIndex = carouselData.getActiveIndex();
    var total = carouselData.$items.length;

    // Create the text we want to display.
    // We increment the index because humans don't count like machines
    //var text = (currentIndex + 1) + " of " + total;
    // You have to create a HTML element <div id="carousel-index"></div>
    // under your carousel to make this work
    //$('#carousel-index').text(text);

    $('[id^=carousel-selector-]').removeClass('selected');
    $('[id^=carousel-selector-'+(currentIndex)+']').addClass('selected');

    // slide to the thumbnail slider item that contains the current myCarousel slide

    /*========================================================================*/
    // Remove active class from #item-x
    //$('[id^=item-]').removeClass('active');
    // Which item contains the current thumbnail
    //$( "[id^=item-]" ).has("[id^=carousel-selector-"+(currentIndex)+"]").addClass( "active" );

    /*========================================================================*/

    var targetItem = $( "[id^=item-]" ).has("[id^=carousel-selector-"+(currentIndex)+"]");
    var id_selector = $(targetItem).attr("id");
    var id = id_selector.substr(id_selector.length -1);
    id = parseInt(id, 10);
    id = (id -1);

    // Create a HTML element <div id="carousel-index"></div>
    $('#carousel-index').text(id);


    //if (( '#thumb-carousel .active' ).has("[id^=carousel-selector-"+(currentIndex)+"]")){}
    //else {$('#thumb-carousel').carousel( id );}// force slide to this item
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
