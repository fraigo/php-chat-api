var i=0;
var currentUser = null
var API_ENDPOINT = null

function timedCall() {
    console.log("contact-worker ",i, currentUser)
    i=i+1;
    if (API_ENDPOINT!=null && currentUser!=null){
        apiCall("Sender/get/"+currentUser,function(data){
            postMessage(data);
        })
    }
    setTimeout(timedCall, 10000);
}

onmessage = function(e) {
    API_ENDPOINT=e.data.endpoint
    currentUser=e.data.user
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