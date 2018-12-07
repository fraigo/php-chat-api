function fakeUser(){
  var loggedUser={
    id: 123123123,
    name: "Fake User",
    imageUrl: "http://localhost:8000/client/messages-120.png",
    email: "fake@user.com",
    token: "fakeuser123456abcdefg"
  }
  onRegisterUser(loggedUser)
}

function onSignIn(googleUser) {
    // Useful data for your client-side scripts:
    var profile = googleUser.getBasicProfile();
    var loggedUser={
      id: profile.getId(),
      name: profile.getName(),
      imageUrl: profile.getImageUrl(),
      email: profile.getEmail(),
      token: googleUser.getAuthResponse().id_token
    }

    // The ID token you need to pass to your backend:
    //var id_token = googleUser.getAuthResponse().id_token;
    
    onRegisterUser(loggedUser);

  };

  function signIn(){
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signIn().then(function (user) {
      onSignIn(user)
    });
  }
  
  function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      console.log('User signed out.');
    });
  }
