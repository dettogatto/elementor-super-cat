document.addEventListener("DOMContentLoaded", function(event){
  var $jq = jQuery.noConflict();
  $jq(".cat-dropdown-list").change(function(){
    var item = $jq(this).find(':selected');
    let container = item.attr("data-container");
    let term = item.attr("data-term");
    let posts = item.attr("data-posts");

    var filters = {};
    filters[posts] = {};
    filters[posts][container] = [];

    // change filter
    filters[posts][container].push(term);

    // add active class
    var allContainers = Object.keys(filters[posts]).reduce(function (r, k) {
        return r.concat(filters[posts][k]);
    }, []);
    $jq("select.cat-filter-for-" + posts + " option").each(function(){
      if(allContainers.includes($jq(this).attr("data-term"))){
        $jq(this).addClass("elementor-active");
      } else {
        $jq(this).removeClass("elementor-active");
      }
    });

    // Show / Hide all
    if (term == 'all') {
      $jq("#"+posts).find('article').show();
    } else {
      // hide all
      $jq("#"+posts).find('article').hide();

      // show some
      $jq("#"+posts).find('article').each(function(){
        var classes = $jq(this).attr("class").split(" ");
        var toShow = false;
        for(var key in filters[posts]){
          if(filters[posts][key].length > 0){
            if(classes.includes(filters[posts][key][0])){
              toShow = true;
              break;
            }
          }
        }
        if(toShow){
          $jq(this).fadeIn(400);
        }
      });
    }
  });
});
