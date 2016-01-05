new Vue({
  el: '#editor',
  data: {
    input: ''
  },
  filters: {
    marked: marked
  }
})
$("#changeUrl").click(function() {
  swal({
      title: "Change URL",
      text: "Choose the new URL : milinks.info/_______/edit",
      type: "input",
      showCancelButton: true,
      closeOnConfirm: false,
      animation: "slide-from-top",
      inputPlaceholder: "Exemple : milinks"
    },
    function(inputValue) {
      if (inputValue === false) return false;
      if (inputValue === "") {
        swal.showInputError("You have to write something if you want to change the URL");
        return false;
      }
      var datastring = 'type=changeUrl&url=' + url + '&new_url=' + inputValue;
      console.log(datastring);
      $.ajax({
        type: "POST",
        url: "#",
        data: datastring,
        success: function(html) {
          swal("Nice!", "You chose: " + inputValue, "success");
          window.location.href = '/'+ inputValue +'/edit';
        }
      });
    });
});

$("#protectView").click(function() {
  swal({
      title: "Protect view",
      text: "Set a password to protect the view",
      type: "input",
      showCancelButton: true,
      closeOnConfirm: false,
      animation: "slide-from-top",
      inputPlaceholder: "password"
    },
    function(inputValue) {
            if (inputValue === false) return false;
      var datastring = 'type=protectView&url=' + url + '&password=' + inputValue;
      console.log(datastring);
      $.ajax({
        type: "POST",
        url: "#",
        data: datastring,
        success: function(html) {
          swal("Nice!", "You wrote: " + inputValue, "success");
        }
      });
    });
});

$("#protectEdit").click(function() {
  swal({
      title: "Protect edit",
      text: "Set a password to protect the edit",
      type: "input",
      showCancelButton: true,
      closeOnConfirm: false,
      animation: "slide-from-top",
      inputPlaceholder: "password"
    },
    function(inputValue) {
      if (inputValue === false) return false;
      var datastring = 'type=protectEdit&url=' + url + '&password=' + inputValue;
      console.log(datastring);
      $.ajax({
        type: "POST",
        url: "#",
        data: datastring,
        success: function(html) {
          swal("Nice!", "You wrote: " + inputValue, "success");
        }
      });
    });
});

$("#save").click(function() {
  $content = $("#content").val();
  var datastring = 'type=save&url=' + url + '&content=' + $content;
      console.log(datastring);
      $.ajax({
        type: "POST",
        url: "#",
        data: datastring,
        success: function(html) {
          swal({   title: "Sauvegarde",   text: "Cette fenêtre se fermera seule",   timer: 1000,   showConfirmButton: false });
        }
      });
});

$("#whats").click(function() {
 swal("What's Milinks.info ?",
 "Milinks is an online Wordpad where markdown syntaxe is supported !\nYou can also protect your notes from robbers with a password for the view and the edit of your note.\n\nCreated by :\nGregoire MOLVEAU\nJulien GIDEL")
});
