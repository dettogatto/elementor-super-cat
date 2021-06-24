(function($){
  $(document).ready(function(){

    $(".super-cat-dropdown-list").change(function(){
      var item = $(this).find(':selected');
      let container = item.attr("data-container");
      let term = item.attr("data-term");
      let posts = item.attr("data-posts");

      // hide all
      $("#"+posts).find('article').hide();
      // set all to inactive
      $(".super-cat-post-filter").removeClass("elementor-active");

      // Show / Hide all
      if (term == '') {
        // show all
        history.replaceState(null, null, ' ');
        $("#"+posts).find('article').fadeIn(400);
        $('.super-cat-post-filter[data-term=""]').addClass("elementor-active");
      } else {
        // show some
        $('.super-cat-post-filter[data-term="' + term + '"]').addClass("elementor-active");
        window.location.hash = "#" + term;
        $("#"+posts).find('article').each(function(){
          var classes = $(this).attr("class").split(" ");
          if(classes.includes(term)){
            $(this).fadeIn(400);
          }
        });
      }

    });

    if(window.location.hash){
      let hhh = window.location.hash.replace("#", "");
      var posts = "";
      $('.super-cat-dropdown-list').each(function(){
        let toSelect = $(this).find('option[data-term="' + hhh + '"]');
        if(toSelect.size() > 0){
          toSelect.attr('selected','selected');
          $(this).trigger('change');
        }
      });
    }

  });
})(jQuery);
