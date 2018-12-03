var app = new Vue({
    el: '#app',
    data: {
        contacts:[],
        messages:[],
        user: {},
        hash: '',
        actions:[
            { title:"Contacts", click: "viewContacts", icon: 'contact_mail'},
            { title:"Profile", click: "viewProfile", icon: 'account_circle' },
            { title:"Login", click: "signIn", icon: 'account_circle', guest:true },
            { title:"Logout", click: "signOut", icon: 'account_circle'},
        ],
        menu: false,
        dialog: false,
        valid:false,
        newMessage:{},
        currentContact:{},
        dialogTitle:"New"
    },
    methods:{
        viewContacts: function(){
            var self=this
            this.hash = "#contacts"
            apiCall("Sender/get/"+this.user.email+"/",function(data){
                self.contacts = data;
                self.currentContact=self.findContact(self.currentContact.email);
                if (!self.currentContact.email && data.length>0){
                    self.contactClick(data[0])
                }
                setTimeout(function(){
                    var list=document.getElementById("messageList")
                    list.scrollTop = list.scrollHeight;
                },500)
            })
        },
        viewMessages: function(email){
            var self=this
            this.hash = "#contacts"
            apiCall("Message/get/"+this.user.email+"/"+email,function(data){
                self.messages = data;
            })
        },
        messageContent(message){
            return 
        },
        findContact(email){
            if (email==this.user.email){
                return this.user
            }
            return this.findItem("contacts","email",email,{})
        },
        imageUrl(url){
            if (url==null || url==""){
                return "user-icon.png"
            }
            return url
        },
        findItem(name,field,value,def){
            var items=this[name]
            for(var i=0;i<items.length;i++){
                if (items[i][field]==value){
                    return items[i]
                }
            }
            return def
        },
        viewProfile: function(){
            var self=this
            this.hash = "#profile"

        },
        signOut(){
            this.user = {},
            this.contacts=[]
            signOut()
        },
        signIn(){
            signIn()
        },
        newContact(){
            this.newMessage.email="@gmail.com"
            this.newMessage.message=""
            this.dialogTitle = "New Contact message"
            if (this.currentContact.email && this.hash=="#contacts"){
                this.newMessage.email=this.currentContact.email;
                this.dialogTitle = "New Message"
            }
            this.dialog=true;
        },
        sendContact(){
            var self=this
            this.dialog = false
            var message=encodeURIComponent(this.newMessage.message)
            var url="Message/push/"+this.newMessage.email+"/?from="+this.user.email+"&message="+message;
            console.log(JSON.stringify(self.newMessage));
            apiCall(url,function(data){
                self.currentContact.email=self.newMessage.email
                self.viewContacts()
                self.contactClick(self.newMessage)
                self.newMessage.email="@gmail.com"
                self.newMessage.message=""
            })
        },
        sendMessage(){
            
        },
        isVisible:function(item){
            if (item.guest){
                return !this.isLogged
            }
            return this.isLogged
        },
        timeAgo(time){
            var current = new Date();
            var currentTime = current.getTime();
            var date=new Date(time*1000)
            var diff=(current - date)/1000;
            if (diff<5){
                return "Now"
            }
            if (diff<60){
                return Math.round(diff) + " seconds ago"
            }
            if (diff<120){
                return "1 minute ago"
            }
            if (diff<3600){
                return Math.round(diff/60) + " minutes ago"
            }
            if (diff<3600*24){
                return Math.round(diff/3600) + " hours ago"
            }
            if (diff<3600*24*30){
                return Math.round(diff/(3600*24)) + " days ago"
            }
            return date.toISOString();
        },
        contactClick(item){
            console.log("item",item)
            var self=this
            this.currentContact = item
            self.messages = []
            this.viewMessages(item.email)
            messageWorker({
                endpoint: API_ENDPOINT,
                contact : item.email,
                user: this.user.email
            },function(data){
                self.messages = data
            })
        },
        messageClick(item){
            console.log(item)
            
        },
        menuClick(item){
            console.log(item.click)
            this[item.click]()
            this.menu=false
        },

    },
    mounted:function(){
        this.$el.style.display="";
        console.log(this.actions);
        startWorkers();
    },
    computed:{
        isLogged:function(){
            return this.user && this.user.email!=null;
        }
    }
})
    
function onRegisterUser(googleUser){
    var profile = googleUser.getBasicProfile();
    var email=profile.getEmail();
    var name=encodeURI(profile.getName());
    var imageLink=encodeURI(profile.getImageUrl());
    var token=encodeURI(googleUser.getAuthResponse().id_token);

    var query="User/register/{email}/?name={name}&imageUrl={imageLink}&token={token}"
        .replace("{email}",email)
        .replace("{name}",name)
        .replace("{imageLink}",imageLink)
        .replace("{token}",token)
    console.log(query);

    apiCall(query, function(data){
        console.log(data)
        app.user = data
        app.viewContacts()
        contactWorker({
            endpoint: API_ENDPOINT,
            user : data.email
        },function(data){
            app.contacts = data
        })
    })

    
}

console.log(document.location.protocol)
API_ENDPOINT = document.location.protocol + '//' + document.location.host + '/index.php/'

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


function startWorkers() {
    if(typeof(Worker) !== "undefined") {
        if(typeof(mw) == "undefined") {
            mw = new Worker("message-worker.js");
        }
        if(typeof(cw) == "undefined") {
            cw = new Worker("contact-worker.js");
        }
    } else {
        alert("Sorry, your browser does not support Web Workers.");
    }
}

function messageWorker(data, callback){
    mw.onmessage = function(event) {
        callback(event.data)
    };
    mw.postMessage(data)
}

function contactWorker(data, callback){
    cw.onmessage = function(event) {
        callback(event.data)
    };
    cw.postMessage(data)
}

function stopWorker() { 
    if (mw){
        mw.terminate();
        mw = undefined;
    }
}