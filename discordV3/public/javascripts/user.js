function initMenu() {
   $("#menu ul").hide();
   $("#menu ul").children(".current").parent().show();
   $("#menu li a").click(function () {
      var checkElement = $(this).next();
      if(checkElement.is("ul") && checkElement.is(":visible")){
         $('#menu ul:first').slideUp('normal');
         return false;
      }

      if(checkElement.is("ul") && !checkElement.is(":visible")){
         $("#menu ul:visible").slideUp("normal");
         checkElement.slideDown("normal");
         return false;
      }
   });
   $("#menu li").click(function () {
      $("#menu li").removeClass("current");
      
      $(this).addClass("current");
   });
}
$(document).ready(function () {
   initMenu();
});
 