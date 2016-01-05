var content = new SimpleMDE({ 
    element: $("#content")[0],
});

$("#changeUrl").click(function(){
    swal({
  title: "An input!",
  text: "Write something interesting:",
  type: "input",
  showCancelButton: true,
  closeOnConfirm: false,
  animation: "slide-from-top",
  inputPlaceholder: "Write something"
},
function(inputValue){
  if (inputValue === false) return false;
  
  if (inputValue === "") {
    swal.showInputError("You need to write something!");
    return false
  }
  
  swal("Nice!", "You wrote: " + inputValue, "success");
});
});

$("#protectView").click(function(){
    swal({
  title: "An input!",
  text: "Write something interesting:",
  type: "input",
  showCancelButton: true,
  closeOnConfirm: false,
  animation: "slide-from-top",
  inputPlaceholder: "Write something"
},
function(inputValue){
  if (inputValue === false) return false;
  
  if (inputValue === "") {
    swal.showInputError("You need to write something!");
    return false
  }
  
  swal("Nice!", "You wrote: " + inputValue, "success");
});
});

$("#protectEdit").click(function(){
    swal({
      title: "An input!",
      text: "Write something interesting:",
      type: "input",
      showCancelButton: true,
      closeOnConfirm: false,
      animation: "slide-from-top",
      inputPlaceholder: "Write something"
    },
    function(inputValue){
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

$("#save").click(function(){
    
});

	