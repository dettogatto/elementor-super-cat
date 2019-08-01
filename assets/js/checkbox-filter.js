document.addEventListener("DOMContentLoaded", function(event){
  var $jq = jQuery.noConflict();
  var filters = {};

  $jq(".cat-checkbox-filter").each(function(){
    let container = $jq("#" + $jq(this).attr("data-container"));
    let posts = $jq(this).attr("data-posts");
    let term = $jq(this).attr("data-term");
    if(term != "" && container.attr("data-hide-empty") == "yes"){
      if($jq("#"+posts).find('article.' + term).length < 1){
        $jq(this).hide();
      }
    }
  });
  
  $jq(".cat-checkbox-filter").click(function(){
    let container = $jq(this).attr("data-container");
    let term = $jq(this).attr("data-term");
    let posts = $jq(this).attr("data-posts");

    if(!filters[posts]){ filters[posts] = {}; }
    if(!filters[posts][container]){ filters[posts][container] = []; }

    // add or remove term from array
    if(filters[posts][container].includes(term)){
      for( var i = 0; i < filters[posts][container].length; i++){
        if ( filters[posts][container][i] === term) {
          filters[posts][container].splice(i, 1);
          i--;
        }
      }
    } else {
      filters[posts][container].push(term);
    }

    // add active class
    var allContainers = Object.keys(filters[posts]).reduce(function (r, k) {
        return r.concat(filters[posts][k]);
    }, []);
    $jq("ul.cat-filter-for-" + posts + " li").each(function(){
      if(allContainers.includes($jq(this).attr("data-term"))){
        $jq(this).addClass("elementor-active");
      } else {
        $jq(this).removeClass("elementor-active");
      }
    });

    // hide all
    $jq("#"+posts).find('article').hide();

    // show some
    $jq("#"+posts).find('article').each(function(){
      var classes = $jq(this).attr("class").split(" ");
      var toShow = true;
      for(var key in filters[posts]){
        if(filters[posts][key].length > 0){
          let found = false;
          for(t of filters[posts][key]){
            if(classes.includes(t)){
              found = true;
              break;
            }
          }
          if(!found){
            toShow = false;
            break;
          }
        }
      }
      if(toShow){
        $jq(this).fadeIn(400);
      }
    });

  });
});
