// Dom7
var $$ = Dom7;

// Init App
var app = new Framework7({
  id: 'com.userapp.homecare', // App bundle ID
  root: '#app', // App root element
  theme: 'md', // Automatic theme detection
  name: 'Homecare', // App name
  dialog: {
	  title: 'Alert',
	  buttonOk: 'Ok',
	  buttonCancel: 'Cancel'
  }, 
  data: function () {
    return {
      appdata: {
        userPhone: '0000',
        userCity: 'None',
        otpCode: '1111',
        fullName: 'Default',
		userEmail: 'demo@example.com',
		firstName: 'Demo',
		lastName: 'Demo',
		appcity: '',
		providerType: 'Demo',
		serviceType: 'Demo',
		expYears: 'Demo',
		appcity:  'Demo',
		serviceList: 'Demo',
		bankList: 'Demo',
		provList: 'Demo',
		uAge: '',
		appVer: '1.0.2',
		uGender: ''
      },
	  appconst: {
		url: 'https://homecare.ng/page/main/site/fxn/',  
		//url: 'http://localhost/homecare/20/ux/site/fxn/',  
		//url: 'https://homecare.ng/homecare/ux/site/fxn/',  
		//url: './api/api?',  
		imgurl: 'https://homecare.ng/page/admin/',  
		constr: '0d119500003cd78ffd5657a753559c4e',
		//constr: '0d119500003cd78ffd5657a753559c4e',
	  }
    };
  },
  methods: {
    helloWorld: function () {
      app.dialog.alert('Hello World!');
    },
	getNoticeprvd: function () {
		
		var lclalertNum = localStorage.getItem("lclalertNum");
		document.getElementById("lclalertNum").innerHTML = lclalertNum;
		//app.dialog.alert('Hello World!');
    },
	getUserAlerts: function () {
      //app.data.appdata.userAlert = 15;
		//var app = this.$app;
		
		var userPhone = app.data.appdata.userPhone;
		var userAlertID = app.data.appdata.userAlertID;
		var url = app.data.appconst.url;
			
			app.request({
				url:url+'type=useralert',
				dataType:'json',
				method: 'GET',
				data:{
				userPhone:userPhone,
				userAlertID:userAlertID
				},
				crossDomain:true,
				success:function(adata){
				 
				 
				if (adata.message == "true") 
				{ 
					app.data.userAlertMsg = adata.userAlertMsg;
					app.data.appdata.userAlertID = adata.userAlertID;
					app.data.appdata.userAlert = adata.userAlert;
					
					
					
					  app.toastBottom =  app.toast.create({
						//text: 'Success!' + userAlertID + '-' + userPhone ,
						text: 'You have a new Message!' ,
						closeTimeout: 3000,
					  });

					//app.toastBottom.open();	
					
					if (adata.userAlert > 0)
					{
						app.toastBottom.open();
						var message = "You have a new Message!";
						//this.$app.methods.localPushNot(message);
						var schedule_time = new Date();
						var title = "Homecare";
						
						cordova.plugins.notification.local.hasPermission(function(granted){
							  if(granted == true)
							  {
								schedule(title, message, schedule_time);
							  }
							  else
							  {
								cordova.plugins.notification.local.registerPermission(function(granted) {
									if(granted == true)
									{
									  schedule(title, message, schedule_time);
									}
									else
									{
									  navigator.notification.alert("Reminder cannot be added because app doesn't have permission");
									}
								});
							  }
						});
						
						function schedule(title, message, schedule_time)
						{
							
							cordova.plugins.notification.local.schedule({
							title: title,
							text: message
							});
						}
					}
				}
				
				app.data.appdata.userSrvReqs = adata.userSrvReqs;
				app.data.userSrvReqList = adata.userSrvReqList;
				
				
				},
				error:function(xhr,status){
				//console.log(error.code);
				//console.log(error.exception);
				//console.log(error);
				console.log(status);
				console.log(xhr);
				//if (error.exception !== ""){console.log(error.exception);}
				  // Create toast
					//if (!app.toastBottom) {
					  app.toastBottom =  app.toast.create({
						text: 'Connection Error!'  + userAlertID + '-' + userPhone,
						closeTimeout: 3000,
					  });
				//}
					
					
					// Open it
				//	app.toastBottom.open();
				},
				});
    },
	localPushNot: function(message){
			
		var schedule_time = new Date();
		var title = "Homecare";
		
		cordova.plugins.notification.local.hasPermission(function(granted){
			  if(granted == true)
			  {
				schedule(title, message, schedule_time);
			  }
			  else
			  {
				cordova.plugins.notification.local.registerPermission(function(granted) {
					if(granted == true)
					{
					  schedule(title, message, schedule_time);
					}
					else
					{
					  navigator.notification.alert("You have a new message!");
					}
				});
			  }
		});
		
		function schedule(title, message, schedule_time)
		{
			cordova.plugins.notification.local.schedule({
			title: title,
			text: message
			});
		}
		
		
		
		
	},
  },
  routes: routes,
  view : {
	  pushState: false,
	  //stackPages: true
  }
});

