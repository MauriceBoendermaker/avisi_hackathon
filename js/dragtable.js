$(function () {
  $("#table1, #table2")
    .sortable({
      items: "tr:not(.unsortable)",
      connectWith: ".connectedSortable",
    })
    .disableSelection();
  $("td").disableSelection();
});

// for #table1 and #table2 check if has less than 2 children if so set .unsortable child to show
$(function () {
  $("#table1, #table2").each(function () {
    if ($(this).children().length < 2) {
      $(this).find(".unsortable").show();
    }
  });
});

$("#table1, #table2").on("sortupdate", function () {
  // if table is empty show .unsortable child otherwise hide it
  if ($(this).children().length > 1) {
    $(this).find(".unsortable").hide();
  }
  else
    $(this).find(".unsortable").show();

  // for each tr in table
  $(this).children().each(function () {
    if ($(this).parent().attr("id") == "table1") {
      $(this).children().eq(4).show();
      $(this).children().last().children().first().hide();
      $(this).children().last().children().eq(1).hide();
      $(this).children().last().children().last().show();
      $(this).find("input").prop('disabled', false);
    }
    else {
      $(this).children().eq(4).hide();
      $(this).children().last().children().first().show();
      $(this).children().last().children().eq(1).hide();
      $(this).children().last().children().last().hide();
      $(this).find("input").prop('disabled', true);
    }
  }); 
});

$(".addbutton").click(function () {
  var table = $(this).parent().parent().parent();
  if (table.attr("id") == "table1") return;
  var tr = $(this).parent().parent();
  var table1 = $("#table1");

  // move tr to table1 and call sortupdate
  table1.append(tr);
  table1.trigger("sortupdate");

  if (table.children().length < 2) table.find(".unsortable").show();
});

$(".removebutton").click(function () {
  var table = $(this).parent().parent().parent();
  if (table.attr("id") == "table2") return;
  var tr = $(this).parent().parent();
  var table2 = $("#table2");

  // move tr to table2 and call sortupdate
  table2.append(tr);
  table2.trigger("sortupdate");

  if (table.children().length < 2) table.find(".unsortable").show();
});