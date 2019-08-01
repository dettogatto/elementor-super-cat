document.addEventListener("DOMContentLoaded", function(event){
  var $jq = jQuery.noConflict();

  $jq(".super-cat-post-filter").each(function(){
    let container = $jq("#" + $jq(this).attr("data-container"));
    let posts = $jq(this).attr("data-posts");
    let term = $jq(this).attr("data-term");
    if(term != "" && container.attr("data-hide-empty") == "yes"){
      if($jq("#"+posts).find('article.' + term).length < 1){
        $jq(this).hide();
      }
    }
  });

  $jq(".super-cat-post-filter").click(function(event){
    let item = $jq(event.target);
    let term = item.attr("data-term");
    let posts = item.attr("data-posts");

    // hide all
    $jq("#"+posts).find('article').hide();
    // set all to inactive
    $jq(".super-cat-post-filter").removeClass("elementor-active");

    // sync option in Dropdown Filters
    $jq('.super-cat-dropdown-list').each(function(){
      let toSelect = $jq(this).find('option[data-term="' + term + '"]');
      if(toSelect.size() > 0){
        toSelect.attr('selected','selected');
      }else{
        $jq(this).find('option[data-term=""]').attr('selected','selected');
      }
    });

    // Show / Hide all
    if (term == '') {
      // show all
      history.replaceState(null, null, ' ');
      $jq("#"+posts).find('article').fadeIn(400);
      $jq('.super-cat-post-filter[data-term=""]').addClass("elementor-active");
    } else {
      // show some
      $jq('.super-cat-post-filter[data-term="' + term + '"]').addClass("elementor-active");
      window.location.hash = "#" + term;
      $jq("#"+posts).find('article.' + term).fadeIn(400);
    }
  });

  if(window.location.hash){
    let hhh = window.location.hash.replace("#", "");
    $jq( 'li.elementor-portfolio__filter[data-term='+hhh+']' ).trigger("click");
  }

});
