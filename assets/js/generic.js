(function($){

  $(document).on("submit", function(e){
    var superCatForms = localStorage.getItem("SuperCatForms");
    if(superCatForms){
      superCatForms = JSON.parse(superCatForms);
    }else{
      superCatForms = {
        sentForms: [],
        formFields: {}
      };
    }

    var data = $(e.target).serializeArray();

    $.each(data, function(index, val){
      if(val["name"] == "form_id" && superCatForms.sentForms.indexOf(val["value"]) === -1 ){
        superCatForms.sentForms.push(val["value"]);
      } else {
        var matches = val["name"].match(/form\_fields\[(.*?)\]/);
        if(matches){ superCatForms.formFields[matches[1]] = val["value"]; }
      }
    });
    localStorage.setItem("SuperCatForms", JSON.stringify(superCatForms));
  });

})(jQuery);
