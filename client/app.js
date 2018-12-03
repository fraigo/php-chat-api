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
        newMessage:{}
    },
    methods:{
        viewContacts: function(){
            var self=this
            this.hash = "#contacts"
            apiCall("Sender/get/"+this.user.email+"/",function(data){
                self.contacts = data;
            })
        },
        viewMessages: function(){
            var self=this
            this.hash = "#contacts"
            apiCall("Message/get/"+this.user.email+"/",function(data){
                console.log(data)
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
            this.user = null,
            this.contacts=[]
            signOut()
        },
        signIn(){
            signIn()
        },
        newContact(){
            this.newMessage.email="@gmail.com"
            this.newMessage.email="@gmail.com"
            
            this.dialog=true;
        },
        timeAgo(time){
            var current = new Date();
            var currentTime = current.getTime();
            var date=new Date(time*1000)
            var diff=(current - date)/1000;
            if (diff<60){
                return "Now"
            }
            if (diff<120){
                return "1 minute ago"
            }
            if (diff<3600){
                return Math.round(diff/60) + " minutes ago"
            }
            return date.toISOString();
        },
        sendContact(){
            
            this.dialog = false
            var message=encodeURIComponent(this.newMessage.message)
            var url="Message/push/"+this.newMessage.email+"/?from="+this.user.email+"&message="+message;
            apiCall(url,function(data){
                console.log(data)
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
        contactClick(item){
            console.log(item)
            this.viewMessages()
        },
        messageClick(item){
            console.log(item)
            
        },
        menuClick(item){
            console.log(item.click)
            this[item.click]()
            this.menu=false
        }
    },
    mounted:function(){
        
        console.log(this.actions);
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
})

    
}



function apiCall(query, callback){
  var url= "";
  var config = {
      headers: {

      },
      method: "GET"
  }
  fetch('http://localhost:8000/index.php/'+query, config)
    .then(function(response) {
        return response.json();
    })
    .then(function(myJson) {
        if (callback){
            callback(myJson)
        }
    });
}