(function($){
  $(document).ready(function(){

    $(".super-cat-post-filter").each(function(){
      let container = $("#" + $(this).attr("data-container"));
      let posts = $(this).attr("data-posts");
      let term = $(this).attr("data-term");
      if(term != "" && container.attr("data-hide-empty") == "yes"){
        if($("#"+posts).find('article.' + term).length < 1){
          $(this).hide();
        }
      }
    });

    $(".super-cat-post-filter").click(function(event){
      let item = $(event.target);
      let term = item.attr("data-term");
      let posts = item.attr("data-posts");

      // hide all
      $("#"+posts).find('article').hide();
      // set all to inactive
      $(".super-cat-post-filter").removeClass("elementor-active");

      // sync option in Dropdown Filters
      $('.super-cat-dropdown-list').each(function(){
        let toSelect = $(this).find('option[data-term="' + term + '"]');
        if(toSelect.size() > 0){
          toSelect.attr('selected','selected');
        }else{
          $(this).find('option[data-term=""]').attr('selected','selected');
        }
      });

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
        $("#"+posts).find('article.' + term).fadeIn(400);
      }
    });

    if(window.location.hash){
      let hhh = window.location.hash.replace("#", "");
      $( 'li.elementor-portfolio__filter[data-term='+hhh+']' ).trigger("click");
    }

  });
})(jQuery);
