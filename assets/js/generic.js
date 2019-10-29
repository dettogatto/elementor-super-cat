document.addEventListener("DOMContentLoaded", function(event) {

  var $jq = jQuery.noConflict();

  $jq("form").click(function(){
    $jq(this).find('input, textarea, select').each(function(){
      /* SET THE COOKIE */
      var name = $jq(this).attr("name");
      var value = $jq(this).val();
      var expires = "";
      var matches = name.match(/form\_fields\[(.*?)\]/);
      if(matches){ name = matches[1]; }
      name = "gatto_form_" + name;
      document.cookie = encodeURIComponent(name) + "=" + encodeURIComponent(value) + expires + "; path=/";
    });
  });


});
