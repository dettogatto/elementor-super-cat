(function($){
  $(document).ready(function(){

    var filters = {};

    $(".cat-checkbox-filter").each(function(){
      let container = $("#" + $(this).attr("data-container"));
      let posts = $(this).attr("data-posts");
      let term = $(this).attr("data-term");
      if(term != "" && container.attr("data-hide-empty") == "yes"){
        if($("#"+posts).find('article.' + term).length < 1){
          $(this).hide();
        }
      }
    });

    $(".cat-checkbox-filter").click(function(){
      let container = $(this).attr("data-container");
      let term = $(this).attr("data-term");
      let posts = $(this).attr("data-posts");

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
      $("ul.cat-filter-for-" + posts + " li").each(function(){
        if(allContainers.includes($(this).attr("data-term"))){
          $(this).addClass("elementor-active");
        } else {
          $(this).removeClass("elementor-active");
        }
      });

      // hide all
      $("#"+posts).find('article').hide();

      // show some
      $("#"+posts).find('article').each(function(){
        var classes = $(this).attr("class").split(" ");
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
          $(this).fadeIn(400);
        }
      });

    });

  });
})(jQuery);
