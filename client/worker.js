var i=0;
var currentContact = null
var currentUser = null
var API_ENDPOINT = null

function timedCall() {
    console.log("call ",i, currentContact)
    i=i+1;
    if (API_ENDPOINT!=null && currentContact!=null){
        apiCall("Message/get/"+currentContact,function(data){
            postMessage(data);
        })
    }
    setTimeout(timedCall, 10000);
}

onmessage = function(e) {
    console.log("message",e.data)
    API_ENDPOINT=e.data.endpoint
    currentUser=e.data.user
    currentContact=e.data.contact
};


function apiCall(query, callback){
    var url= "";
    var config = {
        headers: {
  
        },
        method: "GET"
    }
    fetch(API_ENDPOINT + query, config)
      .then(function(response) {
          return response.json();
      })
      .then(function(myJson) {
          if (callback){
              callback(myJson)
          }
      });
  }

timedCall();