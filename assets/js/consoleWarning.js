//console.trace('[DEBUG] Imported consoleWarning.js')

/*[1,2,3].forEach(function(i) {
    warningMessageConsole();
  });*/

  let i = 0;
  let intrv = setInterval(() => {
      if(i > 2) return clearInterval(this);
    warningMessageConsole();  
    i++
  }, 500);


  function warningMessageConsole() {
          
      
    console.log(
        "%cStop!",
        "color:red;font-family:system-ui;font-size:4rem;-webkit-text-stroke: 1px black;font-weight:bold"
    );
    console.log(
        "%cIf someone told you to copy paste something here, there's a 101% chance you're being scammed.\nLetting those dirty hackers access your account is not what you want, right?",
        "color:white;font-family:system-ui;font-size:1rem;-webkit-text-stroke: 0.5px black;font-weight:bold"
    )
  }