// Init/Create left panel view
var mainView = app.views.create('.view-left', {
  url: '/'
});

// Init/Create main view
var mainView = app.views.create('.view-main', {
  url: '/'
});

/*
//toggle change
$$('#prvdtoggle').on('change', function () {
  app.dialog.alert('Hooray');
//  msgTest();
});
*/

// Login Screen Demo
$$('#my-login-screen .login-button').on('click', function () {
  var username = $$('#my-login-screen [name="username"]').val();
  var password = $$('#my-login-screen [name="password"]').val();

  // Close login screen
  app.loginScreen.close('#my-login-screen');

  // Alert username and password
  app.dialog.alert('Username: ' + username + '<br>Password: ' + password);
});

/*
$$('#iptCity').on('change', function () {
  console.log('input value changed');
  alert("Hooray");
   var iptCity = $$('#iptCity').val();
	app.dialog.alert('Inputed '+ iptCity);
//  msgTest();
});



$$('input[name="cityName"]').on('keyup keydown change', function (e) {
	console.log('input value changed');
	var iptCity = $$('#iptCity').val();
	app.dialog.alert('Inputed '+ iptCity);
});
*/
document.addEventListener("deviceready", onDeviceReady, false);
document.addEventListener("backbutton", onBackKeyDown, false);
//document.getElementById("prvdtoggle").addEventListener ("click", chgOnlineStatus); 



function onDeviceReady() {

	//StatusBar.backgroundColorByName("black");
	//StatusBar.styleBlackOpaque();
	//app.statusbar.setBackgroundColor(000);
}



function chgOnlineStatus() {
	
	  var app = this.$app;
		
		//Retrieve Global Object userPhone
		var userPhone = app.data.appdata.userPhone;
		var url = app.data.appconst.url;
		var onStat = app.data.appdata.prvdOnlineStatus;
		var newOnStat;
		
		if (onStat == "Online")
		{
		newOnStat = "Offline";
		document.getElementById("prvdstat").innerHTML = "Offline"; 
		}
		else
		{
		newOnStat = "Online"; 
		document.getElementById("prvdstat").innerHTML = "Online";
		}
		
		app.data.appdata.prvdOnlineStatus = newOnStat;
		
		// Show Preloader
		app.preloader.show();
		//app.dialog.progress();
		
		//Update Server Record ajax
		app.request({
				url: url+'type=chgOnlineStatus',
				dataType:'json',
				method: 'GET',
				data:{
				onStatus: newOnStat,
				userPhone: userPhone
				},
				crossDomain:true,
				success:function(adata){
				 
				 
				  if (adata.message == "success") {
					app.toastBottom =  app.toast.create({
						text: 'Profile Status changed to '+newOnStat,
						closeTimeout: 2000,
					  });
				//}
					
					
					// Open it
					app.toastBottom.open();
					  }
					
				},
				error:function(error){
				console.log(error.code);
				if (error.exception !== ""){console.log(error.exception);}
					app.dialog.alert('Connection Error, Try Again!');
				},
				complete:function(){
				//Close Dialog
				setTimeout(function () {app.preloader.hide();}, 1000);
				}
			});
	
}

var opened = 0;
function exitApp(){
	if (opened > 0) {
		return false;
	} else {
		app.dialog.confirm('Are you sure you want to exit?', 'Exit App', 
		  function () {
			navigator.app.exitApp();
		  },
		  function () {
			opened = 0;  
			return false;
		  }
		);
		opened = 1;
	}
}


function onBackKeyDown() {
	// Handle the back button
	var bkmov = "false";
	
	if(app.views.main.history.length == 1){
		exitApp();
		e.preventDefault();
		bkmov = "true";
	} 
	
	if(app.views.main.router.url == "/dashboard/")
	{
		//app.dialog.close();
		//app.views.main.router.back();
		//app.views.main.router.clearPreviousHistory();
		e.preventDefault();
		bkmov = "true";
		return false;
	}
	
	if(app.views.main.router.url == "/")
	{
		exitApp();
		e.preventDefault();
		bkmov = "true";
		return false;
	}
	
	if(app.views.main.router.url == "/home/")
	{
		exitApp();
		e.preventDefault();
		bkmov = "true";
		return false;
	}
	
	
	if(bkmov == "false")
	{
		app.dialog.close();
		app.popup.close();
		//app.views.main.router.back();
		return false;
	}
}

document.addEventListener("backbutton", onBackKeyDown, false);

//document.addEventListener('deviceready', pushReady, false);
        function pushReady() {
            console.log("device ready");
            var push = PushNotification.init({
                android:{}
            });

            push.on('registration', function (data) {
                // data.registrationId
                console.log(data.registrationId);
				alert("Registration ID - " + data.registrationId);
            });

            push.on('notification', function (data) {
                alert("Title:"+data.title+" Message:"+ data.message);
            });

            push.on('error', function (e) {
                console.log(e.message)
            });
        }
/*		
$('#iptCity').on('change', function () {
  alert("Hooray");
//  msgTest();
});
*/