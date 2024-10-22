const fs = require('fs');
let userdata = require('./userdata.json')
let messagedata = require('./messages.json')
let msgdatafs = JSON.parse(fs.readFileSync("./messages.json"))


var args = process.argv.slice(2);


if(args[0] == 'send') {
    if(!args[1] || args[1] == "") {
        console.log('Second parameter missing (as (id))')
        return
    }
    if(!args[2] || args[2] == "") {
        console.log('Third parameter missing (message)')
        return
    }
    if(!userExists(args[1])) return console.log("A user with an ID of " + args[1] + " does not exist.");
    
    createMessage(args.slice(2).join(" "), args[1], false, "c:00000000000000000000", new Date(), function(msginfo) {
        delete require.cache[require.resolve(`./messages.json`)]
        const pull = require(`./messages.json`)
        messagedata = pull;
        let html = "";
        fs.writeFileSync("../../log.html", "");
        messagedata.forEach(function (item, index) {
            html = html + formatMessageHTML(item.id)
          
        });
        fs.writeFileSync("../../log.html", fs.readFileSync("../../log.html") + html/* + formatMessageHTML(msginfo.id)*/)
    });



} else if(args[0] == 'refresh') {
    delete require.cache[require.resolve(`./messages.json`)]
    const pull = require(`./messages.json`)
    messagedata = pull;
    let html = "";
    fs.writeFileSync("../../log.html", "");
    messagedata.forEach(function (item, index) {
        html = html + formatMessageHTML(item.id)
      
    });
    fs.writeFileSync("../../log.html", fs.readFileSync("../../log.html") + html/* + formatMessageHTML(msginfo.id)*/)
}



//console.log(getUserData("69420694206942069420", false))


function getUserData(id, decoded = false) {
    var index = userdata.map(function (msginfo) { return msginfo.id; }).indexOf(id);
    let jsonobj = {};
    let email
    let password
    let username = userdata[index].username;
    if(decoded) {
        email = Buffer.from(userdata[index].email, 'base64').toString('utf-8');
        password = Buffer.from(userdata[index].password, 'base64').toString('utf-8');
    } else {
        email = userdata[index].email;
        password = userdata[index].password;
    }
    let admin = userdata[index].admin;
    let avatar = userdata[index].avatar;
    let namecolorbg = userdata[index].namecolorbg;
    let namecolorfg = userdata[index].namecolorfg;

    jsonobj = {
        username: username,
        email: email,
        password: password,
        admin: admin,
        avatar: avatar,
        namecolorbg: namecolorbg,
        namecolorfg: namecolorfg
    }

    return jsonobj
}

//console.log(formatMessageHTML("m:00000000000000000000"))


//console.log(getMessageInfo("m:00000000000000000000"))

function formatMessageHTML(id) {
    let msginfo = getMessageInfo(id);
    let message = "<div class='msgln'><span class='chat-time'>" + msginfo.timestamp + "</span> <a class='taglink' href='javascript:insert_mention(\""+ getUserData(msginfo.authorid, false).username + "\");'><b class='user-name' style='color:" + getUserData(msginfo.authorid, false).namecolorfg + "; background:" + getUserData(msginfo.authorid, false).namecolorbg + ";'>"+ getUserData(msginfo.authorid, true).username +"</b></a> " + msginfo.text + "<br></div>"

    return message
}

function userExists(id) {
    var index = userdata.map(function (userinfo) { return userinfo.id; }).indexOf(id);

    if(index == undefined || index == "" || index == null)  { return false } else { return true }
}

function messageExists(id) {
    var index = messagedata.map(function (msginfo) { return msginfo.id; }).indexOf(id);

    if(index == undefined || index == "" || index == null)  { return false } else { return true }
}


function getMessageInfo(id) {
    var index = messagedata.map(function (msginfo) { return msginfo.id; }).indexOf(id);

    return messagedata[index]
}

function generateId(n) {
    var add = 1, max = 12 - add;   // 12 is the min safe number Math.random() can generate without it starting to pad the end with zeros.   

    if ( n > max ) {
            return generateId(max) + generateId(n - max);
    }

    max        = Math.pow(10, n+add);
    var min    = max/10; // Math.pow(10, n) basically
    var number = Math.floor( Math.random() * (max - min + 1) ) + min;

    return ("" + number).substring(add); 
}


function formatAMPM(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm.toUpperCase();
    return strTime;
  }


  function makeid(length) {
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789[]-_=!@#$%^&*()/~`\'".,;:{}';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * 
 charactersLength));
   }
   return result;
}

function createMessage(text = makeid(15), authorid, hidden = false, reicieverid = "c:00000000000000000000", timestamp = new Date(), _callback) {
    let idlist = [];
    let randomid = "m:" + generateId(20);
    let jsonmsgobj = {};
    msgdatafs.forEach(function (item, index) {
        idlist.push(item.id)
      });

      if(idlist.includes(randomid))

      while(idlist.includes(randomid)) {
           randomid = "m:" + generateId(20);
      }

    jsonmsgobj = {
        "id": randomid,
        "text": text,
        "authorid": authorid,
        "timestamp": formatAMPM(timestamp),
        "hidden": hidden,
        "recieverid": reicieverid
    }
      msgdatafs.push(jsonmsgobj);
      fs.writeFileSync("messages.json", JSON.stringify(msgdatafs, null, 4));

    
    _callback(jsonmsgobj);
}

