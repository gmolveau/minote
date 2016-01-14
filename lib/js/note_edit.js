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
      text: "Choose the new URL : milinks.info/______ < 20 characters",
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
       if (inputValue.length > 20 ) {
        swal.showInputError("The url must be shorter than 24 characters.");
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
        },
        error : function(html){
          swal("An error occured...", "","error");
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
      inputType: "password",
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
          swal("Nice!", "","success");
          window.location.reload();
        },
        error : function(html){
          swal("An error occured...", "","error");
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
      inputType: "password",
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
          swal("Nice!", "","success");
          window.location.reload();
        },
        error : function(html){
          swal("An error occured...", "","error");
       }
      });
    });
});

$("#whats").click(function() {
 swal("What's Milinks.info ?",
 "Milinks is an online Wordpad with markdown support !\n \
 You can also protect your notes from robbers with a password for the view and the edit of your note.\n \
 \nCreated by :\nGregoire MOLVEAU\nJulien GIDEL")
});

$("#view").click(function(){
  save(false);
  window.location.href = '/'+ url +'/view';
});

function save(popup){
// Create Base64 Object
  var content = encodeURIComponent($("#content").val());
  var datastring = 'type=save&url=' + url + '&content=' + content;
      $.ajax({
        type: "POST",
        data: datastring,
        success: function(html) {
          if (popup){
            swal({   title: "Saving...",   text: "",   timer: 1000,   showConfirmButton: false });
          }
        }
      });
};

// AUTO SAVE
$('#content').bind('input propertychange', function() {
  save(false);
});

$( document ).ready(function() {
  displayLock();
});


function displayLock(){
  if (viewProtected){
      $('#lockView').addClass("fa fa-lock");
    }
    else{
      $('#lockView').addClass("fa fa-unlock");
    }
    if (editProtected){
      $('#lockEdit').addClass("fa fa-lock");
    }
    else{
      $('#lockEdit').addClass("fa fa-unlock");
    }
    
}