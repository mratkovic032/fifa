$(document).ready(function() {

    $(".nav-tab").click(function() {
        window.location = $(this).find("a").attr("href"); 
        return false;
    });

    $("#player-keywords").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#player-table tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});